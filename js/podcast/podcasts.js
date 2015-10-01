(function (){
    var app = angular.module('djland.podcasts', ['ui.bootstrap','djland.api','djland.utils',]);
    



    app.controller('episodeList', function($scope, call, $interval, $location, $filter){
        this.Math = window.Math;
        this.plodcasts = [];
        this.editing  = false;
        this.done = false;
        this.show_id = show_id;
        this.MAX_PODCAST_DURATION_HOURS = 8;
        this.member_id = member_id;
        this.offset = 0;
        var this_ = this;
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
            if(!this.done){
                this.loading = true;
                var this_ = this;
                if(this.show_id){
                    this.status = 'loading sheets and podcasts...';
                    call.getShowEpisodes(show_id,this_.offset).then(function(response){
                        this_.status = "Select a Podcast to edit";
                        if(response.data.length > 0){
                            if(this_.offset == 0) this_.episodes = response.data;
                            else{
                                for(var episode in response.data){
                                    this_.episodes.push(response.data[episode]);
                                }
                            }
                            this_.offset += response.data.length;
                        }else{
                            this_.done = true;
                        }
                        this_.loading = false;
                    },function(error){
                        console.log(error);
                    });
                }
            }    
        }

        this.edit_episode = function (episode){
            console.log(episode);
            this.editing = angular.copy(episode);

            var re = new RegExp('-','g');
            this.editing.playsheet.start_time = this.editing.playsheet.start_time.replace(re,'/');
            this.editing.playsheet.end_time = this.editing.playsheet.end_time.replace(re,'/');
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
            this.editing.playsheet.start_time = $filter('date')(this.start,'yyyy/MM/dd HH:mm:ss');
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
        }  
        this.updateEnd = function(){
            this.end.setSeconds(this.editing.end_second);
            this.end.setMinutes(this.editing.end_minute);
            this.end.setHours(this.editing.end_hour);
            this.editing.playsheet.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
        }

        this.date_change = function(){
            this.start = new Date(this.editing.playsheet.start_time);
            this.end = new Date(this.editing.playsheet.end_time);
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
        }
        $scope.$watch('list.editing.playsheet.start_time', function () {
            if(this_.editing.playsheet != null){
                this_.editing.playsheet.start_time = $filter('date')(this_.editing.playsheet.start_time,'yyyy/MM/dd HH:mm:ss');
                this_.start = new Date(this_.editing.playsheet.start_time);
                this_.editing.start_hour =  $filter('pad')(this_.start.getHours(),2);
                this_.editing.start_minute = $filter('pad')(this_.start.getMinutes(),2);
                this_.editing.start_second = $filter('pad')(this_.start.getSeconds(),2);
               
                if(this_.start && this_.end) this_.editing.podcast.duration = (this_.end.getTime() - this_.start.getTime()) /1000;
               console.log("Start Time "+this_.editing.playsheet.start_time + " Start var =" +this_.start);
            }
            
        });
        $scope.$watch('list.editing.playsheet.end_time', function () {
            if(this_.editing.playsheet != null){
                this_.editing.playsheet.end_time = $filter('date')(this_.editing.playsheet.end_time,'yyyy/MM/dd HH:mm:ss');
                this_.end = new Date(this_.editing.playsheet.end_time);
                this_.editing.end_hour =  $filter('pad')(this_.end.getHours(),2);
                this_.editing.end_minute = $filter('pad')(this_.end.getMinutes(),2);
                this_.editing.end_second = $filter('pad')(this_.end.getSeconds(),2);
                if(this_.start && this_.end) this_.editing.podcast.duration = (this_.end.getTime() - this_.start.getTime()) /1000;
                console.log("End Time " + this_.editing.playsheet.end_time+" End var ="+  this_.end);
            }
        });

        


/*        $scope.$watch('editing.podcast.date', function(){
            recalculate_duration();
        }, true);

        $scope.$watch('editing.end_time', function(){
            recalculate_duration();
        }, true);*/
        

        this.save = function(){
            var this_ = this;
            this.editing.podcast.title = this.editing.playsheet.title;
            this.editing.podcast.subtitle = this.editing.playsheet.summary;
            this.editing.podcast.summary = this.editing.playsheet.summary;
            this.message = 'saving...';
            call.saveEpisode(this.editing.playsheet,this.editing.podcast).then(function(response){
                if(response.data = "true"){
                    if(this_.start.getTime() > new Date("2015/06/01 00:00:00").getTime()){
                        call.overwritePodcastAudio(this_.editing.podcast).then(function(response){
                        alert("Successfully Saved");
                        },function(error){
                            alert("Failed to save podcast: " + error);
                        });
                    }else{
                        alert("Saved podcast, did not re-generate audio as it is too far back");
                    }
                }
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




        this.elapsedTime = function(time){
            this.seconds_elapsed = (new Date().getTime() / 1000) -(this.audio_start.getTime()/1000);
            if(time == 'start'){
                var elapsed = new Date(this.start);
                elapsed.setSeconds(elapsed.getSeconds() + this.seconds_elapsed);
            }else if(time == 'end'){
                var elapsed = new Date(this.end);
                 elapsed.setSeconds(this.end.getSeconds() - 10 + this.seconds_elapsed);
            }         
            $('#elapsed').text($filter('date')(elapsed,'yyyy/MM/dd HH:mm:ss'));
        }
        this.load_and_play_sound = function(url,time){
            var this_ = this;
            if(typeof(this.sound) != 'undefined') {
                this.sound.destruct();
            }
            this.seconds_elapsed = 0;
            this.audio_start = new Date();
            this.playing = true;
            this.message = 'playing ...';
            this.sound = sm.createSound(
                angular.extend(basic_sound_options,{
                    autoPlay:true,
                    url:url,
                    onfinish:function(){
                        this_.message = '';
                        this.playing = false;
                        $interval.cancel(this_.elapsedInterval);
                    },

                    whileplaying: function() {

                        this_.elapsedInterval = $interval(this_.elapsedTime(time) , 1000);
                        this_.message = 'playing ...';
                        if (this.duration == 0){
                            this_.message = 'sorry, preview not available.';
                        }
                    }
                })
            );
        };

        this.preview_start = function(){
            var preview_end = new Date(this.start).setSeconds(this.start.getSeconds() + 10);
            var sound_url = this.getPreviewUrl(new Date(this.start), preview_end);
            console.log(sound_url);
            this.load_and_play_sound(sound_url,'start');
        };

        this.preview_end = function(){
            var preview_start = new Date(this.end).setSeconds(this.end.getSeconds() - 10);
            var sound_url = this.getPreviewUrl(preview_start,this.end);
            console.log(sound_url);
            this.load_and_play_sound(sound_url,'end');
        }
        this.getPreviewUrl = function(start,end){
            return 'http://archive.citr.ca/py-test/archbrad/download?'+
                    'archive=%2Fmnt%2Faudio-stor%2Flog'+
                    '&startTime='+$filter('date')(start,'dd-MM-yyyy HH:mm:ss')+
                    '&endTime='+$filter('date')(end,'dd-MM-yyyy HH:mm:ss');
        }

        this.stop_sound = function(){
            sm.stopAll();
            this.message = '';
        }

        //Call initialization function
        this.init();

    });
    app.directive('scrolly', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var raw = element[0];
                console.log('loading directive');
                element.bind('scroll', function () {
                    console.log('in scroll');
                    console.log(raw.scrollTop + raw.offsetHeight);
                    console.log(raw.scrollHeight);
                    if (raw.scrollTop + raw.offsetHeight + raw.scrollHeight/5 >= raw.scrollHeight ) {
                        scope.$apply(attrs.scrolly);
                        //raw.scrollTop = (raw.scrollTop+raw.offsetHeight);
                        console.log("Hit Bottom");
                    }
                });
            }
        };
    });
    app.directive('audio', function($sce) {
        return {
            restrict: 'A',
            scope: { source:'=' },
            replace: true,
            template: '<audio preload="metadata" ng-src="{{url}}" controls></audio>',
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
            url: function(start, end) {
                console.warn(start);

                var start_ = $filter('date')(start.getTime(),'dd-MM-yyyy HH:mm:ss');
                var end_ = $filter('date')(end.getTime(),'dd-MM-yyyy HH:mm:ss');


                
            }
        };
    }]);

    app.controller('datepicker', function($filter) {
      this.today = function() {
        this.dt = $filter('date')(new Date(),'yyyy/MM/dd HH:mm:ss');
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