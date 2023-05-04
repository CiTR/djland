(function (){
    var app = angular.module('djland.uploads',['djland.api']);
	app.controller('UploadController',function(call){
		this.uploads = Array();
		this.listUploads = function(){
			call.getUploads().then(
				(function(response){
					this.uploads = response.data;
					console.log(response);
				}).bind(this),
				function(error){

				}
			);
		}
		this.listUploads();
	});
})();