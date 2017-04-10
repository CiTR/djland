/* JavaScript for POST requests to submit new music submissions, from the admin-
 * only Manual Submissions page in DJLand, or the public Submit Online page on
 * CiTR.ca.
 * Michael Adria, Capstone 2016/2017
 */

function createSubmission(data) {
/*
  console.log(art);

   var format_id = 8;

   //TODO: get this from the database
   switch (format) {
     case "CD":
      format_id = 1;
      break;
     case "LP":
      format_id = 2;
      break;
     case "7\"":
      format_id = 3;
      break;
     case "CASSETTE":
      format_id = 4;
      break;
     case "CART":
      format_id = 5;
      break;
     case "MP3":
      format_id = 6;
      break;
     case "MD":
      format_id = 7;
      break;
     default:
      format_id = 8;
   }

   var artist      = document.getElementById('artist-name').value;
   var email       = document.getElementById('contact-email').value;
   var label       = document.getElementById('record-label').value;
   var location    = document.getElementById('home-city').value;
   var credit      = document.getElementById('member-names').value;
   var title       = document.getElementById('album-name').value;
   var e           = document.getElementById('genre-picker');
   var genre       = e.options[e.selectedIndex].value;
   var releasedate = document.getElementById('date-released').value;
   var femcon      = ($('#female-artist').prop('checked', true)) ? 1 : 0;
   var cancon      = ($('#canada-artist').prop('checked', true)) ? 1 : 0;
   var local       = ($('#vancouver-artist').prop('checked', true)) ? 1 : 0;
   var description = $('#comments-box').val();

   if (label == "") {
     label = "Self-Released";
   }

   var trackNo     = "1";

   var songlist    = 10;
*/
   $.ajax({
     url: "api2/public/submission/",
     /*
     data: {
       format_id: format_id,
       artist: artist,
       email: email,
       label: label,
       location: location,
       credit: credit,
       title: title,
       genre: genre,
       releasedate: releasedate,
       femcon: femcon,
       cancon: cancon,
       local: local,
       description: description,
       songlist: songlist,
       art_url: art,
     },
     */
     data: data,
     type: "POST",
     // async: false,
     cache: false,
     contentType: false,
     processData: false,
     // dataType: "json",
   })

   .done(function(data) {
   var successBox = document.getElementById("submit-button-div");
   successBox.innerHTML = "<p style='text-align:center;margin-bottom:50px;'>Thanks for submitting! A confirmation email will be sent to you shortly.</p>";
   })

   .fail(function() {
     alert("Failure");
   });
 }

  function createArtSubmission(art, format) {

    // console.log('art');
    console.log("passed file: ");
    console.log(art);

    $.ajax({
      url: "api2/public/art/",
      type: "POST",
      enctype: "multipart/form-data",
      /*
      data: {
        art: art,
      },
      */
      data: art,
      cache: false,
      dataType: 'json',
      contentType: false,
      processData: false,
    })

    .done(function(data) {
      alert("album art successfully uploaded");
      createSubmission(format);
    })

    .fail(function() {
      // alert("album art failed to upload");
      console.log("album art failed to upload");
    });

  }

//Unused function
 function namesFromMemberId(id){
 	var string = " ";
 	$.ajax({
 		type:"GET",
 		url: "api2/public/member/" + id + "/firstnamelastname",
 		dataType:'json',
 		async:true,
 		success:function(response){
 			var data = response[0];
 			var identifier = "[name=\'names"+id+"\']";
 			if(data != undefined){
 				string = data['firstname'] + " " + data['lastname'];
 				$(identifier).text(string);
 			} else {
 				$(identifier).text("Unknown");
 				$(identifier).css("color","navy");
 			}
 		},
 		error:function(err){
 			//var json_response = err.responseJSON.msg;
 			console.log("Bad format for AJAX Request with Member ID: " + id + ", the server said:");
 			console.log(err);
 		}
 	});
 }
