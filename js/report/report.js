(function (){
	var app = angular.module('djland.report',['djland.api','ui.bootstrap']);

	app.controller('reportController',function(call,$filter){
		this.show_filter = 'all';
		var date = new Date();
		this.to = $filter('date')(date,'yyyy/MM/dd');
		this.from = $filter('date')(date.setDate(date.getDate() - 1),'yyyy/MM/dd');
		this.member_id = $('#member_id').text();
		this.type = 'crtc';
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
			this.report();
		}
		this.report = function(){
			this_ = this;

			call.getReport(this.show_filter,$filter('date')(this.from, 'yyyy/MM/dd'),$filter('date')(this.to,'yyyy/MM/dd')).then(function(response){
				this_.playsheets = angular.copy(response.data);
			});
		}
		this.toggle_print = function(element){
			var button = $('#print_friendly');
			if(button.text() == "Print Friendly View" ){
				button.text("Normal View");
				$('#nav, #filter_bar').hide();
				$('body').removeClass('wallpaper');
				$('.crtc_report').addClass('print_wrapper');
			}else{
			  	button.text("Print Friendly View");
			  	$('#nav, #filter_bar').show();
				$('body').addClass('wallpaper');
				$('.crtc_report').removeClass('print_wrapper');
			}
				
			
		}
		this.init();
	});

	app.controller('datepicker', function($filter) {
		this.today = function() {
			this.dt = $filter('date')(new Date(), 'yyyy/MM/dd');
		};
		this.clear = function () {
			this.dt = null;
		};
		this.open = function($event){
			$event.preventDefault();
			$event.stopPropagation();
			this.opened = true;
		};
		this.format = 'yyyy-MM-dd';
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
	app.filter('percentage', ['$filter', function ($filter) {
		return function (input, decimals) {
			return $filter('number')(input * 100, decimals) + '%';
		};
	}]);


})();
