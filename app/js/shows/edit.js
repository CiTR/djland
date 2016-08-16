
(function(){
  var app = angular.module('djland.editShow',['djland.api','djland.utils']);

  app.directive('showtime',function(){
    return{
      restrict:'A',
      templateUrl: 'templates/showtime.html'
    };
  });

  app.directive('social',function(){
    return{
      restrict:'A',
      templateUrl: 'templates/social.html'
    };
  });

  app.controller('editShow', function($scope,$rootScope, $filter, call, $location, shared, tools){
    this.loading = true;
    this.init = function(){
      this.admin = false;
      this.member_id = member_id;
      this.username = username;
      this.shared = shared;
      //Get List of all members
      this.getMemberList();
      //Get List of primary genres
      call.getConstants().then(
        (
          function(response){
            this.genres = response.data.genres;
          }
        ).bind(this)
      );
      //Get Shows Member can see
      call.getMemberShows(this.member_id).then(
        (
          function(response){
            this.member_shows = response.data.shows;
            for(var show in this.member_shows){
              if(this.member_shows[show].show.name != null) this.member_shows[show].show.name = tools.decodeHTML(this.member_shows[show].show.name);
              if(this.member_shows[show].show.host != null) this.member_shows[show].show.host = tools.decodeHTML(this.member_shows[show].show.host);
            }

            //Get First show in member_shows
            for(var show in this.member_shows){
              this.active_show = this.member_shows[show];
              break;
            }

            //Need to make the id a string
            this.show_value = ""+this.active_show.id;
            this.loadShow();
          }
        ).bind(this)
        ,(
          function(error){
            this.loading = false;
          }
        ).bind(this)
      );
      //Calculating "current week" this math is really old. Returns 1 or 2
      //this.current_week = Math.floor( ((Date.now()/1000 - 1341100800)*10 / (7*24*60*60))%2 +1);

      //New Method for getting current week
      var d = new Date();
      //Get most recent sunday at 12:00:00am
      d.setHours(0,0,0);
      d.setDate(d.getDate()-(d.getDay()||7));
      //Get Days since epoch start. Then divide by 7 for weeks. Then round down.
      var week_no = Math.floor(((d/8.64e7))/7);
      //divide by 2, and then add one so we have 1||2 instead of 0||1
      this.current_week = ((week_no % 2) +1);
      //Check if user is an administrator or staff
      this.isStaff();
    }
    this.isStaff = function(){
      //Call API to obtain permissions
      call.isStaff(this.member_id).then(
        (
          function(response){
            this.admin = response.data;
          }
        ).bind(this)
        ,function(error){
          console.log(error.data);
        }
      );
    }
    this.newShow = function(){
      this.show_value = 0;
      this.info = {'id':0,'lang_default':'English','crtc_default':'20','pl_req':'60','cc_req':'35','indy_req':'25','fem_req':'35','create_name':username};
      this.socials = new Array();
      this.show_times = new Array();
      this.show_owners = new Array();
      this.primary_genres = new Array();
      this.secondary_genres = new Array();
      this.social_template = {show_id: this.info.id, social_name: "" , social_url:""};
      this.showtime_template = {show_id:this.info.id,start_day:"0",end_day:"0",start_time:"00:00:00",end_time:"00:00:00",start_hour:"00",start_minute:"00",end_hour:"00",end_minute:"00",alternating:'0'};
    }
    this.loadShow = function(){
      call.getShow(this.active_show.id).then(
        (
          function(response){
            this.info = response.data;

            //If either of these have HTML chars strip them so it will save without, the user being none the wiser
            this.info.name = tools.decodeHTML(this.info.name);
            this.info.show_desc = tools.decodeHTML(this.info.show_desc);
            this.shared.setShowName(this.info.name);
            //Split genres on comma to allow user management

            this.primary_genres = this.info.primary_genre_tags != null ? this.info.primary_genre_tags.split(',') : Array();
            this.secondary_genres = this.info.secondary_genre_tags != null ? this.info.secondary_genre_tags.split(',') : Array();


            //Remove Social array from the show object.
            this.socials = response.data.social;
            delete this.info.social;
            this.social_template = {show_id: this.info.id, social_name: "" , social_url:""};
            this.loading = false;
          }
        ).bind(this)
        ,(
          function(error){
            this.loading = false;
          }
        ).bind(this)
      );
      //Call API to get show owners
      call.getShowOwners(this.active_show.id).then(
        (
          function(response)
          {
            //If no response make an empty object
            if(response.data != null){
              this.show_owners = response.data.owners;
            }else{
              this.show_owners = Array();
            }
          }
        ).bind(this)
        ,function(error){
          console.log('Could not get show owners for the active show.')
        }
      );
      //Call API to get show times
      call.getShowTimes(this.active_show.id).then(
        (
          function(response){
            this.show_times = response.data;
            this.showtime_template = {show_id:this.active_show.id,start_day:"0",end_day:"0",start_time:"00:00:00",end_time:"00:00:00",start_hour:"00",start_minute:"00",end_hour:"00",end_minute:"00",alternating:'0'};
            //Allowing show times to be displayed in UI by splitting on colon
            for(var showtime in this.show_times){
              this.show_times[showtime].start_hour = $filter('pad')(this.show_times[showtime].start_time.split(':')[0],2);
              this.show_times[showtime].start_minute = $filter('pad')(this.show_times[showtime].start_time.split(':')[1],2);
              this.show_times[showtime].end_hour = $filter('pad')(this.show_times[showtime].end_time.split(':')[0],2);
              this.show_times[showtime].end_minute = $filter('pad')(this.show_times[showtime].end_time.split(':')[1],2);
            }
          }
        ).bind(this)
        ,(
          function(error){
            this.loading = false;
          }
        ).bind(this)
      );
    }
    this.getMemberList = function(){
      call.getMemberList().then(
        (
          function(response){
            this.member_list = response.data;
          }
        ).bind(this)
      );
    }

    this.addFirstSocial = function(){
      //Add template row for social
      this.socials.push(angular.copy(this.social_template));
    }
    this.addFirstShowTime = function(){
      this.show_times.push(angular.copy(this.showtime_template));

    }
    this.addSocial = function(id){
      this.socials.splice(id+1,0,angular.copy(this.social_template));
    }
    this.addOwner = function(){
      //No need to check for duplicates, as there is only one id per member
      var id = $('#member_access_select').val();
      /*Find objects with id = selected id and return them. As id's are unique we take the first one we get then add it to show owners list
      Found at http://stackoverflow.com/questions/13964155/get-javascript-object-from-array-of-objects-by-value-or-property */
      var exists = false;
      for(var owner_index in this.show_owners){
        if(this.show_owners[owner_index].id == id) exists = true;
      }
      if(!exists){
        this.show_owners.push(this.member_list.filter(function(object){if(object.id == id) return object;})[0]);
      }
    }
    this.addShowTime = function($index){
      this.show_times.splice($index+1,0,angular.copy(this.showtime_template));
      console.log(this.owners);
    }
    this.addPrimaryGenre = function(){
      var genre = this.genres[this.primary_genre_select];
      if( !(this.primary_genres[0] == genre || this.primary_genres[1] == genre)){
        this.primary_genres.splice(this.primary_genres.length,0,genre);
      }
      this.updatePrimaryGenres();
    }
    this.addSecondaryGenre = function(){
      var genre = this.secondary_genre_input;
      var exists = false;
      for(var g in this.secondary_genres){
        if(g == genre) exists = true;
      }
      if(!exists){
        this.secondary_genres.splice(this.secondary_genres.length,0,genre);
        this.secondary_genre_input = '';
      }
      this.updateSecondaryGenres();
    }
    this.removeSocial = function(id){
      this.socials.splice(id,1);
    }
    this.removeOwner = function($index){
      this.show_owners.splice($index,1);
    }
    this.removeShowTime = function($index){
      this.show_times.splice($index,1);
    }
    this.removePrimaryGenre= function($index){
      this.primary_genres.splice($index,1);
      this.updatePrimaryGenres();
    }
    this.removeSecondaryGenre= function($index){
      this.secondary_genres.splice($index,1);
      this.updateSecondaryGenres();
    }
    this.updateShow = function(){
      this.active_show=this.member_shows.filter(
        (
          function(object){
            if(object.id == this.show_value) return object;
          }
        ).bind(this)
      )[0];
      this.loadShow();
    }
    this.updateShowtime = function(showtime){
      showtime.start_time = showtime.start_hour + ":" + showtime.start_minute + ":00";
      showtime.end_time = showtime.end_hour + ":" + showtime.end_minute + ":00";
    }
    this.updatePrimaryGenres = function(){
      this.info.primary_genre_tags = this.primary_genres.join(',')
      ;        }
      this.updateSecondaryGenres = function(){
        this.info.secondary_genre_tags = this.secondary_genres.join(',');
      }

      this.save = function(){
        this.info.edit_name = this.username;
        //this.info.edit_date = $filter('date')(new Date(),'yyyy/MM/dd HH:mm:ss');
        console.log(this);
        this.message = 'saving...';

        if(this.info.id == 0){
          call.saveNewShow(this.info,this.socials,this.show_owners,this.show_times).then(
            (
              function(response){
                var show = response.data['show'];
                alert("Successfully Create New Show: "+this.info.name);
                this.info.id = show['id'];
                for(var sh in this.show_times){
                  this.show_times[sh].show_id = show['id'];
                }
                for(var s in this.social){
                  this.socials[s].show_id = show['id'];
                }
              }
            ).bind(this),
            function(error){
              alert("Failed to save");
              console.error(response);
            }
          );
        }else{
          call.saveShow(this.info,this.socials,this.show_owners,this.show_times).then(
            function(response){
              //                    console.log(response.data.message);
              alert("Successfully Saved");
              console.log(response);
            },
            function(error){
              alert("Failed to save");
              console.error(response);

            });
          }

        }
        this.log = function(element){
          console.log(element.files);
          console.log('here');
        }
        $scope.$on('image_upload', function() {
          $scope.$apply(
            (
              function() {
                this.info.show_img = shared.getShowImg();
              }
            ).bind(this)
          );
        });


        this.init();
      });

      //FILE UPLOAD CONTROLLER
      app.controller('FileUploadCtrl',function($scope,$rootScope,shared){

        //============== DRAG & DROP =============
        // source for drag&drop: http://www.webappers.com/2011/09/28/drag-drop-file-upload-with-html5-javascript/
        var dropbox = document.getElementById("dropbox")
        $scope.dropText = 'Drop show image file here...'
        this.shared = shared;

        // init event handlers
        function dragEnterLeave(evt) {
          evt.stopPropagation()
          evt.preventDefault()
          $scope.$apply(function(){
            $scope.dropText = 'Drop show image file here...'
            $scope.dropClass = ''
          })
        }
        dropbox.addEventListener("dragenter", dragEnterLeave, false)
        dropbox.addEventListener("dragleave", dragEnterLeave, false)
        dropbox.addEventListener("dragover", function(evt) {
          evt.stopPropagation()
          evt.preventDefault()
          var clazz = 'not-available'
          var ok = evt.dataTransfer && evt.dataTransfer.types && evt.dataTransfer.types.indexOf('Files') >= 0
          $scope.$apply(function(){
            $scope.dropText = ok ? 'Drop show image file here...' : 'Only files are allowed!'
            $scope.dropClass = ok ? 'over' : 'not-available'
          })
        }, false)
        dropbox.addEventListener("drop", function(evt) {
          console.log('drop evt:', JSON.parse(JSON.stringify(evt.dataTransfer)))
          evt.stopPropagation()
          evt.preventDefault()
          $scope.$apply(function(){
            $scope.dropText = 'Drop show image file here...'
            $scope.dropClass = ''
          })
          var files = evt.dataTransfer.files
          if (files.length > 0) {
            $scope.$apply(function(){
              $scope.files = []
              for (var i = 0; i < files.length; i++) {
                $scope.files.push(files[i])
              }
            })
          }
        }, false)
        //============== DRAG & DROP =============

        $scope.setFiles = function(element) {
          $scope.$apply(function($scope) {
            console.log('files:', element.files);
            // Turn the FileList object into an Array
            $scope.files = []
            for (var i = 0; i < element.files.length; i++) {
              $scope.files.push(element.files[i])
            }
            $scope.progressVisible = false
          });
        };

        $scope.uploadFile = function() {
          if(shared.getShowName() == "" || shared.getShowName() == null){
            alert("Please set a show name first!");
          }else{
            var fd = new FormData()
            for (var i in $scope.files) {
              fd.append("showFile", $scope.files[i])
            }
            fd.append('show_name',$('#show_name').val());
            var xhr = new XMLHttpRequest()
            xhr.upload.addEventListener("progress", uploadProgress, false)
            xhr.addEventListener("load", uploadComplete, false)
            xhr.addEventListener("error", uploadFailed, false)
            xhr.addEventListener("abort", uploadCanceled, false)
            xhr.open("POST", "/form-handlers/shows/image_upload.php");
            $scope.progressVisible = true
            xhr.send(fd);
          }
        }

        function uploadProgress(evt) {
          $scope.$apply(function(){
            if (evt.lengthComputable) {
              $scope.progress = Math.round(evt.loaded * 100 / evt.total)
            } else {
              $scope.progress = 'unable to compute'
            }
          })
        }

        function uploadComplete(evt) {
          /* This event is raised when the server send back a response */
          var response_object = JSON.parse(evt.target.responseText);
          var status = evt.target.status;
          var response = response_object.response;
          var path = response_object.path;
          var web_path = response_object.web_path;
          var upload_ok = response_object.ok;
          $scope.files = new Array();
          alert(response);
          if(upload_ok){
            //$('#show_image').val(web_path);
            shared.setShowImg(web_path);
          }
        }

        function uploadFailed(evt) {
          alert("There was an error attempting to upload the file.")
        }

        function uploadCanceled(evt) {
          $scope.$apply(function(){
            $scope.progressVisible = false
          })
          alert("The upload has been canceled by the user or the browser dropped the connection.")
        }
      });

      app.factory('shared',function($rootScope){
        var service = {};
        service.show_img = "";
        service.show_name = "";
        service.setShowImg = function(image_url){
          this.show_img = image_url;
          $rootScope.$broadcast('image_upload');

        }
        service.getShowImg = function(){
          return this.show_img;
        }
        service.setShowName = function(name){
          this.show_name = name;
          $rootScope.$broadcast('show_name');
        }
        service.getShowName = function(){
          return this.show_name;
        }
        return service;
      });

    })();
