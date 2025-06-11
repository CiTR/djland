
var app = angular.module('djland.editPlaysheet', ['djland.api', 'djland.utils', 'ui.sortable', 'ui.bootstrap']);
app.controller('PlaysheetController', function ($filter, $rootScope, $scope, $interval, $timeout, call) {
  var api = call;
  const playsheet = this;
  playsheet.info = {
    id: playsheet_id,
    web_exclusive: false
  };
//  $scope.debug = true;
  playsheet.promotions = [];
  playsheet.playitems = {};
  playsheet.podcast = {};
  playsheet.member_id = member_id;
  playsheet.isAdmin = isAdmin;
  playsheet.username = username;
  playsheet.loading = true;
  playsheet.days_of_week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  playsheet.months_of_year = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  //this.tracklist_overlay_header = "Thanks for submitting your playsheet";
  playsheet.max_podcast_length = (max_podcast_length != undefined) ? max_podcast_length : 8 * 60 * 60;
  playsheet.tech_email = "technicalmanager@citr.ca";
  //Helper Variables
  playsheet.tags = tags;
  playsheet.help = help;
  playsheet.complete = false;
  playsheet.skipPodcast = false;
  playsheet.time_changed = false;

  var baseRowTemplate = {
    "show_id": null,
    "playsheet_id": playsheet.info.id,
    "format_id": null,
    "is_playlist": 0,
    "is_canadian": 0,
    "is_yourown": 0,
    "is_indy": 0,
    "is_fem": 0,
    "is_fairplay": 0,
    "is_accesscon": 0,
    "is_afrocon": 0,
    "is_indigicon": 0,
    "is_poccon": 0,
    "is_queercon": 0,
    "is_local": 0,
    "show_date": null,//show_date,
    "duration": 0,
    "is_theme": 0,
    "is_background": 0,
    "crtc_category": playsheet.info.crtc,
    "lang": playsheet.info.lang,
    "is_part": 0,
    "is_inst": 0,
    "is_hit": 0,
    "insert_song_start_hour": "00",
    "insert_song_start_minute": "00",
    "insert_song_length_minute": "00",
    "insert_song_length_second": "00",
    "artist": $scope.debug ? "test" : null,
    "title": $scope.debug ? "test" : null,
    "song": $scope.debug ? "test" : null,
    "composer": $scope.debug ? "test" : null
  }

  this.add = (id) => {
    var row = angular.copy(this.row_template);
    this.playitems.splice(id + 1, 0, row);
    this.update();
  }
  this.remove = (id) => {
    this.playitems.splice(id, 1);
    if (this.playitems.length < 1) {
      $('#addRows').text("Add Row");
    }
    this.update();
  }
  this.addPromotion = () => {
    this.promotions.push(
      {
        "type": "ad",
        "name": "",
        "played": 0,
        num: this.promotions.length
      });
    this.update();
  }
  this.removePromotion = (id) => {
    this.promotions.splice(id, 1);
    this.update();
  }
  this.addFiveRows = () => {
    if ($('#addRows').text() == "Add Five More Rows") {
      for (var i = 0; i < 5; i++) {
        this.add(this.playitems.length - 1);
      }
    } else {
      this.add(0);
      $('#addRows').text("Add Five More Rows");
    }
  }
  this.addStartRow = () => {
    this.playitems = Array();
    this.playitems[0] = angular.copy(this.row_template);
    this.update();

  }
  this.uploadingAudio = false;
  this.audioUrl = "";
  this.audioFile = null;


  this.replacingAudio = false;
  this.beginReplaceAudio = () => {
    this.replacingAudio = true;
  }
  this.deleteAudio = () => {
    if (confirm("Remove the audio? You can re-upload, or Submit playsheet to create it from the archiver.")) {
      this.podcast.url = null;
    }
  }
  this.cancelReplaceAudio = () => {
    this.replacingAudio = false;
  }
  this.canUploadAudio = () => {
    return true;
    var fileElement = $('#audio_file');
    if (fileElement.length == 0) {
      return false;
    }
    var file = fileElement[0].files[0];
    if (file == undefined) {
      return false;
    }
    return true;
  }
  this.uploadAudio = function () {
    var Playsheet = this;

    var fileElement = $('#audio_file');
    if (fileElement.length == 0) {
      alert("file has 0 length");
      return;
    }
    var file = fileElement[0].files[0];
    if (file == undefined) {
      alert("Please click the 'Browse...' button and select an audio file.");
      return;
    }
    const allowedExtensions = ['mp3'];//, 'wav', 'aac', 'ogg', 'flac', 'm4a'];
    const fileName = file.name || '';
    const fileExtension = fileName.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
      alert("Invalid file type. Please select an audio file (mp3).");
      return;
    }

    Playsheet.uploadingAudio = true;
    var form = new FormData();
    form.append('audio', file);
    fetch('api2/public/podcast/' + Playsheet.podcast.id + '/audio', {
      method: 'POST',
      body: form
    })
      .then(response => response.json())
      .then(data => {
        Playsheet.uploadingAudio = false;
        Playsheet.replacingAudio = false;
        console.log(data.audio.url);
        Playsheet.audioUrl = data.audio.url;
        Playsheet.podcast.url = data.audio.url;
        Playsheet.podcast.length = data.audio.length;

        $scope.$apply();
        alert("Uploading audio successful!");
      })
      .catch(error => {
        Playsheet.uploadingAudio = false;
        $scope.$apply();

        if (error.message.includes("JSON.parse:")){
          alert("Sorry, the upload request was dropped by the server. Please try again. If this error persists, please contact " + Playsheet.tech_email);
        } else if (error.statusText){
          alert(error.statusText + " | " + error.message);
        } else {
          alert("An error occurred: " + error.message);
        }
      });
  }

  this.updateTrackDuration = function (playitem) {
    playitem.duration = parseInt(playitem.insert_song_length_minute) * 60 + parseInt(playitem.insert_song_length_second);
  }

  this.updateShowValues = (element) => {
    //When a new show is selected, update all the information.
    this.active_show = this.member_shows.filter(
      (object) => {
        if (object.id == this.show_value) return object;
      }
    )[0];
    this.show = this.active_show.show;
    this.info.show_id = parseInt(this.active_show.id);
    this.info.host = this.active_show.show.host;
    this.info.edit_name = this.username;
    this.podcast.show_id = this.info.show_id;
    this.podcast.author = this.info.host;
    this.info.crtc = this.active_show.crtc;
    this.info.lang = this.active_show.lang;
    for (var playitem in this.playitems) {
      this.playitems[playitem].show_id = this.info.show_id;
      this.playitems[playitem].crtc_category = this.info.crtc;
      this.playitems[playitem].lang = this.info.lang;

      // fairplay
      this.playitems[playitem].fairplay = null;
      this.playitems[playitem].accessCon = null;
      this.playitems[playitem].afrocon = null;
      this.playitems[playitem].indigicon = null;
      this.playitems[playitem].poccon = null;
      this.playitems[playitem].queercon = null;
    }

    api.getNextShowTime(this.active_show.id).then(

      (response) => {
        var start_unix = response.data.start;
        var end_unix = response.data.end;
        this.info.unix_time = response.data.start;
        this.start = new Date(start_unix * 1000);
        this.end = new Date(end_unix * 1000);

        this.info.start_time = $filter('date')(this.start, 'yyyy/MM/dd HH:mm:ss');
        this.info.end_time = $filter('date')(this.end, 'yyyy/MM/dd HH:mm:ss');
        this.start_hour = $filter('pad')(this.start.getHours(), 2);
        this.start_minute = $filter('pad')(this.start.getMinutes(), 2);
        this.start_second = $filter('pad')(this.start.getSeconds(), 2);
        this.end_hour = $filter('pad')(this.end.getHours(), 2);
        this.end_minute = $filter('pad')(this.end.getMinutes(), 2);
        this.end_second = $filter('pad')(this.end.getSeconds(), 2);

        this.updateEnd();
        this.updateStart();
      }

    );
    api.getShowPlaysheets(this.active_show.id).then(function (response) {
      //DISPLAY OLD PLAYSHEETS
      this.existing_playsheets = response.data.sort(function (a, b) {
        var re = new RegExp("-", "g");
        if (!b.start_time) {
          return -1;
        }
        if (!a.start_time) {
          return 1;
        }
        return (
          new Date(b.start_time.replace(re, "/")) -
          new Date(a.start_time.replace(re, "/"))
        );
      });
    });
  }
  this.updateSpokenword = function () {
    this.info.spokenword_duration = this.spokenword_hours * 60 + this.spokenword_minutes;
  }
  this.updateStart = function () {
    this.start.setHours(this.start_hour);
    this.start.setMinutes(this.start_minute);
    this.start.setSeconds(this.start_second);
    this.info.start_time = $filter('date')(this.start, 'yyyy/MM/dd HH:mm:ss');
    this.updatePodcastDate();
    this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;
  }
  this.updateEnd = function () {
    this.end.setHours(this.end_hour);
    this.end.setMinutes(this.end_minute);
    this.end.setSeconds(this.end_second);
    this.info.end_time = $filter('date')(this.end, 'yyyy/MM/dd HH:mm:ss');
    this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;
  }

  //Setting Show Times
  // flag for removal TODO FIXME after we know for sure
  // no one wants it
  this.startShow = function () {
    this.start = new Date();
    this.start_hour = $filter('pad')(this.start.getHours(), 2);
    this.start_minute = $filter('pad')(this.start.getMinutes(), 2);
    this.start_second = $filter('pad')(this.start.getSeconds(), 2);
    this.info.start_time = this.start;
    this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;
  }
  this.endShow = function () {
    this.end = new Date();
    this.end_hour = $filter('pad')(this.end.getHours(), 2);
    this.end_minute = $filter('pad')(this.end.getMinutes(), 2);
    this.end_second = $filter('pad')(this.end.getSeconds(), 2);
    //this.end_time = $filter('date')(end, 'HH:mm:ss');
    this.info.end_time = $filter('date')(this.end, 'yyyy/MM/dd HH:mm:ss');
    this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;
  }

  this.loadRebroadcast = function () {
    api.getPlaysheetData(this.existing_playsheet).then(
      (response) => {
        this.playitems = response.data.playitems;
        this.info.spokenword_duration = response.data.playsheet.spokenword_duration;
        if (this.info.spokenword_duration != null) {
          this.spokenword_hours = Math.floor(this.info.spokenword_duration / 60);
          this.spokenword_minutes = this.info.spokenword_duration % 60;
        } else {
          this_.spokenword_hours = 0;
          this_.spokenword_minutes = null;
        }
        this.promotions = response.data.promotions;
      }
    );
  }
  this.loadIfRebroadcast = function () {
    if (this.info.type == 'Rebroadcast') {
      api.getShowPlaysheets(this.active_show.id).then(
        (response) => {
          //DISPLAY OLD PLAYSHEETS
          this.existing_playsheets = response.data.sort(
            function (a, b) {
              var re = new RegExp('-', 'g');
              return new Date(b.start_time.replace(re, '/')) - new Date(a.start_time.replace(re, '/'));
            }
          );
        }
      );
    }

  }
  this.getNewUnix = function () {
    //  console.log("get new unix");
    if (this.loading == true) return;
    //convert to seconds from javascripts milliseconds
    var start_unix = this.start / 1000;
    var end_unix = this.end / 1000;

    if (end_unix < start_unix) {
      this.update();
      return;
    }

    //get minutes for start, and push unix to 0/30 minute mark on closest hour
    var minutes = this.start.getMinutes();
    start_unix -= minutes * 60;
    if (minutes >= 45) {
      //roll to the next hour by adding 3600s
      start_unix += 60 * 60;
    } else if (minutes < 45 && minutes >= 15) {
      //set to 30 minutes through by adding 1800s
      start_unix += 30 * 60;
    } else {
      //already at zero minutes.
    }
    //Get minutes for end, and push unix to 0/30 minute mark on closes hour
    minutes = this.end.getMinutes();
    end_unix -= minutes * 60;
    if (minutes >= 45) {
      //roll to the next hour by adding 3600s
      end_unix += 60 * 60;
    } else if (minutes < 45 && minutes >= 15) {
      //set to 30 minutes through by adding 1800s
      end_unix += 30 * 60;
    } else {
      //already at zero minutes.
    }

    this.start_unix = start_unix;
    this.end_unix = end_unix;
    var duration = end_unix - start_unix;

    function allEmptyPromotions() {
      return playsheet.promotions.every(
        (promotion) => {
          if (promotion.type == "id") {
            return promotion.played == 0;
          } else {
            return promotion.name == "" || promotion.name == null;
          }
        }
      );
    }

    if (this.info.id < 1 && allEmptyPromotions()) {
      var hours = Math.floor(duration / (60 * 60));
      if (hours === 0) {
        hours = 1;
      } if (hours > 4) {
        hours = 4;
      }
      var index = 0;
      var hourPromos = () => {

        return [
          {
            "type": "id",
            "name": "",
            "played": 0,
            num: index++
          },
          {
            "type": "ad",
            "name": "",
            "played": 0,
            num: index++
          },
          {
            "type": "ad",
            "name": "",
            "played": 0,
            num: index++
          },
          {
            "type": "promo",
            "name": "",
            "played": 0,
            num: index++
          },
          {
            "type": "psa",
            "name": "",
            "played": 0,
            num: index++
          },
        ];
      }
      var newPromotions = [];
      for (var i = 0; i < hours; i++) {
        newPromotions = [...newPromotions, ...hourPromos()];
      }

      this.promotions.map((promotion, index) => {
        if (index < newPromotions.length) {
          newPromotions[index].name = promotion.name;
          newPromotions[index].type = promotion.type;
          newPromotions[index].played = promotion.played;
        }
      });
      this.promotions = newPromotions;
      this.update();

    }
    // replace above

    api.isSocan(this.start_unix).then(

      (response) => {
        if (response.status == '200') {
          var socanText = $('#socan').text().trim();
          this.info.socan = (((socanText == 'true' || socanText == '1' ? true : false) || response.data) ? 1 : 0);
        }
        this.update();
      }

    );
    this.time_changed = true;

  }

  //Initialization of Playsheet
  var init = () => {

    //If playsheet exists, load it.
    if (this.info.id > 0) {
      api.getPlaysheetData(this.info.id).then(
        (data) => {
          var playsheet = data.data;
          this.info = {};
          for (var item in playsheet.playsheet) {
            this.info[item] = playsheet.playsheet[item];
          }
          var re = new RegExp('-', 'g');
          this.info.start_time = this.info.start_time.replace(re, '/');
          this.info.end_time = this.info.end_time.replace(re, '/');
          //Create Extra Variables to allow proper display in UI
          this.start = new Date(this.info.start_time);
          this.end = new Date(this.info.end_time);
          this.start_hour = $filter('pad')(this.start.getHours(), 2);
          this.start_minute = $filter('pad')(this.start.getMinutes(), 2);
          this.start_second = $filter('pad')(this.start.getSeconds(), 2);
          this.end_hour = $filter('pad')(this.end.getHours(), 2);
          this.end_minute = $filter('pad')(this.end.getMinutes(), 2);
          this.end_second = $filter('pad')(this.end.getSeconds(), 2);

          api.isSocan(this.start / 1000).then(

            (response) => {
              if (response.status == '200') {
                var socanText = $('#socan').text().trim();
                this.info.socan = (((socanText == 'true' || socanText == '1' ? true : false) || response.data) ? 1 : 0);
              }
            }

          );


          if (this.info.spokenword_duration != null) {
            this.spokenword_hours = Math.floor(this.info.spokenword_duration / 60);
            this.spokenword_minutes = this.info.spokenword_duration % 60;
          } else {
            this.spokenword_hours = 0;
            this.spokenword_minutes = null;
          }
          //Set Show Data
          this.show = playsheet.show;

          this.playitems = playsheet.playitems;
          this.podcast = playsheet.podcast == null ? { 'id': -1, 'playsheet_id': this.info.id, 'show_id': playsheet.show_id } : playsheet.podcast;
          this.promotions = playsheet.promotions;
          //If no playitems, change "Add Five Rows" button to say "Add Row" instead
          if (this.playitems < 1) {
            $('#addRows').text("Add Row");
          }
          for (var playitem in this.playitems) {
            this.playitems[playitem].insert_song_start_hour = $filter('pad')(this.playitems[playitem].insert_song_start_hour, 2);
            this.playitems[playitem].insert_song_start_minute = $filter('pad')(this.playitems[playitem].insert_song_start_minute, 2);
            this.playitems[playitem].insert_song_length_minute = $filter('pad')(this.playitems[playitem].insert_song_length_minute, 2);
            this.playitems[playitem].insert_song_length_second = $filter('pad')(this.playitems[playitem].insert_song_length_second, 2);
          }

          //Get Member shows, and set active show
          api.getActiveMemberShows(this.member_id).then(
            (data) => {
              var shows = data.data.shows;
              this.member_shows = shows;
              //Find what show this playsheet is for, and set it as active show to load information.
              for (var show in this.member_shows) {

                if (this.show.name.toString() == shows[show].show.name.toString()) {
                  this.active_show = this.member_shows[show];
                  this.show_value = shows[show]['id'];
                  this.show = shows[show]['show'];
                }
              }

              api.getShowPlaysheets(this.active_show.id).then(
                (response) => {
                  //DISPLAY OLD PLAYSHEETS
                  this.existing_playsheets = response.data.sort(
                    function (a, b) {
                      var re = new RegExp('-', 'g');
                      return new Date(b.start_time.replace(re, '/')) - new Date(a.start_time.replace(re, '/'));
                    }
                  );
                }
              );
              //Populate the template row
              this.row_template = {
                ...baseRowTemplate,
                show_id: this.active_show.id,
                playsheet_id: this.info.id,
                show_date: this.start.getDate(),
                crtc_category: this.info.crtc,
                lang: this.info.lang,
              };
              this.checkIfComplete();
              this.loading = false;
              this.time_changed = false;
            }
          );
        }

      );
    } else {

      // we are creating a brand new playsheet object
      this.podcast = {};
      if ($scope.debug) {
        this.podcast = {
          title: "test"
        }
      }

      this.info.status = '1';
      this.info.type = 'Live';
      this.spokenword_hours = 0;
      this.spokenword_minutes = null;
      this.podcast.active = 0;

      //Get Shows Listing
      api.getActiveMemberShows(this.member_id).then(
        (data) => {
          var shows = data.data.shows;
          this.member_shows = shows;
          if (shows) {
            //Cheat Code to get first active show.
            for (var show in this.member_shows) {
              this.active_show = this.member_shows[show];
              this.show = this.active_show.show;

              this.show_value = this.active_show['id'];
              this.info.show_id = parseInt(this.active_show.id);
              this.info.host = this.active_show.show.host;
              this.info.create_name = this.info.host;

              this.podcast.author = this.info.host;
              this.info.crtc = this.active_show.crtc;
              this.info.lang = this.active_show.lang || 'English';

              for (var playitem in this.playitems) {
                this.playitems[playitem].show_id = this.info.show_id;
              }
              break;
            }
            var now = new Date();

            api.getShowPlaysheets(this.show_value).then(
              (response) => {
                this.existing_playsheets = response.data.sort(
                  function (a, b) {
                    var re = new RegExp('-', 'g');
                    return new Date(b.start_time.replace(re, '/')) - new Date(a.start_time.replace(re, '/'));
                  }
                );
              }
            );

            api.getNextShowTime(this.active_show.id, now).then(

              (response) => {
                var start_unix = response.data.start;
                var end_unix = response.data.end;
                this.start = new Date(start_unix * 1000);
                this.end = new Date(end_unix * 1000);

                this.info.unix_time = this.start.getTime() / 1000;
                this.info.start_time = $filter('date')(this.start, 'yyyy/MM/dd HH:mm:ss');
                this.info.end_time = $filter('date')(this.end, 'yyyy/MM/dd HH:mm:ss');
                this.start_hour = $filter('pad')(this.start.getHours(), 2);
                this.start_minute = $filter('pad')(this.start.getMinutes(), 2);
                this.start_second = $filter('pad')(this.start.getSeconds(), 2);
                this.end_hour = $filter('pad')(this.end.getHours(), 2);
                this.end_minute = $filter('pad')(this.end.getMinutes(), 2);
                this.end_second = $filter('pad')(this.end.getSeconds(), 2);

                //Populate Template Row, then add 5 rows
                //Update Podcast information
                this.updatePodcastDate();
                this.updateEnd();
                this.updateStart();

                this.row_template = {
                  ...baseRowTemplate,
                  show_id: this.active_show.id,
                  playsheet_id: this.info.id,
                  show_date: this.start.getDate(),
                  duration: null,
                  is_theme: null,
                  is_background: null,
                  crtc_category: this.info.crtc,
                  lang: this.info.lang,
                }
                this.addStartRow();
                for (var i = 0; i < 4; i++) {
                  this.add(this.playitems.length - 1);
                }
                this.time_changed = false;
                this.update();
                this.loading = false;
              }
            );
          } else {
            this.loading = false;
          }
        }
      );
    }

  }

  this.updatePodcastDate = function () {
    this.podcast.date = this.info.start_time;
    this.podcast.iso_date = this.days_of_week[this.start.getDay()] + ", " + this.start.getDate() + " " + this.months_of_year[this.start.getMonth()] + " " + this.start.getFullYear() + " " + $filter('date')(this.start, 'HH:mm:ss') + " -0700";
  }
  //When a playsheet item is added or removed, check for completeness
  $scope.$watchCollection('playsheet.playitems',
    () => {
      this.update();
    }, true
  );
  $scope.$watch('playsheet.info.start_time',
    () => {
      this.info.start_time = $filter('date')(this.info.start_time, 'yyyy/MM/dd HH:mm:ss');
      this.start = new Date(this.info.start_time);
      this.start_hour = $filter('pad')(this.start.getHours(), 2);
      this.start_minute = $filter('pad')(this.start.getMinutes(), 2);
      this.start_second = $filter('pad')(this.start.getSeconds(), 2);

      if (this.start && this.end) this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;

      var days = Math.floor((this.end - this.start) / (60 * 60 * 1000 * 24));

      if (days !== 0 && this.end) {
      } else {
        this.getNewUnix();
      }
    }
  );
  $scope.$watch('playsheet.info.end_time',

    () => {
      this.info.end_time = $filter('date')(this.info.end_time, 'yyyy/MM/dd HH:mm:ss');
      this.end = new Date(this.info.end_time);
      this.end_hour = $filter('pad')(this.end.getHours(), 2);
      this.end_minute = $filter('pad')(this.end.getMinutes(), 2);
      this.end_second = $filter('pad')(this.end.getSeconds(), 2);
      if (this.start && this.end) this.podcast.duration = (this.end.getTime() - this.start.getTime()) / 1000;
      this.getNewUnix();
    }

  );

  this.update = function () {
    $timeout(this.checkIfComplete, 100);
  }
  this.checkIfComplete = () => {
    var playsheet_okay = true;
    var timeProblem = '';
    if (this.start > this.end) {
      playsheet_okay = false;
      timeProblem = "   The end time is before the start time."
    }
    var problems = [];

    $('.required').each(
      (index, element) => {
        var model = element.getAttribute('ng-model');
        var val = this[model];
        if (element.type == "checkbox") {
          val = element.checked;
        } else {
          val = $(element).val();
        }

        if (val == "" || !val) {
          playsheet_okay = false;
          switch (model) {
            case "playitem.artist":
              if (this.playitems.length > 0) problems.push("an artist");
              break;
            case 'playitem.album':
              if (this.playitems.length > 0) problems.push("an album");
              break;
            case 'playitem.song':
              if (this.playitems.length > 0) problems.push("a song title");
              break;
            case 'playitem.composer':
              if (this.playitems.length > 0) problems.push("a composer");
              break;
            case 'playsheet.spokenword_minutes':
              problems.push("spoken word duration");
              break;
            case 'playsheet.info.title':
              problems.push("episode title");
              break;
            case 'playsheet.info.summary':
              problems.push("episode description");
              break;
            case 'promotion.name':
              problems.push("an ad name");
              break;
            case 'promotion.played':
              problems.push("a station ID")
            default:
              break;
          }
        }
      }
    );
    if (playsheet_okay) {
      this.complete = true;
    } else {
      //deduplicate problems
      problems = problems.filter(function (item, pos) {
        return problems.indexOf(item) == pos;
      });

      this.missing = (problems.length > 0 ? "Playsheet is missing: " + problems.join(', ') : "") + timeProblem;
      this.complete = false;
    }
  }
  this.saveDraft = () => {
    this.info.unix_time = this.start.getTime() / 1000;
    var date = $filter('date')(this.start, 'yyyy/MM/dd');
    for (var playitem in this.playitems) {
      this.playitems[playitem].show_date = date;
    }
    this.podcast.date = this.info.start_time;
    this.podcast.show_id = this.info.show_id;
    this.updatePodcastDate();
    this.podcast.title = this.info.title;
    this.podcast.subtitle = this.info.summary;
    this.podcast.summary = this.info.summary;
    if (this.info.status <= 1) {
      if (this.info.id < 1) {
        //New Playsheet
        this.info.create_name = this.username;
        this.info.show_name = this.active_show.name;
        callback = api.saveNewPlaysheet(this.info, this.playitems, this.podcast, this.promotions)
          .then(
            (response) => {
              alert("Draft Saved ");
              window.location.href =
                "/playsheet.php?id=" +
                response.data.id +
                "&socan=" +
                (this.info.socan == 1 ? "true" : "false");

            },
            (error) => {
              if (error && error.data && error.data.message) {
                alert("Draft was not saved (1). " + error.data.message);
              }
              this.log_error(error);
            }
          );
      } else {
        //Existing Playsheet
        api.savePlaysheet(this.info, this.playitems, this.podcast, this.promotions).then(
          (response) => {
            for (var playitem in this.playitems) {
              this.playitems[playitem].playsheet_id = this.info.id;
            }
            alert("Draft Saved");
          },
          (error) => {
            if (error && error.data && error.data.message) {
              alert("Draft was not saved (2). " + error.data.message);
            }
            this.log_error(error);
          }
        );
      }
    } else {
      alert("Draft not saved - you've already submitted this playsheet. Please re-submit this playsheet to save new information");
    }

  }
  //Submit a Playsheet
  this.makePodcastAudio = () => {
    if (!this.skipPodcast) {
      this.podcast_status = "Your podcast is being created";
      api.makePodcastAudio(this.podcast).then(
        (reponse) => {
          this.podcast_status = "Podcast Audio transfer in progress.";
          this.time_changed = false;
        }
        , (error) => {
          this.podcast_status = "Could not generate podcast. Playsheet was saved successfully.";
          this.error = true;
          this.log_error(error);
        }
      );
    }
    else {
      api.makeXml(this.podcast.show_id);
      this.podcast_status = "Podcast Audio was not updated";
    }
  }
  this.submit = () => {
    this.podcast_status = "";
    $('#playsheet_error').html("");
    this.tracklist_overlay_header = "Thanks for submitting your playsheet";

    this.info.unix_time = this.start.getTime() / 1000;
    this.podcast.show_id = this.info.show_id;
    this.podcast.date = this.info.start_time;
    this.podcast.active = 1;
    this.podcast.title = this.info.title;
    this.podcast.subtitle = this.info.summary;
    this.podcast.summary = this.info.summary;
    this.info.show_name = this.active_show.name;
    //Ensuring start and end times work for podcast generation
    if (new Date(this.info.start_time) > new Date() || new Date(this.info.end_time) > new Date()) {
      alert("Playsheet time is in the future. You can save as a draft and submit later.");
    } else if (new Date(this.info.start_time) > new Date(this.info.end_time)) {
      alert("End time is before start time");
    } else if (this.end.getTime() / 1000 - this.start.getTime() / 1000 > this.max_podcast_length) { // Divide by 10000 because milliseconds
      this.max_podcast_length_hours = this.max_podcast_length / 3600;
      alert("This podcast is over " + this.max_podcast_length_hours + " hours. " + this.max_podcast_length_hours + " Hours is the maximum");
    } else { //The start and end times work - proceed to make podcast

      //Update Status to submitted playsheet
      this.info.status = 2;
      var date = $filter('date')(this.start, 'yyyy/MM/dd');
      for (var playitem in this.playitems) {
        this.playitems[playitem].show_date = date;
      }

      this.updatePodcastDate();

      if (this.info.id < 1) {
        //New Playsheet and new podcast
        this.info.create_name = this.username;
        api.saveNewPlaysheet(this.info, this.playitems, this.podcast, this.promotions).then(
          (response) => {
            this.promotions = response.data.ads;
            this.info.id = response.data.id;
            for (var playitem in this.playitems) {
              this.playitems[playitem].playsheet_id = this.info.id;
            }
            this.podcast.id = response.data.podcast_id;
            this.podcast.playsheet_id = response.data.id;
            this.tracklist_overlay = true;

            this.makePodcastAudio();

            //}
          },
          (error) => {
            this.tracklist_overlay_header = "An error has occurred while saving the playsheet";
            this.podcast_status = "Podcast Not created";
            this.error = true;
            this.log_error(error);
            this.tracklist_overlay = true;
          }
        );
      } else {
        //Existing Playsheet and Podcast
        api.savePlaysheet(this.info, this.playitems, this.podcast, this.promotions).then(
          (response) => {
            this.tracklist_overlay = true;

            if (this.podcast.url) {
              api.makeXml(this.podcast.show_id);
              this.podcast_status = 'Updated podcast channel.';
            }
            else if (!this.skipPodcast){
              this.makePodcastAudio();
              this.podcast_status = 'Creating new Podcast Audio from archive log.';
            }
          },
          (error) => {
            this.podcast_status = "Podcast not created";
            this.error = true;
            this.log_error(error);
            this.tracklist_overlay = true;
          }
        );

      }
    }
  }
  this.log_error = (error) => {
    this.tracklist_overlay_header = "An error has occurred while saving the playsheet";

    if (error && error.data && error.data.message) {
      this.podcast_status = error.data.message;
    }
    $('#playsheet_error').html("Please contact your station technical services at " + this.tech_email + ". Your error has been logged");

  }

  init();

  var basic_sound_options = {
    debugMode: false,
    useConsole: false,
    autoLoad: true,
    multiShot: false,
    volume: 70,
    stream: true
  };

  sm = new SoundManager();
  sm.setup({
    debugMode: false,
  });

  playsheet.preview_start = () => {
    var start = new Date(playsheet.info.start_time);
    var preview_end = new Date(start).setSeconds(start.getSeconds() + 10);
    var sound_url = playsheet.getPreviewUrl(new Date(start), preview_end);
    console.log(sound_url);
    this.load_and_play_sound(sound_url, 'start');
  };

  playsheet.preview_end = () => {
    var end = new Date(playsheet.info.end_time);
    var preview_start = new Date(end).setSeconds(end.getSeconds() - 10);
    var sound_url = playsheet.getPreviewUrl(preview_start, end);
    console.log(sound_url);
    this.load_and_play_sound(sound_url, 'end');
  };

  playsheet.load_and_play_sound = (url, time) => {
    var this_ = this;
    if (typeof (this.sound) != 'undefined') {
      this.sound.destruct();
    }
    this.seconds_elapsed = 0;
    this.audio_start = new Date();
    this.playing = true;
    this.message = 'playing ...';
    this.sound = sm.createSound(
      angular.extend(basic_sound_options, {
        autoPlay: true,
        url: url,
        onfinish: function () {
          this_.message = '';
          this.playing = false;
          $interval.cancel(this_.elapsedInterval);
        },

        whileplaying: function () {

          this_.elapsedInterval = $interval(this_.elapsedTime(time), 1000);
          this_.message = 'playing ...';
          if (this.duration == 0) {
            this_.message = 'sorry, preview not available.';
          }
        }
      })
    );
  };

  playsheet.getPreviewUrl = (start, end) => {
    return 'http://archive.citr.ca/py-test/archbrad/download?' +
      'archive=%2Fmnt%2Faudio_stor%2Flog' +
      '&startTime=' + $filter('date')(start, 'dd-MM-yyyy HH:mm:ss') +
      '&endTime=' + $filter('date')(end, 'dd-MM-yyyy HH:mm:ss');
  };

  playsheet.stop_sound = () => {
    sm.stopAll();
    this.message = '';
  };

  playsheet.elapsedTime = (time) => {
    this.seconds_elapsed = (new Date().getTime() / 1000) - (this.audio_start.getTime() / 1000);
    if (time == 'start') {
      var elapsed = new Date(this.start);
      elapsed.setSeconds(elapsed.getSeconds() + this.seconds_elapsed);
    } else if (time == 'end') {
      var elapsed = new Date(this.end);
      elapsed.setSeconds(this.end.getSeconds() - 10 + this.seconds_elapsed);
    }
    $('#elapsed').text($filter('date')(elapsed, 'yyyy/MM/dd HH:mm:ss'));
  };

});

