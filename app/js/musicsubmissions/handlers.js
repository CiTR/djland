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
		var id = $("#id-reviewed").attr('name');
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
	$("#submissionsapprovalcancel").off('click').on('click',function(e){
		$('#submissionsapprovalpopup').fadeOut(175);
    });
	$("#tagged-extrainfo-button").off('click').on('click',function(e){
		$("#tagged-extrainfo").toggle();
	});
	$("#add_to_library_btn").off('click').on('click',function(e){
		//TODO
	});
	//Listener for preventing catalog # from being anything but a number
    //Removed for now - Andy didn't want it
	/*$("#catalog-tagged").keypress(function(e){
		var chr = String.fromCharCode(e.which);
    	if ("0123456789dig".indexOf(chr) < 0){
			e.preventDefault();
			return false;
		}
	});*/

    //search listener
    $('#newSubmissionSearch').off('input').on('input',function(e){
        $('#newSubmissionCdTable').DataTable().search( this.value ).draw();
        $('#newSubmissionMP3able').DataTable().search( this.value ).draw();
        $('#newSubmissionOtherTable').DataTable().search( this.value ).draw();
    });
    $('#reviewedSubmissionSearch').off('input').on('input',function(e){
        $('#reviewedSubmissionCdTable').DataTable().search( this.value ).draw();
        $('#reviewedSubmissionMP3able').DataTable().search( this.value ).draw();
        $('#reviewedSubmissionOtherTable').DataTable().search( this.value ).draw();
    });
    $('#toTagSubmissionSearch').off('input').on('input',function(e){
        $('#toTagSubmissionCdTable').DataTable().search( this.value ).draw();
        $('#toTagSubmissionMP3able').DataTable().search( this.value ).draw();
        $('#toTagSubmissionOtherTable').DataTable().search( this.value ).draw();
    });
    $('#taggedSubmissionSearch').off('input').on('input',function(e){
        $('#taggedSubmissionCdTable').DataTable().search( this.value ).draw();
        $('#taggedSubmissionMP3able').DataTable().search( this.value ).draw();
        $('#taggedSubmissionOtherTable').DataTable().search( this.value ).draw();
    });


	//CHANGING TABS Listener
	$('#tab-nav').off('click','.submission_action').on('click','.submission_action', function(e){
		$('.submission_action').attr('class','nodrop inactive-tab submission_action');
		$(this).attr('class','nodrop active-tab submission_action');
		$('.submission').hide();
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
	// and green bar for approving a review
	// Adds delete/approve classes to the row - use for applying bulk approvals/rejections
	$('.reviewrow').off('change','.delete_submission').on('change','.delete_submission',function(e) {
		$(this.closest('tr')).toggleClass('delete');
	});
	$('.reviewedrow').off('change','.delete_submission').on('change','.delete_submission',function(e) {
		if($(this).prop('checked') === true) {
			$(this.closest('tr')).addClass('delete');
			$(this.closest('tr')).removeClass('approve');
			$(this.closest('tr')).find('.approve_submission').prop('checked', false);
		} else {
			$(this.closest('tr')).removeClass('delete');
		}
	});
	$('.reviewedrow').off('change','.approve_submission').on('change','.approve_submission',function(e) {
		if($(this).prop('checked') === true) {
			$(this.closest('tr')).addClass('approve');
			$(this.closest('tr')).removeClass('remove');
			$(this.closest('tr')).find('.delete_submission').prop('checked', false);
		} else {
			$(this.closest('tr')).removeClass('approve');
		}
	});
	$('.tagrow').off('change','.delete_submission').on('change','.delete_submission',function(e) {
		$(this.closest('tr')).toggleClass('delete');
	});
	$('.approverow').off('change','.delete_submission').on('change','.delete_submission',function(e) {
		$(this.closest('tr')).toggleClass('delete');
	});
}
