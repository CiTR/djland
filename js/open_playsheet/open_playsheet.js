(function (){
	var app = angular.module('openPlaysheet',['djland.api']);
	
	app.controller('openPlaysheetController',function(call){
		this.member_id = $('#member_id').attr('value');
		console.log(this.member_id);
		this_=this;
		call.getMemberPlaysheets(this_.member_id).then(function(playsheets){
			this_.playsheets = playsheets.data;
		});		
	});
})();

function go(href){
	window.document.location = href;
	}