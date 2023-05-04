(function (){
	var app = angular.module('djland.open_fundrive',['djland.api']);

	app.controller('openFundrive',
		function(call){
			this.forms = Array();
			this.donationTotal = 0;
			this.load = function(){
				call.getForms().then(
					(
						function(response){
							this.forms = response.data;
						}
					).bind(this)
				);
				call.getFundriveTotals().then(
					(
						function(response){
							this.donationTotal = parseFloat(response.data);
						}
					).bind(this)
				);
			}
			this.load();
		}
	);
})();

function go(element){
	href = element.getAttribute('data-href');
	window.document.location = href;
}
