//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_genremanager_listener();
	});
});
function add_genremanager_listener(){
	var activegenre = Object.keys(subgenres)[0]; //default to first
	updateSubGenreListing(activegenre);

    $("#addgenre").click(function(e){
		var genre=prompt("Enter new genre:","Type here");
    });
	$("#addsubgenre").click(function(e){
		var genre=prompt("Enter new subgenre:","Type here");
	});
	$(".genrerow").click(function(e){
		console.log("Single Click");
		activegenre = $(this).closest("tr").find("td:eq(0)").text();
		var string="Subgenres for the " + activegenre + " Genre"
		$("#subgenretitle").text(string);
		updateSubGenreListing(activegenre);
	});
	$(".genrerow").dblclick(function(e){
		$( "#genredialog" ).dialog( "open" );
	});
	$(".subgenrerow").dblclick(function(e){
		$( "#subgenredialog" ).dialog( "open" );
	});


}

$( function() {
   $( "#genredialog" ).dialog({
	 autoOpen: false,
	 buttons: {
        "Apply": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
   });
});
$( function() {
   $( "#subgenredialog" ).dialog({
	 autoOpen: false,
	 buttons: {
        "Apply": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
   });
});

function updateSubGenreListing(genre){
	var newstring = "";
	console.log(genre);
	var list = subgenres[genre];
	console.log(list);	
	for(var subgenre in list){
		var tempstring = "<tr class=\"playitem border subgenrerow\">" +
		"<td class=\"submission_row_element name\">" + list[subgenre] +
		"</td><td class=\"submission_row_element email\">Digital Library</td>" +
		"<td class=\"submission_row_element primary_phone\">Andy</td>" +
		"<td class=\"submission_row_element submission_type\">Nov 14th, 2016</td>" +
		"<td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete_0\"><div class=\"check hidden\">❏</div></td></tr>"
		newstring = newstring + tempstring;
	}
	console.log(newstring);
	$("#subgenrelisting").html(newstring);
}
