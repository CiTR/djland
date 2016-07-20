(function (){
	var app = angular.module('openPlaysheet',['djland.api']);

	app.controller('openPlaysheetController',function(call,$window,$scope){
		this.pageY = window.pageYOffset;
		this.member_id = $('#member_id').attr('value');
		console.log(this.member_id);

		this.offset = 0;
		this.done = false;
		this.show_select = 'all';
		//Check Admin Status
		call.isStaff(this.member_id).then(
<<<<<<< HEAD
			(function(response){
				this.is_admin = response.data;
	        }).bind(this)
		,function(error){
            this_.log_error(error);
        });
=======
			(
				function(response){
					this.admin = response.data;
        		}
			).bind(this)
			,(
				function(error){
		            this.log_error(error);
		        }
			).bind(this)
		);
>>>>>>> 19d2cf8625dd8e5eb87437df1d0d3d0423105f44
		//Get member shows
		call.getActiveMemberShows(this.member_id).then(
			(
				function(response){
					this.shows = response.data.shows;
					this.more();
				}
			).bind(this)
		);
        this.log_error = function(error){

            var error = error.data.split('body>')[1].substring(0,error.data.split('body>')[1].length-2 );
            call.error( this.error).then(function(response){
                $('#error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error has been logged');
            },function(error){
                $('#error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error could not be logged :(');
            });
        }

		this.more = function(reload){
			if(reload == true){
				this.offset = 0;
			}
			if(!this.done){
				this.loading = true;
				if(this.show_select == 'all'){
					call.getMemberPlaysheets(this.member_id,this.offset).then(
						(
							function(playsheets){
								if(playsheets.data.length > 0){
									if(this.offset == 0) this.playsheets = playsheets.data;
									else{
										for(var playsheet in playsheets.data){
											this.playsheets.push(playsheets.data[playsheet]);
										}
									}
									this.offset += playsheets.data.length;
								}else{
									this.done = true;
								}
								this.loading = false;
							}
						).bind(this)
						,(
							function(error){
								this.loading = false;
							}
						).bind(this)
					);
				}else{
					call.getMoreShowPlaysheets(this.show_select,this.offset).then(
						(
							function(playsheets){
								if(playsheets.data.length > 0){
									if(this.offset == 0) this.playsheets = playsheets.data;
									else{
										for(var playsheet in playsheets.data){
											this.playsheets.push(playsheets.data[playsheet]);
										}
									}
									this.offset += playsheets.data.length;
								}else{
									this.done = true;
								}
								this.loading = false;
							}
						).bind(this)
					);
				}

			}
		};
		this.delete = function(id){
			var i = this.playsheets.indexOf(this.playsheets.filter(
				(
					function(object){
						if(object.id == id) return this.playsheets.indexOf(object);
					}
				).bind(this)
			)[0]);
			call.deletePlaysheet(this.playsheets[i].id).then(
				(
					function(response){
						this.playsheets.splice(i,1);
					}
				).bind(this)
			);
		}
	});
	app.directive('scrolly', function () {
	    return {
	        restrict: 'A',
	        link: function (scope, element, attrs) {
	            var raw = element[0];
	            element.bind('scroll', function () {
	                if (raw.scrollTop + raw.offsetHeight + raw.scrollHeight/5 >= raw.scrollHeight ) {
	                    scope.$apply(attrs.scrolly);
	                    console.log("Hit Bottom");
	                }
	            });
	        }
	    };
	});
})();

function go(element){
	href = element.getAttribute('data-href');
	window.document.location = href;
}
