(function (){
    var app = angular.module('djland.podcasts', ['ui.bootstrap','djland.api','djland.utils',]);
    



    app.controller('episodeList', function($scope, call, $location, $filter, archiveService){
        this.Math = window.Math;
        this.plodcasts = [];
        this.editing  = false;
        this.show_id = show_id;
        this.MAX_PODCAST_DURATION_HOURS = 8;
        this.member_id = member_id;

        this.init = function(){
            var this_ = this;
            //Get Episode list for show/channel
            call.getMemberPermissions(this.member_id).then(function(response){
                if(response.data.administrator == '1' || response.data.staff == '1' ){
                    this_.is_admin = true;
                }else{
                    this_.is_admin = false;
                }
            },function(error){
                    console.log(error.data);
            });

            sm = new SoundManager();

            sm.setup({
                debugMode:false
            });

            this.load();
        }
        this.load = function() {
            var this_ = this;
            if(this.show_id){
                this.status = 'loading sheets and podcasts...';
                call.getShowEpisodes(show_id).then(function(response){
                    this_.episodes = response.data;
                    this_.status = "Select a Podcast to edit";
                },function(error){
                    console.log(error);
                });
            }

        }

        this.edit_episode = function (episode){
            this.editing = angular.copy(episode);
            this.start = new Date(this.editing.playsheet.start_time);
            this.end = new Date(this.editing.playsheet.end_time);
            this.editing.start_hour = $filter('pad')(this.start.getHours(),2);
            this.editing.start_minute = $filter('pad')(this.start.getMinutes(),2);
            this.editing.start_second = $filter('pad')(this.start.getSeconds(),2);

            this.editing.end_hour = $filter('pad')(this.end.getHours(),2);
            this.editing.end_minute = $filter('pad')(this.end.getMinutes(),2);
            this.editing.end_second = $filter('pad')(this.end.getSeconds(),2);
        }
        this.updateStart = function(){
            this.start.setSeconds(this.editing.start_second);
            this.start.setMinutes(this.editing.start_minute);
            this.start.setHours(this.editing.start_hour);

            this.editing.playsheet.start_time = $filter('date')(this_.start,'yyyy-MM-dd HH:mm:ss');

        }  

        this.date_change = function(){
            calculate_end_from_start_and_duration();
        }

        var recalculate_duration = function(){
            this.editing.podcast.duration = (this.editing.end_time.getTime() - this.editing.podcast.date.getTime())/1000 ;

            if (this.editing.podcast.duration < 0){

                this.editing.podcast.duration += 24*60*60;
                calculate_end_from_start_and_duration();

            } else if (this.editing.podcast.duration > this.MAX_PODCAST_DURATION_HOURS*60*60){
                var diff = this.editing.podcast.duration - this.MAX_PODCAST_DURATION_HOURS*60*60;

                this.editing.podcast.duration -= diff;
                calculate_end_from_start_and_duration();

            }

            if(this.editing.podcast.duration == this.MAX_PODCAST_DURATION_HOURS*60*60){
                this.message = 'maximum duration of a podcast is '+ this.MAX_PODCAST_DURATION_HOURS+' hours.';
            } else {
                this.message = '';
            }
        }


/*        $scope.$watch('editing.podcast.date', function(){
            recalculate_duration();
        }, true);

        $scope.$watch('editing.end_time', function(){
            recalculate_duration();
        }, true);*/
        

        this.save = function(podcast){
            var this_ = this;
            this.message = 'saving...';

            apiService.saveEpisodeData(podcast)
                .then(function(response){
                    this_.message = 'saved. now updating your feed...';

                    apiService.updatePodcast(this_.editing.podcast,true)
                        .then(function(result){

                            this_.message = 'done updating the podcast'//result;

                            this_.editing.podcast.url = result.data.new_audio_url;
                            this_.load();

                        }).catch(function(result){
                            this_.message = 'error:' + result.data;
                        });
                }).catch(function(response){
                    console.error(response.data);
                    this_.message = 'sorry, saving did not work';
                });
        };

        this.deactivate = function(podcast){
            podcast.active = 0;

            apiService.saveEpisodeData(podcast)
                .then(function(response){
                    this_.message = 'podcast deactivated. now updating feed...';

                    apiService.updatePodcast(podcast, false)
                        .then(function(){
                            this_.message = ' feed updated ';

                            this_.load();

                        })
                })

        }

        var basic_sound_options = {
            debugMode:false,
            useConsole:false,
            autoLoad:true,
            multiShot:false,
            volume:70,
            stream:true
        };


        this.load_and_play_sound = function(url){
            var this_ = this;
            if(typeof(this.sound) != 'undefined') {
                this.sound.destruct();
            }

            this.message = 'playing ...';
            this.sound = sm.createSound(
                angular.extend(basic_sound_options,{
                    autoPlay:true,
                    url:url,
                    onfinish:function(){
                        this_.message = '';
                    },

                    whileplaying: function() {
                        this_.message = 'playing ...';
                        if (this.duration == 0){
                            this_.message = 'sorry, preview not available.';
                        }
                    }
                })
            );

        };

        this.preview_start = function(){

            var start_prev_end = new Date(this.editing.podcast.date);
            start_prev_end.setSeconds(start_prev_end.getSeconds() + 8);
            var sound_url = archiveService.url(this.editing.podcast.date, start_prev_end);


            this.load_and_play_sound(sound_url);
        };

        this.preview_end = function(){
            var end_date = this.editing.podcast.date.setMilliseconds(0) + this.editing.podcast.duration*1000;
            var end_prev_start = new Date(end_date);
            end_prev_start.setSeconds(end_prev_start.getSeconds() - 8);
            var sound_url = archiveService.url(end_prev_start, end_date);
            this.load_and_play_sound(sound_url);

        }

        this.stop_sound = function(){
            sm.stopAll();

            this.message = '';
        }

        //Call initialization function
        this.init();

    });

    app.directive('audio', function($sce) {
        return {
            restrict: 'A',
            scope: { source:'=' },
            replace: true,
            template: '<audio ng-src="{{url}}" controls></audio>',
            link: function (scope) {
                scope.$watch('source', function (newVal, oldVal) {
                   if (newVal !== undefined) {
                       scope.url = $sce.trustAsResourceUrl(newVal);
                   }
                });
            }
        };
    });

    app.factory('archiveService', ['$filter', function($filter) {
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

    app.controller('datepicker', function($filter) {
      this.today = function() {
        this.dt = $filter('date')(new Date(),'yyyy-MM-dd HH:mm:ss');
      };
      this.clear = function () {
        this.dt = null;
      };
      this.open = function($event) {

        $event.preventDefault();
        $event.stopPropagation();
        this.opened = true;
      };
      this.format = 'yyyy-MM-dd HH:mm:ss';
    });

})();