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
	 * Listener for box to do a review
	 */
	//Listener for viewing the review from clicking on their row
	$("tr.reviewrow").off('click').on('click',function(e){
		//Tab your code properly @michaeladria. I'll fix it for you this time
	    var idSubmission = $(this).attr('name');
		$("#view_submissions_row").insertAfter($(this).closest('tr'));
		$("#view_submissions_row").show();
		$('#view_submissions').fadeIn(225);
	    // console.log(idSubmission);
	    getSubmissionDataAndDisplay(idSubmission);
		$(this)[0].scrollIntoView({
    		behavior: "smooth", // or "auto" or "instant"
    		//block: "start" // or "end"
		});
    });
	//Prevent a row from opening a submission when clicking textarea or textbox
	$('.reviewrow input').off('click').on('click',function(e) {
    	e.stopPropagation();
	});
	$("#view_submissions_closer").off('click').on('click',function(e){
		$('#view_submissions').stop(true).fadeOut(175);
		$("#view_submissions_row").fadeOut(175);
	});
	//Listener for submitting a review
	$("#view_submissions_submit_btn").off('click').on('click',function(e){
		var id = $("#id-review-box").attr('name');
		var approvedStatus = $("#approved_status-review-box").val();
		var review_comments = $("#comments-review-box").val();
		submitReview(id, approvedStatus, review_comments);
        //console.log("Submitting review ... ");
	});
	/*
	 * Listeners for approving a review
	 */
	$("tr.reviewedrow").off('click').on('click',function(e){
		var idSubmission = $(this).attr('name');
		$('#reviewed_submissions_view_row').insertAfter($(this).closest('tr'));
		$('#reviewed_submissions_view_row').show();
		$('#reviewed_submissions_view').fadeIn(225);
	    getSubmissionDataAndDisplay(idSubmission);
		$(this)[0].scrollIntoView({
    		behavior: "smooth", // or "auto" or "instant"
    		//block: "start" // or "end"
		});
    });
	//Prevent a row from opening a review when clicking textarea or checkbox
	$('.reviewedrow input').off('click').on('click',function(e) {
    	e.stopPropagation();
	});
	$("#reviewed_submissions_closer").off('click').on('click',function(e){
		$('#reviewed_submissions_view').fadeOut(175);
	});
	$("#approve_review_btn").off('click').on('click',function(e){
		var id = $("#id-reviewed").attr('name')
		approveReview(id);
	});
	$("#trash_review_btn").off('click').on('click',function(e){
		//TODO
	});
	/*
	 * Listeners for tagging sidebar
	 */
	//Listener for viewing the tagging sidebar from clicking on their row
    $(".tagrow").off('click').on('click',function(e){
		$('#submissionspopup').fadeIn(225);
		var idSubmission = $(this).attr('name');
    $('#submissionspopup').attr('name', idSubmission);
		getSubmissionDataAndDisplay(idSubmission);
    });
	//Prevent clicking the checkboxes or textboxes from opening tag box
	$('.tagrow input').off('click').on('click',function(e) {
    	e.stopPropagation();
	});
	$("#submissionscloser").off('click').on('click',function(e){
		$('#submissionspopup').fadeOut(175);
    });
	$("#tagcancel").off('click').on('click',function(e){
		$('#submissionspopup').fadeOut(175);
    });
	$("#approved-extrainfo-button").off('click').on('click',function(e){
		$("#approved-extrainfo").toggle();
	});
    $("#approve-tags-button").off('click').on('click',function(e) {

      var tag    = $("#subgenre-approved").select2("val");
      var id     = $('#submissionspopup').attr('name');
      var catNo  = $('#catalog-approved').val();
      var format = $('#format-approved').val();
      var album  = $('#album-approved').val();
      var artist = $('#artist-approved').val();
      var credit = $('#credit-approved').val();
      var label  = $('#label-approved').val();
      var genre  = $('#genre-approved').select2("val");

      tagReview(tag, id, catNo, format, album, artist, credit, label, genre);
  	});

    $("#approve-album-button").off('click').on('click', function(e) {

      var id        = $("#submissionsapprovalpopup").attr('name');
      var catalog   = $("#catalog-tagged").val();
      var format_id = $("#format-tagged").select2("val");
      var album     = $("#album-tagged").val();
      var artist    = $("#artist-tagged").val();
      var credit    = $("#credit-tagged").val();
      var label     = $("#label-tagged").val();
      var genre     = $("#genre-tagged").select2("val");
      var tag       = $("#subgenre-tagged").select2("val");

      approveTags(tag, id, catalog, format_id, album, artist, credit, label, genre);
    });

	//Listener for preventing catalog # from being anything but a number
	$("#catalog-approved").off('keypress').on('keypress',function(e){
		var chr = String.fromCharCode(e.which);
    	if ("0123456789dig".indexOf(chr) < 0){
			e.preventDefault();
			return false;
		}
	});
	/*
	 * Listeners for approving tags popup
	 */
	$("tr.approverow").off('click').on('click',function(e){
		$('#submissionsapprovalpopup').fadeIn(225);
		var idSubmission = $(this).attr('name');
    $('#submissionsapprovalpopup').attr('name', idSubmission);
		getSubmissionDataAndDisplay(idSubmission);
    });
	//Prevent a row from opening a submission when clicking textarea or textbox
	$('.approverow input').off('click').on('click',function(e) {
    	e.stopPropagation();
	});
	$("#submissionsapprovalcloser").off('click').on('click',function(e){
		$('#submissionsapprovalpopup').fadeOut(175);
    });
	$("#approvecancel").off('click').on('click',function(e){
		$('#submissionsapprovalpopup').fadeOut(175);
    });
	$("#tagged-extrainfo-button").off('click').on('click',function(e){
		$("#tagged-extrainfo").toggle();
	});
	$("#approve_submission_btn").off('click').on('click',function(e){
		//TODO
	});
	//Listener for preventing catalog # from being anything but a number
	$("#catalog-tagged").keypress(function(e){
		var chr = String.fromCharCode(e.which);
    	if ("0123456789dig".indexOf(chr) < 0){
			e.preventDefault();
			return false;
		}
	});

	//CHANGING TABS Listener
	$('#tab-nav').off('click','.submission_action').on('click','.submission_action', function(e){
		$('.submission_action').attr('class','nodrop inactive-tab submission_action');
		$(this).attr('class','nodrop active-tab submission_action');
		$('.submission').hide();
        //Something to do with searching below - TODO
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
        switch( $(this).attr('name') ){
            case "new_submissions":
                populateNewSubmissionsTable();
                break;
            case "reviewed_submissions":
                populateReviewedSubmissionsTable();
				break;
            case "tag":
                populateApprovedSubmissionsTable();
                break;
            case "approve":
                populateTaggedSubmissionsTable();
                break;
            case "admin":
                populateTrashedSubmissionsTable();
                break;
            default:
                populateNewSubmissionsTable();
                break;
        }

	});
	//Toggling red bar for showing submissions you are going to delete
	//TODO - this is how the membership page did it
	$('.membership').off('change','.delete_member').on('change','.delete_member',function(e) {
		$(this.closest('tr')).toggleClass('delete');
	});
}
