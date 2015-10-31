(function (){
	var app = angular.module('djland.report',['djland.api','ui.bootstrap']);

	app.controller('reportController',function(call,$filter){
		this.show_filter = 'all';
		var date = new Date();
		this.to = $filter('date')(date,'yyyy/MM/dd');
		this.from = $filter('date')(date.setDate(date.getDate() - 1),'yyyy/MM/dd');
		this.member_id = $('#member_id').text()
		var this_ = this;

		this.init = function(){
			call.getActiveMemberShows( this.member_id ).then(function(response){
				this_.shows = response.data.shows;
				console.log(this_.shows);
			});
			call.getMemberPermissions(this.member_id).then(function(response){
                if(response.data.administrator == '1' || response.data.staff == '1' ){
                    this_.is_admin = true;
                }else{
                    this_.is_admin = false;
                }
            },function(error){
                  
            });
			this.search();
		}
		this.search = function(){
			this_ = this;
			call.searchPlaysheets(this.show_filter,this.from,this.to).then(function(response){
				this_.playsheets = angular.copy(response.data);
			});
		}
		this.toggle_print = function(element){
			if( $(element).text() == "Print Friendly View" ){

			}else{
			  
			}
			$(element).text('Normal View');
			$('#admin-nav, #nav, #tab-nav, #headerrow, #membership_header').hide();
		}
		this.init();
	});

	app.controller('datepicker', function($filter) {
		this.today = function() {
			this.dt = $filter('date')(new Date(),'yyyy/MM/dd');
		};
		this.clear = function () {
			this.dt = null;
		};
		this.open = function($event){

			$event.preventDefault();
			$event.stopPropagation();
			this.opened = true;
		};
		this.format = 'yyyy/MM/dd';
		});
		app.directive('reportitem',function(){
		return{
			restrict: 'A',
			templateUrl: 'templates/report_item.html'
		}
	});
	app.filter('pad', function () {
	  return function (n, len) {
	    var num = parseInt(n, 10);
	    len = parseInt(len, 10);
	    if (isNaN(num) || isNaN(len)) {
	      return n;
	    }
	    num = ''+num;
	    while (num.length < len) {
	      num = '0'+num;
	    }
	    return num;
	  };
	});


})();
