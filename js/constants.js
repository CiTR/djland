window.myNameSpace = window.myNameSpace || { };
var faculties,training,interests,provinces,member_types,permission_levels,program_years;
$(document).ready ( function() {
	getConstants();
	function getConstants(){
		$.ajax({
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
		}).fail(function(data){
			console.log("failed to load constants");
		});
	}
});


