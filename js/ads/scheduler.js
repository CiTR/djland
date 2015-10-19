(function(){
    var app = angular.module('djland.adScheduler',['djland.api','sam.api','djland.utils']);

    app.controller('adScheduler',function(call,sam,$q,$filter,$scope){
    	var this_ = this;
        this.loading = true;
        this.loaded = 0;
        this.finished = false;
    	

    	
        this.loadPSAs = function(){
            var this_ = this;
            $q.all([sam.getTimelyPSAList(),sam.getCommunityPSAList(),sam.getUBCPSAList()]).then(function(response){
                this_.timelyPSAs = response[0].data;
                this_.communityPSAs = response[1].data;
                this_.UBCPSAs = response[2].data;

                this_.PSAs =  new Array(this_.timelyPSAs.concat(this_.communityPSAs.concat(this_.UBCPSAs)))[0];
            },function(e1,e2,e3){
                this_.loadPSAs();
                console.log('Loading PSAs failed, trying again');
            });
        }
        this.init = function(){
            call.getAdSchedule().then(function(response){
                this_.loading = true;
                this_.dataset = response.data.sort(function(a, b) {
                    return a.start_unix - b.start_unix;
                });
                
                //Handling legacy items
                for(var item in this_.dataset){
                    for(var ad in this_.dataset[item].ads){
                        switch(this_.dataset[item]['ads'][ad].type){
                            case 'AD (PRIORITY)' : this_.dataset[item]['ads'][ad].type = 'ad';
                                break;
                            case 'Station ID' : this_.dataset[item]['ads'][ad].type = 'station id';
                                break;
                            case 'PSA' : this_.dataset[item]['ads'][ad].type = 'psa';
                                break;
                            case 'Show Promo' : this_.dataset[item]['ads'][ad].type = 'promo';
                                break;
                            case '' : 
                                this_.dataset[item]['ads'][ad].type = 'announcement';
                                break;
                            default:
                                break;
                        }
                    }
                }

                this_.showtimes = angular.copy(this_.dataset.slice(0,20));
                this_.loaded = 20;
                this_.loading = false;
            });
            sam.getAdList().then(function(response){
                this_.ads = response.data;
            },function(error){
                console.log(error);
            });
            sam.getStationIDList().then(function(response){
                this_.stationIDs = response.data;

            },function(error){
                console.log(error);
            });
            sam.getPromosList().then(function(response){
                this_.promos = response.data;

            },function(error){
                console.log(error);
            });
            this.loadPSAs();
        }
    	

        this.load = function(){
            if(!this.finished){
                this_ = this;
                if(!this.loading){
                    var items = (this_.loaded + 5 >= this_.dataset.length) ? this_.dataset.length - this.loaded - 1 : 4;
                    if(items != 0){
                        this.loading = true;           
                        window.setTimeout(function(){
                            this_.showtimes = this_.showtimes.concat(angular.copy(this_.dataset.slice(this_.loaded,this_.loaded + items)));
                            this_.loaded += 5;
                            console.log('loading '+(items+1)+' more shows');
                            this_.loading = false;
                            $scope.$apply();
                        },1000);
                    }else{
                        this.finished = true;
                    }
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
        this.save = function(){
            var button = $('#ad_schedule_save');
            button.addClass('saving');
            button.text('Saving...');
            /* Dirty Data Checking
            var data = Array();
            for(var showtime in this.showtimes){
                
                if(!angular.equals(this.showtimes[showtime],this.dataset[showtime])){
                    data.push(this.showtimes[showtime].ads);
                }
            } */ 
            var this_ = this;
            call.saveAds(this.showtimes).then(function(response){
                var data = response.data;

                for(var ad_time in data){
                    this_.showtimes.filter(function( obj ) {
                        if(obj.start_unix == ad_time){
                            obj.ads = data[ad_time];
                        }
                    });
                    button.removeClass('saving');
                    button.text('Save Ad Schedule');
                }
                console.log(response);

            },function(error){
                console.log(error.getMessage());
            });

        }

        
        this.init();
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