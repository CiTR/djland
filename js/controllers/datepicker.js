angular.module('djland.datepicker',[]).controller('datepicker', function($filter) {
  
  this.today = function() {
    this.dt = new Date();
  };

  this.clear = function () {
    this.dt = null;
  };

  this.open = function($event) {
    $event.preventDefault();
    $event.stopPropagation();
    this.opened = true;
  };

  this.format = 'medium';

});