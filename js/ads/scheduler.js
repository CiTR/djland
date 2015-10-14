(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(call,sam,$q,$filter){
    	var this_ = this;

    	call.getAdSchedule().then(function(response){
    		
    		this_.showtimes = response.data;
            for(var showtime in this_.showtimes){
                if(this_.showtimes[showtime].ads.length == 0){
                    this_.showtimes[showtime].ads = Array();
                    var duration = this_.showtimes[showtime].duration * 1000;
                    
                    var i = 0;
                    var date;
                    var ad;
                    var psa;
                    while(i < duration){
                        date = new Date(this_.showtimes[showtime].start_unix * 1000 + i);
                        
                        if(date.getMinutes() == '00'){
                            var id = {'type':'station id','name':'','time': $filter('date')(date,'h:mm a')};
                            this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,id);
                        }else if(date.getMinutes() == '10'){
                            var id = {'type':'station id','name':'','time': $filter('date')(new Date(this_.showtimes[showtime].start_unix * 1000 + i - 10*60*1000),'h:mm a')};
                            this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,id);
                        }
                        ad = {'type':'ad','name':'Test','time': $filter('date')(date,'h:mm a')};
                        psa = {'type':'psa','name':'','time':$filter('date')(date,'h:mm a')};

                        this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,ad);
                        this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,psa);
                        
                        i+= (20*60*1000);
                    }
                    var promo = {'type':'promo','name':'','time': $filter('date')(date,'h:mm a')};
                    this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,promo);
                    
                    var outro = {'type':'announcement','name':'Please announce the upcoming program','time': $filter('date')(date,'h:mm a')};
                    this_.showtimes[showtime].ads.splice(this_.showtimes[showtime].start_unix,0,outro);
                    
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