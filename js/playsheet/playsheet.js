(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','ui.sortable','ui.bootstrap']);
	app.controller('PlaysheetController',function($filter,$rootScope,$scope,$interval,$timeout,call){
        this.info = {};
        this.playitems = {};
        this.podcast = {};
        this.info.id = playsheet_id;
        this.member_id = member_id;
        this.username = username;
        this.loading = true;
        var this_ = this;

        //Helper Variables
        this.using_sam = $('#using_sam').text()=='1' ? true : false;
        this.sam_visible = false;
        this.socan = $('#socan').text() == 'true' ? true : false;
    	this.tags = tags;
    	this.help = help;
        this.complete = false;


        this.add = function(id){
            var row = angular.copy(this_.row_template);
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
            call.getNextShowTime(this_.active_show.id,now).then(function(response){
                    console.log(response.data);
                    var start_unix = response.data.start;
                    var end_unix = response.data.end;
                    this_.info.unix_time = response.data.start;
                    this_.start = new Date(start_unix * 1000);
                    this_.end = new Date(end_unix * 1000);

                    this_.info.start_time = $filter('date')(this_.start,'yyyy-MM-dd HH:mm:ss');
                    this_.info.end_time = $filter('date')(this_.end,'yyyy-MM-dd HH:mm:ss');
                    this_.start_hour =  $filter('pad')(this_.start.getHours(),2);
                    this_.start_minute = $filter('pad')(this_.start.getMinutes(),2);
                    this_.start_second = $filter('pad')(this_.start.getSeconds(),2);
                    this_.end_hour =  $filter('pad')(this_.end.getHours(),2);
                    this_.end_minute = $filter('pad')(this_.end.getMinutes(),2);
                    this_.end_second = $filter('pad')(this_.end.getSeconds(),2);
                    console.log(this_.start_hour);
                    //Populate Template Row, then add 5 rows
                    var show_date = this_.start.getDate();
                                     //Update Podcast information
                    this_.podcast.date = this_.info.start_time;
                    this_.updateEnd();
                    this_.updateStart();

                    call.getAds(start_unix).then(function(response){
                        this_.ads = response.data;
                    });
            });
        }
        this.updateShowValues = function(element){
            this.active_show = this.member_shows.filter(function(object){if(object.id == this_.show_value) return object;})[0];
            
            this.show = this.active_show.show;
            console.log(this.active_show);
            this.info.show_id = parseInt(this.active_show.id);
            this.info.host = this.active_show.show.host;
            this.info.edit_name = this.username;
            this.podcast.show_id = this.info.show_id;
            this.podcast.author = this.info.host;
            for(var playitem in this.playitems){
                this.playitems[playitem].show_id = this.info.show_id;
            }
            this.updateTime();
        }
        this.updateSpokenword = function(){
            this.info.spokenword_duration = this.spokenword_hours * 60 + this.spokenword_minutes;
        }
        this.updateStart = function(){
            this.start.setHours(this.start_hour);
            this.start.setMinutes(this.start_minute);
            this.start.setSeconds(this.start_second);
            this.info.start_time = $filter('date')(this.start,'yyyy-MM-dd HH:mm:ss');
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
        }
        this.updateEnd = function(){
            this.end.setHours(this.end_hour);
            this.end.setMinutes(this.end_minute);
            this.end.setSeconds(this.end_second);
            this.info.end_time = $filter('date')(this.end,'yyyy-MM-dd HH:mm:ss');
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
            this.info.end_time = $filter('date')(this.end,'yyyy-MM-dd HH:mm:ss');
            this.podcast.duration = (this.end.getTime() - this.start.getTime()) /1000;
        }

        this.loadRebroadcast = function(){
            call.getPlaysheetData(this.existing_playsheet).then(function(response){
                this_.playitems = response.data.playitems; 
                this_.info.summary = response.data.playsheet.summary;
                this_.info.title = response.data.playsheet.title;
                this_.info.spokenword_duration = response.data.playsheet.spokenword_duration;
                if(this_.info.spokenword_duration != null){
                    this_.spokenword_hours = Math.round(this_.info.spokenword_duration / 60);
                    this_.spokenword_minutes = this_.info.spokenword_duration % 60;
                }else{
                    this_.spokenword_hours = null;
                    this_.spokenword_minutes = null;
                }
                this_.ads = response.data.ads;

            });
        }

        //Initialization of Playsheet
        this.init = function(){
            var this_ = this;
            //If playsheet exists, load it.
            if(this.info.id > 0){
                call.getPlaysheetData(this.info.id).then(function(data){
                    var playsheet = data.data;
                    this_.info = {};
                    for(var item in playsheet.playsheet){
                        this_.info[item] = playsheet.playsheet[item];
                    }

                    //Create Extra Variables to allow proper display in UI
                    this_.start = new Date(this_.info.start_time);
                    this_.end = new Date(this_.info.end_time);
                    this_.start_hour =  $filter('pad')(this_.start.getHours(),2);
                    this_.start_minute = $filter('pad')(this_.start.getMinutes(),2);
                    this_.start_second = $filter('pad')(this_.start.getSeconds(),2);
                    this_.end_hour =  $filter('pad')(this_.end.getHours(),2);
                    this_.end_minute = $filter('pad')(this_.end.getMinutes(),2);
                    this_.end_second = $filter('pad')(this_.end.getSeconds(),2);
                    
                    if(this_.info.spokenword_duration != null){
                        this_.spokenword_hours = Math.round(this_.info.spokenword_duration / 60);
                        this_.spokenword_minutes = this_.info.spokenword_duration % 60;
                    }else{
                        this_.spokenword_hours = null;
                        this_.spokenword_minutes = null;
                    }
                    
                    //Set Show Data
                    this_.show = playsheet.show;
                    console.log(this_.show);
                    this_.playitems = playsheet.playitems;
                    this_.podcast = playsheet.podcast == null ? {} : playsheet.podcast;
                    this_.ads = playsheet.ads;
                    //If no playitems, change "Add Five Rows" button to say "Add Row" instead
                    if(this_.playitems < 1){
                        $('#addRows').text("Add Row");
                    }
                    for(var playitem in this_.playitems){
                        console.log(this_.playitems[playitem]);
                        this_.playitems[playitem].insert_song_start_hour = $filter('pad')( this_.playitems[playitem].insert_song_start_hour , 2);
                        this_.playitems[playitem].insert_song_start_minute = $filter('pad')( this_.playitems[playitem].insert_song_start_minute , 2);
                        this_.playitems[playitem].insert_song_length_minute = $filter('pad')( this_.playitems[playitem].insert_song_length_minute , 2);
                        this_.playitems[playitem].insert_song_length_second = $filter('pad')( this_.playitems[playitem].insert_song_length_second , 2);
                        console.log(this_.playitems[playitem]);
                    }

                    //Get Member shows, and set active show
                    call.getMemberShows(this.member_id).then(function(data){
                        var shows = data.data.shows;
                        this_.member_shows = shows;
                        //Find what show this playsheet is for, and set it as active show to load information.
                        for(var show in this_.member_shows){
                            
                            if(this_.show.name.toString() == shows[show].show.name.toString()){
                                this_.active_show = this_.member_shows[show];
                                this_.show_value = shows[show]['id'];
                                this_.show = shows[show]['show'];
                            }
                        }
                        console.log(this_.active_show);
                        call.getShowPlaysheets(this_.active_show.id).then(function(response){
                            //DISPLAY OLD PLAYSHEETS
                            this_.existing_playsheets = response.data;
                        });
                        //Populate the template row
                        var show_date = this_.start.getDate();
                        this_.row_template = {"show_id":this_.active_show.id,"playsheet_id":this_.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this_.info.crtc,"lang":this_.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
                        this_.checkIfComplete();
                        this_.loading = false;
                    });

                });
            }else{

                this.podcast = {};

                //TODO load ads.

                this.info.status = '1';
                this.info.type='Live';
                this.info.crtc = 30;
                this.info.lang = 'English';
                this.spokenword_hours = null;
                this.spokenword_minutes = null;
                this.podcast.active = 0;

                //Get Shows Listing
                call.getMemberShows(this.member_id).then(function(data){
                    var shows = data.data.shows;
                    this_.member_shows = shows;
                    //Cheat Code to get first active show.
                    for(var show in this_.member_shows){
                        console.log(shows[show].show.name.toString());
                        this_.active_show = this_.member_shows[show];
                        this_.show = this_.active_show.show;

                        this_.show_value = this_.active_show['id'];
                        this_.info.show_id = parseInt(this_.active_show.id);
                        this_.info.host = this_.active_show.show.host;
                        this_.info.create_name = this_.info.host;

                        this_.podcast.author = this_.info.host;
                        for(var playitem in this_.playitems){
                            this_.playitems[playitem].show_id = this_.info.show_id;
                        }
                        break;
                    }
                    var now = $filter('date')(new Date(),'yyyy-MM-dd HH:mm:ss');

                    call.getShowPlaysheets(this_.show_value).then(function(response){
                        //DISPLAY OLD PLAYSHEETS
                        this_.existing_playsheets = response.data;
                    });

                    call.getNextShowTime(this_.active_show.id,now).then(function(response){
                        console.log(response.data);
                        var start_unix = response.data.start;
                        var end_unix = response.data.end;
                        this_.start = new Date(start_unix * 1000);
                        this_.end = new Date(end_unix * 1000);

                        this_.info.unix_time = this_.start.getTime() / 1000;
                        this_.info.start_time = $filter('date')(this_.start,'yyyy-MM-dd HH:mm:ss');
                        this_.info.end_time = $filter('date')(this_.end,'yyyy-MM-dd HH:mm:ss');
                        this_.start_hour =  $filter('pad')(this_.start.getHours(),2);
                        this_.start_minute = $filter('pad')(this_.start.getMinutes(),2);
                        this_.start_second = $filter('pad')(this_.start.getSeconds(),2);
                        this_.end_hour =  $filter('pad')(this_.end.getHours(),2);
                        this_.end_minute = $filter('pad')(this_.end.getMinutes(),2);
                        this_.end_second = $filter('pad')(this_.end.getSeconds(),2);

                        console.log(this_.start_hour);
                        //Populate Template Row, then add 5 rows
                        var show_date = this_.start.getDate();
                                            //Update Podcast information
                        this_.podcast.date = this_.info.start_time;
                        this_.updateEnd();
                        this_.updateStart();
                         this_.row_template = {"show_id":this_.active_show.id,"playsheet_id":this_.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this_.info.crtc,"lang":this_.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
                        this_.addStartRow();
                        for(var i = 0; i<4; i++) {
                            this_.add(this_.playitems.length-1);
                        }
                        call.getAds(start_unix).then(function(response){
                            this_.ads = response.data;
                        });
                        this_.update();
                        if(this_.using_sam){
                            this_.loadSamPlays();
                        }
                        this_.loading = false;
                    });         
                });
            }

            
        }
        //When a playsheet item is added or removed, check for completeness
        $scope.$watchCollection('playsheet.playitems', function () {
            this_.update();
        },true);
        $scope.$watch('playsheet.info.start_time', function () {
            this_.info.start_time = $filter('date')(this_.info.start_time,'yyyy-MM-dd HH:mm:ss');
            this_.start = new Date(this_.info.start_time);
            if(this_.start && this_.end) this_.podcast.duration = (this_.end.getTime() - this_.start.getTime()) /1000;
            console.log("Start Time "+this_.info.start_time);
        });
        $scope.$watch('playsheet.info.end_time', function () {
            this_.info.end_time = $filter('date')(this_.info.end_time,'yyyy-MM-dd HH:mm:ss');
            this_.end = new Date(this_.info.end_time);
            if(this_.start && this_.end) this_.podcast.duration = (this_.end.getTime() - this_.start.getTime()) /1000;
            console.log("End Time " + this_.info.end_time);
        });



        this.update = function(){
            $timeout(function(){this_.checkIfComplete();},100);
        }
        this.checkIfComplete = function(){
            var this_ = this;
            var playsheet_okay = 'true';
            this.missing = "You have empty values";
            if(this.info.start > this.info.end){
                playsheet_okay = false;
            }
            var m= {'artist':0,'song':0,'album':0,'composer':0,'spokenword':0,'episode_title':0,'episode_summary':0};
            $('.required').each(function(index,element){
                $e = element;

                var model = $e.getAttribute('ng-model');
                if( $(element).val() == "" || $(element).val() == null){
                    playsheet_okay='false';
                    switch(model){
                        case "playitem.artist":
                            if(this_.playitems.length>0) m.artist = 1;
                            break;
                        case 'playitem.album':
                            if(this_.playitems.length>0) m.album = 1;
                            break;
                        case 'playitem.song':
                            if(this_.playitems.length>0) m.song = 1;
                            break;
                        case 'playitem.composer':
                            if(this_.playitems.length>0) m.composer= 1;
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
            });
            if(playsheet_okay == 'true'){
                this.complete = true;
            }else{
                this.missing = "You have empty values for these fields:" 
                + (m.artist == 1 ? "an artist,":"") 
                + (m.song == 1 ? 'a song title,':"" )
                + (m.album == 1 ? 'an album,':"")
                + (m.composer == 1 ? 'a composer,':'')
                + (m.spokenword == 1 ? 'your spoken word duration,':"")
                + (m.episode_title ==1 ? 'your episode title,':"")
                + (m.episode_summary == 1 ? 'your episode description':"")
                + '.';
                this.complete = false;
            }
        }
        this.saveDraft = function(){
            var this_ = this;
            this.info.unix_time = this.start.getTime() / 1000;
            var date = $filter('date')(this.start,'yyyy-MM-dd');
            for(var playitem in this_.playitems){
                this_.playitems[playitem].show_date = date;
            }
            this.podcast.show_id = this.info.show_id;
            this.podcast.date = this.info.start_time;
            this.podcast.title = this.info.title;
            this.podcast.subtitle = this.info.summary;
            if(this.info.status <= 1){
                if(this.info.id < 1){
                    //New Playsheet
                    callback = call.saveNewPlaysheet(this_.info,this_.playitems,this_.podcast,this_.ads).then(function(response){
                        for(var playitem in this_.playitems){
                            this_.playitems[playitem].playsheet_id = this_.info.id;
                        }
                        this_.info.id = response.data.id;
                        this_.podcast.id = response.data.podcast_id;
                        this_.podcast.playsheet_id = response.data.id;
                        alert("Draft Saved");
                        
                    },function(error){
                        alert(error);
                    });
                }else{
                    //Existing Playsheet
                    call.savePlaysheet(this_.info,this_.playitems,this_.podcast,this_.ads).then(function(response){
                        alert("Draft Saved");
                    },function(error){
                        alert(error);
                    });
                }
            }else{
                alert("You've already submitted this playsheet, please submit it instead");
            }
            
        }
        //Submit a Playsheet
        this.submit = function () {
            var this_ = this;
            this.info.unix_time = this.start.getTime() / 1000;
             this.podcast.show_id = this.info.show_id;
            this.podcast.active = 1;
            this.podcast.title = this.info.title;
            this.podcast.subtitle = this.info.summary;
            //Ensuring start and end times work for podcast generation
            if(new Date(this.info.start_time) > new Date() || new Date(this.info.end_time) > new Date()){
                alert("Cannot create a podcast in the future, please save as a draft.");
            }else if(new Date(this.info.start_time) > new Date(this.info.end_time)){
                alert("End time is before start time");
            }else{
               //Update Status to submitted playsheet
                this.info.status = 2;
                var date = $filter('date')(this.start,'yyyy-MM-dd');
                for(var playitem in this_.playitems){
                    this_.playitems[playitem].show_date = date;
                }
                this.podcast.date = this.info.start_time;

                if(this.info.id < 1){
                    //New Playsheet
                    this.tracklist_overlay = true;
                    callback = call.saveNewPlaysheet(this_.info,this_.playitems,this_.podcast,this_.ads).then(function(response){
                        for(var playitem in this_.playitems){
                            this_.playitems[playitem].playsheet_id = this_.info.id;
                        }
                        this_.info.id = response.data.id;
                        this_.podcast.id = response.data.podcast_id;
                        this_.podcast.playsheet_id = response.data.id;
                        
                        call.makePodcastAudio(this_.podcast).then(function(reponse){
                            console.log(response.data);
                        });
                    },function(error){
                        alert(error);
                    });
                }else{
                    //Existing Playsheet
                    this.tracklist_overlay = true;
                    callback = call.savePlaysheet(this_.info,this_.playitems,this_.podcast,this_.ads).then(function(response){
                        call.makePodcastAudio(this_.podcast).then(function(reponse){
                            console.log(response.data);
                        });
                    },function(error){
                        alert(error);
                    });
                } 
            } 
        }

        this.addSamPlay = function (sam_playitem) {
            this.playitems.splice(this.playitems.length,0,sam_playitem); 
        };
        this.formatSamPlay = function (sam_play) {
            var djland_entry = angular.copy(this.row_template);
            djland_entry.artist = sam_play.artist;
            djland_entry.album = sam_play.album;
            djland_entry.song = sam_play.title;
            djland_entry.composer = sam_play.composer;
            djland_entry.insert_song_start_hour = $filter('pad')( new Date(sam_play.date_played).getHours(), 2);
            djland_entry.insert_song_start_minute = $filter('pad')( new Date(sam_play.date_played).getMinutes(), 2);
            djland_entry.insert_song_length_minute = $filter('pad')((sam_play.durMin / 60000), 2);
            djland_entry.insert_song_length_second = $filter('pad')( (sam_play.durSec/1000)%60 , 2);
            djland_entry.is_can = sam_play.mood.indexOf('cancon') > -1 ? '1':'0';
            djland_entry.is_fem = sam_play.mood.indexOf('femcon') > -1 ? '1':'0';
            djland_entry.lang = this_.info.lang;
            return djland_entry;
        };
        this.loadSamPlays = function () {
            var this_ = this;
            call.getSamRecent(0).then(function (data) {
                this_.samRecentPlays = [];
                for (var samplay in data.data) {
                    this_.samRecentPlays.push(this_.formatSamPlay(data.data[samplay]));
                }
            });
        };
        this.samRange = function () {
            var this_ = this;
            call.getSamRange($filter('date')(this.start,'yyyy-MM-dd HH:mm:ss'),$filter('date')(this.end,'yyyy-MM-dd HH:mm:ss')).then(function(data){
                for (var samplay in data.data) {
                    this_.addSamPlay(this_.formatSamPlay(data.data[samplay]));
                }
            });
            this.sam_visible= false;
        };
        

        // Call Initialization function at end of controller
        this.init();
    });

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

    //Declares playitem attribute
    app.directive('playitem',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/playitem.html'
    	};
    });
    //Declares ad attribute
    app.directive('ad',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/ad.html'
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

    //TODO: Use Socan Call to get socan status
    var socan = false;
})();



    