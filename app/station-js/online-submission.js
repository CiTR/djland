var form, trackButton, albumArtButton, submitButton;
var artistField, contactField, recordField, cityField, memberField;
var albumField, genrePicker, dateField, canadaBox, vancouverBox;
var femArtistBox, commentField, cover, trackNumber, nameField;
var composerField, performerField, albumViewer;
var totalTracks = 0;

window.addEventListener('load', function() {
  form           = document.getElementById("submit-field");
  albumArtButton = document.getElementById("album-art-input-button");
  trackButton    = document.getElementById("new-track-button-input");
  submitButton   = document.getElementById("submit-button");
  artistField    = document.getElementById("artist-name");
  contactField   = document.getElementById("contact-email");
  recordField    = document.getElementById("record-label");
  cityField      = document.getElementById("home-city");
  memberField    = document.getElementById("member-names");
  albumField     = document.getElementById("album-name");
  genrePicker    = document.getElementById("genre-picker");
  dateField      = document.getElementById("date-released");
  canadaBox      = document.getElementById("canada-artist");
  vancouverBox   = document.getElementById("vancouver-artist");
  femArtistBox   = document.getElementById("female-artist");
  commentField   = document.getElementById("comments-box");
  albumViewer    = document.getElementById("album-viewer");

  submitButton.addEventListener('click', submitForm);

  albumArtButton.addEventListener('change', handleAlbum, false);

  trackButton.addEventListener('change', handleTracks, false);

});

function submitForm() {
  var missing = [];
  var success = true;

  var artist    = artistField.value;
  var email     = contactField.value;
  var label     = recordField.value;
  var city      = cityField.value;
  var members   = memberField.value;
  var album     = albumField.value;
  var genre     = genrePicker.value;
  var date      = dateField.value;
  var canada    = canadaBox.checked;
  var vancouver = vancouverBox.checked;
  var female    = femArtistBox.checked;
  var comments  = commentField.value;

  if (artist == "") {
    success = false;
    missing.push("\n• Artist / Band name");
  }
  if (email == "") {
    success = false;
    missing.push("\n• Contact email");
  }
  if (city == "") {
    success = false;
    missing.push("\n• Home city");
  }
  if (album == "") {
    success = false;
    missing.push("\n• Album name");
  }
  if (genre == "") {
    success = false;
    missing.push("\n• Genre");
  }
  if (date == "") {
    success = false;
    missing.push("\n• Date released");
  }

  if (success) {
    var submission = document.getElementById("submit-button-div");
    submission.innerHTML = "<p style='text-align:center;margin-bottom:50px;'>Thanks for submitting! A confirmation email will be sent to you shortly.</p>";
    postRequest("MP3");
  } else {
    var alertString = "You are missing the following fields:";
    for (var i = 0; i < missing.length; i++) {
      alertString += missing[i];
    }
    alert(alertString);
  }

}

function handleAlbum(evt) {
  var files = evt.target.files;
  cover = files[0];

  if(cover.type.match('image.*')) {
    var reader = new FileReader();

    reader.onload = (function(theFile) {
      return function(e) {
        var span = document.createElement('span');
        span.setAttribute('id', 'thumb-span');
        span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
        albumViewer.innerHTML = "";
        // document.getElementById("album-viewer").insertBefore(span, null);
        albumViewer.insertBefore(span, null);
      };
    })(cover);

    reader.readAsDataURL(cover);
  } else alert("Please choose an image.");
}

function handleTracks(evt) {
  var files = evt.target.files;
  var filesAdded = 0;
  var warning = false;
  // TODO: Needs to remove non-music files from files[]
  for (var i = 0, f; f = files[i]; i++) {

    if (!f.type.match('audio.*')) {
      warning = true;
      continue;
    }

    var fileName = f.name;
    addTrackForm(fileName, (totalTracks + i + 1) );
    filesAdded++;
  }
  if (warning) alert("Please only upload audio files");
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
  // TODO: use name of file given.
  childNode.appendChild(document.createTextNode("File name: " + fileName));
  divNode.appendChild(childNode);

  // Add the track number field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "track-number-label");
  childNode.appendChild(document.createTextNode("Track number:"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "track-number-field");
  childNode.setAttribute("value", trackNo);
  divNode.appendChild(childNode);

  // Add the track name field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Track name:"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  divNode.appendChild(childNode);

  // Add the composer field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Composer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  divNode.appendChild(childNode);

  // Add the performer field
  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Performer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  childNode.setAttribute("value", artistField.value);
  divNode.appendChild(childNode);

  form.appendChild(divNode);
}

/*
function fillForm() {

  // ----- Create the album form. ----- //

  newNode = document.createElement("div");
  newNode.setAttribute("id", "artist-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Album artist:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  newNode = document.createElement("div");
  newNode.setAttribute("id", "email-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Contact email:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  newNode = document.createElement("div");
  newNode.setAttribute("id", "record-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Record label:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  newNode = document.createElement("div");
  newNode.setAttribute("id", "location-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Location:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  newNode = document.createElement("div");
  newNode.setAttribute("id", "credit-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Album credit:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  newNode = document.createElement("div");
  newNode.setAttribute("id", "name-input-album");
  var newChildNode = document.createElement("p");
  newChildNode.setAttribute("class", "input-album-label");
  newChildNode.appendChild(document.createTextNode("Album name:"));
  newNode.appendChild(newChildNode);

  newChildNode = document.createElement("input");
  newChildNode.setAttribute("class", "input-album-field");
  newNode.appendChild(newChildNode);
  form.appendChild(newNode);

  // ----- ----- ----- ----- ----- ----- //

  // Create the 'Add Album Art' button.
  albumArtButton = document.createElement("button");
  albumArtButton.setAttribute("id", "album-art-button");
  albumArtButton.appendChild(document.createTextNode("Add Album Art"));
  form.appendChild(albumArtButton);
  albumArtButton.addEventListener('click', function() {
    // TODO: Make this prompt user for image file.
  });

    // Instructions for adding tracks.
    var newNode = document.createElement("p");
    newNode.appendChild(document.createTextNode("Please submit a minimum of four 320kbps MP3 files."));
    form.appendChild(newNode);

    */

    /*
    // Create the 'Add Track' button.
    trackButton = document.createElement("button");
    trackButton.setAttribute("id", "new-track-button");
    trackButton.setAttribute("class", "submission-button");
    trackButton.appendChild(document.createTextNode("Add Track"));
    form.appendChild(trackButton);
    trackButton.addEventListener('click', function() {
      // TODO: Prompt user for file.
      addTrackForm();
    });
    */


    /*
  // Create the 'Submit' button.
  submitButton = document.createElement("button");
  submitButton.setAttribute("id", "submit-button");
  submitButton.appendChild(document.createTextNode("Submit"));
  form.appendChild(submitButton);
  submitButton.addEventListener('click', function() {
    // TODO: Verify information entered and send to DJLand.
  });



}
*/
