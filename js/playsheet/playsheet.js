(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','ui.sortable','ui.bootstrap']);

	app.controller('PlaysheetController',function($filter,$scope,$interval,$timeout,call){
        this.info = Array();
        this.shows = Array();
        this.info.id = playsheet_id;
        this.member_id = member_id;
        var this_ = this;
        //Helper Variables
        this.sam_visible = false;
        this.socan = socan;
    	this.tags = tags;
    	this.help = help;
        this.complete = false;

        this.add = function(id){
            var row = angular.copy(this_.row_template);
            $scope.$apply( function(){} );
        
            this_.playitems.splice(id+1,0,row);

        }
        this.remove = function(id){
            this.playitems.splice(id,1);
            if(this.playitems.length < 1){
                $('#addRows').text("Add Row");
            }
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
        this.updateShowValues = function(){
            this.active_show = this.member_shows.filter(function(object){if(object.id == this_.show_value) return object;})[0];
        }
        this.updateSpokenword = function(){
            this.info.spokenword_duration = this.spokenword_hours * 60 + this.spokenword_minutes;
        }
        this.updateStart = function(){
            this.start.setHours(this.start_hour);
            this.start.setMinutes(this.start_minute);
            this.start.setSeconds(this.start_second);
            this.info.start_time = this.start;
        }
        this.updateEnd = function(){
            this.end.setHours(this.end_hour);
            this.end.setMinutes(this.end_minute);
            this.end.setSeconds(this.end_second);
            this.info.end_time = this.end;
        }

        //Setting Show Times
        this.startShow = function(){
            this.start = new Date();
            this.start_hour =  $filter('pad')(this.start.getHours(),2);
            this.start_minute = $filter('pad')(this.start.getMinutes(),2);
            this.start_second = $filter('pad')(this.start.getSeconds(),2);
            this.info.start_time = this.start;
        }
        this.endShow = function(){
            this.end = new Date();
            this.end_hour =  $filter('pad')(this.end.getHours(),2);
            this.end_minute = $filter('pad')(this.end.getMinutes(),2);
            this.end_second = $filter('pad')(this.end.getSeconds(),2);
            //this.end_time = $filter('date')(end, 'HH:mm:ss');
            this.info.end_time = this.end.getHours();
        }

        //Initialization of Playsheet
        this.init = function(){
            console.log(this.info.id);
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
                    this_.end = new Date(this_.start.getFullYear() +'-'+this_.start.getMonth()+'-'+this_.start.getDate() + " " +this_.info.end_time);
                    this_.start_hour =  $filter('pad')(this_.start.getHours(),2);
                    this_.start_minute = $filter('pad')(this_.start.getMinutes(),2);
                    this_.start_second = $filter('pad')(this_.start.getSeconds(),2);
                    this_.end_hour =  $filter('pad')(this_.end.getHours(),2);
                    this_.end_minute = $filter('pad')(this_.end.getMinutes(),2);
                    this_.end_second = $filter('pad')(this_.end.getSeconds(),2);
                    
                    this_.spokenword_hours = Math.round(this_.info.spokenword / 60);
                    this_.spokenword_minutes = this_.info.spokenword % 60;
                    //Set Show Data
                    this_.show = playsheet.show;
                    this_.playitems = playsheet.playitems;
                    this_.ads = playsheet.ads;
                    this_.host = playsheet.host;
                    //If no playitems, change "Add Five Rows" button to say "Add Row" instead
                    if(this_.playitems < 1){
                        $('#addRows').text("Add Row");
                    }
                    //Get Member shows, and set active show
                    call.getMemberShows(this.member_id).then(function(data){
                        var shows = data.data.shows;
                        this_.member_shows = shows;
                        //Find what show this playsheet is for, and set it as active show to load information.
                        for(var show in this_.member_shows){
                            if(this_.show.name.toString() == shows[show].name.toString()){
                                this_.active_show = this_.member_shows[show]; 
                                this_.show_value = this_.active_show['name'];
                            }
                        }
                        //Populate the template row
                        var show_date = this_.start.getDate();
                        this_.row_template = {"show_id":this_.active_show.id,"playsheet_id":this_.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this_.info.crtc,"lang":this_.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
                        this_.checkIfComplete();
                    });
                });
            }else{
                //TODO: Check member id and find possible upcoming show time. Load info of next show they have.
                
                //Create Extra Variables to allow proper display in UI
                this.start = new Date();
                this.start.setMinutes(0);
                this.start.setSeconds(0);
                this.end = new Date(this.start);
                this.end.setHours(this.end.getHours()+1);
                this.info.start_time = this.start;
                this.info.end_time = this.end.getHours + ":" + this.end.getMinutes() + ":" + this.end.getSeconds;
                this.start_hour =  $filter('pad')(this.start.getHours(),2);
                this.start_minute = $filter('pad')(this.start.getMinutes(),2);
                this.start_second = $filter('pad')(this.start.getSeconds(),2);
                this.end_hour =  $filter('pad')(this.end.getHours(),2);
                this.end_minute = $filter('pad')(this.end.getMinutes(),2);
                this.end_second = $filter('pad')(this.end.getSeconds(),2);
                this.info.status = '1';
                this.info.type='Live';
                this.info.crtc = 30;
                this.info.lang = 'English';
                this.info.id = -1;
                this.spokenword_hours = null;
                this.spokenword_minutes = null;
                //Get Shows Listing
                call.getMemberShows(this.member_id).then(function(data){
                    var shows = data.data.shows;
                    this_.member_shows = shows;
                    //Cheat Code to get first active show.
                    for(var show in shows){
                        this_.active_show = shows[show];
                        break;
                    }
                    this_.show_value = this_.active_show['name'];
                    //Populate Template Row, then add 5 rows
                    var show_date = this_.start.getDate();
                    this_.row_template = {"show_id":this_.active_show.id,"playsheet_id":this_.info.id,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":show_date,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this_.info.crtc,"lang":this_.info.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":"00","insert_song_start_minute":"00","insert_song_length_minute":"00","insert_song_length_second":"00","artist":null,"title":null,"song":null,"composer":null};
                    this_.addStartRow();
                    for(var i = 0; i<4; i++) {
                        this_.add(this_.playitems.length-1);
                    }
                    
                });
            }
        }
        //When a playsheet item is added or removed, check for completeness
        $scope.$watch('playsheet.playitems', function () {
            this_.checkIfComplete();
        },true);
        this.checkIfComplete = function(){
            var playsheet_okay = 'true';
            this.missing = "You have empty values";
            if(this.info.start > this.info.end){
                playsheet_okay = false;
            }
            var m= {'artist':0,'song':0,'title':0,'composer':0,'spokenword':0,'podcast':0};
            $('.required').each(function(index,element){
                $e = element;
                var model = $e.getAttribute('ng-model');
                if( $(element).val() == "" || $(element).val() == null){
                    playsheet_okay='false';
                    switch(model){
                        case "playitem.song.artist":
                            m.artist = 1;
                            break;
                        case 'playitem.song.title':
                            m.title = 1;
                            break;
                        case 'playitem.song.song':
                            m.song = 1;
                            break;
                        case 'playitem.song.composer':
                            m.composer= 1;
                            break;
                        case 'playsheet.spokenword_hours':
                            m.spokenword = 1;
                            break;
                        case 'playsheet.spokenword_minutes':
                            m.spokenword = 1;
                            break;
                        case 'playsheet.info.spokenword':
                            m.podcast = 1;
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
                + (m.song == 1 ? 'a title,':"" )
                + (m.title == 1 ? 'an album,':"")
                + (m.composer == 1 ? 'a composer,':'')
                + (m.spokenword == 1 ? 'your spoken word duration,':"")
                + (m.podcast == 1 ? 'your podcast description':"")
                + '.';
                this.complete = false;
            }
        }
        this.saveDraft = function(){
            if(this.id > 1){
                 //New Playsheet
                call.saveNewPlaysheet(angular.toJson(this_.info.id),angular.toJson(this_.playitems)).then(function(response){
                    alert("Draft Saved");
                },function(error){
                    alert(error.responseText);
                });
            }else{
                alert("You have already submitted this playsheet");
            }
        }
        //Submit a Playsheet
        this.submit = function () {
            this.status = 2;
            var this_ = this;
            var date = $filter('date')(this.start,'yyyy-MM-dd');
            console.log(date);
            for(var playitem in this_.playitems){
                this_.playitems[playitem].show_date = date;
            }
            if(this.id > 1){
                //New Playsheet
                call.saveNewPlaysheet(angular.toJson(this_.info.id),angular.toJson(this_.playitems)).then(function(response){
                    this_.tracklist_overlay = true;
                },function(error){
                    alert(error.responseText);
                });
            }else{
                console.log(this_.info);
                console.log(this_.playitems);
                //Existing Playsheet
                call.savePlaysheet(this_.info,this_.playitems).then(function(response){
                    this_.tracklist_overlay = true;
                },function(error){
                    alert(error.responseText);
                });
            }
        }   

        this.addSamPlay = function (sam_playitem) {
            this.playitems.splice(this.playitems.length,0,sam_playitem); 
        };
        this.loadSamPlays = function () {
            call.getRecentSamPlays().then(function (data) {
                this_.samRecentPlays = [];
                for (var samplay in data) {
                    samRecentPlays.push(this_.formatSamPlay(samplay));
                }
            });
        };
        this.formatSamPlay = function (sam_play) {
            var djland_entry = angular.copy(row_template);
            djland_entry.song.artist = sam_play.artist;
            djland_entry.song.title = sam_play.album;
            djland_entry.song.song = sam_play.title;
            djland_entry.song.composer = sam_play.composer;
            djland_entry.insert_song_start_hour = $filter('pad')(sam_play.hour, 2);
            djland_entry.insert_song_start_minute = $filter('pad')(sam_play.minute, 2);
            djland_entry.insert_song_length_minute = $filter('pad')(sam_play.durMin, 2);
            djland_entry.insert_song_length_second = $filter('pad')(sam_play.durSec, 2);
            djland_entry.crtc_category = this_.playsheet.crtc;
            djland_entry.lang = this_.playsheet.lang;
            return djland_entry;
        };
        // Call Initialization function at end of controller
        this.init();
    });

    app.controller('datepicker', function($filter) {
      this.today = function() {
        this.dt = new Date();
      };
      this.clear = function () {
        this.dt = null;
      };
      this.open = function($event) {
        console.log('event');
        $event.preventDefault();
        $event.stopPropagation();
        this.opened = true;
      };
      this.format = 'medium';
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

    //TODO: Use Socan Call to get socan status
    var socan = false;
})();



    