(function (){
	var app = angular.module('openPlaysheet',['djland.api']);
	
	app.controller('openPlaysheetController',function(call){
		this_=this;
		call.getUserPlaysheets(15).then(function(playsheets){
			this_.playsheets = playsheets.data;
		});
	});
	

})();