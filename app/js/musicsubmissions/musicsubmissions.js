//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_submission_handlers();
	});
	populateNewSubmissionsTable();
});

// TODO: on admins page, search past accepted submissions by date
function populateAcceptedSubmissions(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='acceptedSubmissions']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "";
			$("tbody[name='acceptedSubmissions']").append(markup);
		}
	}
}
// TODO: on admins page, search past submissions (accepted and rejected)
function populatePastSubmissions(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='pastSubmissions']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "";
			$("tbody[name='pastSubmissions']").append(markup);
		}
	}
}

// Getting data for a specific submission given the ID and call the right function to display it.
function getSubmissionDataAndDisplay(id) {
  $.ajax({
    type: "GET",
    url: "api2/public/submissions/" + id,
    dataType: "json",
    async: true,
    success: function(data) {
      //console.log(data);
      switch (data['status']) {
        case "unreviewed":
          displayReviewBox(data);
          break;
        case "reviewed":
          displayReviewedBox(data);
          break;
        case "approved":
          displayApprovedBox(data);
          break;
		case "tagged":
          displayTaggedBox(data);
          break;
		case "trashed":
          // TODO
         break;
      }
    }
  });
}

function displayReviewBox(data) {
  var id			= data['id'];
  var artist      	= data['artist'];
  var location    	= data['location'];
  var album       	= data['title'];
  var label       	= data['label'];
  var genre       	= data['genre'];
  var tags        	= data['tags'];
  var releasedate 	= data['releasedate'];
  var submitted   	= data['submitted'];
  var credit      	= data['credit'];
  var email       	= data['email'];
  var description 	= data['description'];
  var art_url		= data['art_url'];

  if (releasedate == "" || releasedate == null) {
    releasedate = "No date submitted";
    $("#releaseDate-review-box").attr('style', 'color:navy');
  }
  if (credit == "" || credit == null) {
    credit = "No members submitted";
    $("#albumCredit-review-box").attr('style', 'color:navy');
  }
  if (description == "" || description == null) {
    description = "No description submitted";
    $("#description-review-box").attr('style', 'color:navy');
  }

  $("#id-review-box").attr('name', id);
  $("#artist-review-box").text(artist);
  $("#location-review-box").text(location);
  $("#album-review-box").text(album);
  $("#label-review-box").text(label);
  $("#genre-review-box").text(genre);
  $("#tag-review-box").text(tags);
  $("#releaseDate-review-box").text(releasedate);
  $("#submissionDate-review-box").text(submitted);
  $("#albumCredit-review-box").text(credit);
  $("#contact-review-box").text(email);
  $("#description-review-box").text(description);
  $("#albumArt-review-box").attr("src", art_url);
  $("#comments-review-box").text("");
  $("#approved_status-review-box").val(0).change();
}

function displayReviewedBox(data) {

  var id 				= data['id'];
  var artist      		= data['artist'];
  var location   		= data['location'];
  var album       		= data['title'];
  var label       		= data['label'];
  var genre       		= data['genre'];
  var tags        		= data['tags'];
  var releasedate 		= data['releasedate'];
  var submitted   		= data['submitted'];
  var credit      		= data['credit'];
  var email       		= data['email'];
  var description 		= data['description'];
  var art_url     		= data['art_url'];
  var review_comments 	= data['review_comments'];
  var approved 			= data['approved'];

  if (releasedate == "" || releasedate == null) {
    releasedate = "No date submitted";
    $("#releaseDate-review-box").attr('style', 'color:navy');
  }
  if (credit == "" || credit == null) {
    credit = "No members submitted";
    $("#albumCredit-review-box").attr('style', 'color:navy');
  }
  if (description == "" || description == null) {
    description = "No description submitted";
    $("#description-review-box").attr('style', 'color:navy');
  }

  $("#id-reviewed").attr('name', id);
  $("#artist-reviewed").text(artist);
  $("#location-reviewed").text(location);
  $("#album-reviewed").text(album);
  $("#label-reviewed").text(label);
  $("#genre-reviewed").text(genre);
  $("#tag-reviewed").text(tags);
  $("#release-reviewed").text(releasedate);
  $("#submitted-reviewed").text(submitted);
  $("#credit-reviewed").text(credit);
  $("#contact-reviewed").text(email);
  $("#description-reviewed").text(description);
  $("#albumArt-reviewed").attr("src", art_url);
  $("#reviewed_comments").text(review_comments);
  $("reviewed_approved_status").val(approved).change();
}

