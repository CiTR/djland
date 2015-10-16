(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(call,sam,$q,$filter,$scope){
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
             this_ = this;
            if(!this.loading){
                var items = (this_.loaded + 5 >= this_.dataset.length) ? this_.dataset.length - this.loaded - 1 : 4;
                if(items != 0){
                    this.loading = true;           
                    window.setTimeout(function(){
                    
                        this_.showtimes = this_.showtimes.concat(this_.dataset.slice(this_.loaded,this_.loaded + items));
                        this_.loaded += 5;
                        console.log('loading '+(items+1)+' more shows');
                        this_.loading = false;
                        $scope.$apply();
                    },1000);
                }
            }
        }
        this.add = function(show_index){
            var time = $('#ad_time_'+show_index).val();
            var type = $('#ad_type_'+show_index).val();
            var unix = $('#unix_'+show_index).val();
            var ad = {'type':type,'time':time,'time_block':unix,'name':'' };
            this.showtimes[show_index].ads.splice(this.showtimes[show_index].ads.length,0,ad);
        }
        this.remove = function(show_index,ad_index){
            console.log(show_index+','+ad_index);
            this.showtimes[show_index].ads.splice(ad_index,1);
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