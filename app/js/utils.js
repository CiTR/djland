
var app = angular.module('djland.utils',[]);
app.factory('tools',function(){
  return{
    decodeHTML : function (str){
        if(str != null){
          	str = str.replace(new RegExp('&quot;','gi'),'"');
          	str = str.replace(new RegExp('&Atilde;','gi'),'Ã');
          	str = str.replace(new RegExp('&copy;','gi'),'©');
          	return str.replace(/&#(\d+);/g, function(match, dec) {
              	return String.fromCharCode(dec);
          	});
        }else{
        	return "";
        }
            
      }
  	}
});

app.controller('datepicker', function($filter) {
      this.update = function(date){
        this.dt = date;
        console.log(this.dt);
      }
      this.today = function() {
        this.dt = $filter('date')(new Date(),'yyyy/MM/dd HH:mm:ss');
      };
      this.clear = function () {
        this.dt = null;
      };
      this.open = function($event) {

        $event.preventDefault();
        $event.stopPropagation();
        this.opened = true;
      };
      this.setHour = function(date){
        date.date = '0';
      }
      this.format = 'yyyy-MM-dd HH:mm:ss';
    });

app.filter('range', function($filter) {
  return function(input, min, max) {
    min = parseInt(min); //Make string input int
    max = parseInt(max);
    for (var i=min; i<max; i++)
      input.push($filter('pad')(i,2));
    return input;
  };
});

app.controller('timepicker', function($scope, $filter, timezone_offset) {
//  var episode = $scope.$parent.episode;
//  episode.time = episode.date;
//  episode.duration_obj = new Date((episode.duration-timezone_offset) * 1000);

  $scope.start_changed = function(time){
    var hh = time.getHours();var mm = time.getMinutes();var ss = time.getSeconds();
    var episode_date = new Date(episode.date);
    episode_date.setHours( hh);episode_date.setMinutes( mm);episode_date.setSeconds( ss);
    episode.date = episode_date;//$filter('date')(episode_date, 'medium');
    episode.date_unix = episode_date.getTime() / 1000;

    episode.updateTimeObjs()
  };

  $scope.length_changed = function(time){

    var existing_duration = time.getSeconds();
//    episode.duration = ( time.getTime() / 1000 ) + timezone_offset;
    var hh = time.getHours();var mm = time.getMinutes();var ss = time.getSeconds();

    var new_end_date = new Date(episode.date);
    var start_hh = new_end_date.getHours();
    var start_mm = new_end_date.getMinutes();
    var start_ss = new_end_date.getSeconds();

    new_end_date.setSeconds(start_ss + ss + timezone_offset);
    new_end_date.setMinutes(start_mm + mm);
    new_end_date.setHours(start_hh + hh);

    episode.end_obj = new_end_date;
    episode.updateTimeObjs()

  };
});


app.filter('range', function($filter) {
  return function(input, min, max) {
    min = parseInt(min); //Make string input int
    max = parseInt(max);
    for (var i=min; i<max; i++)
      input.push($filter('pad')(i,2));
    return input;
  };
});

app.filter('rangeByFives', function($filter) {
  return function(input, min, max) {
    min = parseInt(min); //Make string input int
    max = parseInt(max);
    for (var i=min; i<max;){
      input.push($filter('pad')(i,2));
      i+=5;
    }
    return input;
  };
});

app.filter('rangeNoPad', function() {
  return function(input, min, max) {
    min = parseInt(min); //Make string input int
    max = parseInt(max);
    for (var i=min; i<max; i++)
      input.push(i);
    return input;
  };
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