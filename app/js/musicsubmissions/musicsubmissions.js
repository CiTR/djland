//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then( function () {
		add_submission_handlers();
	});
	populateNewSubmissionsTable();
});
/********************
 ***** Handlers *****
 ********************/
function add_submission_handlers(){
	//Listener for adding 'updated' to allow only updated comments to be submitted for saving
	//TODO
	$('#membership_table').off('keyup','.staff_comment').on('keyup','.staff_comment',function(element){
		$(this).addClass('updated');
	});

	//Listener for saving comments
	//TODO
	$('#search').off('click','#save_comments').on('click','#save_comments',function(element){
		saveComments();
	});
	//Listener for closing stuff with ESC key
	$(document).keyup(function(e) {
	    if (e.keyCode == 27) { // escape key maps to keycode `27`
	        $('#submissionspopup').fadeOut(175);
			$('#submissionsapprovalpopup').fadeOut(175);
			$('#view_submissions').stop().fadeOut(175);
			$("#view_submissions_row").fadeOut(175);
	    	$('#reviewed_submissions_view').fadeOut(175);
			$('#reviewed_submissions_view_row').fadeOut(175);
		}
	});
	/*
	 * Listeners for tagging sidebar
	 */
	//Listener for viewing the tagging sidebar from clicking on their row
    $(".tagrow").click(function(e){
		$('#submissionspopup').fadeIn(225);
		var idSubmission = $(this).attr('name');
		getSubmissionDataAndDisplay(idSubmission);
    });
	$("#submissionscloser").click(function(e){
		$('#submissionspopup').fadeOut(175);
    });
	$("#tagcancel").click(function(e){
		$('#submissionspopup').fadeOut(175);
    });
	$("#approved-extrainfo-button").hover(function(e){
		$("#approved-extrainfo").show();
	},function(e){
		$("#approved-extrainfo").hide();
	});
    $("#approve-tags-button").click(function(e){
	    if ($("#subgenre-approved").select2("val") == "No Subgenre") {
	      $("#subgenre-tag-warning").show();
	    } else {
	      $("#subgenre-tag-warning").hide();
	      console.log($("#subgenre-approved").select2("val"));
	      // addTagToSubmission($("#subgenre-approved").select2("val"));
    	}
  	});
	/*
	 * Listeners for approving tags popup
	 */
	$(".approverow").click(function(e){
		$('#submissionsapprovalpopup').fadeIn(225);
		var idSubmission = $(this).attr('name');
		getSubmissionDataAndDisplay(idSubmission);
    });
	$("#submissionsapprovalcloser").click(function(e){
		$('#submissionsapprovalpopup').fadeOut(175);
    });
	$("#approvecancel").click(function(e){
		$('#submissionsapprovalpopup').fadeOut(175);
    });
	$("#tagged-extrainfo-button").hover(function(e){
		$("#tagged-extrainfo").show();
	},function(e){
		$("#tagged-extrainfo").hide();
	});
	$("#approve_submission_btn").click(function(e){
		//TODO
	});
	/*
	 * Listener for box to do a review
	 */
	//Listener for viewing the review from clicking on their row
	$(".reviewrow").click(function(e){
		//Tab your code properly @michaeladria. I'll fix it for you this time
	    var idSubmission = $(this).attr('name');
		$("#view_submissions_row").insertAfter($(this).closest('tr'));
		$("#view_submissions_row").show();
		$('#view_submissions').fadeIn(225);
	    // console.log(idSubmission);
	    getSubmissionDataAndDisplay(idSubmission);
    });
	$("#view_submissions_closer").click(function(e){
		$('#view_submissions').stop(true).fadeOut(175);
		$("#view_submissions_row").fadeOut(175);
	});
	//Listener for submitting a review
	$("#view_submissions_submit_btn").click(function(e){
		var id = $("#id-review-box").attr('name');
		var approvedStatus = $("#view_submissions_approved_status").val();
		var review_comments = $("#view_submissions_comments").val();
		submitReview(id, approvedStatus, review_comments);
	});
	/*
	 * Listeners for approving a review
	 */
	$(".reviewedrow").click(function(e){
		var idSubmission = $(this).attr('name');
		$('#reviewed_submissions_view_row').insertAfter($(this).closest('tr'));
		$('#reviewed_submissions_view_row').show();
		$('#reviewed_submissions_view').fadeIn(225);
	    getSubmissionDataAndDisplay(idSubmission);
    });
	$("#reviewed_submissions_closer").click(function(e){
		$('#reviewed_submissions_view').fadeOut(175);
	});
	$("#approve_review_btn").click(function(e){
		console.log("Hello");
		var id = $("#id-reviewed").attr('name')
		approveReview(id);
	});
	$("#trash_review_btn").click(function(e){
		//TODO
	});
/*
 * Get submissions checked for deletion
 */
function getCheckedSubmissions(chkboxName) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkedSubIDs = [];

  for (var i=0; i<checkboxes.length; i++) {
     if (checkboxes[i].checked) {
			 var id = checkboxes[i].id.replace(/\D/g,'');
       checkedSubIDs.push(id);
     }
  }
  // Return the array if it is non-empty, or null
  return checkedSubIDs.length > 0 ? checkedSubIDs : null;
}
	/*
	 * Listeners for listeners for trashing/untrashing a submission
	 */
	// on New Submissions page
	$("#trash_submission_new_cd").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_new_cd");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_new_mp3").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_new_mp3");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_new_other").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_new_other");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	// on Tag Accepted page
	$("#trash_submission_accepted_cd").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_accepted_cd");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_accepted_mp3").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_accepted_mp3");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_accepted_other").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_accepted_other");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	// on "Approve" page
	$("#trash_submission_tagged_cd").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_tagged_cd");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_tagged_mp3").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_tagged_mp3");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#trash_submission_tagged_other").click(function(e){
		var submissionIDs = getCheckedSubmissions("delete_submission_tagged_other");

		$.ajax({
			url: "api2/public/submissions/trash",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Trashed");
			}
		});
	});
	$("#undo_trash_submission").click(function(e){
		var submissionIDs = getCheckedSubmissions("restore_submission");

		$.ajax({
			url: "api2/public/submissions/restore",
			type:'PUT',
			dataType:'json',
			data: {
				'id':submissionIDs
			},
			async:true,
			success:function(data){
				console.log(data);
				alert("Submission Restored");
			}
		});
	});
	/*
	 * Listeners for submissions admin page - viewing past submissions
	 */
	$("#submitDates_Approved").click(function(e){
		var date1 = $("#from").val();
		var date2 = $("#to").val();
	  getAndPopulateAcceptedSubmissions(date1, date2);
	});
	$("#submitDates_Past").click(function(e){
		// TODO: get search variables
	  getAndPopulatePastSubmissions();
	});

	//CHANGING TABS Listener
	$('#tab-nav').off('click','.submission_action').on('click','.submission_action', function(e){
		$('.submission_action').attr('class','nodrop inactive-tab submission_action');
		$(this).attr('class','nodrop active-tab submission_action');
		$('.submission').hide();
		if($(this).attr('name') == 'search'){
			var search_value;
			$('.search_value').each(function(e){
				if($(this).css('display') != 'none'){
					search_value = $(this).val();
				}
			});
			//I'm sure this line does nothing?
			//TODO: determine if it does or not
			displayMemberList( getVal('search_by'), search_value || "", getVal('paid_status'), $('.year_select[name="search"]').val(), getVal('order_by'));
		}
		$('.submission#'+$(this).attr('name')).show();

	});
	//Toggling red bar for showing submissions you are going to delete
	//TODO - this is how the membership page did it
	$('.membership').off('change','.delete_member').on('change','.delete_member',function(e) {
		$(this.closest('tr')).toggleClass('delete');
	});
}

