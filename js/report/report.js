(function (){
	var app = angular.module('djland.report',['djland.api','ui.bootstrap']);
	
	app.controller('reportController',function(call,$filter){
		this.show_filter = 'all';
		
		var date = new Date();
		this.to = $filter('date')(date,'yyyy/MM/dd');
		this.from = $filter('date')(date.setDate(date.getDate() - 1),'yyyy/MM/dd');

		this.search = function(){
			this_ = this;
			call.searchPlaysheets(this.show_filter,this.from,this.to).then(function(response){
				this_.playsheets = angular.copy(response.data);
				console.log(this_.playsheets);
			});
		}
		this.search();
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

    
})();
