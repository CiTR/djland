<?php


session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

// Echos HTML head
echo "<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<base href='shows.php'>
	<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
<link rel=\"stylesheet\" href=\"css/style.css\" type=\"text/css\">";


?>

<title>DJ-L Special Broadcasts</title>

<style >

#new_sb{
background-color:salmon;
}

  button{
    color:black;
  }

  input.date_picker{
    width:30% !important;
  }
</style>
</head>
<body>

<?php
print_menu();
?>

<div ng-app="djLand"  id="mainleft">
  <div ng-controller="specialBroadcasts">

    <h2>special broadcasts</h2>
    <center>
    <button class="bigbutton" ng-click="creating = !creating" ng-click="new_sb = {};">Create New</button>
    </center><hr/>

    <div id="new_sb" ng-controller="singleSB" ng-show="creating">

      <form>
      {{sb}}
      <label>Name:</label> <input ng-model="new_sb.name"><br/>
      <label>Show ID:</label> <input ng-model="new_sb.show_id"><br/>
      <label>Description:</label> <textarea ng-model="new_sb.description" rows="10"></textarea><br/>

      <span ng-controller='datepicker' class='date_pick'>
        <label>Start:</label>
          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="$parent.new_sb.start"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="false" />
        <button ng-click="open($event)">...</button>

      </span><br/>
      <span ng-controller='datepicker' class='date_pick'>
        <label>End:</label>
          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="$parent.new_sb.end"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="false" />

        <button ng-click="open($event)">...</button>

      </span>




<br/>
        <label>Image:</label> <input ng-model="new_sb.image" placeholder="http://address.of/image.jpg"><br/>
      <label>URL:</label> <input ng-model="new_sb.url"><br/>
      {{message}}<br/><br/>{{new_sb}}
      <center>
      <button class=bigbutton ng-click="create(new_sb)">Done</button>
      </center>
      </form>
    </div>




    <div ng-repeat="sb in specialBroadcasts  track by sb.id " >
      <div ng-controller="singleSB">
      <label>Name:</label> <input ng-model="sb.name"><br/>
      <label>Show ID:</label> <input ng-model="sb.show_id"><br/>
      <label>Description:</label> <textarea ng-model="sb.description" rows="10"></textarea><br/>

      <label>Image:</label> <input ng-model="sb.image"><br/>
      <label>URL:</label> <input ng-model="sb.url"><br/>


      <span ng-controller='datepicker' class='date_pick'>
        <label>Start:</label>
          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="$parent.sb.start"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="false" />
        <button ng-click="open($event)">...</button>

      </span><br/>
      <span ng-controller='datepicker' class='date_pick'>
        <label>End:</label>
          <input class="date_picker" type="text" datepicker-popup="{{format}}"
                 ng-model="$parent.sb.end"  is-open="opened"
                 ng-required="true" close-text="Close" ng-hide="false" />

        <button ng-click="open($event)">...</button>

      </span><br/>
{{sb}}


        <button class=bigbutton ng-click="save(sb);">Save {{sb.name}}</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button  ng-click="delete(sb);">Delete {{sb.name}}</button>
      {{message}}
      <hr/>
      </div>
    </div>

  </div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script src="js/angular.js"></script>
<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>

	<script type="text/javascript">
		var djland = angular.module('djLand', ['ui.bootstrap']);

    djland.controller('specialBroadcasts', ['$scope', 'apiService', function($scope, apiService){
      apiService.getSpecialBroadcasts().then(function(result){
        for(var i in result.data){
          var start =  new Date(parseInt(result.data[i].start) * 1000);
          result.data[i].start = start.toISOString();

          var end = new Date(parseInt(result.data[i].end) * 1000);
          result.data[i].end = end.toISOString();
        }
        $scope.specialBroadcasts = result.data;
      });


    }]);

    djland.controller('singleSB', ['$scope', 'apiService', function($scope, apiService){


      $scope.save = function(sb){
        $scope.message = 'saving...'
        apiService.saveSpecialBroadcast(sb).success(function(result){
          $scope.message = 'result: '+result.message;
        }).error(function(result){
          $scope.message = 'error: '+result.message;
        })
      }



      $scope.create = function(sb){
        $scope.message = 'creating...';
        apiService.createSpecialBroadcast(sb).success(function(result){
          var new_sb = sb;
          new_sb.id = result.id
          $scope.$parent.specialBroadcasts.push(new_sb);
          $scope.message = 'save success ('+result.id+')';
//          $scope.$parent.creating = false;

        }).error(function(result){
          $scope.message = 'error: '+result.message;
        });
      };



    }]);



	</script>
	<script src="js/angular-djland.js"></script>


</body>
</html>

