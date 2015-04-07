

    app.controller('showCtrl', ['$scope','API_URL_BASE','apiService',function($scope, API_URL_BASE, apiService){


      $scope.showData = {};


      apiService.getShowData(278)
          .then(function(response){
              $scope.showData = response.data  ;

      });

      $scope.save = function(){
        $scope.message = 'saving...';
        apiService.saveShowData($scope.showData)
            .then(function(response){
              $scope.message = response.data.message;
            });
      }



    }])


//

;