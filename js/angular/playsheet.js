



djland.controller('playsheetCtrl', function ($scope, $filter, $http, $location, $window, apiService, channel_id) {

  $scope.playsheet = {};
  console.log('channel id is ' + channel_id);

  $scope.samVisible = false;
  $scope.totals = {cancon2: 0, cancon3: 0, hits: 0, femcon: 0, nu: 0};

  apiService.getShowData(channel_id).then(function (showData) {
    var playsheet_id = $location.search().id;
    $scope.socan = ($location.search().socan === 'true');
    $scope.typeofsocan = typeof socan;
    if (playsheet_id) {
      apiService.getFullPlaylistData(playsheet_id).success(
          function (result) {

            $scope.playsheet = result.playlist;
            $scope.playsheet.plays = result.plays;
            $scope.playsheet.ads = result.ads;

            init();

          });
    } else {
      $scope.playsheet = $scope.blankPlaysheet(showData);
      init();
    }


  });


  $scope.blankPlaysheet = function (showData) {

    var blankplays = [];
    for (var i = 0; i < 5; i++) {
      blankplays.push({
        artist: '',
        album: '',
        song: '',
        nu: false,
        cancon: false,
        femcon: false,
        instrumental: false,
        partial: false,
        hit: false,
        crtc: showData.crtc,
        language: showData.language
      });
    }
    var now = new Date();
    var later = new Date();
    later.setHours(now.getHours() + 1);
    now.setMinutes(0);
    later.setMinutes(0);
    now.setSeconds(0);
    later.setSeconds(0);
    now = now.getTime();
    later = later.getTime();

    return {
      type: 'live',
      show_name: showData.name,
      show_id: showData.id,
      host: showData.host,
      language: showData.language,
      crtc: showData.crtc,
      start: now,
      end: later,
      podcast: {
        title: 'broadcast on ' + $filter('date')(now, 'mediumDate'),
        subtitle: '',
        summary: '',
        active: '1'
      },
      plays: blankplays
    };
  };


  var init = function () {

    $scope.playsheet.start_time = new Date($scope.playsheet.start_time);
    $scope.playsheet.end_time = new Date($scope.playsheet.end_time);

    $scope.start_hour = $filter('pad')($scope.playsheet.start_time.getHours(), 2);
    $scope.start_minute = $filter('pad')($scope.playsheet.start_time.getMinutes(), 2);
    $scope.end_hour = $filter('pad')($scope.playsheet.end_time.getHours(), 2);
    $scope.end_minute = $filter('pad')($scope.playsheet.end_time.getMinutes(), 2);

    $scope.$watch('playsheet.plays', function () {
      var newTotals = {cancon2: 0, cancon3: 0, hits: 0, femcon: 0, nu: 0};
      var num = $scope.playsheet.plays.length;
      var num_20 = 0;
      var num_30 = 0;
      for (var i = 0; i < num; i++) {
        if ($scope.playsheet.plays[i].is_playlist) {
          newTotals.is_playlist++;
        }
        if ($scope.playsheet.plays[i].is_canadian && $scope.playsheet.plays[i].crtc_category == 20) {
          newTotals.cancon2++;
        }
        if ($scope.playsheet.plays[i].is_canadian && $scope.playsheet.plays[i].crtc_category == 30) {
          newTotals.cancon3++;
        }
        if ($scope.playsheet.plays[i].is_fem) {
          newTotals.is_fem++;
        }
        if ($scope.playsheet.plays[i].is_hit) {
          newTotals.hits++;
        }

        if ($scope.playsheet.plays[i].crtc_category == 20) {
          num_20++;
        }
        if ($scope.playsheet.plays[i].crtc_category == 30) {
          num_30++;
        }
      }

      newTotals.cancon2 = 100.00 * newTotals.cancon2 / num_20;
      newTotals.cancon3 = 100.00 * newTotals.cancon3 / num_30;
      newTotals.is_fem = 100.00 * newTotals.is_fem / num;
      newTotals.hits = 100.00 * newTotals.hits / num;
      newTotals.is_playlist = 100.00 * newTotals.is_playlist / num;
      $scope.totals = newTotals;
    }, true);

    var entry_template = {
      song:{artist: '',
        title: '',
        song: '',
        composer: ''},
      is_playlist: false,
      is_canadian: false,
      is_fem: false,
      is_inst: false,
      is_part: false,
      is_hit: false,
      crtc_category: $scope.playsheet.crtc,
      lang: $scope.playsheet.lang
    };


    $scope.add = function (id) {
      $scope.playsheet.plays.splice(id + 1, 0, angular.copy(entry_template));

      for (var i = 0; i < $scope.playsheet.plays.length; i++) {
        $scope.playsheet.plays[i].id = i;
      }

    };

    $scope.remove = function (id) {
      $scope.playsheet.plays.splice(id, 1);

      for (var i = 0; i < $scope.playsheet.plays.length; i++) {

      }
    };
    $scope.cue = function(row){
      row.start = new Date();
      row.insert_song_start_hour = $filter('pad')(row.start.getHours(),2);
      row.insert_song_start_minute = $filter('pad')(row.start.getMinutes(),2);

    }
    $scope.updateNow = function(row){
      row.start = new Date();
      row.start.setHours(row.insert_song_start_hour);
      row.start.setMinutes(row.insert_song_start_minute);
      row.start.setSeconds(0);
    }

    $scope.end = function(row){
      var start_milliseconds = row.start.getTime();//1000*60*60*row.insert_song_start_hour + 1000*60*row.insert_song_start_minute;

      var rightnow = new Date();
      var end_milliseconds = rightnow.getTime();
      var length = end_milliseconds - start_milliseconds;
      var length = new Date(length);
      row.insert_song_length_minute = $filter('pad')(length.getMinutes(),2);
      row.insert_song_length_second = $filter('pad')(length.getSeconds(),2);

    }
    // DATE STUFF (faking knowing the start of current episode

    $scope.persistent_date = {};

    $scope.persistent_date.start = $scope.playsheet.start;
    $scope.persistent_date.duration = 60 * 60;
    $scope.episode = $scope.playsheet.podcast;


    $scope.sam_add = function (sam) {
      $scope.playsheet.plays.push(angular.copy(sam));
    };


    $scope.processSam = function (sam_play) {
      var djland_entry = angular.copy(entry_template);
      djland_entry.song.artist = sam_play.artist;
      djland_entry.song.title = sam_play.album;
      djland_entry.song.song = sam_play.title;
      djland_entry.song.composer = sam_play.composer;

      djland_entry.insert_song_start_hour = $filter('pad')(sam_play.hour,2);
      djland_entry.insert_song_start_minute = $filter('pad')(sam_play.minute,2);
      djland_entry.insert_song_length_minute = $filter('pad')(sam_play.durMin,2);
      djland_entry.insert_song_length_second = $filter('pad')(sam_play.durSec,2);

      return djland_entry;
    };

    $scope.loadSAM = function () {

      apiService.getRecentSamPlays()
          .success(function (data) {
            var samRecent = [];
            for (var i = 0; i < data.length; i++) {
              samRecent.push(
                  $scope.processSam(data[i])
              );
            }
            $scope.samRecent = samRecent;

          });

    };

    $scope.loadSAM();

    $scope.samRange = function () {
      apiService.getSamFromRange($scope.playsheet.start_time, $scope.playsheet.end_time)
          .success(function (result){
            for (var i in result) {
              $scope.sam_add($scope.processSam(result[i]));
            }
            $scope.samVisible = false;

          });
    };

    $scope.save = function(){
      $scope.message = 'saving...';
      var good = true;

      for( var i in $scope.playsheet.plays){
        if ($scope.playsheet.plays[i].song.artist == undefined
            || $scope.playsheet.plays[i].song.title == undefined
            || $scope.playsheet.plays[i].song.song == undefined
            || $scope.playsheet.plays[i].song.artist == ''
            || $scope.playsheet.plays[i].song.title == ''
            || $scope.playsheet.plays[i].song.song == ''
            || $scope.playsheet.plays[i].song.artist == null
            || $scope.playsheet.plays[i].song.title == null
            || $scope.playsheet.plays[i].song.song == null){
          good = false;
        }
      }

      if(good){

        apiService.savePlaylist($scope.playsheet)
            .success(function(result){



              $scope.message = 'success: '+result.message;

            });

      } else {
        alert('please make sure all "artist", "album", and "song" entries are filled in');
        $scope.message = 'please make sure all "artist", "album", and "song" entries are filled in';
      }

    }

  };


  $scope.varshidden = false;
});