function displayApprovedBox(data) {
	//console.log(data);
	var catalog		= data['catalog'];
	if(catalog == null) catalog = "";
	var format;
	switch( data['format_id']){
		case 1:
			format = 'CD';
			break;
		case 2:
			format = 'LP';
			break;
		case 3:
			format = '7\"';
			break;
		case 4:
			format = 'CASS';
			break;
		case 5:
			format = 'CART';
			break;
		case 6:
			format = 'MP3';
			break;
		case 7:
			format = 'MD';
			break;
		case 8:
			format = 'Unknown';
			break;
		default:
			format = "Format Error";
			console.log("Invalid format detected in tagging box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
			break;
	}
	var album       	= data['title'];
	var artist      	= data['artist'];
	var credit      	= data['credit'];
	var label       	= data['label'];
	var genre       	= data['genre'];
	var tags        	= data['tags'];
	var location    	= data['location'];
	var cancon			= data['cancon'];
	var femcon			= data['femcon'];
	var local			= data['local'];
	var playlist		= data['playlist'];
	var compilation		= data['compilation'];
	var in_sam			= data['in_SAM'];
	var email       	= data['email'];
	var description 	= data['description'];
	var review_comments = data['review_comments'];
	var art_url     	= data['art_url'];
	var submitted  		= data['submitted'];
	var releasedate 	= data['releasedate'];
	//console.log(review_comments);

	//Un-editable fields
	$("#release-approved").text("Album release date: " + releasedate);
    $("#submitted-approved").text("Date submitted: " + submitted);
	$("#contact-approved").text("Band email: " + email);
	if(description == null){
		$("#description-approved").text("No description given.");
	} else{
		$("#description-approved").text(description);
	}
	if(review_comments == null){
		$("#review_comments-approved").text("No review comments given.");
	} else{
		$("#review_comments-approved").text(review_comments);
	}
    $("#albumArt-approved").attr("src", art_url);
	//Editable fields
	$("#catalog-approved").val( String(catalog) );
	$("#format-approved").prop('value', format).change();
	$("#album-approved").val(album);
	$("#artist-approved").val(artist);
	$("#credit-approved").val(credit);
	$("#label-approved").val(label);
	$("#genre-approved").prop('value', genre).change();
	//if(tags != null){
	//	$("#tags-approved").html("The following subgenre tags were specified by the band: <b>" + tags + "</b>. Specify an appropiate subgenre below:");
	//} else{
	//	$("tags-approved").text("No subgenre tags were specified by the band. Specify a subgenre, if any are appropiate, below:");
	//}
	$("#location-approved").val(location);
	if(cancon == 1){
		$("#cancon-approved").prop('checked', true);
	}
	if(femcon == 1) {
		$("#femcon-approved").prop('checked', true);
	}
	if(local == 1) {
		$("#local-approved").prop('checked', true);
	}
	if(playlist == 1) {
		$("#playlist-approved").prop('checked', true);
	}
	if(compilation == 1) {
		$("#compilation-approved").prop('checked', true);
	}
	if(in_sam == 1) {
		$("#in_sam-approved").prop('checked', true);
	}
}

function displayTaggedBox(data) {
	var catalog		= data['catalog'];
	if(catalog == null) catalog = "";
	var format;
	switch( data['format_id']){
		case 1:
			format = 'CD';
			break;
		case 2:
			format = 'LP';
			break;
		case 3:
			format = '7\"';
			break;
		case 4:
			format = 'CASS';
			break;
		case 5:
			format = 'CART';
			break;
		case 6:
			format = 'MP3';
			break;
		case 7:
			format = 'MD';
			break;
		case 8:
			format = 'Unknown';
			break;
		default:
			format = "Format Error";
			console.log("Invalid format detected in tagging box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
			break;
	}
	var album       	= data['title'];
	var artist      	= data['artist'];
	var credit      	= data['credit'];
	var label       	= data['label'];
	var genre       	= data['genre'];
	var tags        	= data['tags'];
	var location    	= data['location'];
	var cancon			= data['cancon'];
	var femcon			= data['femcon'];
	var local			= data['local'];
	var playlist		= data['playlist'];
	var compilation		= data['compilation'];
	var in_sam			= data['in_SAM'];
	var email       	= data['email'];
	var description 	= data['description'];
	var review_comments = data['review_comments'];
	var art_url     	= data['art_url'];
	var submitted  		= data['submitted'];
	var releasedate 	= data['releasedate'];

	//Un-editable fields
	$("#release-tagged").text("Album release date: " + releasedate);
    $("#submitted-tagged").text("Date submitted: " + submitted);
	$("#contact-tagged").text("Band email: " + email);
	if(description == null){
		$("#description-tagged").text("No description given.");
	} else{
		$("#description-tagged").text(description);
	}
	if(review_comments == null){
		$("#review_comments-tagged").text("No review comments given.");
	} else{
		$("#review_comments-tagged").text(review_comments);
	}
    $("#albumArt-tagged").attr("src", art_url);
	//Editable fields
	$("#catalog-tagged").val( String(catalog) );
	$("#format-tagged").prop('value', format).change();
	$("#album-tagged").val(album);
	$("#artist-tagged").val(artist);
	$("#credit-tagged").val(credit);
	$("#label-tagged").val(label);
	$("#genre-tagged").prop('value', genre).change();
	//if(tags != null){
	//	$("#tags-tagged").html("The following subgenre tags were specified by the band: <b>" + tags + "</b>. Specify an appropiate subgenre below:");
	//} else{
	//	$("tags-tagged").text("No subgenre tags were specified by the band. Specify a subgenre, if any are appropiate, below:");
	//}
	$("#location-tagged").val(location);
	if(cancon == 1){
		$("#cancon-tagged").prop('checked', true);
	}
	if(femcon == 1) {
		$("#femcon-tagged").prop('checked', true);
	}
	if(local == 1) {
		$("#local-tagged").prop('checked', true);
	}
	if(playlist == 1) {
		$("#playlist-tagged").prop('checked', true);
	}
	if(compilation == 1) {
		$("#compilation-tagged").prop('checked', true);
	}
	if(in_sam == 1) {
		$("#in_sam-tagged").prop('checked', true);
	}
}

//Manual Submission AJAX
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
  canadaBox      = $("#canada-artist");
  vancouverBox   = $("#vancouver-artist");
  femArtistBox   = $("#female-artist");
  commentField   = $("#comments-box");
  albumViewer    = document.getElementById("album-viewer");
  formatPicker   = document.getElementById("format-picker");

  submitButton.addEventListener('click', submitForm);

  albumArtButton.addEventListener('change', handleAlbum, false);

  trackButton.addEventListener('change', handleTracks, false);

});

