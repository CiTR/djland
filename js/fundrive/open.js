(function (){
	var app = angular.module('djland.open_fundrive',['djland.api']);

	app.controller('openFundrive',function(call){
		this.forms = Array();

		this.load = function(){
			var this_ = this;
			call.getForms().then(function(response){
				this_.forms = response.data;
				console.log(response);


			});
		}
		this.load();
	});

})();

function go(element){
	href = element.getAttribute('data-href');
	window.document.location = href;
	}