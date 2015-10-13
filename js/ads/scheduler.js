(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(call,sam,$q){
    	var this_ = this;

    	call.getAdSchedule().then(function(response){
    		console.log(response.data);
    		this_.showtimes = response.data;
            for(var showtime in this_.showtimes){
                if(this_.showtimes[showtime].ads.length == 0){
                    this_.showtimes[showtime].ads = Array();
                    var duration = this_.showtimes[showtime].duration * 1000;
                    for(var i = 20*60*1000; i < duration; i+= (20*60*1000)){
                        var ad = {'type':'ad','name':''};
                        this_.showtimes[showtime].splice(this_.showtimes[showtime].start_unix + i,0,ad);
                    }
                }
            }
    	});

    	sam.getAdList().then(function(response){
    		this_.ads = response.data;
    	});
    	sam.getStationIDList().then(function(response){
    		this_.stationIDs = response.data;

    	});
        sam.getPromosList().then(function(response){
            this_.promos = response.data;

        });
    	$q.all([sam.getTimelyPSAList(),sam.getCommunityPSAList(),sam.getUBCPSAList()]).then(function(response){
			this_.timelyPSAs = response[0].data;
			this_.communityPSAs = response[1].data;
			this_.UBCPSAs = response[2].data;

			this_.PSAs =  new Array(this_.timelyPSAs.concat(this_.communityPSAs.concat(this_.UBCPSAs)))[0];
    	},function(e1,e2,e3){
    		console.log("error");
    	});


    });
})();