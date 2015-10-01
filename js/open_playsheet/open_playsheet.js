(function (){
	var app = angular.module('openPlaysheet',['djland.api']);
	
	app.controller('openPlaysheetController',function(call,$window,$scope){
		this.loading = true;

		this.pageY = window.pageYOffset;
		this.member_id = $('#member_id').attr('value');
		console.log(this.member_id);
		this_=this;
		this.offset = 0;

		this.more = function(){
			call.getMemberPlaysheets(this_.member_id,this.offset).then(function(playsheets){
				this_.loading = false;
				if(this_.offset == 0) this_.playsheets = playsheets.data;
				else{
					for(var playsheet in playsheets.data){
						this_.playsheets.push(playsheets.data[playsheet]);
					}
				}
				console.log(this_.playsheets);
				this_.offset += playsheets.data.length;
			});
		};
		this.more();		
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
	                if (raw.scrollTop + raw.offsetHeight >= raw.scrollHeight - 10) {
	                    scope.$apply(attrs.scrolly);
	                    raw.scrollTop = (raw.scrollTop+raw.offsetHeight);
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


