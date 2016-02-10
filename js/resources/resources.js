(function (){
	var app = angular.module('djland.resources',['djland.api']);

	app.controller('resourceController',function($scope){
		var api_base = 'api2/public/resource';
		this.resources = Array();
		this.resources['general'] = Array();
		this.resources['training'] = Array();
		this.resources['programming'] = Array();
		this.load = function(){
			var this_ = this;
			var load_request = $.ajax({
		        type:"GET",
		        url: api_base,
		        dataType: "json",
		        async: true
		    });
			$.when(load_request).then(
				function(response){
					for(var r in response){
						this_.resources[response[r]['type']].push(response[r]);
					}
					$scope.$apply();
					console.log(this_.resources);
				},
				function(error){
					console.log("error");
				}
			);
		}

		this.add = function(){
			var this_ = this;
			var add_request = $.ajax({
				type:"PUT",
				url: api_base,
				dataType: "json",
				async: true
			});
			$.when(add_request).then(
				function(response){
					this_.resources.push(response.data);
				},
				function(error){

				}
			);
		}
		this.remove = function(index){
			var this_ = this;
			var remove_request = $.ajax({
				type:"REMOVE",
				url: api_base + '/' + resource.id,
				dataType: "json",
				async: true
			});
			$.when(add_request).then(
				function(response){
					this_.resources.splice(index,1);
				},
				function(error){

				}
			);
		}
		this.save = function(){
			var save_requests = Array();
			for(var resource in this.resources){
				var save_request = $.ajax({
					type:"POST",
					url: api_base + '/' + resource.id,
					dataType: "json",
					async: true
				});
				save_requests.push(save_request);
			}
		}

		this.load();

	});


})();
