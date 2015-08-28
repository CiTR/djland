
(function(){
    var app = angular.module('djland.editShow',['djland.api']);
    
    app.controller('editShow', function($scope, call, $location){
        
        
        this.init = function(){
            this.is_admin = false;
            var this_ = this;
             this.member_id = member_id;
            call.getConstants().then(function(response){
                this_.primary_genres = response.data.primary_genres;
            });

            call.getMemberShows(this.member_id).then(function(response){
                var shows_list = response.data.shows;

                call.getShow(369).then(function(response){
                    for(var prop in response.data){
                        this_[prop] = response.data[prop];
                    }
                    console.log(this_);
                    this_.social_template = {show_id: this_.id, social_name: null , social_url:null, short_name: null, unlink: 0};
                    //this_.show = response.data;
                });
            });
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
            this.message = 'saving...';
            apiService.saveShowData($scope.formData)
                .then(function(response){
                    $scope.message = response.data.message;
                }).catch(function(response){
                    console.error(response.data);
                    $scope.message = 'sorry, saving did not work';
                });
        }
        this.log = function(element){
            console.log(element.files);
            console.log('here');

        }
        this.init();
    });
    app.controller('FileUploadCtrl',function($scope){

        //============== DRAG & DROP =============
        // source for drag&drop: http://www.webappers.com/2011/09/28/drag-drop-file-upload-with-html5-javascript/
        var dropbox = document.getElementById("dropbox")
        $scope.dropText = 'Drop files here...'

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
                $scope.dropText = ok ? 'Drop files here...' : 'Only files are allowed!'
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
            var response = response_object.response;
            var path = response_object.path;
            var web_path = response_object.web_path;
            var upload_ok = response_object.ok;
            console.log(upload_ok);
            alert(response);
            if(upload_ok){
                $('#show_image').val(web_path);
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

})();