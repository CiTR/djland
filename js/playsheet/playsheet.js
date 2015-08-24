(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','djland.datepicker','ui.sortable','ui.bootstrap']);
	var shows;
	app.controller('PlaysheetController',function($filter,call){
       	this.id = playsheet_id;
        console.log(playsheet_id);
        this.socan = socan;
    	this.tags = tags;
    	this.help = help;
		this.shows = Array();
		var this_ = this;
      	
        this.add = function(id){
            var row_template = angular.copy(this.playitems[id]);
            for(var item in row_template){
                if(item != 'lang' && item != 'crtc_category'){
                    row_template[item]=null;
                }else if(item == 'lang'){
                    row_template[item] = this.lang;
                }else{
                    row_template[item] = this.crtc;
                }
            }
            this.playitems.splice(id+1,0,row_template); 
            console.log("adding");
        }
        this.remove = function(id){
            this.playitems.splice(id,1);
        }
        this.addFiveRows = function(){
            for(var i=0;i<5;i++){
                this.add(this.playitems.length-1);
            }
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
                this_.show = playsheet.show;
                this_.playitems = playsheet.playitems;
                this_.ads = playsheet.ads;
                this_.hosts = playsheet.hosts;
                console.log(this_);

                });
            }else{
                //TODO: Check member id, find possible shows. Load info of next show they have.
                var date = new Date();
                date.setMinutes(0);
                date.setSeconds(0);
                this.start_time = date.getTime();
                this.start_hour = date.getHours();
                console.log(this.start_hour);
                this.type='Live';
                this.crtc = 30;
                this.lang = 'English';
                this.id = 1;
               this.addStartRow();
            }
        }
        this.addStartRow = function(){
            var template = {"show_id":this.show_id,"playsheet_id":this.id,"song_id":null,"format_id":null,"is_playlist":0,"is_canadian":0,"is_yourown":0,"is_indy":0,"is_fem":0,"show_date":this.start_time,"duration":null,"is_theme":null,"is_background":null,"crtc_category":this.crtc,"lang":this.lang,"is_part":0,"is_inst":0,"is_hit":0,"insert_song_start_hour":null,"insert_song_start_minute":null,"insert_song_length_minute":null,"insert_song_length_second":null,"song":{"id":null,"artist":null,"title":null,"song":null,"composer":null}};
            this.playitems = Array();
            this.playitems[0] = template;
        }  
        this.init();

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



    