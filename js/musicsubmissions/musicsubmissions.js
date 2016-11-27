//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_popup();
	});
});
function add_popup(){
	//Listener for viewing individual members from clicking on their row
    $("#tagrow1").click(function(e){
		$('#submissionspopup').show();
    });
	$("#tagrow2").click(function(e){
		$('#submissionspopup').show();
    });
	$("#tagrow3").click(function(e){
		$('#submissionspopup').show();
    });
	$("#tagrow4").click(function(e){
		$('#submissionspopup').show();
    });
	$("#tagrow5").click(function(e){
		$('#submissionspopup').show();
    });
	$("#tagrow6").click(function(e){
		$('#submissionspopup').show();
    });
	$("#submissionscloser").click(function(e){
		$('#submissionspopup').hide();
    });
	$("#tagcancel").click(function(e){
		$('#submissionspopup').hide();
    });
}
