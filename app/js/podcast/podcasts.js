(function (){
    var app = angular.module('djland.podcasts', ['ui.bootstrap','djland.api','djland.utils',]);

    app.controller('episodeList', function($scope, call, $interval, $location, $filter){
        this.Math = window.Math;
        this.days_of_week = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        this.months_of_year = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        this.plodcasts = [];
        this.editing  = false;
        this.done = false;
        this.show_id = show_id;
        //TODO: Get this from config constant
        this.MAX_PODCAST_DURATION_HOURS = 8;
        this.member_id = member_id;
        this.offset = 0;
        var this_ = this;
        this.time_changed = false;
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
            if( ! this.done){
                this.loading = true;
                var this_ = this;
                if(this.show_id){
                    this.status = 'loading sheets and podcasts...';
                    call.getShowEpisodes(show_id,this.offset).then(function(response){
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

			this.episode_image = call.getEpisodeImage(this.editing.podcast.id);
            this.time_changed = false;
        }
		this.uploadAudio = function(podcast_id){
			var form = new FormData();
			var file = $('#audio_file')[0].files[0];
			form.append('audio',file);
			var request = $.ajax({
				url: 'api2/public/podcast/'+podcast_id+'/audio',
				method: 'POST',
				dataType: 'json',
				processData: false,
				contentType: false,
				data: form
			});
			$.when(request).then((function(response){
				console.log(response);
                this.episodes.filter(function(object){
                    if(object.podcast.id == podcast_id) return object;
                })[0].url = response.audio.url;
                this.editing.podcast.url = response.audio.url;
				$scope.$apply();
                alert("Uploading audio successful!");
			}).bind(this),function(error){
				alert(error.responseText);
			});
		}
		this.uploadImage = function(){
			var form = new FormData($('#upload_image'));
			var file = $('#image_file')[0].files[0];
			console.log(file);
			form.append('image',file);

			var request = $.ajax({
				url: 'api2/public/podcast/'+this.editing.podcast.id+'/image',
				method: 'POST',
				dataType: 'json',
				processData: false,
				contentType: false,
				data: form
			});

			$.when(request).then((function(response){
				this.editing.podcast.image = response.url;
				$scope.$apply();
			}).bind(this),function(error){
				alert(error.responseText);
			});
		}
		this.deleteImage = function(){
			call.deleteEpisodeImage(this.editing.podcast.id).then((function(){
				this.editing.podcast.image = '';
			}).bind(this));
		}
        this.updateStart = function(){
            this.start.setSeconds(this.editing.start_second);
            this.start.setMinutes(this.editing.start_minute);
            this.start.setHours(this.editing.start_hour);
            this.editing.playsheet.start_time = $filter('date')(this.start,'yyyy/MM/dd HH:mm:ss');
            this.editing.podcast.date =  this_.editing.playsheet.start_time;
            this.editing.podcast.iso_date = this.days_of_week[this.start.getDay()] + ", " + this.start.getDate() + " " + this.months_of_year[this.start.getMonth()] + " " + this.start.getFullYear() + " " + $filter('date')(this.start,'HH:mm:ss') + " -0700" ;
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
            this.time_changed = true;
        }
        this.updateEnd = function(){
            this.end.setSeconds(this.editing.end_second);
            this.end.setMinutes(this.editing.end_minute);
            this.end.setHours(this.editing.end_hour);
            this.editing.playsheet.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
            this.time_changed = true;
        }

        this.date_change = function(){
            this.start = new Date(this.editing.playsheet.start_time);
            this.end = new Date(this.editing.playsheet.end_time);
            this.editing.podcast.duration = (this.end.getTime() - this.start.getTime())/1000;
            this.time_changed = true;
        }
        $scope.$watch('list.editing.playsheet.start_time', 
            (function () {
                if(this.editing.playsheet != null){
                    this.editing.playsheet.start_time = $filter('date')(this.editing.playsheet.start_time,'yyyy/MM/dd HH:mm:ss');
                    this.start = new Date(this.editing.playsheet.start_time);
                    this.editing.start_hour =  $filter('pad')(this.start.getHours(),2);
                    this.editing.start_minute = $filter('pad')(this.start.getMinutes(),2);
                    this.editing.start_second = $filter('pad')(this.start.getSeconds(),2);
                    this.editing.podcast.date =  this.editing.playsheet.start_time;
                    this.editing.podcast.iso_date = this.days_of_week[this.start.getDay()] + ", " + this.start.getDate() + " " + this.months_of_year[this_.start.getMonth()] + " " + this.start.getFullYear() + " " + $filter('date')(this.start,'HH:mm:ss') + " -0700" ;
                    if(this.start && this.end) this.editing.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
                }
            }).bind(this)
        );
        $scope.$watch('list.editing.playsheet.end_time', 
            (function () {
                if(this.editing.playsheet != null){
                    this.editing.playsheet.end_time = $filter('date')(this.editing.playsheet.end_time,'yyyy/MM/dd HH:mm:ss');
                    this.end = new Date(this.editing.playsheet.end_time);
                    this.editing.end_hour =  $filter('pad')(this.end.getHours(),2);
                    this.editing.end_minute = $filter('pad')(this.end.getMinutes(),2);
                    this.editing.end_second = $filter('pad')(this.end.getSeconds(),2);
                    if(this.start && this.end) this.editing.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
                }
            }).bind(this)
        );

        this.save = function(){
            var this_ = this;
            this.editing.podcast.title = this.editing.playsheet.title;
            this.editing.podcast.subtitle = this.editing.playsheet.summary;
            this.editing.podcast.summary = this.editing.playsheet.summary;
            this.savemessage = 'saving...';
            call.saveEpisode(this.editing.playsheet,this.editing.podcast).then(function(response){
                if(response.data = "true"){
                    if(this_.editing.playsheet.status == '2'){
                        alert("Successfully Saved");
                    }else{
                        alert("Successfully saved. Please submit this playsheet!");
                    }
                } else {
                    console.log(response);
                    alert("Not saved. Your error has been logged");
                }
            },function(error){
                console.log(error);
                alert("Failed to save podcast. Your error has been logged");
            });
        };
        this.recreate_audio = function(){
            var this_ = this;
            this.editing.podcast.title = this.editing.playsheet.title;
            this.editing.podcast.subtitle = this.editing.playsheet.summary;
            this.editing.podcast.summary = this.editing.playsheet.summary;
            this.message = 'saving...';
            
            call.saveEpisode(this.editing.playsheet,this.editing.podcast).then(
                (function(response){
                    if(response.data = "true"){
                        if(this.start.getTime() > new Date("2016/02/02 00:00:00").getTime() && this.editing.podcast.url.length != 0){
                            call.overwritePodcastAudio(this_.editing.podcast).then(function(response){
                                alert("Successfully saved, audio generated from on-air recording!");
                                this.time_changed = false;
                            },function(error){
                                console.log(error);
                                alert("Failed to save podcast: Could not overwrite audio.");
                            });
                        }
                        else if (this_.start.getTime() < new Date("2016/02/02 00:00:00")) {
                            alert('Successfully saved. Could not regenerate audio, as it is too far back');
                        }
                        else if(this_.editing.podcast.url.length == 0 || this.time_changed){
                            if (this_.editing.playsheet.status == '2'){
                                call.makePodcastAudio(this_.editing.podcast).then(function(response){
                                    alert("Successfully saved, audio generated from on-air recording!");
                                    this.time_changed = false;
                                },function(error){
                                    console.log(error);
                                    alert("Failed to save podcast: Could not write audio to directory" );
                                });
                            }
                            else {
                                alert('Successfully saved, please submit this playsheet!');
                            }
                        }else{
                            alert("Successfully saved. Audio did not need updating");
                        }
                    }
                }).bind(this)
            );
        };

        this.formatError = function(error){
            return error.data.split('body>')[1].substring(0,error.data.split('body>')[1].length-2 );
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
                    'archive=%2Fmnt%2Faudio_stor%2Flog'+
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
                //console.log('loading directive');
                element.bind('scroll', function () {
                    //console.log('in scroll');
                    //console.log(raw.scrollTop + raw.offsetHeight);
                    //console.log(raw.scrollHeight);
                    if (raw.scrollTop + raw.offsetHeight + raw.scrollHeight/5 >= raw.scrollHeight ) {
                        scope.$apply(attrs.scrolly);
                        //raw.scrollTop = (raw.scrollTop+raw.offsetHeight);
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
