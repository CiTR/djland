(function (){
	var app = angular.module('djland.specialbroadcasts',['djland.api','ui.bootstrap','djland.utils']);

	app.controller('specialbroadcastController',function(call,$scope,$filter){
		this.list = Array();
        this.times = Array();
        this.shows = Array();
		this.loading = true;
        var this_ = this;	
		this.init = function(){ 
			this.loadBroadcasts();
		}
		this.loadBroadcasts = function(){
			this.loading = true;
			var this_ = this;
            call.getActiveShows().then(function(response){
                var l = response.data.length;
                for(var i = 0; i < l; i ++){
                    this_.shows[i] = response.data[i];
                }
                this_.shows = response.data;
            });
			call.getBroadcasts().then(function(response){
                this_.list = response.data;
                var l = this_.list.length;
				for(var i = 0; i < l; i ++){
                    this_.initTime(i);
                }
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
                this_.initTime(this_.list.push({'id':response.data.id,'start':new Date() / 1000,'end':new Date() / 1000}) - 1);
            });            
		}
        this.save = function(){
            call.saveBroadcasts(this.list).then(function(response){
                console.log(response);
                alert("Saved Successfully");
            });
        }
        this.initTime = function(index){
            var time = {};
            time.start_time = new Date(this_.list[index].start * 1000);
            time.end_time = new Date(this_.list[index].end * 1000);
            time.start_hour =  $filter('pad')(time.start_time.getHours(),2);
            time.start_minute = $filter('pad')(time.start_time.getMinutes(),2);
            time.start_second = $filter('pad')(time.start_time.getSeconds(),2);
            time.end_hour =  $filter('pad')(time.end_time.getHours(),2);
            time.end_minute = $filter('pad')(time.end_time.getMinutes(),2);
            time.end_second = $filter('pad')(time.end_time.getSeconds(),2);
            this_.list[index].time = time;
        }
        this.updateStart = function(index){
            this_.list[index].time.start_time.setHours(this_.list[index].time.start_hour);
            this_.list[index].time.start_time.setMinutes(this_.list[index].time.start_minute);
            this_.list[index].time.start_time.setSeconds(this_.list[index].time.start_second);
            this.list[index].start = this_.list[index].time.start_time / 1000;
        }
        this.updateEnd = function(index){
            this_.list[index].time.end_time.setHours(this_.list[index].time.end_hour);
            this_.list[index].time.end_time.setMinutes(this_.list[index].time.end_minute);
            this_.list[index].time.end_time.setSeconds(this_.list[index].time.end_second);
            this.list[index].end = this_.list[index].time.end_time / 1000;

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
                form.append('specialbroadcastFile',input.prop('files')[0]);
                form.append('specialbroadcast_name',name);
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
                    $scope.$apply(function(){
                        broadcast.image = response.web_path+ "?" + new Date().getTime();
                    });
                });
            }
           
        }

        this.init();
	});

    


})();