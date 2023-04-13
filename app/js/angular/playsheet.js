djland.controller('playsheetCtrl', function ($scope, $filter, $http, $location, $interval, apiService, show_id, channel_id, MAX_PODCAST_DURATION_HOURS, adminStatus) {
  $scope.adminStatus = adminStatus;

  var loadAds = function (time) {
    show_message('Loading the ads...');
    apiService.getAdsFromBlock(time)
        .then(function (result) {
          if (result.data) {

            $scope.playsheet.ads = result.data;
            $scope.message.text += '... done!';
            $scope.message.age = 4;
          } else {

            var message = 'No ads found. have a great show!';
            show_message(message);
            $scope.ad_message = message;
          }

        })
        .catch(function (result) {
          var message = 'No ads found. have a great show!';
          show_message(message);
          $scope.ad_message = message;
        });
  };

  var show_message = function (stuff) {
    $scope.message.text = stuff;
    $scope.message.age = 0;
  }
  $scope.message = {text: '', age: 0};

  $scope.playsheet = {};
  console.log('channel id is ' + show_id);

  $scope.samVisible = false;

  $scope.show_the_tracklist_overlay = false;

  apiService.listActiveShows()
      .success(function (result) {
        $scope.shows = result;
      })

  show_message('Loading show data...');

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

            $scope.message.text += '...done!';
            $scope.message.age = 5;

          });
    } else {
      $scope.playsheet = $scope.blankPlaysheet(showData);

      var time = new Date($scope.playsheet.start_time);

      show_message('Initializing...');
      apiService.getNextShow(time)
          .then(function (result) {
            $scope.message.text += '... done!';

            var start = result.data.start;
            var end = result.data.end;
            if(end < start) end += 24*60*60;

            $scope.playsheet.start_time = new Date(start * 1000);
            $scope.playsheet.end_time = new Date(end * 1000);
            $scope.playsheet.podcast.duration = end - start;

            $scope.start_hour = $filter('pad')($scope.playsheet.start_time.getHours(), 2);
            $scope.start_minute = $filter('pad')($scope.playsheet.start_time.getMinutes(), 2);
            $scope.end_hour = $filter('pad')($scope.playsheet.end_time.getHours(), 2);
            $scope.end_minute = $filter('pad')($scope.playsheet.end_time.getMinutes(), 2);

            loadAds($scope.playsheet.start_time);
            init();

          })
          .catch(function(result){
            init();

          });

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
        active:"1",
        duration:"0",
        edit_date:""
      }
    };
  };


  $scope.adjust_times = function(){

    $scope.playsheet.start_time.setHours($scope.start_hour);
    $scope.playsheet.start_time.setMinutes($scope.start_minute);

    $scope.playsheet.end_time.setHours($scope.end_hour);
    $scope.playsheet.end_time.setMinutes($scope.end_minute);

    $scope.playsheet.podcast.duration = ($scope.playsheet.end_time.getTime() - $scope.playsheet.start_time.getTime())/1000;

    if ($scope.playsheet.podcast.duration < 0){

      $scope.playsheet.podcast.duration += 24*60*60;
      calculate_end_from_start_and_duration($scope.playsheet.podcast.duration);

    } else if($scope.playsheet.podcast.duration > MAX_PODCAST_DURATION_HOURS*60*60){
      var diff = $scope.playsheet.podcast.duration - MAX_PODCAST_DURATION_HOURS*60*60;

      $scope.playsheet.podcast.duration -= diff;
      calculate_end_from_start_and_duration($scope.playsheet.podcast.duration);

    }

    if($scope.playsheet.podcast.duration == MAX_PODCAST_DURATION_HOURS*60*60){
      show_message('Maximum duration of a podcast is '+MAX_PODCAST_DURATION_HOURS+' hours.');
    } else {

    }

  }


  var calculate_end_from_start_and_duration = function(new_duration){
    var new_end = $scope.playsheet.start_time.getTime() + new_duration*1000;
    $scope.playsheet.end_time = new Date(new_end);
    $scope.end_hour = $filter('pad')($scope.playsheet.end_time.getHours(),2);
    $scope.end_minute = $filter('pad')($scope.playsheet.end_time.getMinutes(),2);

    $scope.adjust_times();
  }

  var init = function () {

    $scope.playsheet.start_time = new Date($scope.playsheet.start_time);
    $scope.playsheet.end_time = new Date($scope.playsheet.end_time);

    $scope.playsheet.podcast.date = $scope.playsheet.start_time;
    $scope.playsheet.podcast.duration =
        $scope.playsheet.end_time.getTime()/1000 - $scope.playsheet.start_time.getTime()/1000;


    $scope.start_hour = $filter('pad')($scope.playsheet.start_time.getHours(), 2);
    $scope.start_minute = $filter('pad')($scope.playsheet.start_time.getMinutes(), 2);
    $scope.end_hour = $filter('pad')($scope.playsheet.end_time.getHours(), 2);
    $scope.end_minute = $filter('pad')($scope.playsheet.end_time.getMinutes(), 2);

    calculate_end_from_start_and_duration($scope.playsheet.podcast.duration);

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
      var newRow = angular.copy(entry_template);
      newRow.crtc_category = $scope.playsheet.crtc;
      newRow.lang = $scope.playsheet.lang;
      $scope.playsheet.plays.splice(id + 1, 0, newRow);

      for (var i = 0; i < $scope.playsheet.plays.length; i++) {
        $scope.playsheet.plays[i].id = i;
      }

    };

    if ($scope.playsheet.plays.length == 0 && !$scope.playsheet.id) {
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
    // DATE STUFF (faking knowing the start of current episode)

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

      djland_entry.crtc_category = sam_play.category;
      djland_entry.lang = $scope.playsheet.lang;

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

    $scope.submitting = false;


    $scope.isTheFormAcceptible = function(){

      var good = true;

      if (!$scope.playsheet.podcast.title ||
          ($scope.playsheet.podcast.title == '.')||
          ($scope.playsheet.podcast.title == '/')||
          ($scope.playsheet.podcast.title == '//')||
          ($scope.playsheet.podcast.title == "'")||
          ($scope.playsheet.podcast.title == ';')||
          ($scope.playsheet.podcast.title == ',') ){
        good = false;
      }

      $scope.formAcceptible = good;

    }


    $scope.isTheFormAcceptible();

    $scope.submit = function () {

      if ($scope.songsComplete && $scope.formAcceptible && ($scope.playsheet.end_time < $scope.currentTime)) {
        $scope.playsheet.status = 2;

        $scope.submitting = true;

        $scope.playsheet.podcast.date = $scope.playsheet.start_time;

        apiService.savePlaylist($scope.playsheet)
            .then(function (result) {

              $scope.playsheet.id = result.data.playsheet_id;
              $scope.playsheet.podcast.id = result.data.podcast_id;
              $scope.show_the_tracklist_overlay = true;

              $scope.podcast_status = 'Getting the podcast audio...' ;

              var now = new Date();
              if($scope.playsheet.end_time.getTime() <= now.getTime()) {
                $scope.playsheet.podcast.active = 1;
                apiService.updatePodcast($scope.playsheet.podcast)
                    .then(function (result) {

                      $scope.podcast_status = 'Podcast has been updated';


                    })
                    .catch(function (result) {

                      $scope.podcast_status = 'Something went wrong. Please try again later from the Podcasts page.';

                    });
              } else {
                $scope.podcast_status = 'Future Episode will be created later.';
              }


            })
            .catch(function(result){
              show_message('Sorry, something went wrong while saving. Please try saving a draft');
              $scope.submitting = false;
            });

      }
    };

    $scope.saveDraft = function () {

      if(!$scope.saving) {
        apiService.savePlaylist($scope.playsheet)
            .then(function (result) {
              $scope.saving = false;
              var now = new Date();
              show_message('Draft saved at ' + now.getHours() + ':' + $filter('pad')(now.getMinutes(), 2));

              if(!$scope.playsheet.id) {
                var new_url = 'l.h/djland-podcast/playsheet.php?action=edit';
                if ($scope.socan) new_url += '&socan=true';
                new_url += '&id=' + result.data.playsheet_id;// <== new playsheet id
                $location.url(new_url);
                $scope.playsheet.id = result.data.playsheet_id;
                $scope.playsheet.podcast.id = result.data.podcast_id;
              }

              $scope.saving = false;
            }).catch(function (result) {
              show_message('Sorry, something went wrong. please try again later');
              $scope.saving = false;
            })

        $scope.saving = true;
        $scope.playsheet.status = 1;
      }

    }


    var age = $interval(function () {
      $scope.message.age += 1;
    }, 1000);

    var time = $interval(function(){
      $scope.currentTime = new Date();
    },1000);





    $scope.date_change = function () {

      calculate_end_from_start_and_duration($scope.playsheet.podcast.duration);

    };


    $scope.loadIfRebroadcast = function () {

      if ($scope.playsheet.type == 'Rebroadcast') {

        $scope.loadButtonText = 'Loading list...';
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

    }

    $scope.loadPlays = function (desired_playsheet) {
      if (desired_playsheet == 0) return;
      apiService.getFullPlaylistData(desired_playsheet)
          .success(function (result) {
            $scope.playsheet.plays = result.plays;
          })
    }

    $scope.varshidden = false;

    $scope.startPodcast = function(){

      apiService.getArchiverTime()
          .success(function(result){
            var start = new Date(result*1000);
            $scope.playsheet.start_time = start;

            $scope.start_hour = $filter('pad')(start.getHours(),2);
            $scope.start_minute = $filter('pad')(start.getMinutes(),2);

            $scope.playsheet.podcast.date = start;

            $scope.startClicked = true;
          });
    };

    $scope.startClicked = false;
    $scope.endClicked = false;
    $scope.endPodcast = function(){

      apiService.getArchiverTime()
          .success(function(result){
            var end = new Date(result*1000);
            $scope.playsheet.end_time = end;

            $scope.end_hour = $filter('pad')(end.getHours(),2);
            $scope.end_minute = $filter('pad')(end.getMinutes(),2);

            var duration = (end.getTime() - $scope.playsheet.podcast.date.getTime()) / 1000;

            $scope.playsheet.podcast.duration = duration;

            $scope.adjust_times();

            $scope.endClicked = true;
          });
    };

    $scope.albumHelp =			"Enter the title of the album, EP, or single that the track is released on."
    +"If playing an mp3 or streaming from youtube, soundcloud etc, please take a moment to find the title of the album,"
    +"EP, or single that the track is released on. If it is unreleased, enter 'unreleased'. "
    +"If you are confused about what to enter here, please contact music@citr.ca This will help the artist chart "
    +"and help provide listeners with information about the release.";
    $scope.artistHelp =		"Enter the name of the artist";
    $scope.compHelp = 			"OPTIONAL: Enter the name of the composer or author";
    $scope.timeHelp1 =			"Hit the CUE button when the song starts playing . Or enter the start time. Time Format is HOUR:MIN";
    $scope.timeHelp2 =			"Enter the duration of the song. Time Format is MIN:SECOND";
    $scope.plHelp =			"Playlist (New) Content: Was the song released in the last 6 months? ";
    $scope.ccHelp =			"Cancon: two of the following must apply: Music written by a Canadian, Artist performing it is Canadian, Performance takes place in Canada, Lyrics Are written by a Canadian";
    $scope.feHelp =			"Femcon: two of the following must apply: Music is written by a female, Performers (at least one) are female, Words are written by a female, Recording is made by a female engineer.";
    $scope.instHelp =			"Is the song instrumental? (no vocals)";
    $scope.partHelp =			"Partial songs: For a track to count as cancon, you need to play the whole thing and it must be at least 1 minute.";
    $scope.hitHelp =			"Has the song ever been a hit in Canada?  By law, the maximum is 10% Hits played, but we aim for 0% - you really shouldn't play hits!";
    $scope.themeHelp =			"Is the song your themesong?";
    $scope.backgroundHelp =	"Is the song playing in the background? Talking over the intro to a song does not count as background";
    $scope.crtcHelp =			"Category 2: Rock, Pop, Dance, Country, Acoustic, Easy Listening.  Category 3: Concert, Folk, World Beat, Jazz, Blues, Religious, Experimental. <a href='https://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'>Click for more info</a>";
    $scope.songHelp =			"Enter the name of the song";
    $scope.langHelp =			"The language of the song";
    $scope.adsHelp =			"Station IDs must be played or spoken in the first ten minutes of every hour";
    $scope.guestsHelp =		"Any non-music features on your show.  This helps us to reach our 15% local spoken word minimum";
    $scope.toolsHelp=			"Tools: [-] Delete the row  [+]Add a new row below";
    $scope.accessConHelp = "AccessCon: Applies to artists who are D/deaf, visibly, and/or invisibly disabled";
    $scope.afroConHelp = "AfroCon: Applies to artists who are Black and may be assigned to an artist who is visibly Black in visuals provided by the artist and/or representation. Any album logged as AfroCon qualifies as POC’Con as well";
    $scope.indigiConHelp = "IndigiCon: Applies to artists who are Indigenous, Métis, and/or Inuit. Includes all Indigenous peoples who have been displaced and/or affected by colonial occupation on Turtle Island";
    $scope.pocConHelp = "POCcon: Applies to artists of color and may be assigned to an artist who is racialized and/or visibly non-white in visuals provided by the artist and/or representatives";
    $scope.queerConHelp = "QueerCon: Applies to artists who are lesbian, gay, bisexual/pansexual, trans, two spirit, queer/questioning, intersex, asexual, and/or any other non-heterosexual, non-cisgender identity";


  };

});
