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
  var header = "<tr id=\"headerrow\" style=\"display: table-row;\"><th>Artist</th><th>Album</th><th>Changes</th><th>Undo?</th></tr>";
  $("tbody[name='recentEdits']").append(header);
  $("#undoEdits").show();

  // call function to fill out table
  populateLibraryEditsTable();
}

function undoEdits(source) {
  var id = source.name;
  $.ajax({
    type:"GET",
    url: "api2/public/recenteditentry",
    dataType:'json',
    data: {
      'id':id
    },
    async:true,
    success:function(data){
      // write back to update library with "old" values
      update_entry(data['library_id'], data['old_title'], data['old_artist'], data['old_label'], data['old_genre'], data['old_catalog'], data['old_format_id'], data['old_status'], data['old_cancon'], data['old_femcon'], data['old_playlist'], data['old_local'], data['old_compilation'], data['old_digitized']);
    }
  });
}

function saveEntry(source) {

  // read in new values
  var entryID = source.name;
  var title   = $("#title" + entryID).val();
  var artist  = $("#artist" + entryID).val();
  var label   = $("#label" + entryID).val();
  var genreDropdown = document.getElementById("genre" + entryID);
  var genre;
  if(genreDropdown.selectedIndex == 0) {
    genre = null;
  } else {
    genre = genreDropdown.options[genreDropdown.selectedIndex].text;
  }
  var catalog = $("#catalog" + entryID).val();
  var format_id  = $("#format" + entryID).val();
  if(format_id == 0) format_id = null;
  var status  = $("#status" + entryID).val();
  var cancon  = ($("#cancon" + entryID).is(":checked") == true) ? 1 : 0;
  var femcon  = ($("#femcon" + entryID).is(":checked") == true) ? 1 : 0;
  var playlist = ($("#playlist" + entryID).is(":checked") == true) ? 1 : 0;
  var local   = ($("#local" + entryID).is(":checked") == true) ? 1 : 0;
  var compilation = ($("#compilation" + entryID).is(":checked") == true) ? 1 : 0;
  var digitized = ($("#digitized" + entryID).is(":checked") == true) ? 1 : 0;

  // call function to write changes to database
  update_entry(entryID, title, artist, label, genre, catalog, format_id, status, cancon, femcon, playlist, local, compilation, digitized);
}

function saveChanges() {
  // get checked entries
  var checkedIDs = getCheckedEntries("entry");

  // get values updated in boxes
  var title       = $("#astitle").val();
  var artist      = $("#asartist").val();
  var label       = $("#aslabel").val();
  var genre       = $("#asgenre").val();
  var catalog     = $("#ascatalog").val();
  var format_id   = $("#asformat").val();
  var status      = $("#asstatus").val();
  var cancon      = $("#ascancon").prop('checked') ? 1 : 0;
  var femcon      = $("#asfemcon").prop('checked') ? 1 : 0;
  var playlist    = $("#asplaylist").prop('checked') ? 1 : 0;
  var local       = $("#aslocal").prop('checked') ? 1 : 0;
  var compilation = $("#ascompilation").prop('checked') ? 1 : 0;
  var digitized   = $("#asdigitized").prop('checked') ? 1 : 0;

  if(format_id == 0) format_id = null;

  // call update function for each entry
  for(var number in checkedIDs){
    var id = checkedIDs[number];
    update_entry(id, title, artist, label, genre, catalog, format_id, status, cancon, femcon, playlist, local, compilation, digitized);
  }
}

