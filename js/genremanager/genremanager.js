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
		$( "#addgenredialog" ).dialog( "open" );
    });
	$("#addsubgenre").click(function(e){
		$( "#addsubgenredialog" ).dialog( "open" );
	});
	$(".genrerow").click(function(e){
		activegenre = $(this).closest("tr").find("td:eq(0)").text();
		var string="Subgenres for the " + activegenre + " Genre"
		$("#subgenretitle").text(string);
		updateSubGenreListing(activegenre);
	});
	$(".genrerow").dblclick(function(e){
		var toedit = $(this).closest("tr").find("td:eq(0)").text();
		$("#genrebox").text(toedit);
		$( "#genredialog" ).dialog( "open" );
	});
	$(".subgenrerow").dblclick(function(e){
		var toedit = $(this).closest("tr").find("td:eq(0)").text();
		$("#subgenrebox").text(toedit);
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
$( function() {
   $( "#addgenredialog" ).dialog({
	 autoOpen: false,
	 buttons: {
        "Add": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
   });
});
$( function() {
   $( "#addsubgenredialog" ).dialog({
	 autoOpen: false,
	 buttons: {
        "Add": function() {
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

	var list = subgenres[genre];

	for(var subgenre in list){
		var tempstring = "<tr class=\"playitem border subgenrerow\">" +
		"<td class=\"submission_row_element name\">" + list[subgenre] +
		"</td><td class=\"submission_row_element email\">Digital Library</td>" +
		"<td class=\"submission_row_element primary_phone\">Andy</td>" +
		"<td class=\"submission_row_element submission_type\">Nov 14th, 2016</td>" +
		"<td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete_0\"><div class=\"check hidden\">❏</div></td></tr>"
		newstring = newstring + tempstring;
	}

	$("#subgenrelisting").html(newstring);

	//Add listeners on newly inserted rows
	$(".subgenrerow").dblclick(function(e){
		var toedit = $(this).closest("tr").find("td:eq(0)").text();
		$("#subgenrebox").text(toedit);
		$( "#subgenredialog" ).dialog( "open" );
	});
}
