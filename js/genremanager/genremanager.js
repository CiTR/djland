//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		console.log("Hello");
		add_genremanager_listener();
		console.log("Hi");
	});
});
function add_genremanager_listener(){
	//Listener for viewing individual members from clicking on their row
    $("#addgenre").click(function(e){
		console.log("Handler fired");
		var genre=prompt("Enter new genre:","Type here");
    });
	$("#addsubgenre").click(function(e){
		console.log("Handler fired");
		var genre=prompt("Enter new subgenre:","Type here");
	});
}