function update_entry(entryID, title, artist, label, genre, catalog, format_id, status, cancon, femcon, playlist, local, compilation, digitized){
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
      'status':status,
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
          'status':status,
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
          window.location.reload(true);
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
        var changes = "";
        if(item['artist']!= item['old_artist']) {
            changes += "| " + item['old_artist'] + " -> " + item['artist'] + " | ";
        }
        if(item['title']!= item['old_title']) {
            changes += "| " + item['old_title'] + " -> " + item['title'] + " | ";
        }
        if(item['label']!= item['old_label']) {
            changes += "| " + item['old_label'] + " -> " + item['label'] + " | ";
        }
        if(item['genre']!= item['old_genre']) {
            changes += "| " + item['old_genre'] + " -> " + item['genre'] + " | ";
        }
        if(item['catalog']!= item['old_catalog']) {
            changes += "| " + item['old_catalog'] + " -> " + item['catalog'] + " | ";
        }
        if(item['format_id']!= item['old_format_id']) {
            changes += "| format " + item['old_format_id'] + " -> format " + item['format_id'] + " | ";
        }
        if(item['status']!= item['old_status']) {
            changes += "| " + item['old_status'] + " -> " + item['status'] + " | ";
        }
        if(item['cancon']!= item['old_cancon']) {
          if(item['cancon'] == 1) {
            changes += "| not cancon -> cancon | ";
          } else {
            changes += "| cancon -> not cancon | ";
          }
        }
        if(item['femcon']!= item['old_femcon']) {
          if(item['femcon'] == 1) {
            changes += "| not femcon -> femcon | ";
          } else {
            changes += "| femcon -> not femcon | ";
          }
        }
        if(item['playlist']!= item['old_playlist']) {
          if(item['playlist'] == 1) {
            changes += "| not playlist -> playlist | ";
          } else {
            changes += "| playlist -> not playlist | ";
          }
        }
        if(item['local']!= item['old_local']) {
          if(item['local'] == 1) {
            changes += "| not local -> local | ";
          } else {
            changes += "| local -> not local | ";
          }
        }
        if(item['compilation']!= item['old_compilation']) {
          if(item['compilation'] == 1) {
            changes += "| not compilation -> compilation | ";
          } else {
            changes += "| compilation -> not compilation | ";
          }
        }
        if(item['digitized']!= item['old_digitized']) {
          if(item['digitized'] == 1) {
            changes += "| not digitized -> digitized | ";
          } else {
            changes += "| digitized -> not digitized | ";
          }
        }
  			var markup = "<tr class=\"playitem border editsrow\" name=\"" + item['id'] + "\"><td class=\"edits_row_element\"> " + item['artist'] + " </td><td class=\"edits_row_element\">" + item['title'] + "</td><td class=\"edits_row_element\">" + changes + "</td><td><input type=submit VALUE=\"Undo\" onClick=\"undoEdits(this)\" name=\""
        + item['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>";
  			$("tbody[name='recentEdits']").append(markup);
  		}
  	}
  }

function editLine(thisObj, entryId, artist, title, label, genre, catalog, format, status, cancon, femcon, local, playlist, compilation, digitized, genres) {
  var populateObj = {
    artist: artist,
    title: title,
    label: label,
    genre: genre,
    catalog: catalog,
    format: format,
    status: status,
    cancon: cancon,
    femcon: femcon,
    local: local,
    playlist: playlist,
    compilation: compilation,
    digitized: digitized
  };

  var inputObj, val, morphedVal, objKeys, key;

  objKeys = Object.keys(populateObj);

  for (var i in objKeys) {
    key = objKeys[i];
    inputObj = $('#as'+key);
    val = populateObj[key];
    morphedVal = null;

    if ($(inputObj).length === 0) {
      // console.log({
      //   inputObj: inputObj,
      //   val: val
      // });
      // console.log('continue');
      continue;
    }

    if ($(inputObj).is(':checkbox')) {
      morphedVal = Boolean($.parseJSON(val));

      if ($(inputObj).prop('checked') != morphedVal) {
        $(inputObj).prop('checked', morphedVal).trigger('change');
      }
    } else if ($(inputObj).is('select:not([multiple])')) {
      if ($(inputObj).has('option[value="'+val+'"]').length) {
        // console.log("select has option with val");
        $(inputObj).val(val).trigger('change');
      } else if ($(inputObj).has("option:contains('"+val+"')").length) {
        // console.log("select has option containing text");
        $(inputObj).val($(inputObj).find("option:contains('"+val+"')").val()).trigger('change');
      } else {
        // console.log("select has no option found");
      }
    } else if ($(inputObj).is(':input:not(select)')) {
      $(inputObj).val(val).trigger('change');
    }
    // console.log({
    //   inputObj: inputObj,
    //   val: val,
    //   morphedVal: morphedVal,
    //   inputObjVal: $(inputObj).val()
    // });
  }

  if ($("#albumEntry-select-all").prop('checked')) {
    $("#albumEntry-select-all").prop('checked', false).trigger('change');
  }

  $('tr.albumEntry').find(':checkbox[name="entry"]:checked').prop('checked', false).trigger('change');

  $(thisObj).siblings(':has(:checkbox[name="entry"])')
            .find(':checkbox[name="entry"]')
            .prop('checked', true)
            .trigger('change');
}

function toggleSelectAll(thisObj) {
  if ($(thisObj).is(':checkbox')) {
    $('.albumEntry :checkbox').prop('checked', $(thisObj).prop("checked"));
  }
}