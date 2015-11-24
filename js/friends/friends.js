(function (){
	var app = angular.module('djland.friends',['djland.api']);

	app.controller('friendsController',function(call,shared,$scope){
		this.list = [{'id':0,'name':'test','address':'124 E 5'},{'id':0,'name':'test','address':'124 E 5'}];
		this.loading = true;	
		this.init = function(){ 
			console.log('hello');
			this.loadFriends();

		}
		this.loadFriends = function(){
			this.loading = true;
			var this_ = this;
			$.when(call.getFriends()).then(function(response){
				this_.list = response;
				console.log(response);
				this_.loading = false;
			});

		}
		this.delete = function(index){
			this.list.splice(index,1);
		}
		this.add = function(){
			this.list.push({});
		}
		$scope.$on('image_upload', function() {
            $scope.$apply(function() { 
                 this_.img = shared.getShowImg();
            });
        });

        this.init();
	});

	//FILE UPLOAD CONTROLLER
    app.controller('FileUploadCtrl',function($scope,$rootScope,shared){

        //============== DRAG & DROP =============
        // source for drag&drop: http://www.webappers.com/2011/09/28/drag-drop-file-upload-with-html5-javascript/
        var dropbox = document.getElementById("dropbox")
        $scope.initialDropText = 'Drop image file here...'
        $scope.dropText = $scope.initialDropText;
        this.shared = shared;

        // init event handlers
        function dragEnterLeave(evt) {
            evt.stopPropagation()
            evt.preventDefault()
            $scope.$apply(function(){
                $scope.dropText = $scope.initialDropText;
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
                $scope.dropText = ok ? $scope.initialDropText : 'Only files are allowed!'
                $scope.dropClass = ok ? 'over' : 'not-available'
            })
        }, false)
        dropbox.addEventListener("drop", function(evt) {
            console.log('drop evt:', JSON.parse(JSON.stringify(evt.dataTransfer)))
            evt.stopPropagation()
            evt.preventDefault()
            $scope.$apply(function(){
                $scope.dropText = $scope.initialDropText;
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
        service.id = -1;
        service.img = "";
        service.setImg = function(id,image_url){
            this.img = image_url;
            this.id = id;
            $rootScope.$broadcast('image_upload');
        }
        service.getImg = function(id){
            return this.img;
        }
        service.getId = function(){
        	return this.id;
        }
        return service;
    });

})();