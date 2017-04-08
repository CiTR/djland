// JavaScript Document

$(document).ready(function() {

	dopeysecurityval = 'something';

	$('.lib-delete').click(function(){
		$(this).replaceWith(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'<a class=yesdelete>delete forever?</a>');

var id=$(this).attr('id');
		$('.yesdelete').click(function(){
			$thisguy = $(this);

			$.ajax({
				type: "POST",
				url: "./form-handlers/library_handler.php",
				data: { dopeysecurity: dopeysecurityval,action: "delete", id: id },
				beforeSend: function(){
					$(this).html('deleting...');
					}
				}).done( function( msg ) {
					console.log(msg);
					$oldCD = $thisguy.parent();
					$oldCD = $oldCD.parent();
					$oldCD.html(msg);
				}).always(function(){
					console.log('tried to delete');
				}).fail( function( ) {
					$thisguy.replaceWith('&nbsp;&nbsp;&nbsp; there was a problem - unable to delete ');
				});


		});

	});


$('#nukem').click(function(){


	});

});

function toggle(source) {
	checkboxes = document.getElementsByName('entry');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}

function editLine(source, id, artist, title, label, genre, catalog, modified, added, format, cancon, femcon, local, playlist, compilation, digitized) {
	var tr = source.parentNode;
	var table = tr.parentNode;

	if(tr.nextSibling.id != "editableLine") {
		var entryID = id;
		var artistID = "artist" + entryID;
		var titleID = "title" + entryID;
		var labelID = "label" + entryID;
		var genreID = "genre" + entryID;
		var catalogID = "catalog" + entryID;
		var formatID = "format" + entryID;
		var canconID = "cancon" + entryID;
		var femconID = "femcon" + entryID;
		var localID = "local" + entryID;
		var playlistID = "playlist" + entryID;
		var compilationID = "compilation" + entryID;
		var digitizedID = "digitized" + entryID;

		var newtr1 = document.createElement("tr");
		newtr1.id = "editableLine";
		newtr1.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Artist: <INPUT TYPE=text value='"+artist+"' id='"+artistID+"' size=29> Title: <INPUT TYPE=text value='"+title+"' id='"+titleID+"' size=35></td>";

		var newtr2 = document.createElement("tr");
		newtr2.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Label: <INPUT TYPE=text value='"+label+"' id='"+labelID+"' size=20> Genre: <INPUT TYPE=text value='"+genre+"' id='"+genreID+"' size=20> Catalog #: <INPUT TYPE=text value='"+catalog+"' id='"+catalogID+"' size=10></td>";

		var newtr3 = document.createElement("tr");
		newtr3.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Format: <INPUT TYPE=text value='"+format+"' id='"+formatID+"' size=7></td>";

		var newtr4 = document.createElement("tr");
		newtr4.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Can: <input type=checkbox id='"+canconID+"'> Fem: <input type=checkbox id='"+femconID+"'> Loc: <input type=checkbox id='"+localID+"'> PL: <input type=checkbox id='"+playlistID+"'> Comp: <input type=checkbox id='"+compilationID+"'> SAM: <input type=checkbox id='"+digitizedID+"'></td>";

		var newtr5 = document.createElement("tr");
		newtr5.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td><input type=submit VALUE='Save Changes' id='saveEntryButton' onClick='saveEntry()' name='"+entryID+"'> <input type=submit VALUE='Cancel' onClick='cancel(this)'></td>";

		table.insertBefore(newtr5, tr.nextSibling);
		table.insertBefore(newtr4, newtr5);
		table.insertBefore(newtr3, newtr4);
		table.insertBefore(newtr2, newtr3);
		table.insertBefore(newtr1, newtr2);

		if(cancon == 1)
			document.getElementById(canconID).checked = "true";

		if(femcon == 1)
			document.getElementById(femconID).checked = "true";

		if(local == 1)
			document.getElementById(localID).checked = "true";

		if(playlist == 1)
			document.getElementById(playlistID).checked = "true";

		if(compilation == 1)
			document.getElementById(compilationID).checked = "true";

		if(digitized == 1)
			document.getElementById(digitizedID).checked = "true";
	}
}

function cancel(source) {
	var td = source.parentNode;
	var tr = td.parentNode;
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.remove();
}
