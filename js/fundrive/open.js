(function (){
	var app = angular.module('djland.open_fundrive',['djland.api']);

	app.controller('openFundrive',function(call){
		this.forms = Array();
		this.donationTotal = 0;

		this.load = function(){
			var this_ = this;
			call.getForms().then(function(response){
				this_.forms = response.data;
			});
			call.getFundriveTotals().then(function(response){
				this_.donationTotal = parseFloat(response.data);
			});
		}
		this.load();
		});
})();

function go(element){
	href = element.getAttribute('data-href');
	window.document.location = href;
}
