



djland.controller('playsheetCtrl', function ($scope, $filter, $http, $location, $interval, apiService, show_id, channel_id) {


  $scope.message = {text: '', age: 0};

  $scope.playsheet = {};
  console.log('channel id is ' + show_id);

  $scope.samVisible = false;

  $scope.show_the_tracklist_overlay = false;

  apiService.listActiveShows()
      .success(function (result) {
        $scope.shows = result;
      })

  apiService.getShowData(show_id).then(function (showData) {
    var showData = showData.data;
    var playsheet_id = $location.search().id;
    $scope.show_name = showData.name;
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

      var time = new Date($scope.playsheet.start_time);

      apiService.getNextShow(time)
          .success(function (result) {

            var start = new Date(result.start * 1000);
            var end = new Date(result.end * 1000);

            $scope.playsheet.start_time = start;
            $scope.playsheet.end_time = end;

            $scope.start_hour = $filter('pad')(start.getHours(), 2);
            $scope.start_minute = $filter('pad')(start.getMinutes(), 2);
            $scope.end_hour = $filter('pad')(end.getHours(), 2);
            $scope.end_minute = $filter('pad')(end.getMinutes(), 2);

            $scope.loadAds($scope.playsheet.start_time);
          });

      init();
    }


  });


  $scope.blankPlaysheet = function (showData) {

    var now = new Date();
    now.setHours(now.getHours() - 24);
    now.setMinutes(0);
    now.setSeconds(0);
    var later = new Date();
    later.setHours(now.getHours() + 1);

//    now.setMinutes(0);
//    later.setMinutes(0);
//    now.setSeconds(0);
//    later.setSeconds(0);
    now = now.getTime();
    later = later.getTime();

    $scope.show_name = showData.name;
    return {
      type: showData.showtype || 'Live',
//      show_name: showData.name,
      show_id: showData.show_id,
      host: showData.host_name,
      lang: showData.lang_default || 'English',
      crtc: showData.crtc_default,
      start_time: now,
      end_time: later,
      plays: [],
      podcast:{
        id:0,
        title:"",
        subtitle:"",
        summary: "",
        date:"",
        channel_id:channel_id,
        url:"",
        length:0,
        author:"CiTR",
        active:"0",
        duration:"0",
        edit_date:""
      }
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

      $scope.songsComplete = checkComplete();

      var newTotals = {cancon2: 0, cancon3: 0, hits: 0, is_fem: 0, is_playlist: 0};
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

      if (num_30 == 0) newTotals.cancon3 = 100;
      if (num_20 == 0) newTotals.cancon2 = 100;

      newTotals.is_fem = 100.00 * newTotals.is_fem / num;
      newTotals.hits = 100.00 * newTotals.hits / num;
      newTotals.is_playlist = 100.00 * newTotals.is_playlist / num;

      $scope.playsheet.star = 1;
      if (newTotals.cancon2 < 35.00) $scope.playsheet.star = 0;
      if (newTotals.cancon3 < 12.00) $scope.playsheet.star = 0;
      if (newTotals.hits > 10.00) $scope.playsheet.star = 0;
      if (newTotals.is_fem < 35.00) $scope.playsheet.star = 0;
      if (newTotals.is_playlist < 15.00) $scope.playsheet.star = 0;
      $scope.totals = newTotals;
    }, true);

    var entry_template = {
      song: {
        artist: '',
        title: '',
        song: '',
        composer: ''
      },
      is_playlist: false,
      is_canadian: false,
      is_fem: false,
      is_inst: false,
      is_part: false,
      is_hit: false,
      crtc_category: $scope.playsheet.crtc,
      lang: $scope.playsheet.lang,
      insert_song_start_hour: "00",
      insert_song_start_minute: "00",
      insert_song_length_minute: "00",
      insert_song_length_second: "00",
      start: '0'
    };

    $scope.add = function (id) {
      $scope.playsheet.plays.splice(id + 1, 0, angular.copy(entry_template));

      for (var i = 0; i < $scope.playsheet.plays.length; i++) {
        $scope.playsheet.plays[i].id = i;
      }

    };

    if ($scope.playsheet.plays.length == 0) {
      for (var i = 0; i < 5; i++) {
        $scope.add(i);
      }
    }

    $scope.remove = function (id) {
      $scope.playsheet.plays.splice(id, 1);

      for (var i = 0; i < $scope.playsheet.plays.length; i++) {

      }
    };

    $scope.cue = function (row) {
      row.start = new Date();
      row.insert_song_start_hour = $filter('pad')(row.start.getHours(), 2);
      row.insert_song_start_minute = $filter('pad')(row.start.getMinutes(), 2);

    };

    $scope.updateNow = function (row) {
      row.start = new Date();
      row.start.setHours(row.insert_song_start_hour);
      row.start.setMinutes(row.insert_song_start_minute);
      row.start.setSeconds(0);
    };

    $scope.end = function (row) {
      if (row.start == '0') return;
      var start_milliseconds = row.start.getTime();//1000*60*60*row.insert_song_start_hour + 1000*60*row.insert_song_start_minute;

      var rightnow = new Date();
      var end_milliseconds = rightnow.getTime();
      var length = end_milliseconds - start_milliseconds;
      var length = new Date(length);
      row.insert_song_length_minute = $filter('pad')(length.getMinutes(), 2);
      row.insert_song_length_second = $filter('pad')(length.getSeconds(), 2);

    }
    // DATE STUFF (faking knowing the start of current episode

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

      djland_entry.insert_song_start_hour = $filter('pad')(sam_play.hour, 2);
      djland_entry.insert_song_start_minute = $filter('pad')(sam_play.minute, 2);
      djland_entry.insert_song_length_minute = $filter('pad')(sam_play.durMin, 2);
      djland_entry.insert_song_length_second = $filter('pad')(sam_play.durSec, 2);

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
          .success(function (result) {
            for (var i in result) {
              $scope.sam_add($scope.processSam(result[i]));
            }
            $scope.samVisible = false;

          });
    };

    var checkComplete = function () {
      var good = true;

      for (var i in $scope.playsheet.plays) {
        if ($scope.playsheet.plays[i].song.artist == undefined
            || $scope.playsheet.plays[i].song.title == undefined
            || $scope.playsheet.plays[i].song.song == undefined
            || $scope.playsheet.plays[i].song.artist == ''
            || $scope.playsheet.plays[i].song.title == ''
            || $scope.playsheet.plays[i].song.song == ''
            || $scope.playsheet.plays[i].song.artist == ' '
            || $scope.playsheet.plays[i].song.title == ' '
            || $scope.playsheet.plays[i].song.song == ' '
            || $scope.playsheet.plays[i].song.artist == '.'
            || $scope.playsheet.plays[i].song.title == '.'
            || $scope.playsheet.plays[i].song.song == '.'
            || $scope.playsheet.plays[i].song.artist == "'"
            || $scope.playsheet.plays[i].song.title == "'"
            || $scope.playsheet.plays[i].song.song == "'"
            || $scope.playsheet.plays[i].song.artist == '/'
            || $scope.playsheet.plays[i].song.title == '/'
            || $scope.playsheet.plays[i].song.song == '/'
            || $scope.playsheet.plays[i].song.artist == null
            || $scope.playsheet.plays[i].song.title == null
            || $scope.playsheet.plays[i].song.song == null) {
          good = false;
        }

        if ($scope.socan) {

          if ($scope.playsheet.plays[i].song.composer == undefined
              || $scope.playsheet.plays[i].song.composer == ''
              || $scope.playsheet.plays[i].song.composer == ' '
              || $scope.playsheet.plays[i].song.composer == '.'
              || $scope.playsheet.plays[i].song.composer == "'"
              || $scope.playsheet.plays[i].song.composer == '/'
              || $scope.playsheet.plays[i].song.composer == null) {
            good = false;
          }

          if ($scope.playsheet.plays[i].insert_song_length_minute == "00"
              && $scope.playsheet.plays[i].insert_song_length_second == "00") {
            good = false;
          }

          if ($scope.playsheet.plays[i].start != '0' && isNaN($scope.playsheet.plays[i].start.getTime())) {
            good = false;
          }


        }
      }
      return good;
    };

    $scope.submit = function () {

      if ($scope.songsComplete) {
        $scope.playsheet.status = 2;

        apiService.savePlaylist($scope.playsheet)
            .success(function (result) {
              $scope.show_message('success: ' + result.message);
              $scope.playsheet.id = result.message;
              $scope.show_the_tracklist_overlay = true;

              $scope.podcast_status('Getting the podcast audio...');

               apiService.createPodcastAudio($scope.podcast)
               .then(function(result){
               $scope.playsheet.podcast = result.data;
               $scope.show_message('Podcast episode has been created');


               })
               .catch(function(result){

               $scope.show_message('Podcast to be created later');

               });


            });

      }
    };

    $scope.saveDraft = function () {
      $scope.saving = true;
      $scope.playsheet.status = 1;

      apiService.savePlaylist($scope.playsheet)
          .success(function (result) {
            $scope.saving = false;
            var now = new Date();
            $scope.show_message('draft saved at ' + now.getHours() + ':' + $filter('pad')(now.getMinutes(), 2));

            var new_url = 'l.h/djland-podcast/playsheet.php?action=edit';
            if ($scope.socan) new_url += '&socan=true';
            new_url += '&id=' + result.message;// <== new playsheet id
            $location.url(new_url);

            $scope.playsheet.id = result.message;
          })
    }


    var age = $interval(function () {
      $scope.message.age += 1;
    }, 1000);

    $scope.show_message = function (stuff) {
      $scope.message.text = stuff;
      $scope.message.age = 0;
    }

    $scope.date_change = function () {


    };

    $scope.loadAds = function (time) {
      $scope.show_message('loading the ads...');
      apiService.getAdsFromBlock(time)
          .then(function (result) {
            $scope.playsheet.ads = result.data;

          })
          .catch(function (result) {
            var message = 'no ads found. have a great show!';
            $scope.show_message(message);
            $scope.ad_message = message;
          });
    };

    $scope.loadIfRebroadcast = function () {
      $scope.loadButtonText = 'loading list...';
      apiService.getEveryonesPlaylists(2000, 0)
          .success(function (result) {
            $scope.available_playsheets = result;

            $scope.available_playsheets.sort(function (a, b) {
              if (a.start_time > b.start_time) return -1;
              else return 1;
            })

            $scope.available_playsheets.unshift({
              playlist_id: "0",
              sh_id: "0",
              show_name: "Select a Playsheet..",
              start_time: ""
            });
            $scope.desired_playsheet = "0";
            $scope.loadButtonText = '<-- Load plays from this playsheet'
          });

    }

    $scope.loadPlays = function (desired_playsheet) {
      if (desired_playsheet == 0) return;
      apiService.getFullPlaylistData(desired_playsheet)
          .success(function (result) {
            $scope.playsheet.plays = result.plays;
          })
    }

    $scope.varshidden = false;


    $scope.browserTime = new Date();

    $scope.startPodcast = function(){

      apiService.getArchiverTime()
          .success(function(result){
            var start = new Date(result*1000);
            $scope.playsheet.start_time = start;

            $scope.start_hour = $filter('pad')(start.getHours(),2);
            $scope.start_minute = $filter('pad')(start.getMinutes(),2);

            $scope.playsheet.podcast.date = start;
          });
    };


    $scope.endPodcast = function(){

      apiService.getArchiverTime()
          .success(function(result){
            var end = new Date(result*1000);
            $scope.playsheet.end_time = end;

            $scope.end_hour = $filter('pad')(end.getHours(),2);
            $scope.end_minute = $filter('pad')(end.getMinutes(),2);

            var duration = (end.getTime() - $scope.playsheet.podcast.date.getTime()) / 1000;

            $scope.playsheet.podcast.duration = duration;
          });
    };

  };

});


