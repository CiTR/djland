//Created by Scott Pidzarko
window.myNameSpace = window.myNameSpace || { };

var genres, subgenres;
var activegenreid = 1;

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		//This function triggers getting subgenres and populating both tables
		initialGet();
		add_genremanager_listeners();
	});
});
function add_genremanager_listeners(){
    $("#addgenre").off('click').on('click', function(e){
		$("#addgenredialog").dialog("open");
    });
	$("#addsubgenre").off('click').on('click', function(e){
		$("#addsubgenredialog").dialog("open");
	});
	$(".genrerow").off('click').on('click', function(e){
		var activegenre = $(this).closest("tr").find("td:eq(0)").text();
		var string="Subgenres for the " + activegenre + " Genre"
		$("#subgenretitle").text(string);
		activegenreid = $(this).attr('name');
		activegenreid = activegenreid.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
		getSubGenres(activegenreid);
	});
	$(".genrerow").off('dblclick').on('dblclick', function(e){
		var toedit = $(this).closest("tr").find("td:eq(0)").text();
		$("#genrebox").text(toedit);
		activegenreid = $(this).attr('name');
		activegenreid = activegenreid.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
		$("#genredialog").dialog("open");
		getSubGenres(activegenreid);
	});
	$(".subgenrerow").off('dblclick').on('dblclick', function(e){
		var toedit = $(this).closest("tr").find("td:eq(0)").text();
		$("#subgenrebox").text(toedit);
		$("#subgenredialog").dialog( "open" );
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
		modal: true,
		buttons: {
        	"Add": function() {
				addGenre($('#addgenrebox').val(),this);
        	},
        	Cancel: function() {
          		$(this).dialog("close");
        	}
      	}
   	});
});
$( function() {
   	$( "#addsubgenredialog" ).dialog({
	 	autoOpen: false,
		modal: true,
	 	buttons: {
        	"Add": function() {
				addSubgenre($('#addsubgenrebox').val(),activegenreid,this);
        	},
        	Cancel: function() {
          		$(this).dialog("close");
        	}
      	}
   	});
});

//Takes in a genre listing an re-prints the genre table accordingly
function updateGenreListing(genres){
	var newstring = "";
	for(var item in genres){
		var genre = genres[item];
		var tempstring = "<tr name=\"genre" + genre['id'] + "\" class=\"playitem border genrerow\">" +
		"<td class=\"submission_row_element\">" + genre['genre'] + "</td>" +
		"<td class=\"submission_row_element\">" + genre['default_crtc_category'] + "</td>" +
		"<td class=\"submission_row_element\">" + genre['created_by'] + "</td>" +
		"<td class=\"submission_row_element\">" + genre['updated_by'] + "</td>" +
		"<td class=\"submission_row_element\">" + genre['updated_at'] + "</td>" +
		"<td><input type=\"checkbox\" class=\"delete_genre\" id=\"delete_" + genre['id'] + "\"><div class=\"check hidden\">❏</div></td>" +
		"</tr>";
		newstring = newstring + tempstring;
	}
	$("#genrelisting").html(newstring);

	add_genremanager_listeners();
}

//Takes in a subgenre listing an re-prints the subgenre table
function updateSubGenreListing(subgenres){
	var newstring = "";

	for(var item in subgenres){
		var subgenre = subgenres[item];
		var tempstring = "<tr name =\"subgenre" + subgenre['id'] + "\" class=\"playitem border subgenrerow\">" +
		"<td class=\"submission_row_element name\">" + subgenre['subgenre'] +
		"</td><td class=\"submission_row_element\">" + subgenre['created_by'] + "</td>" +
		"<td class=\"submission_row_element\">" + subgenre['updated_by'] + "</td>" +
		"<td class=\"submission_row_element\">" + subgenre['updated_at'] + "</td>" +
		"<td><input type=\"checkbox\" class=\"delete_subgere\" id=\"delete_" + subgenre['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>"
		newstring = newstring + tempstring;
	}

	$("#subgenrelisting").html(newstring);

	add_genremanager_listeners();
}

function initialGet(){
	$.ajax({
		type:"GET",
		url: "api2/public/genres",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			updateGenreListing(data);
			activegenreid=(data['0']['id']);
			getSubGenres(data['0']['id']);
		}
	});
}
function getGenres(){
	$.ajax({
		type:"GET",
		url: "api2/public/genres",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			updateGenreListing(data);
		}
	});
}
function getSubGenres(genreid){
	$.ajax({
		type:"GET",
		url: "api2/public/genres/subgenres/" + genreid,
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			updateSubGenreListing(data);
		}
	});
}
function addGenre(genre,dialogbox){
	//console.log(genre);
	$.ajax({
		type:"POST",
		url: "api2/public/genres",
		data:{genre: genre},
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			$(dialogbox).dialog("close");
			getGenres();
		}
	});
}
function addSubgenre(subgenre,parent_genre_id,dialogbox){
	console.log(subgenre,parent_genre_id);
	$.ajax({
		type:"POST",
		url: "api2/public/subgenres",
		data:{subgenre: subgenre,parent_genre_id:parent_genre_id},
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			$(dialogbox).dialog("close");
			getSubGenres(parent_genre_id);
		}
	});
}
function updateGenre(id,newgenre,dialogbox){
	$.ajax({
		type:"PUT",
		url: "api2/public/genres",
		data:{id: id,genre: newgenre},
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			$(dialogbox).dialog("close");
			getGenres();
		}
	});
}
function updateSubgenre(id,newsubgenre,parent_genre_id,dialogbox){
	$.ajax({
		type:"PUT",
		url: "api2/public/subgenres",
		data:{id: id,subgenre: newsubgenre,parent_genre_id:parent_genre_id},
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			$(dialogbox).dialog("close");
			getSubGenres(parent_genre_id);
		}
	});
}
