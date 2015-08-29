
(function(){
    var app = angular.module('djland.editShow',['djland.api']);
    
    app.controller('editShow', function($scope,$rootScope, $filter, call, $location, shared){
        
        var this_ = this;
        this.init = function(){
            this.is_admin = false;
            var this_ = this;
            this.member_id = member_id;
            this.username = username;
            this.shared = shared;
            call.getConstants().then(function(response){
                this_.primary_genres = response.data.primary_genres;
            });

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
        }
        this.loadShow = function(){
            var this_ = this;
            call.getShow(this_.active_show.id).then(function(response){
                    this_.info = response.data;
                    this_.social = response.data.social;
                    delete this_.info.social;
                    
                    this_.social_template = {show_id: this_.info.id, social_name: null , social_url:null};
                console.log(this_);
            });

        }
        this.changeShow = function(){
            this.active_show=this.member_shows[this.show_value];
            this.loadShow();
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
        this.addFirst = function(){
            this.addSocial(0);
        }
        this.save = function(){
            var this_ = this;
            this.info.edit_name = this.username;
            this.info.edit_date = $filter('date')(new Date(),'yyyy-MM-dd HH:mm:ss');
            console.log(this);
            this.message = 'saving...';
            call.saveShow(this_.info,this_.social).then(
                function(response){
//                    console.log(response.data.message);
                    console.log(response.data);
                },
                function(error){
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