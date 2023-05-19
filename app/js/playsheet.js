(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','playsheet.constants','ui.sortable','ui.bootstrap']);
	var shows;
	app.controller('PlaysheetController',
		function($filter,call){
	    	this.id = id;
	    	this.socan = socan;
	    	this.name = name;
	    	this.tags = tags;
	    	this.help = help;
			this.shows = Array();
			call.getEveryonesPlaysheets(10).then(
				(
					function(data){
						this.playsheet_id = data.data[0].playsheet_id;
						return call.getFullPlaylistData(data.data[3].playsheet_id)
					}
				).bind(this)
			).then(
				(
					function(data){
						var playsheet = data.data;
						this.playsheet = playsheet;
						this.playitems = playsheet.plays;
						this.ads = playsheet.ads;
						this.edit_date = playsheet.edit_date;
						this.host_name = playsheet.hostname;
					}
				).bind(this)
			);
	    }
	);

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
    var socan = true;

	var id = '101';
	var name = 'Test Show';

})();
