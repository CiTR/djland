

    app.controller('showCtrl', ['$scope','API_URL_BASE','apiService','$location',function($scope, API_URL_BASE, apiService, $location){

      $scope.dj_edit_fields_only = true;// TODO - if robin editing, set to false, migrate markup from php

      $scope.showData = {}; // <- gets all loaded from server
      $scope.formData = {}; // <- all private
      $scope.formData.show_id = $location.search().id;
      var editable_by_dj = [
        'name',
        'show_desc',
        'secondary_genre_tags',
        'alerts'
      ];

      apiService.getShowData($scope.formData.show_id)
          .then(function(response){
              $scope.showData = response.data;

            if($scope.dj_edit_fields_only){
              for(var i in editable_by_dj){

                Object.defineProperty(
                    $scope.formData,editable_by_dj[i],
                    { value:$scope.showData[editable_by_dj[i]],
                      enumerable:true,writable:true
                    }
                )





              }

            } else {
              // Robin view formData gets everything in showData...
              // also load 'notes' field (sensitive data)
              // need to add that to private api, accessible only by Robin
            }

      });

      $scope.save = function(){
        $scope.message = 'saving...';

        apiService.saveShowData($scope.formData)
            .then(function(response){
              $scope.message = response.data.message;
            }).catch(function(response){
              console.error(response.data);
              $scope.message = 'sorry, saving did not work';
            });
      }



    }])


//

;