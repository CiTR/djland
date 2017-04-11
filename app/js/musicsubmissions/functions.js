/* JavaScript for POST requests to submit new music submissions, from the admin-
 * only Manual Submissions page in DJLand, or the public Submit Online page on
 * CiTR.ca.
 * Michael Adria, Capstone 2016/2017
 */

function createSubmission(data) {

   $.ajax({
     url: "api2/public/submission/",
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
   console.log(data);
   })

   .fail(function(data) {
    // var response = $.parseJSON(data);
    // console.log(response);
     alert("Failure");
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
