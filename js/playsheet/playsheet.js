(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','ui.sortable','ui.bootstrap']);
	app.controller('PlaysheetController',function($filter,$rootScope,$scope,$interval,$timeout,call){
        this.info = {};
        this.ads = {};
        this.playitems = {};
        this.podcast = {};
        this.info.id = playsheet_id;
        this.member_id = member_id;
        this.username = username;
        this.loading = true;
        this.days_of_week = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        this.months_of_year = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        this.tracklist_overlay_header = "Thanks for submitting your playsheet";
        this.podcast_status = "Your podcast is being created";

        //Helper Variables
        this.using_sam = $('#using_sam').text()=='1' ? true : false;
        this.sam_visible = false;
        this.info.socan = $('#socan').text() == 'true' ? true : false;
    	this.tags = tags;
    	this.help = help;
        this.complete = false;


        this.add = function(id){
            var row = angular.copy(this.row_template);
            this.playitems.splice(id+1,0,row);
            this.update();
        }
        this.remove = function(id){
            this.playitems.splice(id,1);
            if(this.playitems.length < 1){
                $('#addRows').text("Add Row");
            }
            this.update();
        }
        this.addFiveRows = function(){
            if($('#addRows').text() == "Add Five More Rows"){
                for(var i=0;i<5;i++){
                    this.add(this.playitems.length-1);
                }
            }else{
                this.add(0);
                $('#addRows').text("Add Five More Rows");
            }
        }
        this.addStartRow = function(){
            this.playitems = Array();
            this.playitems[0] = angular.copy(this.row_template);
            this.update();

        }
        this.cueTrack = function (playitem) {
            playitem.start = new Date();
            playitem.insert_song_start_hour = $filter('pad')(playitem.start.getHours(), 2);
            playitem.insert_song_start_minute = $filter('pad')(playitem.start.getMinutes(), 2);
        }
        this.endTrack = function (playitem) {
            if (playitem.start == '0' || playitem.start == null) return;
            var start_milliseconds = playitem.start.getTime();//1000*60*60*playitem.insert_song_start_hour + 1000*60*playitem.insert_song_start_minute;
            var now = new Date();
            var end_milliseconds = now.getTime();
            var length = end_milliseconds - start_milliseconds;
            var length = new Date(length);
            playitem.insert_song_length_minute = $filter('pad')(length.getMinutes(), 2);
            playitem.insert_song_length_second = $filter('pad')(length.getSeconds(), 2);
        }

        //Sync Variables On Change
        this.updateTrackStart = function (playitem) {
            playitem.start = new Date();
            playitem.start.setHours(playitem.insert_song_start_hour);
            playitem.start.setMinutes(playitem.insert_song_start_minute);
            playitem.start.setSeconds(0);
        };
        this.updateTime = function(){
            var now = new Date();
            call.getNextShowTime(this.active_show.id,now).then(
				(
					function(response){
	                    var start_unix = response.data.start;
	                    var end_unix = response.data.end;
	                    this.info.unix_time = response.data.start;
	                    this.start = new Date(start_unix * 1000);
	                    this.end = new Date(end_unix * 1000);


	                    this.info.start_time = $filter('date')(this.start,'yyyy/MM/dd HH:mm:ss');
	                    this.info.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
	                    this.start_hour =  $filter('pad')(this.start.getHours(),2);
	                    this.start_minute = $filter('pad')(this.start.getMinutes(),2);
	                    this.start_second = $filter('pad')(this.start.getSeconds(),2);
	                    this.end_hour =  $filter('pad')(this.end.getHours(),2);
	                    this.end_minute = $filter('pad')(this.end.getMinutes(),2);
	                    this.end_second = $filter('pad')(this.end.getSeconds(),2);
	                    //Populate Template Row, then add 5 rows
	                    var show_date = this.start.getDate();
                        //Update Podcast information Mon, 26 Oct 2015 07:58:08 -0700

	                    this.updateEnd();
	                    this.updateStart();

	                    if(this.info.id < 1){
	                        call.getAds(start_unix,end_unix-start_unix,this.active_show.id).then(
								(
									function(response){
			                            this.ads = response.data;
			                            console.log(this.ads);
			                        }
								).bind(this)
								,(
									function(error){
			                            this.log_error(error);
			                            call.getAds(start_unix,end_unix-start_unix,this.active_show.id).then(
											(
												function(response){
					                                this.ads = response.data;
					                            }
											).bind(this)
										);
	                        		}
								).bind(this)
							);
	                    }
            		}
				).bind(this)
			);
        }
        this.updateShowValues = function(element){
            //When a new show is selected, updat all the information.
            this.active_show = this.member_shows.filter( (function(object){if(object.id == this.show_value) return object;}).bind(this))[0];
            this.show = this.active_show.show;
            this.info.show_id = parseInt(this.active_show.id);
            this.info.host = this.active_show.show.host;
            this.info.edit_name = this.username;
            this.podcast.show_id = this.info.show_id;
            this.podcast.author = this.info.host;
            this.info.crtc = this.active_show.crtc;
            this.info.lang = this.active_show.lang;
            for(var playitem in this.playitems){
                this.playitems[playitem].show_id = this.info.show_id;
                this.playitems[playitem].crtc_category = this.info.crtc;
                this.playitems[playitem].lang = this.info.lang;
            }
            call.getShowPlaysheets(this.active_show.id).then(function(response){
                //DISPLAY OLD PLAYSHEETS
                this.existing_playsheets = response.data.sort(function(a, b) {
					var re = new RegExp('-','g');
					return new Date(b.start_time.replace(re,'/')) - new Date(a.start_time.replace(re,'/'));
				});
            });
            this.updateTime();
        }
        this.updateSpokenword = function(){
            this.info.spokenword_duration = this.spokenword_hours * 60 + this.spokenword_minutes;
        }
        this.updateStart = function(){
            this.start.setHours(this.start_hour);
            this.start.setMinutes(this.start_minute);
            this.start.setSeconds(this.start_second);
            this.info.start_time = $filter('date')(this.start,'yyyy/MM/dd HH:mm:ss');
            this.updatePodcastDate();
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;

        }
        this.updateEnd = function(){
            this.end.setHours(this.end_hour);
            this.end.setMinutes(this.end_minute);
            this.end.setSeconds(this.end_second);
            this.info.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
        }

        //Setting Show Times
        this.startShow = function(){
            this.start = new Date();
            this.start_hour =  $filter('pad')(this.start.getHours(),2);
            this.start_minute = $filter('pad')(this.start.getMinutes(),2);
            this.start_second = $filter('pad')(this.start.getSeconds(),2);
            this.info.start_time = this.start;
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
        }
        this.endShow = function(){
            this.end = new Date();
            this.end_hour =  $filter('pad')(this.end.getHours(),2);
            this.end_minute = $filter('pad')(this.end.getMinutes(),2);
            this.end_second = $filter('pad')(this.end.getSeconds(),2);
            //this.end_time = $filter('date')(end, 'HH:mm:ss');
            this.info.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
        }

        this.loadRebroadcast = function(){
            call.getPlaysheetData(this.existing_playsheet).then(
				(function(response){
                    this.playitems = response.data.playitems;
                    this.info.spokenword_duration = response.data.playsheet.spokenword_duration;
                    if(this.info.spokenword_duration != null){
                        this.spokenword_hours = Math.floor(this.info.spokenword_duration / 60);
                        this.spokenword_minutes = this.info.spokenword_duration % 60;
                    }else{
                        this_.spokenword_hours = 0;
                        this_.spokenword_minutes = null;
                    }
                    this.ads = response.data.ads;
                }).bind(this)
			);
        }
		this.getNewUnix = function(){
			if(this.loading == true) return;
			//convert to seconds from javascripts milliseconds
			var start_unix = this.start / 1000;
			var end_unix = this.end / 1000;

			//get minutes for start, and push unix to 0/30 minute mark on closest hour
			var minutes = this.start.getMinutes();
			start_unix-=minutes*60;
			if(minutes >= 45){
				//roll to the next hour by adding 3600s
				start_unix+=60*60;
			}else if(minutes < 45 && minutes >= 15){
				//set to 30 minutes through by adding 1800s
				start_unix+=30*60;
			}else{
				//already at zero minutes.
			}
			//Get minutes for end, and push unix to 0/30 minute mark on closes hour
			minutes = this.end.getMinutes();
            end_unix-=minutes*60;
			if(minutes >= 45){
				//roll to the next hour by adding 3600s
				end_unix+=60*60;
			}else if(minutes < 45 && minutes >= 15){
				//set to 30 minutes through by adding 1800s
				end_unix+=30*60;
			}else{
				//already at zero minutes.
			}

			this.start_unix = start_unix;
			this.end_unix = end_unix;
			var duration = start_unix - end_unix;
			if(this.info.id < 1){
				call.getAds(start_unix,end_unix-start_unix,this.active_show.id).then(
					(function(response){
						this.ads = response.data;
					}).bind(this)
					,(function(error){
						this.log_error(error);
						call.getAds(start_unix,end_unix-start_unix,this.active_show.id).then(
							(function(response){
								this.ads = response.data;
							}).bind(this)
						);
					}).bind(this)
				);
			}
		}

        //Initialization of Playsheet
        this.init = function(){
            //If playsheet exists, load it.
            if(this.info.id > 0){
                call.getPlaysheetData(this.info.id).then(
					(function(data){
	                    var playsheet = data.data;
	                    this.info = {};
	                    for(var item in playsheet.playsheet){
	                        this.info[item] = playsheet.playsheet[item];
	                    }
	                    var re = new RegExp('-','g');
	                    this.info.start_time = this.info.start_time.replace(re,'/');
	                    this.info.end_time = this.info.end_time.replace(re,'/');
	                    //Create Extra Variables to allow proper display in UI
	                    this.start = new Date(this.info.start_time);
	                    this.end = new Date(this.info.end_time);
	                    this.start_hour =  $filter('pad')(this.start.getHours(),2);
	                    this.start_minute = $filter('pad')(this.start.getMinutes(),2);
	                    this.start_second = $filter('pad')(this.start.getSeconds(),2);
	                    this.end_hour =  $filter('pad')(this.end.getHours(),2);
	                    this.end_minute = $filter('pad')(this.end.getMinutes(),2);
	                    this.end_second = $filter('pad')(this.end.getSeconds(),2);

	                    if(this.info.spokenword_duration != null){
	                        this.spokenword_hours = Math.floor(this.info.spokenword_duration / 60);
	                        this.spokenword_minutes = this.info.spokenword_duration % 60;
	                    }else{
	                        this.spokenword_hours = 0;
	                        this.spokenword_minutes = null;
	                    }
	                    //Set Show Data
	                    this.show = playsheet.show;

	                    this.playitems = playsheet.playitems;
	                    this.podcast = playsheet.podcast == null ? {'id':-1,'playsheet_id':this.info.id, 'show_id':playsheet.show_id} : playsheet.podcast;
	                    this.ads = playsheet.ads;
	                    //If no playitems, change "Add Five Rows" button to say "Add Row" instead
	                    if(this.playitems < 1){
	                        $('#addRows').text("Add Row");
	                    }
	                    for(var playitem in this.playitems){
	                        this.playitems[playitem].insert_song_start_hour = $filter('pad')( this.playitems[playitem].insert_song_start_hour , 2);
	                        this.playitems[playitem].insert_song_start_minute = $filter('pad')( this.playitems[playitem].insert_song_start_minute , 2);
	                        this.playitems[playitem].insert_song_length_minute = $filter('pad')( this.playitems[playitem].insert_song_length_minute , 2);
	                        this.playitems[playitem].insert_song_length_second = $filter('pad')( this.playitems[playitem].insert_song_length_second , 2);
	                    }

	                    //Get Member shows, and set active show
	                    call.getActiveMemberShows(this.member_id).then(
							(function(data){
		                        var shows = data.data.shows;
		                        this.member_shows = shows;
		                        //Find what show this playsheet is for, and set it as active show to load information.
		                        for(var show in this.member_shows){

		                            if(this.show.name.toString() == shows[show].show.name.toString()){
		                                this.active_show = this.member_shows[show];
		                                this.show_value = shows[show]['id'];
		                                this.show = shows[show]['show'];
		                            }
		                        }

		                        call.getShowPlaysheets(this.active_show.id).then(
									(function(response){
			                            //DISPLAY OLD PLAYSHEETS
										this.existing_playsheets = response.data.sort(
											function(a, b) {
											var re = new RegExp('-','g');
											return new Date(b.start_time.replace(re,'/')) - new Date(a.start_time.replace(re,'/'));
											}
										);
		                        	}).bind(this)
								);
		                        //Populate the template row
		                        var show_date = this.start.getDate();
		                        this.row_template = {"show_id":this.active_show.id,"playsheet_id":this.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this.info.crtc,"lang":this.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
		                        this.checkIfComplete();
		                        if(this.using_sam){
		                            this.updateSamPlays();
		                        }
		                        this.loading = false;
		                    }).bind(this)
						);
                	}).bind(this)
				);
            }else{

                this.podcast = {};

                this.info.status = '1';
                this.info.type='Live';
                this.spokenword_hours = 0;
                this.spokenword_minutes = null;
                this.podcast.active = 0;

                //Get Shows Listing
                call.getActiveMemberShows(this.member_id).then(
					(function(data){
	                    var shows = data.data.shows;
	                    this.member_shows = shows;
	                    if(shows){
	                        //Cheat Code to get first active show.
	                        for(var show in this.member_shows){
	                            this.active_show = this.member_shows[show];
	                            this.show = this.active_show.show;

	                            this.show_value = this.active_show['id'];
	                            this.info.show_id = parseInt(this.active_show.id);
	                            this.info.host = this.active_show.show.host;
	                            this.info.create_name = this.info.host;

	                            this.podcast.author = this.info.host;
	                            this.info.crtc = this.active_show.crtc;
	                            this.info.lang = this.active_show.lang || 'English';

	                            for(var playitem in this.playitems){
	                                this.playitems[playitem].show_id = this.info.show_id;
	                            }
	                            break;
	                        }
	                        var now = new Date();

	                        call.getShowPlaysheets(this.show_value).then(
								(
									function(response){
										this.existing_playsheets = response.data.sort(
											function(a, b) {
												var re = new RegExp('-','g');
												return new Date(b.start_time.replace(re,'/')) - new Date(a.start_time.replace(re,'/'));
											}
										);
		                        	}
								).bind(this)
							);

	                       call.getNextShowTime(this.active_show.id,now).then(
							   (
								   function(response){
			                            var start_unix = response.data.start;
			                            var end_unix = response.data.end;
			                            this.start = new Date(start_unix * 1000);
			                            this.end = new Date(end_unix * 1000);

			                            this.info.unix_time = this.start.getTime() / 1000;
			                            this.info.start_time = $filter('date')(this.start,'yyyy/MM/dd HH:mm:ss');
			                            this.info.end_time = $filter('date')(this.end,'yyyy/MM/dd HH:mm:ss');
			                            this.start_hour =  $filter('pad')(this.start.getHours(),2);
			                            this.start_minute = $filter('pad')(this.start.getMinutes(),2);
			                            this.start_second = $filter('pad')(this.start.getSeconds(),2);
			                            this.end_hour =  $filter('pad')(this.end.getHours(),2);
			                            this.end_minute = $filter('pad')(this.end.getMinutes(),2);
			                            this.end_second = $filter('pad')(this.end.getSeconds(),2);

			                            //Populate Template Row, then add 5 rows
			                            var show_date = this.start.getDate();
			                                                //Update Podcast information
			                            this.updatePodcastDate();
			                            this.updateEnd();
			                            this.updateStart();
			                            this.row_template = {"show_id":this.active_show.id,"playsheet_id":this.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this.info.crtc,"lang":this.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
			                            this.addStartRow();
			                            for(var i = 0; i<4; i++) {
			                                this.add(this.playitems.length-1);
			                            }
			                            call.getAds(start_unix, end_unix-start_unix,this.active_show.id).then(
											(function(response){
				                                this.ads = response.data;
				                            }).bind(this)
											,(function(error){
				                            this.log_error(error);
				                                call.getAds(start_unix,end_unix-start_unix,this.active_show.id).then(
													(function(response){
				                                    this.ads = response.data;
												}).bind(this));
				                            }).bind(this)
										);
			                            this.update();
			                            if(this.using_sam){
			                                this.updateSamPlays();
			                            }
			                            this.loading = false;
			                        }
								).bind(this)
							);
	                    }else{
	                        this.loading = false;
	                    }
	                }).bind(this)
				);
            }
        }
        this.updatePodcastDate = function(){
            this.podcast.date = this.info.start_time;
            this.podcast.iso_date = this.days_of_week[this.start.getDay()] + ", " + this.start.getDate() + " " + this.months_of_year[this.start.getMonth()] + " " + this.start.getFullYear() + " " + $filter('date')(this.start,'HH:mm:ss') + " -0700" ;
        }
        //When a playsheet item is added or removed, check for completeness
        $scope.$watchCollection('playsheet.playitems',
			(function () {
            	this.update();
        	}).bind(this),true
		);
        $scope.$watch('playsheet.info.start_time',
			(
				function(){
		            this.info.start_time = $filter('date')(this.info.start_time,'yyyy/MM/dd HH:mm:ss');
		            this.start = new Date(this.info.start_time);
		            this.start_hour =  $filter('pad')(this.start.getHours(),2);
		            this.start_minute = $filter('pad')(this.start.getMinutes(),2);
		            this.start_second = $filter('pad')(this.start.getSeconds(),2);

		            if(this.start && this.end) this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
		            this.updateSamPlays();
					this.getNewUnix();
        		}
			).bind(this)
		);
        $scope.$watch('playsheet.info.end_time',
			(
				function () {
		            this.info.end_time = $filter('date')(this.info.end_time,'yyyy/MM/dd HH:mm:ss');
		            this.end = new Date(this.info.end_time);
		            this.end_hour =  $filter('pad')(this.end.getHours(),2);
		            this.end_minute = $filter('pad')(this.end.getMinutes(),2);
		            this.end_second = $filter('pad')(this.end.getSeconds(),2);
		            if(this.start && this.end) this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
		            this.updateSamPlays();
		            console.log("End Time " + this.info.end_time+" End var ="+  this.end);
					this.getNewUnix();
		        }
			).bind(this)
		);



        this.update = function(){
            $timeout( (function(){this.checkIfComplete();}).bind(this),100);
        }
        this.checkIfComplete = function(){
            var playsheet_okay = 'true';
            this.missing = "You have empty values";
            if(this.info.start > this.info.end){
                playsheet_okay = false;
            }
            var m= {'artist':0,'song':0,'album':0,'composer':0,'spokenword':0,'episode_title':0,'episode_summary':0};
            $('.required').each(
				(function(index,element){
	                $e = element;

	                var model = $e.getAttribute('ng-model');
	                if( $(element).val() == "" || $(element).val() == null){
	                    playsheet_okay='false';
	                    switch(model){
	                        case "playitem.artist":
	                            if(this.playitems.length>0) m.artist = 1;
	                            break;
	                        case 'playitem.album':
	                            if(this.playitems.length>0) m.album = 1;
	                            break;
	                        case 'playitem.song':
	                            if(this.playitems.length>0) m.song = 1;
	                            break;
	                        case 'playitem.composer':
	                            if(this.playitems.length>0) m.composer= 1;
	                            break;
	                        case 'playsheet.spokenword_hours':
	                            m.spokenword = 1;
	                            break;
	                        case 'playsheet.spokenword_minutes':
	                            m.spokenword = 1;
	                            break;
	                        case 'playsheet.info.title':
	                            m.episode_title = 1;
	                            break;
	                        case 'playsheet.info.summary':
	                            m.episode_summary = 1;
	                            break;
	                        default:
	                            break;
	                    }
                	}
            	}).bind(this)
			);
            if(playsheet_okay == 'true'){
                this.complete = true;
            }else{
                this.missing = "You have empty values for these fields:"
                + (m.artist == 1 ? " an artist,":"")
                + (m.song == 1 ? " a song title,":"" )
                + (m.album == 1 ? " an album,":"")
                + (m.composer == 1 ? " a composer,":'')
                + (m.spokenword == 1 ? " your spoken word duration,":"")
                + (m.episode_title ==1 ? " your episode title,":"")
                + (m.episode_summary == 1 ? " your episode description":"")
                + '.';
                this.complete = false;
            }
        }
        this.saveDraft = function(){
            this.info.unix_time = this.start.getTime() / 1000;
            var date = $filter('date')(this.start,'yyyy/MM/dd');
            for(var playitem in this.playitems){
                this.playitems[playitem].show_date = date;
            }
			this.podcast.date = this.info.start_time;
            this.podcast.show_id = this.info.show_id;
            this.updatePodcastDate();
            this.podcast.title = this.info.title;
            this.podcast.subtitle = this.info.summary;
            this.podcast.summary = this.info.summary;
            if(this.info.status <= 1){
                if(this.info.id < 1){
                    //New Playsheet
                    this.info.create_name = this.username;
					this.info.show_name = this.active_show.name;
                    callback = call.saveNewPlaysheet(this.info,this.playitems,this.podcast,this.ads).then(
						(
							function(response){
		                        this.info.id = response.data.id;
		                        for(var playitem in this.playitems){
		                            this.playitems[playitem].playsheet_id = this.info.id;
		                        }
								this.ads = response.data.ads;
								console.log(this.ads);
		                        var show_date = this.start.getDate();
		                        this.row_template = {"show_id":this.active_show.id,"playsheet_id":this.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this.info.crtc,"lang":this.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
		                        this.podcast.id = response.data.podcast_id;
		                        this.podcast.playsheet_id = response.data.id;
		                        alert("Draft Saved");

	                    	}
						).bind(this),
						(
							function(error){
                        		alert("Draft was not saved. Please contract tecnical services at technicalservices@citr.ca or technicalmanager@citr.ca");
                        		this.log_error(error);
                    		}
						).bind(this)
					);
                }else{
                    //Existing Playsheet
                    call.savePlaysheet(this.info,this.playitems,this.podcast,this.ads).then(
						(
							function(response){
		                        for(var playitem in this.playitems){
		                            this.playitems[playitem].playsheet_id = this.info.id;
		                        }
		                        alert("Draft Saved");
		                    }
						).bind(this)
						,(
							function(error){
		                        alert("Draft was not saved. Please contract tecnical services at technicalservices@citr.ca or technicalmanager@citr.ca");
		                        this.log_error(error);
		                    }
						).bind(this)
					);
                }
            }else{
                //TODO: Fix the grammar here
                alert("You've already submitted this playsheet, please submit it instead");
            }

        }
        //Submit a Playsheet
        this.submit = function () {
            this.info.unix_time = this.start.getTime() / 1000;
            this.podcast.show_id = this.info.show_id;
			this.podcast.date = this.info.start_time;
            this.podcast.active = 1;
            this.podcast.title = this.info.title;
            this.podcast.subtitle = this.info.summary;
            this.podcast.summary = this.info.summary;
			this.info.show_name = this.active_show.name;
            //Ensuring start and end times work for podcast generation
            if(new Date(this.info.start_time) > new Date() || new Date(this.info.end_time) > new Date()){
                alert("Cannot create a podcast in the future, please save as a draft.");
            }else if(new Date(this.info.start_time) > new Date(this.info.end_time)){
                alert("End time is before start time");
            }else if(this.start.getTime()/1000 - this.end.getTime()/1000 > 8*60*60){
                //TODO: Make this correspond to a config constant so that we can adjust the length of the max podcast on the config - see #255
                alert("This podcast is over 8 hours. 8 Hours is the maximum");
            }else{
               //Update Status to submitted playsheet
                this.info.status = 2;
                var date = $filter('date')(this.start,'yyyy/MM/dd');
                for(var playitem in this.playitems){
                    this.playitems[playitem].show_date = date;
                }

                this.updatePodcastDate();

                if(this.info.id < 1){
                    //New Playsheet
                    this.info.create_name = this.username;

                    call.saveNewPlaysheet(this.info,this.playitems,this.podcast,this.ads).then(
						(
							function(response){
		                        for(var playitem in this.playitems){
		                            this.playitems[playitem].playsheet_id = this.info.id;
		                        }
								this.ads = response.data.ads;
		                        this.info.id = response.data.id;
		                        this.podcast.id = response.data.podcast_id;
		                        this.podcast.playsheet_id = response.data.id;
		                        this.tracklist_overlay = true;
		                        call.makePodcastAudio(this.podcast).then(
									(
										function(reponse){
			                            	this.podcast_status = "Podcast Audio Created Successfully.";
			                        	}
									).bind(this)
									,(
										function(error){
				                            this.podcast_status = "Could not generate podcast. Playsheet was saved successfully.";
				                            this.error = true;
				                            this.log_error(error);
			                        	}
									).bind(this)
								);
	                    	}
						).bind(this)
						,(
							function(error){
		                        this.tracklist_overlay_header = "An error has occurred while saving the playsheet";
		                        this.podcast_status = "Podcast Not created";
		                        this.error = true;
		                        this.log_error(error);
		                        this.tracklist_overlay = true;
		                    }
						).bind(this)
					);
                }else{
                    //Existing Playsheet

                    if(this.podcast.id < 1){
                        this.podcast.playsheet_id = this.info.id;
                        this.podcast.show_id = this.info.show_id;

                        call.saveNewPodcast(this.podcast).then(
						(function(response){
                            this.podcast.id = response.data['id'];
							console.log(response);
                            call.savePlaysheet(this.info,this.playitems,this.podcast,this.ads).then(
								(function(response){
	                                this.tracklist_overlay = true;
	                                call.makePodcastAudio(this.podcast).then(
										(function(reponse){
		                                    this.podcast_status = "Podcast Audio Created Successfully.";
		                                }).bind(this)
										,(function(error){
			                                this.podcast_status = "Could not generate podcast. Playsheet was saved successfully.";
			                                this.error = true;
			                                this.log_error(error);
			                            }).bind(this)
									);
								}).bind(this)
							);
						}).bind(this)
						,(function(error){
                            this.podcast_status = "Podcast Not created";
                            this.error = true;
                            this.log_error(error);
                            this.tracklist_overlay = true;
                        }).bind(this)
                    	);
                    }else{
                        call.savePlaysheet(this.info,this.playitems,this.podcast,this.ads).then(
							(function(response){
	                            this.tracklist_overlay = true;
	                            call.makePodcastAudio(this.podcast).then(
									(
										function(reponse){
			                                this.podcast_status = "Podcast Audio Created Successfully.";
			                            }
									).bind(this)
									,(
										function(error){
			                                this.podcast_status = "Could not generate podcast. Playsheet was saved successfully.";
			                                this.error = true;
			                                this.log_error(error);
			                            }
									).bind(this)
								);
                    		}).bind(this)
							,(function(error){
	                            this.podcast_status = "Podcast Not created";
	                            this.error = true;
	                            this.log_error(error);
	                            this.tracklist_overlay = true;
	                        }).bind(this)
						);
                    }
                }
            }
        }
        this.log_error = function(error){
            this.tracklist_overlay_header = "An error has occurred while saving the playsheet";
            var error = error.data.split('body>')[1].substring(0,error.data.split('body>')[1].length-2 );
            call.error( error).then(function(response){
                $('#playsheet_error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error has been logged');
            },function(error){
                $('#playsheet_error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error could not be logged :(');
            });
        }

        this.addSamPlay = function (sam_playitem) {
            this.playitems.splice(this.playitems.length,0,sam_playitem);
            console.log(sam_playitem);
        };
        this.formatSamPlay = function (sam_play) {
            var djland_entry = angular.copy(this.row_template);
            djland_entry.artist = sam_play.artist;
            djland_entry.album = sam_play.album;
            djland_entry.song = sam_play.title;
            djland_entry.composer = sam_play.composer;
            djland_entry.insert_song_start_hour = $filter('pad')( new Date(sam_play.date_played).getHours(), 2);
            djland_entry.insert_song_start_minute = $filter('pad')( new Date(sam_play.date_played).getMinutes(), 2);
            djland_entry.insert_song_length_minute = $filter('pad')((sam_play.duration / 60000), 2);
            djland_entry.insert_song_length_second = $filter('pad')( (sam_play.duration/1000)%60 , 2);
            djland_entry.is_canadian = sam_play.mood.toLowerCase().indexOf('cancon') > -1 ? '1':'0';
            djland_entry.is_fem = sam_play.mood.toLowerCase().indexOf('femcon') > -1 ? '1':'0';
            djland_entry.lang = this.info.lang;
            return djland_entry;
        };
        this.loadSamPlays = function () {
            call.getSamRecent(0).then(
				(function (data) {
		            this.samRecentPlays = [];
		            for (var samplay in data.data) {
		                this.samRecentPlays.push(this.formatSamPlay(data.data[samplay]));
		            }
		        }).bind(this)
			);
        };
        this.samRange = function () {
            call.getSamRange($filter('date')(this.start,'yyyy-MM-dd HH:mm:ss'),$filter('date')(this.end,'yyyy-MM-dd HH:mm:ss')).then(
				(function(data){
		            for (var samplay in data.data) {
		                this.addSamPlay(this.formatSamPlay(data.data[samplay]));
		            }
		        }).bind(this)
			);
            this.sam_visible= false;
        };
        this.updateSamPlays = function(){
            call.getSamRange($filter('date')(this.start,'yyyy-MM-dd HH:mm:ss'),$filter('date')(this.end,'yyyy-MM-dd HH:mm:ss')).then(
				(function(data){
		            this.samRecentPlays = [];
		            for (var samplay in data.data) {
		                this.samRecentPlays.push(this.formatSamPlay(data.data[samplay]));
		            }
		        }).bind(this)
			);
        }
        this.init();
    });

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
		this.format = 'yyyy/MM/dd HH:mm:ss';
    });

    //Declares playitem attribute
    app.directive('playitem',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/playitem.html'
    	};
    });
    //Declares ad attribute
    app.directive('promotion',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/promotion.html'
    	}
    });
    app.directive('datepickerPopup', function (){
        return {
            restrict: 'EAC',
            require: 'ngModel',
            link: function(scope, element, attr, controller) {
                //remove the default formatter from the input directive to prevent conflict
                controller.$formatters.shift();
            }
        }
    });
})();

