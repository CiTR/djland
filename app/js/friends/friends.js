(function (){
	var app = angular.module('djland.friends',['djland.api']);

	app.controller('friendsController',function(call,$scope){
		this.list = [{'id':0,'name':'test','address':'124 E 5'},{'id':0,'name':'test','address':'124 E 5'}];
		this.loading = true;
		this.init = function(){
			console.log('hello');
			this.loadFriends();
		}
		this.loadFriends = function(){
			this.loading = true;
			call.getFriends().then(
				(
					function(response){
						this.list = response.data;
						this.loading = false;
					}
				).bind(this)
			);
		}
		this.delete = function(index){
            call.deleteFriend(this.list[index].id).then(
				(
					function(response){
		                this.list.splice(index,1);
		            }
				).bind(this)
			);
		}
		this.add = function(){
            call.addFriend().then(
				(
					function(response){
		                this.list.push({'id':response.data.id});
            		}
				).bind(this)
			);
		}
        this.save = function(){
            call.saveFriends(this.list).then(function(response){
                alert("Saved Successfully");
            });
        }
        this.imageUpload = function(id,name){
            var input = $('.file'+id);
            var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
            if($.inArray(input.val().split('.').pop().toLowerCase(), fileExtension) == -1){
                alert('Only formats allowed are:' + fileExtension.join(', '));
            }else{
                console.log(input.prop('files')[0]);
                var form = new FormData();
                form.append('friendFile',input.prop('files')[0]);
                form.append('friend_name',name);
                var ajax = $.ajax({
                    type:"POST",
                    processData: false,
                    contentType: false,
                    data: form,
                    url: "/form-handlers/friends/image_upload.php",
                    dataType: "json",
                    async: true,
                });
                $.when(ajax).then(
					(
						function(response){
		                    var friend = this.list.filter(function(object){if(object.id == id) return object;})[0];
		                    $scope.$apply(function(){
		                        friend.image_url = response.web_path+ "?" + new Date().getTime();
		                    });
		                }
					).bind(this)
				);
            }
        }
        this.init();
	});
})();
