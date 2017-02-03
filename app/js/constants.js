window.myNameSpace = window.myNameSpace || { };
var faculties,training,interests,provinces,member_types,permission_levels,program_years,primary_genres,subgenres,constants_request;
$(document).ready ( function() {
	getConstants();
});
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
		subgenres = data['subgenres'];
	}).fail(function(data){
		console.log("failed to load constants");
	});
}
