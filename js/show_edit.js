
(function(){
  var app = angular.module('djland.editShow',['djland.api']);
    app.controller('editShow', function($scope, call, $location){
      /*$scope.dj_edit_fields_only = true;// TODO - if robin editing, set to false, migrate markup from php

      $scope.showData = {}; // <- gets all loaded from server
      $scope.formData = {}; // <- all private
      $scope.formData.show_id = $location.search().id;*/
/*      var editable_by_dj = [
        'name',
        'show_desc',
        'secondary_genre_tags',
        'alerts'
      ];*/
var this_ = this;
      this.member_id = member_id;
      call.getConstants().then(function(response){
        this_.primary_genres = response.data.primary_genres;
      });
      
      call.getMemberShows(this.member_id).then(function(response){
          var shows_list = response.data.shows;
          for(var show in shows_list){
             var show_id = shows_list[show]['id'];
          }
          call.getShow(show_id).then(function(response){
            for(var prop in response.data){
              this_[prop] = response.data[prop];
            }
            console.log(this_);
            //this_.show = response.data;
          });

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
    });

})();