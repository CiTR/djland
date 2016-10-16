(function (){
	var app = angular.module('memberResources',['djland.api']);

	app.controller('memberResourcesController',function(call,$scope,$sce){
		var this_ = this;
		this.htmlcontent = {html:"",trusted:""};
		
		call.getResources().then(
			function(response){
				this_.htmlcontent.html = response.data[0].value;
			},
			function(error_response){
				console.log(error_response);
			}
		);
		$scope.$watch('resources.htmlcontent.html',function(newVal){
			this_.htmlcontent.trusted = $sce.trustAsHtml(newVal);
		},true);
		this.save = function(){
			console.log(this.htmlcontent.html);
			call.saveResources(this.htmlcontent.html).then(
				function(response){
					console.log(response);
				},function(error_response){

				}
			);
		}
		
	});

})();