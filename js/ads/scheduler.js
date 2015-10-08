(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(sam,$q){
    	var this_ = this;

    	sam.getAdList().then(function(response){
    		this_.ads = response.data;
    	});
    	sam.getStationIDList().then(function(response){
    		this_.stationIDs = response.data;

    	});

    	$q.all([sam.getTimelyPSAList(),sam.getCommunityPSAList(),sam.getUBCPSAList()]).then(function(response){
			this_.timelyPSAs = response[0].data;
			this_.communityPSAs = response[1].data;
			this_.UBCPSAs = response[2].data;

			this_.PSAs =  new Array(this_.timelyPSAs.concat(this_.communityPSAs.concat(this_.UBCPSAs)))[0];
			console.log(this_.PSAs);
    	},function(e1,e2,e3){
    		console.log("error");
    	});
    });
})();