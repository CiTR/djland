/*
 * Get checked entries
 */
function getCheckedEntries(chkboxName) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkedIDs = [];

  for (var i=0; i<checkboxes.length; i++) {
     if (checkboxes[i].checked) {
			 var id = checkboxes[i].id.replace(/\D/g,'');
       checkedIDs.push(id);
     }
	 }
  // Return the array if it is non-empty, or null
  return checkedIDs.length > 0 ? checkedIDs : null;
}

// Handlers
function viewEdits() {
  $("tbody[name='recentEdits']").empty();
  var header = "<tr id=\"headerrow\" style=\"display: table-row;\"><th>Artist</th><th>Album</th><th>Label</th><th>Genre</th><th>Catalog</th><th>Format</th><th>Cancon</th><th>Femcon</th><th>Playlist</th><th>Local</th><th>Digitized</th></tr>";
  $("tbody[name='recentEdits']").append(header);
  $("#undoEdits").show();

  // call function to fill out table
  populateLibraryEditsTable();
}

function undoEdits() {
  // get checked entries

  // get their id in the library database

  // write old info back to library

  // write new entry to library edits table
}

function saveEntry() {

  // read in new values
  var entryID = $("#saveEntryButton").attr('name');
  var title   = $("#title" + entryID).val();
  var artist  = $("#artist" + entryID).val();
  var label   = $("#label" + entryID).val();
  var genre   = $("#genre" + entryID).val();
  var catalog = $("#catalog" + entryID).val();
  var format_id  = $("#format" + entryID).val();
  var cancon  = $("#cancon" + entryID).val();
  var femcon  = $("#femcon" + entryID).val();
  var playlist = $("#playlist" + entryID).val();
  var local   = $("#local" + entryID).val();
  var compilation = $("#compilation" + entryID).val();
  var digitized = $("#digitized" + entryID).val();

  // call function to write changes to database
  update_entry(entryID, title, artist, label, genre, catalog, format_id, cancon, femcon, playlist, local, compilation, digitized);

  // refresh search page
}

function saveChanges() {
  // get checked entries
  var checkedIDs = getCheckedEntries("entry");

  // get values updated in boxes
  var title   = $("#astitle").val();
  var artist  = $("#asartist").val();
  var label   = $("#aslabel").val();
  var genre   = $("#asgenre").val();
  var catalog = $("#ascatalog").val();
  var format_id  = $("#asformat").val();
  var cancon  = $("#ascancon").val();
  var femcon  = $("#asfemcon").val();
  var playlist = $("#asplaylist").val();
  var local   = $("#aslocal").val();
  var compilation = $("#ascompilation").val();
  var digitized = $("#asdigitized").val();

  // call update function for each entry
  for(var number in checkedIDs){
    var id = checkedIDs[number];
    update_entry(id, title, artist, label, genre, catalog, format_id, cancon, femcon, playlist, local, compilation, digitized);
  }

  // refresh search page
}

function update_entry(entryID, title, artist, label, genre, catalog, format_id, cancon, femcon, playlist, local, compilation, digitized){
  // write new entry to library edits database
  $.ajax({
    url: "api2/public/libraryedits",
    type:'POST',
    dataType:'json',
    data: {
      'libraryID':entryID,
      'title':title,
      'artist':artist,
      'label':label,
      'genre':genre,
      'catalog':catalog,
      'format_id':format_id,
      'cancon':cancon,
      'femcon':femcon,
      'playlist':playlist,
      'local':local,
      'compilation':compilation,
      'digitized':digitized
    },
    async:true,
    success:function(data){
      console.log(data);

      // update entry in library database
      $.ajax({
        url: "api2/public/updateentry",
        type:'PUT',
        dataType:'json',
        data: {
          'libraryID':entryID,
          'title':title,
          'artist':artist,
          'label':label,
          'genre':genre,
          'catalog':catalog,
          'format_id':format_id,
          'cancon':cancon,
          'femcon':femcon,
          'playlist':playlist,
          'local':local,
          'compilation':compilation,
          'digitized':digitized
        },
        async:true,
        success:function(data){
          console.log(data);
        },
        fail:function(data){
          console.log("Updating library entry failed. Response data: " + data);
          alert("Error: Library entry was not updated");
        }
      });
    },
    fail:function(data){
      console.log("Writing to library edits database failed. Response data: " + data);
      alert("Error: Library edit was not completed");
    }
  });
}

function populateLibraryEditsTable(){
    $(".editsrow").remove();
    $(".editsrowNotFound").remove();
	$.ajax({
		type:"GET",
		url: "api2/public/recentedits",
		dataType:'json',
		async:true,
		success:function(data){
			populateLibraryEdits(data);
		}
	});
}

  function populateLibraryEdits(edits){
  	if(edits[0] == null){
  		var markup = "<tr class=\"playitem border editsrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
  		$("tbody[name='recentEdits']").append(markup);
  	} else{
  		for(var number in edits) {
  			var item = (edits[number]);
  			var markup = "<tr class=\"playitem border editsrow\" name=\"" + item['id'] + "\"><td class=\"edits_row_element\"> " + item['artist'] + " </td><td class=\"edits_row_element\">" + item['title'] + "</td><td class=\"edits_row_element\">" + item['label'] + "</td><td class=\"edits_row_element\">" + item['genre'] + "</td><td class=\"edits_row_element\">"
  				+ item['catalog'] + "</td><td><input class=\"edits_row_element\"> " + item['format_id'] + " </td><td><input class=\"edits_row_element\"> " + item['cancon'] + " </td><td><input class=\"edits_row_element\"> " + item['femcon'] + " </td><td><input class=\"edits_row_element\"> " + item['playlist'] + " </td><td><input class=\"edits_row_element\"> "
          + item['local'] + " </td><td><input class=\"edits_row_element\"> " + item['compilation'] + " </td><td><input class=\"edits_row_element\"> " + item['digitized'] + " </td><td><input type=\"checkbox\" class=\"undo_edits\" id=\"undo" + item['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>";
  			$("tbody[name='recentEdits']").append(markup);
  		}
  	}
  }