function populateTables(){
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/unreviewed/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateNewSubmissionsOther(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/reviewed/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateReviewedSubmissionsOther(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/cd",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/approved/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateToTagSubmissionsOther(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/cd",
		dataType:'json',
		async:true,
		success:function(data){
			populateTaggedSubmissionsCd(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/mp3",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTaggedSubmissionsMP3(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/tagged/other",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTaggedSubmissionsOther(data);
		}
	});
	$.ajax({
		type:"GET",
		url: "api2/public/submissions/bystatus/trashed",
		dataType:'json',
		async:true,
		success:function(data){
			//console.log(data);
			populateTrashedSubmissions(data);
		}
	});
}
//
function populateNewSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_cd\" id=\"delete" + item['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>";
				//console.log(markup);
			$("tbody[name='newSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateNewSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='newSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='newSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateReviewedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td></tr>";
			$("tbody[name='reviewedSubmissionCd']").append(markup);
		}
		var endrow = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionCd']").append(endrow);
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='reviewedSubmissionMP3']").append(markup);
		}
		var endrow = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionMP3']").append(endrow);
		add_submission_handlers();
	}
}
function populateReviewedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='reviewedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='reviewedSubmissionOther']").append(markup);
		}
		var endrow = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><button>Apply Approvals</button></td></tr>"
		$("tbody[name='reviewedSubmissionOther']").append(endrow);
		add_submission_handlers();
	}
}
//
function populateToTagSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateToTagSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='toTagSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='toTagSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
//
function populateTaggedSubmissionsCd(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionCd']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			//<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_tagged_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionCd']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsMP3(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionMP3']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_tagged_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionMP3']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTaggedSubmissionsOther(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
		$("tbody[name='taggedSubmissionOther']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['releasedate'] + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">"
				+ item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td><td><input type=\"checkbox\" class=\"delete_submission_tagged_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='taggedSubmissionOther']").append(markup);
		}
		add_submission_handlers();
	}
}
function populateTrashedSubmissions(submissions){
	if(submissions[0] == null){
		var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
		$("tbody[name='trashedSubmissions']").append(markup);
	} else{
		for(var number in submissions) {
			var item = (submissions[number]);
			var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"\"></td></td><td>" + item['reviewed']+ "</td><td>" + item['approved'] + "</td><td><input type=\"checkbox\" class=\"restore_submission\" id=\"restore" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
			$("tbody[name='trashedSubmissions']").append(markup);
		}
		add_submission_handlers();
	}
}
// on admins page, search past accepted submissions by date
function getAndPopulateAcceptedSubmissions(date1, date2){
	$.ajax({
		url: "api2/public/submissions/getaccepted",
		type:'GET',
		dataType:'json',
		data: {
			'date1':date1,
			'date2':date2
		},
    success: function(data) {
			if(data[0] == null){
				var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
				$("tbody[name='pastAcceptedSubmissions']").append(markup);
			} else{
				for(var number in data) {
					var item = (data[number]);
					var markup = "<tr class=\"playitem border\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td class=\"submission_row_element\">" + item['contact'] + "</td></tr>";
					$("tbody[name='pastAcceptedSubmissions']").append(markup);
				}
			}
    }
  });

// TODO: on admins page, search past submissions (accepted and rejected)
function getAndPopulatePastSubmissions(){

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
  } else {
    $("#releaseDate-review-box").attr('style', '');
  }
  if (credit == "" || credit == null) {
    credit = "No members submitted";
    $("#albumCredit-review-box").attr('style', 'color:navy');
  } else {
    $("#albumCredit-review-box").attr('style', '');
  }
  if (description == "" || description == null) {
    description = "No description submitted";
    $("#description-review-box").attr('style', 'color:navy');
  } else {
    $("#description-review-box").attr('style', '');
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
    $("#release-reviewed").attr('style', 'color:navy');
  } else {
    $("#release-reviewed").attr('style', '');
  }
  if (credit == "" || credit == null) {
    credit = "No members submitted";
    $("#credit-reviewed").attr('style', 'color:navy');
  } else {
    $("#credit-reviewed").attr('style', '');
  }
  if (description == "" || description == null) {
    description = "No description submitted";
    $("#description-reviewed").attr('style', 'color:navy');
  } else {
    $("#description-reviewed").attr('style', '');
  }

  if (review_comments == "" || review_comments == null) {
    review_comments = "No review submitted";
    $("#reviewed_comments").attr('style', 'color:red');
  } else {
    $("#reviewed_comments").attr('style', '');
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
	var format = data['format_id'];
	//TODO: determine if it's a bad format based on db table
	if(format > 8 || format < 1){
		console.log("Invalid format detected in tagging box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
		console.log("Setting format to \"Unknown\"");
		var format = 8;
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
	//console.log(format);
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
	var format = data['format_id'];
	//TODO: determine if it's a bad format based on db table
	if(format > 8 || format < 1){
		console.log("Invalid format detected in tagged box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
		console.log("Setting format to \"Unknown\"");
		var format = 8;
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
  canadaBox      = document.getElementById("canada-artist");
  vancouverBox   = document.getElementById("vancouver-artist");
  femArtistBox   = document.getElementById("female-artist");
  commentField   = document.getElementById("comments-box");
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
			$('#view_submissions').fadeOut(175);
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
	console.log(id);
	$.ajax({
		url: "api2/public/submissions/approve",
		type:'PUT',
		dataType:'text json',
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

function tagReview(tag, id, catNo, format, album, artist, credit, label, genre)
{
	console.log("Tagging review ... ");
  console.log(id);
  console.log(tag);
	$.ajax({
		url: "api2/public/submissions/tag",
		type:'PUT',
		dataType:'json',
		data: {
			'id':id,
      		'tags':tag,
      		'catalog':catNo,
      		'format_id':format,
      		'title':album,
      		'artist':artist,
      		'credit':credit,
      		'label':label,
      		'genre':genre
		},
		async:true,
		success:function(data){
			//console.log(data);
			alert("Submission tagged");
			$('#submissionspopup').fadeOut(175);
			var selector = "[name=\'" + id + "\']";
			$(selector).fadeOut(100);
			//TODO: Change the button and show a spinny thing
		},
		//commented out to avoid infinite loop
		fail:function(data){
			console.log("Submitting Review Failed. Response data: " + data);
			alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
		}
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
  var canada    = canadaBox.checked;
  var vancouver = vancouverBox.checked;
  var female    = femArtistBox.checked;
  var comments  = commentField.value;
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
