

//app.module('episodeModule', [''])

djland.factory('apiService', function ($http, $location) {

      var API_URL_BASE = 'api'; // api.citr.ca when live

      return {

        getShowData: function (id) {
          return $http.get(API_URL_BASE + '/show?ID=' + id);
        },

        getEpisodeData: function (id) {
          return $http.get(API_URL_BASE + '/episode?ID=' + id);
        },

        saveShowData: function (data) {
          return $http.post(API_URL_BASE + '/show/save.php', data);
        },

        saveEpisodeData: function (data) {
          return $http.post(API_URL_BASE + '/episode/save.php', data);
        },

        getPlaylists: function (limit,offset) {
          limit = limit || 100; offset = offset || 0;
          return $http.get(API_URL_BASE + '/playlists/mine.php');
        },

        getPlaylistData: function (id) {
          return $http.get(API_URL_BASE+ '/playlist?ID='+id);
        },

        getEpisodes: function () {
          return $http.get(API_URL_BASE + '/episodes/list.php');
        }


      };
    });

djland.config(function($locationProvider) {
  $locationProvider.html5Mode(true).hashPrefix('!');
});

djland.controller('showCtrl', ['$scope','apiService','$location',function($scope, apiService, $location){

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



}]);


djland.controller('episodeList', ['$scope','apiService','$location', function($scope, apiService, $location){
// GET id FROM list provider...

  $scope.status = 'loading playlists and podcasts...';
  $scope.episodes = [];
  $scope.playlists = [];
  $scope.items = [];
  $scope.editing  = false;

  var episodes_loaded = false;
  var playlist_loaded = false;
  apiService.getPlaylists()
      .then(function(response){
        $scope.playlists = response.data;
      });

  apiService.getEpisodes()
      .then(function(response){
        for( var i in response.data){
          $scope.episodes[response.data[i].id] = response.data[i];
        }

      });

  $scope.edit_episode = function (id){
    $scope.editing = true;
    $scope.editing_episode = [];
    $scope.editing_episode[0] = $scope.episodes[id];
  }


}]);


djland.controller('episodeSingle', ['$scope', '$location', 'apiService', function($scope, $location, apiService) {
// GET id FROM LOCATION BAR
  $scope.status = 'loading episode...';
  $scope.episodes = [];
  apiService.getEpisodeData($location.search().id)
      .then(function(response){
        $scope.episodes[0] = response.data;
        $scope.status = '';
      });


}]);

djland.controller('episodeCtrl', ['$scope','apiService', function($scope, apiService){

  $scope.episodeData = $scope.$parent.episode;

  $scope.save = function(data){
    $scope.message = 'saving...';

    apiService.saveEpisodeData(data)
        .then(function(response){
          $scope.message = response.data.message;
        }).catch(function(response){
          console.error(response.data);
          $scope.message = 'sorry, saving did not work';
        });
  };


}]);