$(document).ready(function(){

    var can_2_required = $('#can_2_required').text();
    var can_3_required = $('#can_3_required').text();
    var fem_required = $('#fem_required').text();
    var playlist_required = $('#playlist_required').text();
    var hit_max = $('#hit_max').text();

    var can_2_element = $('#can_2_total');
    var can_3_element = $('#can_3_total');
    var fem_element = $('#fem_total');
    var playlist_element = $('#playlist_total');
    var hit_element = $('#hit_total');

    setInterval(function(){
        crtc_totals();
    },3000);

    function crtc_totals(){
        var playitems_count = 0;
        var can_2_count = 0;
        var can_3_count = 0;

        var can_2_total = 0;
        var can_3_total = 0;
        var fem_total = 0;
        var playlist_total = 0;
        var hit_total = 0;

        $('.playitem').each(function(element){
            playitems_count ++;
            if($(this).find('button.femcon').hasClass('filled')) fem_total ++;
            if($(this).find('button.playlist').hasClass('filled')) playlist_total ++;
            if($(this).find('button.hit').hasClass('filled')) hit_total ++;

            if($(this).find('select.crtc_category').val() == '20'){
                can_2_count ++;
                if($(this).find('button.cancon').hasClass('filled')) can_2_total ++;
            }else{
                can_3_count ++;
                if($(this).find('button.cancon').hasClass('filled')) can_3_total ++;
            }
        });
        can_2_element.text((can_2_total / (can_2_count!=0?can_2_count:1) * 100).toFixed(0) + "%");
        if(can_2_total/(can_2_count!=0?can_2_count:1) * 100 < can_2_required && can_2_count > 0) can_2_element.addClass('red');
        else can_2_element.removeClass('red');

        can_3_element.text((can_3_total / (can_3_count!=0?can_3_count:1) * 100).toFixed(0) + "%");
         if(can_3_total/(can_3_count!=0?can_3_count:1) * 100 < can_3_required && can_3_count > 0) can_3_element.addClass('red');
        else can_3_element.removeClass('red');

        fem_element.text((fem_total / (playitems_count!=0?playitems_count:1) * 100).toFixed(0) + "%");
         if(fem_total/(playitems_count!=0?playitems_count:1) * 100 < fem_required && playitems_count > 0) fem_element.addClass('red');
        else fem_element.removeClass('red');

        playlist_element.text((playlist_total / (playitems_count!=0?playitems_count:1) * 100).toFixed(0) + "%");
        if(playlist_total/(playitems_count!=0?playitems_count:1) * 100 < playlist_required && playitems_count > 0) playlist_element.addClass('red');
        else playlist_element.removeClass('red');

        hit_element.text((hit_total / (playitems_count!=0?playitems_count:1) * 100).toFixed(0) + "%");
        if(hit_total/(playitems_count!=0?playitems_count:1) * 100 > hit_max && playitems_count > 0) hit_element.addClass('red');
        else hit_element.removeClass('red');

    }
});
