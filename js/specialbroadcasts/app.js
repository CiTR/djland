(function (){
	var app = angular.module('djland.specialbroadcasts',['djland.api','ui.bootstrap','djland.utils']);

	app.controller('specialbroadcastController',function(call,$scope){
		this.list = Array();
		this.loading = true;
        var this_ = this;	
		this.init = function(){ 
			console.log('hello');
			this.loadBroadcasts();

		}
		this.loadBroadcasts = function(){
			this.loading = true;
			var this_ = this;
			call.getBroadcasts().then(function(response){
				this_.list = response.data;
				console.log(response.data);
				this_.loading = false;
			});

		}
		this.delete = function(index){
            var this_ = this;
            call.deleteBroadcast(this.list[index].id).then(function(response){
                console.log(response.data);
                this_.list.splice(index,1);
            });
		}
		this.add = function(){
            var this_ = this;
            call.addBroadcast().then(function(response){
                this_.list.push({'id':response.data.id});
                console.log(this_.list);
            });            
		}
        this.save = function(){
            call.saveBroadcasts(this.list).then(function(response){
                console.log(response);
                alert("Saved Successfully");
            });
        }
        this.updateStart = function(){

        }
        this.updateEnd = function(){
            
        }
        this.imageUpload = function(id,name){
            var this_ = this;            
            var input = $('.file'+id);
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            if($.inArray(input.val().split('.').pop().toLowerCase(), fileExtension) == -1){
                alert('Only formats allowed are:' + fileExtension.join(', '));
            }else{
                console.log(input.prop('files')[0]);
                var form = new FormData();
                form.append('broadcastFile',input.prop('files')[0]);
                form.append('broadcast_name',name);
                var ajax = $.ajax({
                    type:"POST",
                    processData: false,
                    contentType: false,
                    data: form,
                    url: "/form-handlers/specialbroadcasts/image_upload.php",
                    dataType: "json",
                    async: true,

                });
                $.when(ajax).then(function(response){
                    var broadcast = this_.list.filter(function(object){if(object.id == id) return object;})[0];
                    console.log(broadcast);
                    
                    $scope.$apply(function(){
                        broadcast.image_url = response.web_path+ "?" + new Date().getTime();
                    });
                    console.log(broadcast);
                });
            }
           
        }

        this.init();
	});

    


})();