function submitReview(id,appproved_status,review_comments){
	//console.log("ID: " + id + " Status: " + appproved_status + " Comments: " + review_comments);
	console.log("Submitting review ... ");
	$.ajax({
		url: "api2/public/submissions/review",
		type:'PUT',
		dataType:'json',
		data: {
			'id':id,
			'approved':appproved_status,
			'review_comments':review_comments
		},
		async:true,
		success:function(data){
			$("#comments-review-box").val('');
			$("#approved_status-review-box").val(0).change();
			$('#view_submissions').stop().fadeOut(175);
			$("#view_submissions_row").fadeOut(175);
			var selector = "[name=\'" + id + "\']";
			$(selector).fadeOut(100);
			alert("Review Submitted");
			//TODO: Change the button and show a spinny thing
		}//,
		//commented out to avoid infinite loop
		//fail:function(data){
		//	console.log("Submitting Review Failed. Response data: " + data);
		//	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
		//}
	});
}

function approveReview(id){
	console.log("Approving review ... ");
	$.ajax({
		url: "api2/public/submissions/approve",
		type:'PUT',
		dataType:'json',
		data: {
			'id':id
		},
		async:true,
		success:function(data){
			//console.log(data);
			alert("Review Approved");
			$("#reviewed_comments").val('');
			$("#reviewed_approved_status").val(0).change();
			$('#reviewed_submissions_view').fadeOut(175);
			$("#reviewed_submissions_view_row").fadeOut(175);
			var selector = "[name=\'" + id + "\']";
			$(selector).fadeOut(100);
			//TODO: Change the button and show a spinny thing
		}//,
		//commented out to avoid infinite loop
		//fail:function(data){
		//	console.log("Submitting Review Failed. Response data: " + data);
		//	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
		//}
	});
}

function tagReview(id) {
	console.log("Tagging review ... ");
	$.ajax({
		url: "api2/public/submissions/tag",
		type:'PUT',
		dataType:'json',
		data: {
			'id':id
		},
		async:true,
		success:function(data){
			//console.log(data);
			alert("Submission tagged");
			$('#submissionspopup').fadeOut(175);
			var selector = "[name=\'" + id + "\']";
			$(selector).fadeOut(100);
			//TODO: Change the button and show a spinny thing
		}//,
		//commented out to avoid infinite loop
		//fail:function(data){
		//	console.log("Submitting Review Failed. Response data: " + data);
		//	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
		//}
	});
}

function approveTags(id) {
	console.log("Approving tags ... ");
	$.ajax({
		url: "api2/public/submissions/tolibrary",
		type:'PUT',
		dataType:'json',
		data: {
			'id':id
		},
		async:true,
		success:function(data){
			//console.log(data);
			alert("Tags Approved");
			$('#submissionsapprovalpopup').fadeOut(175);
			var selector = "[name=\'" + id + "\']";
			$(selector).fadeOut(100);
			//TODO: Change the button and show a spinny thing
		}//,
		//commented out to avoid infinite loop
		//fail:function(data){
		//	console.log("Submitting Review Failed. Response data: " + data);
		//	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
		//}
	});
}

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
  var canada;
  if(canadaBox.attr('checked') == true) canada = 1;
  else canada = 0;
  var vancouver = vancouverBox.attr('checked');
  var female    = femArtistBox.attr('checked');
  var comments  = commentField.val();
  console.log(comments);
  var format    = formatPicker.value;

  //console.log("formatPicker value: " + format);

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
  /*
  if (date == "") {
    success = false;
    missing.push("\n• Date released");
  }
  */

  if (success) {
    /*
    var submission = document.getElementById("submit-button-div");
    submission.innerHTML = "<p style='text-align:center;margin-bottom:50px;'>Thanks for submitting! A confirmation email will be sent to you shortly.</p>";
    */
    createSubmission(format);
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
