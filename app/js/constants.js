window.myNameSpace = window.myNameSpace || { };
var faculties,training,interests,provinces,member_types,permission_levels,program_years,primary_genres,constants_request;
var max_podcast_length;
$(document).ready ( function() {
	getConstants();
	function getConstants(){
		constants_request = $.ajax({
			type:"GET",
			url: "headers/constants.php",
			data: {},
			dataType: "json",
			async: true
		}).success( function (data){
			faculties = data['faculties'];
			training = data['training'];
			interests = data['interests'];
			provinces = data['member_types'];
			member_types = data['member_types'];
			permission_levels = data['permission_levels'];
			program_years = data['program_years'];
			primary_genres = data['primary_genres'];
			max_podcast_length = data['max_podcast_length'];
		}).fail(function(data){
			console.log("Failed to load constants");
		});
	}
});
