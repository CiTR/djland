(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(call,sam,$q,$filter){
    	var this_ = this;
        this.loading = true;
        this.loaded = 0;
    	call.getAdSchedule().then(function(response){
    		this_.loading = true;
            this_.dataset = response.data.sort(function(a, b) {
                return a.start_unix - b.start_unix;
            });
            this_.showtimes = this_.dataset.slice(0,19);
            this_.loaded = 20;
            this_.loading = false;
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

        this.load = function(){
            if(!this.loading){
                this.loading = true;
                this.showtimes = this.showtimes.concat(this.dataset.slice(this.loaded,this.loaded+19));
                this.loaded += 20;
                this.loading = false;
                console.log('loading more');
            }
        }

        

    });
     app.directive('scrolly', function () {
            return {
                restrict: 'A',
                link: function (scope, element, attrs) {
                    var raw = element[0];
                    console.log('loading directive');
                    element.bind('scroll', function () {
                        console.log('in scroll');
                        console.log(raw.scrollTop + raw.offsetHeight);
                        console.log(raw.scrollHeight);
                        if (raw.scrollTop + raw.offsetHeight + raw.scrollHeight/5 >= raw.scrollHeight ) {
                            scope.$apply(attrs.scrolly);
                            //raw.scrollTop = (raw.scrollTop+raw.offsetHeight);
                            console.log("Hit Bottom");
                        }
                    });
                }
            };
        });

})();