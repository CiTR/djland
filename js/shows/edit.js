
(function(){
    var app = angular.module('djland.editShow',['djland.api','djland.utils']);
    app.directive('showtime',function(){
        return{
            restrict:'A',
            templateUrl: 'templates/showtime.html'
        };
    });
    app.filter('range', function($filter) {
        return function(input, min, max) {
            min = parseInt(min); //Make string input int
            max = parseInt(max);
            for (var i=min; i<max; i++)
                input.push($filter('pad')(i,2));
            return input;
        };
    });
    app.controller('editShow', function($scope,$rootScope, $filter, call, $location, shared){
        
        var this_ = this;
        this.init = function(){
            this.is_admin = false;
            var this_ = this;
            this.member_id = member_id;
            this.username = username;
            this.shared = shared;
            //Get List of all members
            this.getMemberList();
            //Get List of primary genres
            call.getConstants().then(function(response){
                this_.primary_genres = response.data.primary_genres;
            });
            //Get Shows Member can see
            call.getMemberShows(this.member_id).then(function(response){
                this_.member_shows = response.data.shows;
                //Get First show in member_shows
                for(var show in this_.member_shows){
                    this_.active_show = this_.member_shows[show];   
                }
                //Need to make the id a string
                this_.show_value = ""+this_.active_show.id;
                this_.loadShow();
            });
            //Calculating "current week" this math is really old. Returns 1 or 2
            this.current_week = Math.floor( ((Date.now()/1000 - 1341100800)*10 / (7*24*60*60))%2 +1);
            
            //Check if user is an administrator or staff
            this.isAdmin();
        }
        this.isAdmin = function(){
            var this_ = this;
            call.getMemberPermissions(this.member_id).then(function(response){
                if(response.data.administrator == '1' || response.data.staff == '1' ){
                    this_.is_admin = true;
                }else{
                    this_.is_admin = false;
                }
            },function(error){
                console.log(error.data);
            });
        }
        this.loadShow = function(){
            var this_ = this;
            call.getShow(this_.active_show.id).then(function(response){
                    this_.info = response.data;
                    this_.social = response.data.social;
                    delete this_.info.social;
                    this_.social_template = {show_id: this_.info.id, social_name: null , social_url:null};

            });
            call.getShowOwners(this_.active_show.id).then(function(response)
            {
                this_.show_owners = response.data;
            },function(error){

            });
            call.getShowTimes(this_.active_show.id).then(function(response){
                this_.show_times = response.data;
                this_.showtime_template = {show_id:this_.active_show.id,start_day:"0",end_day:"0",start_time:"00:00:00",end_time:"00:00:00",start_hour:"00",start_minute:"00",end_hour:"00",end_minute:"00",alternating:'0'};
                //Allowing show times to be displayed in UI
                for(var showtime in this_.show_times){
                    this_.show_times[showtime].start_hour = $filter('pad')(this_.show_times[showtime].start_time.split(':')[0],2);
                    this_.show_times[showtime].start_minute = $filter('pad')(this_.show_times[showtime].start_time.split(':')[1],2);
                    this_.show_times[showtime].end_hour = $filter('pad')(this_.show_times[showtime].start_time.split(':')[0],2);
                    this_.show_times[showtime].end_minute = $filter('pad')(this_.show_times[showtime].start_time.split(':')[1],2);
                }
                console.log(this_.show_times);
            },function(error){

            });

        }
        this.getMemberList = function(){
            var this_ = this;
            call.getMemberList().then(function(response){
                this_.member_list = response.data;
            });
        }
        this.changeShow = function(){
            var this_ = this;
            this.active_show=this.member_shows.filter(function(object){if(object.id == this_.show_value) return object;})[0];
            this.loadShow();
        }
        this.addFirstSocial = function(){
            //Add template row for social
            this.addSocial(0);
        }
        this.addSocial = function(id){
            var row = angular.copy(this.social_template);
            if(id < 1){
                this.social.push(row);
            }else{
                this.social.splice(id+1,0,row);
            }
        }
        this.removeSocial = function(id){
            this.social.splice(id,1);
        }
        this.addOwner = function(){
            //No need to check for duplicates, as there is only one id per member
            var id = $('#member_access_select').val();
            console.log(this.member_list);
            /*Find objects with id = selected id and return them. As id's are unique we take the first one we get then add it to show owners list
            Found at http://stackoverflow.com/questions/13964155/get-javascript-object-from-array-of-objects-by-value-or-property */    
            this.show_owners[id] = this.member_list.filter(function(object){if(object.id == id) return object;})[0];
        }
        this.removeOwner = function(id){
            //Is Object, not array. Must use delete instead of splice.
            delete this.show_owners[id];
        }
        this.addFirstShowTime = function(){
            this.show_times.push(this.showtime_template);
        }
        this.addShowTime = function($index){
            this.show_times.splice($index+1,0,angular.copy(this.showtime_template));
        }
        this.removeShowTime = function($index){
            this.show_times.splice($index,1);
        }

        this.updateShowtime = function(showtime){
            console.log(showtime);
            showtime.start_time = showtime.start_hour + ":" + showtime.start_minute + ":00";
            showtime.end_time = showtime.end_hour + ":" + showtime.end_minute + ":00";
            console.log(this.show_times);
        }
        this.save = function(){
            var this_ = this;         
            this.info.edit_name = this.username;
            this.info.edit_date = $filter('date')(new Date(),'yyyy-MM-dd HH:mm:ss');
            console.log(this);
            this.message = 'saving...';
            call.saveShow(this_.info,this_.social,this_.show_owners,this_.show_times).then(
                function(response){
//                    console.log(response.data.message);
                    alert("Successfully Saved");
                    console.log(response.data);
                },
                function(error){
                    alert("Failed to save");
                    console.error(response.data);
                    
                });
        }
        this.log = function(element){
            console.log(element.files);
            console.log('here');
        }

        $scope.$on('image_upload', function() {
           
            $scope.$apply(function() { 
                 this_.info.show_img = shared.getShowImg();
            });
        });
        this.init();
    });
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
            xhr.send(fd)
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
        service.setShowImg = function(image_url){
                this.show_img = image_url;
                $rootScope.$broadcast('image_upload');

        }
        service.getShowImg = function(){
                return this.show_img;
            }
        return service;
    });

})();