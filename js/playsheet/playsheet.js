(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','ui.sortable','ui.bootstrap']);

    var row_template = {"show_id":this.show_id,"playsheet_id":this.id,"song_id":null,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":this.start_time,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this.crtc,"lang":this.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":null,"insert_song_start_minute":null,"insert_song_length_minute":null,"insert_song_length_second":null,"song":{"id":null,"artist":null,"title":null,"song":null,"composer":null}};
	app.controller('PlaysheetController',function($filter,$scope,call){
       	this.id = playsheet_id;
        this.member_id = member_id;

        this.socan = socan;
    	this.tags = tags;
    	this.help = help;
		this.shows = Array();
		var this_ = this;
      	
        this.add = function(id){
            if(id > 0){
                var row = angular.copy(this.playitems[id]);
            }else{
                var row = row_template;

            }
            for(var item in row){
                if(item != 'lang' && item != 'crtc_category'){
                    row[item]=null;
                }else if(item == 'lang'){
                    row[item] = this.lang;
                }else{
                    row[item] = this.crtc;
                }
            }
            this.playitems.splice(id+1,0,row_template); 
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
            this.playitems[0] = row_template;
        }  
        this.updateShowValues = function(){
            this.active_show=this.member_shows[this.show_value];
        }
        

        this.init = function(){
            if(this.id > 0){
                call.getPlaysheetData(this.id).then(function(data){
                    //this_.shows = data;
                    //this_.playsheet_id = data.data.playsheet_id;
                    var playsheet = data.data;
                    for(var item in playsheet.playsheet){
                        this_[item] = playsheet.playsheet[item];
                    }
                    var start = new Date(this_.start_time);
                    var end = new Date(start.getFullYear() +'-'+start.getMonth()+'-'+start.getDate() + " " +this_.end_time);
                    this_.start_hour =  $filter('pad')(start.getHours(),2);
                    this_.start_minute = $filter('pad')(start.getMinutes(),2);
                    this_.end_hour =  $filter('pad')(end.getHours(),2);
                    this_.end_minute = $filter('pad')(end.getMinutes(),2);
                    this_.show = playsheet.show;
                    this_.playitems = playsheet.playitems;
                    if(this_.playitems < 1){
                        $('#addRows').text("Add Row");
                    }
                    this_.ads = playsheet.ads;
                    this_.host = playsheet.host;
                    call.getMemberShows(this.member_id).then(function(data){
                        var shows = data.data.shows;
                        this_.member_shows = shows;
                        for(var show in shows){
                            if(this_.show.name.toString() == shows[show].name.toString()){
                                this_.active_show = shows[show];
                                this_.show_value = show;   
                            }
                        }
                    });
                });


            }else{
                //TODO: Check member id, find possible shows. Load info of next show they have.
                var start = new Date();
                start.setMinutes(0);
                start.setSeconds(0);
                var end = new Date(start);
                end.setHours(end.getHours()+1);
                this.start_time = start;
                this.end_time = end;

                this.start_hour =  $filter('pad')(start.getHours(),2);
                this.start_minute = $filter('pad')(start.getMinutes(),2);
                this.end_hour =  $filter('pad')(end.getHours(),2);
                this.end_minute = $filter('pad')(end.getMinutes(),2);

                this.type='Live';
                this.crtc = 30;
                this.lang = 'English';
                this.id = 1;
               this.addStartRow();
                call.getMemberShows(this.member_id).then(function(data){
                    var shows = data.data.shows;
                    this_.member_shows = shows;
                    console.log(shows);
                    for(var show in shows){
                        this_.active_show = shows[show];
                        this_.show_value = ""+shows[show]['id'] || 'Fill In Host';
                        break;
                    }
                });
            }


        }
        

        this.init();

    });

    app.controller('datepicker', function($filter,playsheet_time) {
      this.today = function() {
        this.dt = new Date();
      };
      this.clear = function () {
        this.dt = null;
      };
      this.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        this.opened = true;
      };
      this.format = 'medium';
      this.start_time = playsheet_time.start_time;
      this.end_time = playsheet_time.end_time;
      this.start_hour = playsheet_time.start_hour;

    });

    app.service('playsheet_time',function(){
        this.start_time;
        this.end_time
        this.start_hour;
        this.end_minute;
        this.end_hour;
        this.end_minute;
        return{
            getStart: function(){
                this.start_time.setHours(this.start_hour);
                this.start_time.setMinutes(this.start_minute);
                return this.start_time;
            },
            getEnd: function(){
                this.end_time.setHours(this.end_hour);
                this.end_time.setMinutes(this.end_minute);
                return this.end_time;
            },setTime: function(start_time,end_time){
                this.start_time = start_time;
                this.end_time=end_time;

                this.start_hour = start_time.getHours();
                this.start_minute = new Date(start_time).getMinutes();

                this.end_hour = new Date(end_time).getHours();
                this.end_minute = new Date(end_time).getMinutes();
            }
        }
    });
    //Declares <playitem> tag
    app.directive('playitem',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/playitem.html'
    	};
    });
    app.directive('ad',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/ad.html'
    	}
    });
    var socan = false;
    
    
	var name = 'Test Show';
})();



    