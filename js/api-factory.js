
  angular.module('djland.api',[]).factory('call', function ($http, $location) {

    var API_URL_BASE = 'api'; // api.citr.ca when live

    return {

      getShowData: function (id) {
        return $http.get(API_URL_BASE + '/show?ID=' + id);
      },

      listActiveShows: function(){
        return $http.get(API_URL_BASE + '/shows');
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

      getPlaysheets: function (limit,offset) {
        limit = limit || 100; offset = offset || 0;
        return $http.get(API_URL_BASE + '/playlists/mine.php');
      },

      getPlodcasts: function(show_id) {
        return $http.get(API_URL_BASE + '/playlists/plodcast.php?show='+show_id);

      },

      getEveryonesPlaysheets: function (limit,offset) {
        limit = limit || 100; offset = offset || 0;
        return $http.get(API_URL_BASE + '/playlists/all.php?LIMIT='+limit+'&offset='+offset);
      },

      getPlaylistData: function (id) {
        return $http.get(API_URL_BASE+ '/playlist?ID='+id);
      },

      getFullPlaylistData: function (id) {
        return $http.get(API_URL_BASE+ '/playlist/full.php?ID='+id);
      },

      getEpisodes: function () {
        return $http.get(API_URL_BASE + '/episodes/mine.php');
      },

      getSpecialBroadcasts: function () {
        return $http.get(API_URL_BASE + '/specialevents');
      },

      saveSpecialBroadcast: function (data) {
        return $http.post(API_URL_BASE + '/specialevents/save.php', data);
      },

      createSpecialBroadcast: function (data) {
        return $http.post(API_URL_BASE + '/specialevents/create.php', data);
      },

      getRecentSamPlays: function () {
        return $http.get(API_URL_BASE + '/sam/recent.php');
      },

      getSamFromRange: function(min, max) {
        return $http.post(API_URL_BASE + '/sam/range.php',angular.toJson({'min':min,'max':max}));

      },

      savePlaylist: function(data) {
        return $http.post(API_URL_BASE + '/playlist/save.php', data);
      },

      getNextShow: function(time){
        return $http.get(API_URL_BASE + '/schedule/nextshow.php?time='+time)
      },

      getAdsFromBlock: function(time){
        return $http.get(API_URL_BASE + '/ad/scheduled.php?timeblock='+time)
      },

      savePodcast: function(podcast){
        return $http.post(API_URL_BASE + '/episode/create.php')
      },

      getArchiverTime: function(){
        return $http.get('http://archive.citr.ca/time/')
      },


      updatePodcast: function(data, updateAudio){
        data.updateAudio = updateAudio;
        return $http.post(API_URL_BASE + '/podcasting/update_podcast.php', data)
      },

      logout: function(){
        return $http.post('')
      },

      def: function(){
        return $http.post(API_URL_BASE + '/deferMe.php',{data: 'some data'});
      }

    };
  });
