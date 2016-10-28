// this is only used by playsheet editor.  episode control is duplicated for the multi-episode channel editor

angular.module('podcastEpisode',['soundManager'])
    .controller('episodeCtrl', ['$scope', '$http', '$filter', 'archiveService', 'channel_id', function($scope, $http, $filter, archiveService, channel_id){

        $scope.editing = true;
        $scope.episode = $scope.$parent.episode;

        var episode = $scope.episode;

        episode.active = parseInt(episode.active,10);

        episode.start_obj = new Date(episode.date_unix*1000);
        episode.date = $filter('date')(episode.start_obj, 'medium');

        episode.updateTimeObjs = function(){
            var start = new Date(episode.date);
            episode.date_unix = start.getTime() / 1000;
            episode.start_obj = start;

            var end = new Date(episode.date_unix*1000);
            var end_seconds = end.getSeconds();
            end.setSeconds(end_seconds + parseInt(episode.duration,10));
            episode.end = $filter('date')(end, 'mediumTime');
            episode.end_obj = end;

        };
        episode.updateTimeObjs();

        episode.archiveURL = archiveService.url(episode.start_obj, episode.end_obj);


        $scope.editToggle = function(){
            $scope.editing = !$scope.editing;

        }

        $scope.save = function(episode){
            $scope.status = 'saving...';

            var data_to_post = {};
            data_to_post.url = episode.url;
            data_to_post.channel = channel_id;
            data_to_post.data = {
                title:episode.title,
                subtitle:episode.subtitle,
                summary:episode.summary,
                id:episode.id,
                date:episode.date,
                duration:episode.duration,
                active:episode.active,
                author:episode.author

            };

            $http({
                url:'/podcasting/episode.php',
                method:'POST',
                data:$.param(data_to_post)
            })
                .success(function(data, status, headers, config){
                    $scope.status = data;

                })
                .error(function(data,status,headers,config){
                    $scope.status = 'error: '+status;

                });
        }

        var basic_sound_options = {
            debugMode:false,
            useConsole:false,
            autoLoad:true,
            multiShot:false,
            stream:true/*,
             onplay: function(){
             for (var i = 0; i< soundManager.soundIDs.length ; i++){
             var soundID = soundManager.soundIDs[i];
             if( (this.id != soundID) && soundManager.getSoundById(soundID).playState ){
             soundManager.getSoundById(soundID).stop();
             console.warn('stopped playing sound id '+soundID);
             }
             }
             }*/
        };

        soundManager.setup({
            debugMode:true,
            useConsole:true,/*
             onready:function(){

             var music_url = episode.url;

             episode.sound = soundManager.createSound(
             angular.extend(basic_sound_options,{
             id:'full'+episode.id,
             url:episode.url
             })
             );

             },
             ontimeout: function() {
             console.error('Soundmanager init failed!');
             }*/

        });

        $scope.load_and_play_sound = function(url){
            var the_scope = this;
            if(typeof(episode.sound) != 'undefined') {
                episode.sound.destruct();
            }
            soundManager.stopAll();
            episode.sound = soundManager.createSound(
                angular.extend(basic_sound_options,{
                    autoPlay:true,
                    multiShot:false,
                    id:'sound'+episode.id,
                    url:url,
                    onfinish:function(){
                    },
                    whileloading: function() {
                        the_scope.sound_status = 'loading preview...';
                        if (this.duration == 0){
                            the_scope.sound_status = 'sorry, preview not available';
                        }
                    },
                    whileplaying: function() {
                        the_scope.sound_status = 'playing ... type: '+this.type;
                        if (this.duration == 0){
                            the_scope.sound_status = 'sorry, preview not available.';
                        }
                    }
                })
            );

        };

        $scope.preview_start = function(){

            var start_prev_end = new Date($scope.editing.podcast.date);
            start_prev_end.setSeconds(start_prev_end.getSeconds() + 5);
            var sound_url = archiveService.url($scope.editing.podcast.date, start_prev_end);


            $scope.load_and_play_sound(sound_url);
        };

        $scope.preview_end = function(){
            var end_date = $scope.editing.podcast.date.setMilliseconds(0) + $scope.editing.podcast.duration*1000;
            var end_prev_start = new Date(end_date);
            end_prev_start.setSeconds(end_prev_start.getSeconds() - 5);
            var sound_url = archiveService.url(end_prev_start, end_date);
            $scope.load_and_play_sound(sound_url);

        }

    }])
    .factory('archiveService', ['$filter', function($filter) {
        return {
            url: function(date, end) {

                console.warn(date);

                var start_ = $filter('date')(date.getTime(),'dd-MM-yyyy HH:mm:ss');
                var end_ = $filter('date')(end.getTime(),'dd-MM-yyyy HH:mm:ss');

                console.warn(start_);

                return 'http://archive.citr.ca/py-test/archbrad/download?'+
                    'archive=%2Fmnt%2Faudio-stor%2Flog'+
                    '&startTime='+start_+
                    '&endTime='+end_;

            }
        };
    }]);

angular.module('soundManager',[])
    .factory('soundManager',[function(){
        return {
            soundManager: function(){

                return new SoundManager();
            }
        }
    }]);
