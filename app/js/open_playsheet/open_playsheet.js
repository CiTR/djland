(function (){
	var app = angular.module('openPlaysheet',['djland.api']);

	app.controller('openPlaysheetController',function(call,$window,$scope){
		this.pageY = window.pageYOffset;
		this.member_id = $('#member_id').attr('value');
		console.log(this.member_id);
		this_=this;
		this.offset = 0;
		this.done = false;
		this.show_select = 'all';
		//Check Admin Status
		call.getMemberPermissions(this.member_id).then(function(response){
            if(response.data.administrator == '1' || response.data.staff == '1' ){
                this_.is_admin = true;
            }else{
                this_.is_admin = false;
            }
        },function(error){
            this_.log_error(error);
        });
		//Get member shows
		call.getActiveMemberShows(this.member_id).then(function(response){
			console.log(this_.show_select);
			this_.shows = response.data.shows;
			console.log(this_.shows);
			this_.more();
		});
        this.log_error = function(error){
            var this_ = this;

            var error = error.data.split('body>')[1].substring(0,error.data.split('body>')[1].length-2 );
            call.error( this_.error).then(function(response){
                $('#error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error has been logged');
            },function(error){
                $('#error').append('Please contact technical services at technicalservices@citr.ca or technicalmanager@citr.ca. Your error could not be logged :(');
            });
        }

		this.more = function(reload){
			if(reload == true){
				this_.offset = 0;
			}
			if(!this.done){
				this.loading = true;
				if(this.show_select == 'all'){
					call.getMemberPlaysheets(this_.member_id,this_.offset).then(function(playsheets){
						if(playsheets.data.length > 0){
							if(this_.offset == 0) this_.playsheets = playsheets.data;
							else{
								for(var playsheet in playsheets.data){
									this_.playsheets.push(playsheets.data[playsheet]);
								}
							}
							this_.offset += playsheets.data.length;
						}else{
							this_.done = true;
						}
						this_.loading = false;
					},function(error){
						console.log(error.statusText);
						this_.loading = false;
					});
				}else{
					call.getShowPlaysheets(this.show_select,this_.offset).then(function(playsheets){
						if(playsheets.data.length > 0){
							if(this_.offset == 0) this_.playsheets = playsheets.data;
							else{
								for(var playsheet in playsheets.data){
									this_.playsheets.push(playsheets.data[playsheet]);
								}
							}
							this_.offset += playsheets.data.length;
						}else{
							this_.done = true;
						}
						this_.loading = false;
					});
				}

			}
		};
		this.delete = function(id){
			var this_ = this;
			var i = this_.playsheets.indexOf(this_.playsheets.filter(function(object){if(object.id == id) return this_.playsheets.indexOf(object);})[0]);
			call.deletePlaysheet(this_.playsheets[i].id).then(function(response){
				this_.playsheets.splice(i,1);
			});
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
