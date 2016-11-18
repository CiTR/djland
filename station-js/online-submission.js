/* DJLand online submission form creator
 *
 * To implement, include this in your HTML:
 *     <div id="submit-field"></div>
 * Add this file as a <script>, and provide your own CSS.
 */

 var form, trackButton, albumArtButton, submitButton;
 var trackNumber = 1;

window.addEventListener('load', function() {
  form = document.getElementById("submit-field");
  fillForm();
});

function fillForm() {
  // Instructions at the top.
  var newNode = document.createElement("p");
  newNode.appendChild(document.createTextNode("Please submit a minimum of four 320kbps MP3 files."));
  form.appendChild(newNode);

  // Create the 'Add Track' button.
  trackButton = document.createElement("button");
  trackButton.setAttribute("id", "new-track-button");
  trackButton.appendChild(document.createTextNode("Add Track"));
  form.appendChild(trackButton);
  trackButton.addEventListener('click', function() {
    // TODO: Prompt user for file.
    addTrackForm();
  });

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

  // Create the 'Submit' button.
  submitButton = document.createElement("button");
  submitButton.setAttribute("id", "submit-button");
  submitButton.appendChild(document.createTextNode("Submit"));
  form.appendChild(submitButton);
  submitButton.addEventListener('click', function() {
    // TODO: Verify information entered and send to DJLand.
  });

}

function addTrackForm() {
  // Create the surrounding div.
  var divNode = document.createElement("div");
  divNode.setAttribute("id", "track-" + trackNumber);
  trackNumber++;
  divNode.setAttribute("class", "track-form");

  // Add the file name
  var childNode = document.createElement("p");
  childNode.setAttribute("class", "track-file-name");
  // TODO: use name of file given.
  childNode.appendChild(document.createTextNode("file" + (trackNumber - 1) + "name.mp3"));
  divNode.appendChild(childNode);

  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Track name:"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  divNode.appendChild(childNode);

  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Composer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  divNode.appendChild(childNode);

  childNode = document.createElement("p");
  childNode.setAttribute("class", "input-track-label");
  childNode.appendChild(document.createTextNode("Performer(s):"));
  divNode.appendChild(childNode);

  childNode = document.createElement("input");
  childNode.setAttribute("class", "input-track-field");
  divNode.appendChild(childNode);

  form.insertBefore(divNode, trackButton);
}
