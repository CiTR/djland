/********************
 ***** Handlers *****
 ********************/
function add_submission_handlers() {
    //Listener for saving comments
    $('.staff_comment').off('change').on('change', function (element) {
        saveComment($(this).attr('id'), $(this).val());
    });
    $('.memberList').off('change').on('change', function (element) {
        saveAssignee($(this).attr('id'), $(this).val());
    })
    /*
     * Listener for box to do a review
     */
    //Listener for viewing the review from clicking on their row
    $("tr.reviewrow").off('click').on('click', function (e) {
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
    $('.reviewrow input').off('click').on('click', function (e) {
        e.stopPropagation();
    });
    $("#view_submissions_closer").off('click').on('click', function (e) {
        //pause audio - this won't work with class selectors for whatever reason
        $('audio').each(function (i, e) {
            this.pause();
        });
        $('#view_submissions').fadeOut(175);
        $("#view_submissions_row").fadeOut(175);
    });
    //Listener for submitting a review
    $("#view_submissions_submit_btn").off('click').on('click', function (e) {
        var id = $("#id-review-box").attr('name');
        var approvedStatus = $("#approved_status-review-box").val();
        var review_comments = $("#comments-review-box").val();
        submitReview(id, approvedStatus, review_comments);
        //console.log("Submitting review ... ");
    });
    /*
     * Listeners for approving a review
     */
    $("tr.reviewedrow").off('click').on('click', function (e) {
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
    $('.reviewedrow input').off('click').on('click', function (e) {
        e.stopPropagation();
    });
    $("#reviewed_submissions_closer").off('click').on('click', function (e) {
        //pause audio - this won't wok with class selectors for whatever reason.
        $('audio').each(function (i, e) {
            this.pause();
        });
        $('#reviewed_submissions_view').fadeOut(175);
    });
    $("#approve_review_btn").off('click').on('click', function (e) {
        var id = $("#id-reviewed").attr('name');
        approveReview(id);
    });
    $("#trash_review_btn").off('click').on('click', function (e) {
        var id = $("#id-reviewed").attr('name');
        trashReview(id);
    });
    /*
     * Listeners for tagging sidebar
     */
    //Listener for viewing the tagging sidebar from clicking on their row
    $(".tagrow").off('click').on('click', function (e) {
        $('#submissionspopup').fadeIn(225);
        var idSubmission = $(this).attr('name');
        $('#submissionspopup').attr('name', idSubmission);
        getSubmissionDataAndDisplay(idSubmission);
    });
    //Prevent clicking the checkboxes or textboxes from opening tag box
    $('.tagrow input').off('click').on('click', function (e) {
        e.stopPropagation();
    });
    $("#submissionscloser").off('click').on('click', function (e) {
        $('#submissionspopup').fadeOut(175);
    });
    $("#tagcancel").off('click').on('click', function (e) {
        $('#submissionspopup').fadeOut(175);
    });
    $("#approved-extrainfo-button").off('click').on('click', function (e) {
        $("#approved-extrainfo").toggle();
    });
    $("#approve-tags-button").off('click').on('click', function (e) {

        var tag = $("#subgenre-approved").select2("val");
        var id = $('#submissionspopup').attr('name');
        var catNo = $('#catalog-approved').val();
        var format = $('#format-approved').val();
        var album = $('#album-approved').val();
        var artist = $('#artist-approved').val();
        var credit = $('#credit-approved').val();
        var label = $('#label-approved').val();
        var genre = $('#genre-approved').select2("val");
        var cancon = +$("#cancon-approved").is(':checked');
        var femcon = +$("#femcon-approved").is(':checked');
        var local = +$("#local-approved").is(':checked');
        var compilation = +$("#compilation-approved").is(':checked');
        var in_sam = +$("#in_sam-approved").is(':checked');
        var playlist = +$("#playlist-approved").is(':checked');
        //console.log(cancon, femcon, local, compilation, in_sam, playlist);

        tagReview(tag, id, catNo, format, album, artist, credit, label, genre,
            cancon, femcon, local, compilation, in_sam, playlist);
    });

    $("#approve-album-button").off('click').on('click', function (e) {

        var submission_id = $("#submissionsapprovalpopup").attr('name');
        var catalog = $("#catalog-tagged").val();
        var format_id = $("#format-tagged").select2("val");
        var album = $("#album-tagged").val();
        var artist = $("#artist-tagged").val();
        var credit = $("#credit-tagged").val();
        var label = $("#label-tagged").val();
        var genre = $("#genre-tagged").select2("val");
        var tag = $("#subgenre-tagged").select2("val");
        var cancon = +$("#cancon-tagged").is(':checked');
        var femcon = +$("#femcon-tagged").is(':checked');
        var local = +$("#local-tagged").is(':checked');
        var compilation = +$("#compilation-tagged").is(':checked');
        var in_sam = +$("#in_sam-tagged").is(':checked');
        var playlist = +$("#playlist-tagged").is(':checked');
        var art_url = $('#albumArt-tagged').attr('src');
        approveTags(tag, submission_id, catalog, format_id, album, artist, credit,
            label, genre, cancon, femcon, local, compilation, in_sam,
            playlist, art_url);
    });

    //DISABLED BECAUSE ANDY SAID SO
    //Listener for preventing catalog # from being anything but a number
    //$("#catalog-approved").off('keypress').on('keypress', function (e) {
    //    var chr = String.fromCharCode(e.which);
    //    if ("0123456789dig".indexOf(chr) < 0) {
    //        e.preventDefault();
    //        return false;
    //    }
    //});
    /*
     * Listeners for approving tags popup
     */
    $("tr.approverow").off('click').on('click', function (e) {
        $('#submissionsapprovalpopup').fadeIn(225);
        var idSubmission = $(this).attr('name');
        $('#submissionsapprovalpopup').attr('name', idSubmission);
        getSubmissionDataAndDisplay(idSubmission);
    });
    //Prevent a row from opening a submission when clicking textarea or textbox
    $('.approverow input').off('click').on('click', function (e) {
        e.stopPropagation();
    });
    $("#submissionsapprovalcloser").off('click').on('click', function (e) {
        $('#submissionsapprovalpopup').fadeOut(175);
    });
    $("#submissionsapprovalcancel").off('click').on('click', function (e) {
        $('#submissionsapprovalpopup').fadeOut(175);
    });
    $("#tagged-extrainfo-button").off('click').on('click', function (e) {
        $("#tagged-extrainfo").toggle();
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
    $('#newSubmissionSearch').off('input').on('input', function (e) {
        $('#newSubmissionCdTable').DataTable().search(this.value).draw();
        $('#newSubmissionMP3Table').DataTable().search(this.value).draw();
        $('#newSubmissionOtherTable').DataTable().search(this.value).draw();
    });
    $('#reviewedSubmissionSearch').off('input').on('input', function (e) {
        $('#reviewedSubmissionCdTable').DataTable().search(this.value).draw();
        $('#reviewedSubmissionMP3Table').DataTable().search(this.value).draw();
        $('#reviewedSubmissionOtherTable').DataTable().search(this.value).draw();
    });
    $('#toTagSubmissionSearch').off('input').on('input', function (e) {
        $('#toTagSubmissionCdTable').DataTable().search(this.value).draw();
        $('#toTagSubmissionMP3Table').DataTable().search(this.value).draw();
        $('#toTagSubmissionOtherTable').DataTable().search(this.value).draw();
    });
    $('#taggedSubmissionSearch').off('input').on('input', function (e) {
        $('#taggedSubmissionCdTable').DataTable().search(this.value).draw();
        $('#taggedSubmissionMP3Table').DataTable().search(this.value).draw();
        $('#taggedSubmissionOtherTable').DataTable().search(this.value).draw();
    });

    //Order by listeners
    $('#new_submissions_order_by').off('change').on('change', function () {
        switch (this.value) {
        case 'submissionDate':
            sortNum = 4;
            break;
        case 'releaseDate':
            sortNum = 2;
            break;
        case 'artist':
            sortNum = 0;
            break;
        case 'album':
            sortNum = 1;
            break;
        case 'genre':
            sortNum = 3;
            break;
        case 'assignee':
            sortNum = 6;
            break;
        default:
            sortNum = -1;
        }
        $('#newSubmissionCdTable').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#newSubmissionMP3Table').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#newSubmissionOtherTable').DataTable().column(sortNum + ':visible').order('asc').draw();
    });
    $('#reviewed_submissions_order_by').off('change').on('change', function () {
        switch (this.value) {
        case 'artist':
            sortNum = 0;
            break;
        case 'album':
            sortNum = 1;
            break;
        case 'submissionDate':
            sortNum = 2;
            break;
        case 'reviewedBy':
            sortNum = 4;
            break;
        case 'approvalStatus':
            sortNum = 5;
            break;
        default:
            sortNum = 0;
        }
        $('#reviewedSubmissionCdTable').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#reviewedSubmissionMP3Table').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#reviewedSubmissionOtherTable').DataTable().column(sortNum + ':visible').order('asc').draw();
    });
    $('#toTag_submissions_order_by').off('change').on('change', function () {
        switch (this.value) {
        case 'submissionDate':
            sortNum = 4;
            break;
        case 'releaseDate':
            sortNum = 2;
            break;
        case 'artist':
            sortNum = 0;
            break;
        case 'album':
            sortNum = 1;
            break;
        case 'genre':
            sortNum = 3;
            break;
        case 'assignee':
            sortNum = 6;
            break;
        default:
            sortNum = -1;
        }
        $('#toTagSubmissionCdTable').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#toTagSubmissionMP3Table').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#toTagSubmissionOtherTable').DataTable().column(sortNum + ':visible').order('asc').draw();
    });
    $('#tagged_submissions_order_by').off('change').on('change', function () {
        switch (this.value) {
        case 'submissionDate':
            sortNum = 4;
            break;
        case 'releaseDate':
            sortNum = 2;
            break;
        case 'artist':
            sortNum = 0;
            break;
        case 'album':
            sortNum = 1;
            break;
        case 'genre':
            sortNum = 3;
            break;
        case 'assignee':
            sortNum = 6;
            break;
        default:
            sortNum = -1;
        }
        $('#taggedSubmissionCdTable').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#taggedSubmissionMP3Table').DataTable().column(sortNum + ':visible').order('asc').draw();
        $('#taggedSubmissionOtherTable').DataTable().column(sortNum + ':visible').order('asc').draw();
    });
    //CHANGING TABS Listener
    $('#tab-nav').off('click', '.submission_action').on('click', '.submission_action', function (e) {
        $('.submission_action').attr('class', 'nodrop inactive-tab submission_action');
        $(this).attr('class', 'nodrop active-tab submission_action');
        $('.submission').hide();
        $('.submission#' + $(this).attr('name')).show();
        switch ($(this).attr('name')) {
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
    $('.reviewrow').off('change', '.delete_submission_new_cd').on('change', '.delete_submission_new_cd', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.reviewrow').off('change', '.delete_submission_new_mp3').on('change', '.delete_submission_new_mp3', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.reviewrow').off('change', '.delete_submission_new_other').on('change', '.delete_submission_new_other', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.reviewedrow').off('change', '.delete_submission_reviewed_cd').on('change', '.delete_submission_reviewed_cd', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('delete');
            $(this.closest('tr')).removeClass('approve');
            $(this.closest('tr')).find('.approve_submission_cd').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('delete');
        }
    });
    $('.reviewedrow').off('change', '.approve_submission_cd').on('change', '.approve_submission_cd', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('approve');
            $(this.closest('tr')).removeClass('remove');
            $(this.closest('tr')).find('.delete_submission_reviewed_cd').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('approve');
        }
    });
    $('.reviewedrow').off('change', '.delete_submission_reviewed_mp3').on('change', '.delete_submission_reviewed_mp3', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('delete');
            $(this.closest('tr')).removeClass('approve');
            $(this.closest('tr')).find('.approve_submission_mp3').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('delete');
        }
    });
    $('.reviewedrow').off('change', '.approve_submission_mp3').on('change', '.approve_submission_mp3', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('approve');
            $(this.closest('tr')).removeClass('remove');
            $(this.closest('tr')).find('.delete_submission_reviewed_mp3').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('approve');
        }
    });
    $('.reviewedrow').off('change', '.delete_submission_reviewed_other').on('change', '.delete_submission_reviewed_other', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('delete');
            $(this.closest('tr')).removeClass('approve');
            $(this.closest('tr')).find('.approve_submission_other').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('delete');
        }
    });
    $('.reviewedrow').off('change', '.approve_submission_other').on('change', '.approve_submission_other', function (e) {
        if ($(this).prop('checked') === true) {
            $(this.closest('tr')).addClass('approve');
            $(this.closest('tr')).removeClass('remove');
            $(this.closest('tr')).find('.delete_submission_reviewed_other').prop('checked', false);
        } else {
            $(this.closest('tr')).removeClass('approve');
        }
    });
    $('.tagrow').off('change', '.delete_submission_accepted_cd').on('change', '.delete_submission_accepted_cd', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.tagrow').off('change', '.delete_submission_accepted_mp3').on('change', '.delete_submission_accepted_mp3', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.tagrow').off('change', '.delete_submission_accepted_other').on('change', '.delete_submission_accepted_other', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.approverow').off('change', '.delete_submission_tagged_cd').on('change', '.delete_submission_tagged_cd', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.approverow').off('change', '.delete_submission_tagged_mp3').on('change', '.delete_submission_tagged_mp3', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.approverow').off('change', '.delete_submission_tagged_other').on('change', '.delete_submission_tagged_other', function (e) {
        $(this.closest('tr')).toggleClass('delete');
    });
    $('.restore_submission').off('change').on('change', function (e) {
        $(this.closest('tr')).toggleClass('approve');
    });
}
