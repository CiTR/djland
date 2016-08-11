(function (){
	var app = angular.module('openPlaysheet',['djland.api']);

	app.controller('openPlaysheetController',function(call,$window,$scope){
		this.pageY = window.pageYOffset;
		this.member_id = $('#member_id').attr('value');
		this.loading;
		this.offset = 0;
		this.done = false;
		this.show_select = 'all';
		//Check Admin Status
		call.isStaff(this.member_id).then(
			(function(response){
				this.is_admin = response.data;
	        }).bind(this)
			,(function(error){
            this.log_error(error);
        	}).bind(this)
    	);
		//Get member shows
		call.getActiveMemberShows(this.member_id).then(
			(function(response){
				this.shows = response.data.shows;
				this.more();
			}).bind(this)
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

			if(!this.done && !this.loading){
				this.loading = true;
				if(this.show_select == 'all'){
					call.getMemberPlaysheets(this.member_id,this.offset).then(

						(function(playsheets){
							console.log('getting all playsheets');
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
						}).bind(this)
						,function(error){
							console.log(error.statusText);
							this.loading = false;
						}
					);
				}else{
					call.getShowPlaysheets(this.show_select,this.offset).then(
						(function(playsheets){
							console.log('getting show playsheets');
							if(playsheets.data.length > 0){
								if(this_.offset == 0) this.playsheets = playsheets.data;
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
						}).bind(this)
					);
				}

			}
		};
		this.delete = function(id){
			var i = this.playsheets.indexOf(this.playsheets.filter((function(object){if(object.id == id) return this.playsheets.indexOf(object);}).bind(this))[0]);
			call.deletePlaysheet(this.playsheets[i].id).then(
				(function(response){
					this.playsheets.splice(i,1);
				}).bind(this)
			);
		}

	});
	app.directive('scrolly', function () {
	    return {
	        restrict: 'A',
	        link: function (scope, element, attrs) {
	            var raw = element[0];
	            console.log('loading directive');
	            element.bind('scroll', function () {
	                console.log('in scroll');
	                console.log(raw.scrollTop + raw.offsetHeight);
	                console.log(raw.scrollHeight);
	                if (raw.scrollTop + raw.offsetHeight + raw.scrollHeight/5 >= raw.scrollHeight ) {
	                    scope.$apply(attrs.scrolly);
	                    //raw.scrollTop = (raw.scrollTop+raw.offsetHeight);
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