app.controller('datepicker', function ($filter) {
  this.today = function () {
    this.dt = $filter('date')(new Date(), 'yy/MM/dd HH:mm:ss');
  };
  this.clear = function () {
    this.dt = null;
  };
  this.open = function ($event) {
    $event.preventDefault();
    $event.stopPropagation();
    this.opened = true;
  };
  this.format = 'yyyy/MM/dd HH:mm:ss';
});

//Declares playitem attribute
app.directive('playitem', function () {
  return {
    restrict: 'A',
    templateUrl: 'templates/playitem.html?v=20230526'
  };
});
//Declares ad attribute
app.directive('promotion', function () {
  return {
    restrict: 'A',
    templateUrl: 'templates/promotion.html?v=20230526'
  }
});
app.directive('datepickerPopup', function () {
  return {
    restrict: 'EAC',
    require: 'ngModel',
    link: function (scope, element, attr, controller) {
      //remove the default formatter from the input directive to prevent conflict
      controller.$formatters.shift();
    }
  }
});


$(document).ready(function () {
  var can_2_element = $('#can_2_total');
  var can_3_element = $('#can_3_total');
  var fairplay_element = $('#fairplay_total'); // "Fem" was replaced with Fairplay
  var playlist_element = $('#playlist_total');
  var hit_element = $('#hit_total');

  setInterval(function () {
    crtc_totals();
  }, 3000);


  function crtc_totals() {
    var can_2_required = parseInt($('#can_2_required').text(), 10);
    var can_3_required = parseInt($('#can_3_required').text(), 10);
    var fem_required = parseInt($('#fem_required').text(), 10);
    var playlist_required = parseInt($('#playlist_required').text(), 10);
    var hit_max = parseInt($('#hit_max').text(), 10);

    var playitems_count = $('.playitem').length;
    var totals_divide = ($('.playitem').length) ? $('.playitem').length : 1;
    var can_2_count = 0;
    var can_3_count = 0;

    var can_2_total = 0;
    var can_3_total = 0;
    var fairplay_total = $('.playitem').has('button.fairplay.filled').length;
    var playlist_total = $('.playitem').has('button.playlist.filled').length;
    var hit_total = $('.playitem').has('button.hit.filled').length;

    $('.playitem').each(function (element) {
      if ($(this).find('select.crtc_category').val() == '20') {
        can_2_count++;

        if ($(this).find('button.cancon').hasClass('filled')) {
          can_2_total++;
        }
      } else {
        can_3_count++;

        if ($(this).find('button.cancon').hasClass('filled')) {
          can_3_total++;
        }
      }
    });

    can_2_element.text((can_2_total / (can_2_count ? can_2_count : 1) * 100).toFixed(0) + "%");
    if (can_2_total / (can_2_count != 0 ? can_2_count : 1) * 100 < can_2_required && can_2_count > 0) can_2_element.addClass('red');
    else can_2_element.removeClass('red');

    can_3_element.text((can_3_total / (can_3_count ? can_3_count : 1) * 100).toFixed(0) + "%");
    if (can_3_total / (can_3_count != 0 ? can_3_count : 1) * 100 < can_3_required && can_3_count > 0) can_3_element.addClass('red');
    else can_3_element.removeClass('red');

    playlist_element.text((playlist_total / totals_divide * 100).toFixed(0) + "%");
    if (playlist_total / totals_divide * 100 < playlist_required && playitems_count > 0) playlist_element.addClass('red');
    else playlist_element.removeClass('red');

    hit_element.text((hit_total / totals_divide * 100).toFixed(0) + "%");
    if (hit_total / totals_divide * 100 > hit_max && playitems_count > 0) hit_element.addClass('red');
    else hit_element.removeClass('red');

    var fairplay_element_text = fairplay_total / totals_divide * 100;

    fairplay_element.text(fairplay_element_text.toFixed(0) + "%");

    if (fairplay_total / totals_divide * 100 < fem_required && playitems_count > 0) fairplay_element.addClass('blue');
    else fairplay_element.removeClass('blue');
  }
});

