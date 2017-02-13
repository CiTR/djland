/* JavaScript for POST requests to submit new music submissions, from the admin-
 * only Manual Submissions page in DJLand, or the public Submit Online page on
 * CiTR.ca.
 * Michael Adria, Capstone 2016/2017
 */

function createSubmission(format) {
    //Hack, TODO: Reference the types_format DB table
    /*
   if(format == "CD"){
       var format_id = 1;
   }else if(format == "MP3"){
       var format_id = 6;
   }
   */

   console.log("Passed format: " + format);

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

   console.log("Format: " + format + "\nformat_id: " + format_id);

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
   //var songlist    = [];
   var songlist    = 10;
   /* while (document.getElementById('track-' + trackNo) != null) {
     e = document.getElementById('track-' + trackNo).childNodes[4].value;
     songlist.push(e);
     trackNo = (Number(trackNo) + 1).toString();
    }*/
   // var art_url  = TODO, below is temporary
   var art_url     = "https://cdn.pastemagazine.com/www/system/images/photo_albums/best-album-covers-2012/large/photo_9459_0.jpg?1384968217";

   $.ajax({
     url: "api2/public/submission/",
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
       art_url: art_url,
     },
     type: "POST",
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
 				$(identifier).css("color","C02F1D");
 			}
 		},
 		error:function(err){
 			//var json_response = err.responseJSON.msg;
 			console.log("Bad format for AJAX Request with Member ID: " + id + ", the server said:");
 			console.log(err);
 		}
 	});
 }
