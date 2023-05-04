// JavaScript Document

var totalTracks = 0;
var form;
var songFiles = [];
var totalTrackSize = 0;

$(document).ready(function() {

  form        = document.getElementById("submit-field");
  var trackButton = document.getElementById("new-track-button-input");
  if (trackButton !== null) {
    trackButton.addEventListener('change', handleTracks, false);
  }
  var submitButton   = document.getElementById("submit-button");
  if (submitButton !== null) {
    submitButton.addEventListener('click', submitForm);
  }

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

function handleTracks(evt) {
  var newFiles = evt.target.files;
  var filesAdded = 0;
  var fileWarning = false;
  var sizeWarning = false;

  for (var i = 0, f; f = newFiles[i]; i++) {

    if (!f.type.match('audio.*')) {
      fileWarning = true;
      continue;
    }

    if (f.size > 175000000) {
      sizeWarning = true;
      continue;
    }

    var fileName = f.name;
    addTrackForm(fileName, (totalTracks + i + 1) );
    songFiles[totalTracks + i] = f;
    filesAdded++;

    totalTrackSize += f.size;
  }
  if (fileWarning) alert("Please only upload audio files");
  if (sizeWarning) alert("Please keep file size below 175 megabytes.\nIf you want to submit large files, please email us.");
  totalTracks = totalTracks + filesAdded;
}

function addTrackForm(fileName, trackNo) {
  // Create the surrounding div.
  var divNode = document.createElement("div");
  divNode.setAttribute("id", "track-" + trackNo);
  divNode.setAttribute("class", "track-form");

  // Add the file name
  var childNode = document.createElement("p");
  childNode.setAttribute("class", "track-file-name");
  childNode.appendChild(document.createTextNode("File name: " + fileName));
  divNode.appendChild(childNode);

  // Add the track number field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "track-number-label");
  childNode.appendChild(document.createTextNode("★ Track number:"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "track-number-field");
  childNode.setAttribute("value", trackNo);
  divNode.appendChild(childNode);

  // Add the track name field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("★ Track name:"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field input-track-field-name");
  divNode.appendChild(childNode);

  // Add the composer field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Composer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("id", "composer-" + trackNo);
  childNode.setAttribute("class", "input-track-field input-track-field-composer");
  var defaultComposer = document.getElementById("default-composer");
  childNode.setAttribute("value", defaultComposer.value);
  divNode.appendChild(childNode);

  // Add the performer field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Performer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field input-track-field-performer");
  childNode.setAttribute("style", "clear:right;");
  var defaultPerformer = document.getElementById("default-performer");
  childNode.setAttribute("value", defaultPerformer.value);
  divNode.appendChild(childNode);

  // Add the Include checkbox
  childNode = document.createElement("input");
  childNode.setAttribute("id", "include-" + trackNo);
  childNode.setAttribute("type", "checkbox");
  childNode.setAttribute("class", "include-track");
  childNode.setAttribute("style", "margin-right:15px;margin-left:5%;");
  divNode.appendChild(childNode);

  childNode = document.createElement("label");
  childNode.setAttribute("for", "include-" + trackNo);
  childNode.appendChild(document.createTextNode("Include (de-select to remove track from submission)"));
  divNode.appendChild(childNode);

  form.appendChild(divNode);

  $("#include-" + trackNo).prop('checked', true);
}

function submitForm() {

  $("#submit-button").text("Please Wait...");
  $("#submit-button").prop("disable", true);

  if (totalTrackSize > 525000000) {
    alert("Your submission is too big. Please add songs in smaller groups.");
    $("#submit-button").text("Submit");
    $("#submit-button").prop("disable", false);

  } else {

    var missing = [];
    var success = true;

    var alertString = "You are missing the following:";

    // Check that files have been added
    var tracks = $("#submit-field").children();
    if (tracks.length < 1) {
      // missing.push("\n• Music files to upload");
      alertString += "\n• Music files to upload";
      success = false;
    }

    // Checks that required track info has been added
    var trackNumberCheck = [];
    var missingTrackNumbers = 0;
    var missingTrackNames = 0;
    var trackNumError = false;
    var totalTracksChecked = 0;

    for (var i = 0; i < tracks.length; i++) {

      var thisTrack = $(tracks.get(i));

      var trackNumberValue = thisTrack.find(".track-number-field").val();
      var trackName        = thisTrack.find(".input-track-field-name").val();
      var checked          = thisTrack.find(".include-track").is(":checked");

      if (checked) {

        if (trackName == "") {
          success = false;
          missingTrackNames++;
        }

        totalTracksChecked++;

        if (trackNumberValue == "" ) {
          success = false;
          missingTrackNumbers++;
        } else if ( isNaN(parseInt(trackNumberValue)) ) {
          success = false;
          trackNumError = true;
        } else {
          trackNumberCheck.push(trackNumberValue);
        }
      }
    }

    if (missingTrackNames == 1) {
      alertString += "\n• 1 Track name";
    } else if (missingTrackNames > 1) {
      alertString += "\n• " + missingTrackNames + " track names";
    }

    if (missingTrackNumbers == 1) {
      alertString += "\n• 1 Track number";
    } else if (missingTrackNumbers > 1) {
      alertString += "\n• " + missingTrackNumbers + " track numbers";
    }

    if (trackNumError) {
      alertString += "\n\n Only numbers may be used in the track number field.";
    }

    if ((totalTracksChecked < 1) && (tracks.length > 0)) {
      success = false;
      alertString += "\nPlease add your files to the upload by clicking the checkboxes.";
    }

    if (success) { // possibly add sorting algorithm here in case of large array
      var duplicate = false;
      for (var i = 0; i < trackNumberCheck.length; i++) {
        if (duplicate == true) break;
        for (var j = i + 1; j < trackNumberCheck.length; j++) {
          if (parseInt(trackNumberCheck[i]) == parseInt(trackNumberCheck[j])) {
            success = false;
            alertString = "There are duplicate track numbers — please correct"
            duplicate = true;
            break;
          }
        }
      }
    }

    if (success) {


      var tracks = $("#submit-field").children();
      var songSuccess = true;

      for (var i = 0; i < tracks.length; i++) {
        var trackFile = songFiles[i];
        var x = $(tracks.get(i));
        if (x.find(".include-track").is(":checked")) {
          var a = new FormData();
          a.append('number', x.find('.track-number-field').val());
          a.append('name', x.find('.input-track-field-name').val());
          a.append('composer', x.find('.input-track-field-composer').val());
          a.append('performer', x.find('.input-track-field-performer').val());
          a.append('file', trackFile);
          a.append('filename', trackFile.name);

          var libraryId; // need to figure out how to find this

          songSuccess = songSuccess && addSongToLibraryEntry(a, libraryId, trackFile.name);
        }
      }
      if (songSuccess) {
        $("#submit-button").hide();
        $("#success-message").show();
      }
    } else {
      alert(alertString);
      $("#submit-button").text("Submit");
      $("#submit-button").prop("disable", false);
    }
  }

}

function addSongToLibraryEntry(data, libraryId, filename) {

  $.ajax({
    url: "something" + libraryId,
    data: data,
    type: "POST",
    cache: false,
    contentType: false,
    processData: false,
  })
  .done(function(data) {
    console.log("File '" + filename +"' sent.");
    return true;
  })
  .fail(function(data) {
    alert("Failed to send file: " + filename);
    return false;
  })
}
