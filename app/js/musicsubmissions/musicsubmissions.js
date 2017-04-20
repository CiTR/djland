//Created by Scott Pidzarko based off of work on membership.js, which was done by Evan Friday
window.myNameSpace = window.myNameSpace || {};

//PAGE CREATION
$(document).ready(function () {
    // Set lightbox options
    lightbox.option({
        'fadeDuration': 200,
        'imageFadeDuration': 200,
        'resizeDuration': 200,
        'wrapAround': true,
        'disableScrolling': true
    });
    //set the datepicker date format
    $(function () {
        $("#date-released").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    $(function () {
        $("#past-from").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    $(function () {
        $("#past-to").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    $(function () {
        $("#new-from").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    $(function () {
        $("#new-to").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    //Initialize sorting
    var newSubmissionsMinDateFilter = '';
    $("#new-submissions-from").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            newSubmissionsMinDateFilter = new Date($(this).val()).getTime();
            $("#newSubmissionCdTable").DataTable().draw();
            $("#newSubmissionMP3Table").DataTable().draw();
            $("#newSubmissionOtherTable").DataTable().draw();
        }
    });
    var newSubmissionsMaxDateFilter;
    $("#new-submissions-to").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            var newSubmissionsMaxDateFilter = new Date($(this).val()).getTime();
            $("#newSubmissionCdTable").DataTable().draw();
            $("#newSubmissionMP3Table").DataTable().draw();
            $("#newSubmissionOtherTable").DataTable().draw();
        }
    });
    var reviewedSubmissionsMinDateFilter = '';
    $("#reviewed-submissions-from").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            reviewedSubmissionsMinDateFilter = new Date($(this).val()).getTime();
            $("#reviewedSubmissionCdTable").DataTable().draw();
            $("#reviewedSubmissionMP3Table").DataTable().draw();
            $("#reviewedSubmissionOtherTable").DataTable().draw();
        }
    });
    var reviewedSubmissionsMaxDateFilter;
    $("#reviewed-submissions-to").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            var reviewedSubmissionsMaxDateFilter = new Date($(this).val()).getTime();
            $("#reviewedSubmissionCdTable").DataTable().draw();
            $("#reviewedSubmissionMP3Table").DataTable().draw();
            $("#reviewedSubmissionOtherTable").DataTable().draw();
        }
    });
    var toTagSubmissionsMinDateFilter = '';
    $("#toTag-submissions-from").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            toTagSubmissionsMinDateFilter = new Date($(this).val()).getTime();
            $("#toTagSubmissionCdTable").DataTable().draw();
            $("#toTagSubmissionMP3Table").DataTable().draw();
            $("#toTagSubmissionOtherTable").DataTable().draw();
        }
    });
    var toTagSubmissionsMaxDateFilter;
    $("#toTag-submissions-to").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            var toTagSubmissionsMaxDateFilter = new Date($(this).val()).getTime();
            $("#toTagSubmissionCdTable").DataTable().draw();
            $("#toTagSubmissionMP3Table").DataTable().draw();
            $("#toTagSubmissionOtherTable").DataTable().draw();
        }
    });
    var taggedSubmissionsMinDateFilter = '';
    $("#tagged-submissions-from").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            taggedSubmissionsMinDateFilter = new Date($(this).val()).getTime();
            $("#taggedSubmissionCdTable").DataTable().draw();
            $("#taggedSubmissionMP3Table").DataTable().draw();
            $("#taggedSubmissionOtherTable").DataTable().draw();
        }
    });
    var taggedSubmissionsMaxDateFilter;
    $("#tagged-submissions-to").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText, inst) {
            var taggedSubmissionsMaxDateFilter = new Date($(this).val()).getTime();
            $("#taggedSubmissionCdTable").DataTable().draw();
            $("#taggedSubmissionMP3Table").DataTable().draw();
            $("#taggedSubmissionOtherTable").DataTable().draw();
        }
    });
    //Date filtering listener
    $.fn.dataTableExt.afnFiltering.push(
        function (oSettings, aData, iDataIndex) {
            if (oSettings.sTableId == "newSubmissionCdTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (newSubmissionsMinDateFilter && !isNaN(newSubmissionsMinDateFilter)) {
                    if (aData._date < newSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (newSubmissionsMaxDateFilter && !isNaN(newSubmissionsMaxDateFilter)) {
                    if (aData._date > newSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "newSubmissionMP3Table") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (newSubmissionsMinDateFilter && !isNaN(newSubmissionsMinDateFilter)) {
                    console.log(newSubmissionsMinDateFilter);
                    if (aData._date < newSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (newSubmissionsMaxDateFilter && !isNaN(newSubmissionsMaxDateFilter)) {
                    if (aData._date > newSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "newSubmissionOtherTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (newSubmissionsMinDateFilter && !isNaN(newSubmissionsMinDateFilter)) {
                    if (aData._date < newSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (newSubmissionsMaxDateFilter && !isNaN(newSubmissionsMaxDateFilter)) {
                    if (aData._date > newSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "reviewedSubmissionCdTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (reviewedSubmissionsMinDateFilter && !isNaN(reviewedSubmissionsMinDateFilter)) {
                    if (aData._date < reviewedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (reviewedSubmissionsMaxDateFilter && !isNaN(reviewedSubmissionsMaxDateFilter)) {
                    if (aData._date > reviewedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "reviewedSubmissionMP3Table") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (reviewedSubmissionsMinDateFilter && !isNaN(reviewedSubmissionsMinDateFilter)) {
                    console.log(reviewedSubmissionsMinDateFilter);
                    if (aData._date < reviewedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (reviewedSubmissionsMaxDateFilter && !isNaN(reviewedSubmissionsMaxDateFilter)) {
                    if (aData._date > reviewedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "reviewedSubmissionOtherTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (reviewedSubmissionsMinDateFilter && !isNaN(reviewedSubmissionsMinDateFilter)) {
                    if (aData._date < reviewedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (reviewedSubmissionsMaxDateFilter && !isNaN(reviewedSubmissionsMaxDateFilter)) {
                    if (aData._date > reviewedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "toTagSubmissionCdTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (toTagSubmissionsMinDateFilter && !isNaN(toTagSubmissionsMinDateFilter)) {
                    if (aData._date < toTagSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (toTagSubmissionsMaxDateFilter && !isNaN(toTagSubmissionsMaxDateFilter)) {
                    if (aData._date > toTagSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "toTagSubmissionMP3Table") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (toTagSubmissionsMinDateFilter && !isNaN(toTagSubmissionsMinDateFilter)) {
                    console.log(toTagSubmissionsMinDateFilter);
                    if (aData._date < toTagSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (toTagSubmissionsMaxDateFilter && !isNaN(toTagSubmissionsMaxDateFilter)) {
                    if (aData._date > toTagSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "toTagSubmissionOtherTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (toTagSubmissionsMinDateFilter && !isNaN(toTagSubmissionsMinDateFilter)) {
                    if (aData._date < toTagSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (toTagSubmissionsMaxDateFilter && !isNaN(toTagSubmissionsMaxDateFilter)) {
                    if (aData._date > toTagSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "taggedSubmissionCdTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (taggedSubmissionsMinDateFilter && !isNaN(taggedSubmissionsMinDateFilter)) {
                    if (aData._date < taggedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (taggedSubmissionsMaxDateFilter && !isNaN(taggedSubmissionsMaxDateFilter)) {
                    if (aData._date > taggedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "taggedSubmissionMP3Table") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (taggedSubmissionsMinDateFilter && !isNaN(taggedSubmissionsMinDateFilter)) {
                    console.log(taggedSubmissionsMinDateFilter);
                    if (aData._date < taggedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (taggedSubmissionsMaxDateFilter && !isNaN(taggedSubmissionsMaxDateFilter)) {
                    if (aData._date > taggedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            if (oSettings.sTableId == "taggedSubmissionOtherTable") {
                if (typeof aData._date == 'undefined') {
                    aData._date = new Date(aData[4]).getTime();
                }
                if (taggedSubmissionsMinDateFilter && !isNaN(taggedSubmissionsMinDateFilter)) {
                    if (aData._date < taggedSubmissionsMinDateFilter) {
                        return false;
                    }
                }
                if (taggedSubmissionsMaxDateFilter && !isNaN(taggedSubmissionsMaxDateFilter)) {
                    if (aData._date > taggedSubmissionsMaxDateFilter) {
                        return false;
                    }
                }
                return true;
            }
            //Display info by default
            return true;
        }
    );
    //Initialize selec2 boxes:
    $(".vueselect").select2();

    $("body").keydown(function (e) {
        //check if a select dropdown is open, if not close the things
        if (e.keyCode == 27) { // escape key maps to keycode `27`
            $('#submissionspopup').fadeOut(175);
            $('#submissionsapprovalpopup').fadeOut(175);
            $('#view_submissions').stop().fadeOut(175);
            $("#view_submissions_row").fadeOut(175);
            $('#reviewed_submissions_view').fadeOut(175);
            $('#reviewed_submissions_view_row').fadeOut(175);
        }
    });

    $("#pastAcceptedAndRejectedSubmissionsTable").DataTable();
    $.when(constants_request).then(function () {
        add_submission_handlers();
    });
    populateNewSubmissionsTable();
});
/*
 * Get submissions checked for deletion
 */
function getCheckedSubmissions(chkboxName) {
    var checkboxes = document.getElementsByClassName(chkboxName);
    var checkedSubIDs = [];

    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            var id = checkboxes[i].id.replace(/\D/g, '');
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
function trash_submission_new_cd() {
    var submissionIDs = getCheckedSubmissions("delete_submission_new_cd");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateNewSubmissionsTable();
}

function trash_submission_new_mp3() {
    var submissionIDs = getCheckedSubmissions("delete_submission_new_mp3");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateNewSubmissionsTable();
}

function trash_submission_new_other() {
    var submissionIDs = getCheckedSubmissions("delete_submission_new_other");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateNewSubmissionsTable();
}

function approve_submission_reviewed_cd() {
    var discardSubmissionIDs = getCheckedSubmissions("delete_submission_reviewed_cd");
    var approveSubmissionIDs = getCheckedSubmissions("approve_submission_cd");
    if (discardSubmissionIDs != null) {
        for (var i = 0; i < discardSubmissionIDs.length; i++) {
            $.ajax({
                url: "api2/public/submissions/trash",
                type: 'PUT',
                dataType: 'json',
                data: {
                    'id': discardSubmissionIDs[i]
                },
                async: true,
                success: function (data) {
                    console.log(data);
                },
                fail: function (data) {
                    console.log("Deleting the submission failed. Response data: " + data);
                    alert("Error: Submission was not deleted");
                }
            });
        }
    }
    if (approveSubmissionIDs != null) {
        for (var j = 0; j < approveSubmissionIDs.length; j++) {
            approveReview(approveSubmissionIDs[j]);
        }
    }
    // update the tables
    populateReviewedSubmissionsTable();
}

function approve_submission_reviewed_mp3() {
    var discardSubmissionIDs = getCheckedSubmissions("delete_submission_reviewed_mp3");
    var approveSubmissionIDs = getCheckedSubmissions("approve_submission_mp3");
    if (discardSubmissionIDs != null) {
        for (var i = 0; i < discardSubmissionIDs.length; i++) {
            $.ajax({
                url: "api2/public/submissions/trash",
                type: 'PUT',
                dataType: 'json',
                data: {
                    'id': discardSubmissionIDs[i]
                },
                async: true,
                success: function (data) {
                    console.log(data);
                },
                fail: function (data) {
                    console.log("Deleting the submission failed. Response data: " + data);
                    alert("Error: Submission was not deleted");
                }
            });
        }
    }
    if (approveSubmissionIDs != null) {
        for (var j = 0; j < approveSubmissionIDs.length; j++) {
            approveReview(approveSubmissionIDs[j]);
        }
    }
    // update the tables
    populateReviewedSubmissionsTable();
}

function approve_submission_reviewed_other() {
    var discardSubmissionIDs = getCheckedSubmissions("delete_submission_reviewed_other");
    var approveSubmissionIDs = getCheckedSubmissions("approve_submission_other");
    if (discardSubmissionIDs != null) {
        for (var i = 0; i < discardSubmissionIDs.length; i++) {
            $.ajax({
                url: "api2/public/submissions/trash",
                type: 'PUT',
                dataType: 'json',
                data: {
                    'id': discardSubmissionIDs[i]
                },
                async: true,
                success: function (data) {
                    console.log(data);
                },
                fail: function (data) {
                    console.log("Deleting the submission failed. Response data: " + data);
                    alert("Error: Submission was not deleted");
                }
            });
        }
    }
    if (approveSubmissionIDs != null) {
        for (var j = 0; j < approveSubmissionIDs.length; j++) {
            approveReview(approveSubmissionIDs[j]);
        }
    }
    // update the tables
    populateReviewedSubmissionsTable();
}

// on Tag Accepted page
function trash_submission_accepted_cd() {
    var submissionIDs = getCheckedSubmissions("delete_submission_accepted_cd");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateApprovedSubmissionsTable();
}

function trash_submission_accepted_mp3() {
    var submissionIDs = getCheckedSubmissions("delete_submission_accepted_mp3");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateApprovedSubmissionsTable();
}

function trash_submission_accepted_other() {
    var submissionIDs = getCheckedSubmissions("delete_submission_accepted_other");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateApprovedSubmissionsTable();
}
// on "Approve" page
function trash_submission_tagged_cd() {
    var submissionIDs = getCheckedSubmissions("delete_submission_tagged_cd");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateTaggedSubmissionsTable();
}

function trash_submission_tagged_mp3() {
    var submissionIDs = getCheckedSubmissions("delete_submission_tagged_mp3");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateTaggedSubmissionsTable();
}

function trash_submission_tagged_other() {
    var submissionIDs = getCheckedSubmissions("delete_submission_tagged_other");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/trash",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
            },
            fail: function (data) {
                console.log("Deleting the submission failed. Response data: " + data);
                alert("Error: Submission was not deleted");
            }
        });
    }
    // update the tables
    populateTaggedSubmissionsTable();
}

function undo_trash_submission() {
    var submissionIDs = getCheckedSubmissions("restore_submission");
    for (var i = 0; i < submissionIDs.length; i++) {
        $.ajax({
            url: "api2/public/submissions/restore",
            type: 'PUT',
            dataType: 'json',
            data: {
                'id': submissionIDs[i]
            },
            async: true,
            success: function (data) {
                console.log(data);
                alert("Submission Restored");
            },
            fail: function (data) {
                console.log("Restoring the submission failed. Response data: " + data);
                alert("Error: Submission was not restored");
            }
        });
    }
    populateTrashedSubmissionsTable();
}

/*
 * Listeners for submissions admin page - viewing past submissions
 */
function SubmitDates_Approved() {
    var date1 = $("#new-from").val();
    var date2 = $("#new-to").val();

    if (date1 == null || date2 == null) {
        alert("Please enter a start date and an end date");
    } else if (date1 > date2) {
        alert("Start date must be earlier than end date");
    } else {
        getAndPopulateAcceptedSubmissions(date1, date2);
    }
}

function SubmitDates_Past() {
    var date1 = $("#past-from").val();
    var date2 = $("#past-to").val();
    var artist = $("#past-artist").val();
    var album = $("#past-album").val();

    if (date1 == null || date2 == null || date1 == '' || date2 == '') {
        alert("Please enter a start date and an end date");
    } else if (date1 > date2) {
        alert("Start date must be earlier than end date");
    } else {
        getAndPopulatePastSubmissions(date1, date2, album, artist);
    }
}


// on admins page, search past accepted submissions by date
function getAndPopulateAcceptedSubmissions(date1, date2) {
    $.ajax({
        url: "api2/public/submissions/bystatus/accepted",
        type: 'GET',
        dataType: 'json',
        data: {
            'date1': date1,
            'date2': date2
        },
        async: true,
        success: function (data) {
            //clear out any rows already in the table
            $("tbody[name='pastAcceptedSubmissions']").empty();
            var header = "<tr id=\"headerrow\" style=\"display: table-row;\"><th>Artist</th><th>Album</th><th>Date of Submission</th><th>Cancon</th><th>Femcon</th><th>Local</th><th>Contact Info</th></tr>";
            $("tbody[name='pastAcceptedSubmissions']").append(header);

            if (data[0] == null) {
                var markup = "<tr class=\"playitem border\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
                $("tbody[name='pastAcceptedSubmissions']").append(markup);
            } else {
                for (var number in data) {
                    var item = (data[number]);

                    var cancon;
                    if (item['cancon'] == 1)
                        cancon = "yes";
                    else
                        cancon = "no";

                    var femcon;
                    if (item['femcon'] == 1)
                        femcon = "yes";
                    else
                        femcon = "no";

                    var local;
                    if (item['local'] == 1)
                        local = "yes";
                    else
                        local = "no";

                    var markup = "<tr class=\"playitem border\" name=\"" + item['id'] + "\"  align=\"center\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td class=\"submission_row_element\"> " + cancon + " </td><td class=\"submission_row_element\"> " + femcon + " </td><td class=\"submission_row_element\"> " + local + " </td><td class=\"submission_row_element\">" + item['contact'] + "</td></tr>";
                    $("tbody[name='pastAcceptedSubmissions']").append(markup);
                }
            }
        },
        fail: function (data) {
            console.log("Getting archived submissions failed. Response data: " + data);
        }
    });
}

// on admins page, search past submissions (accepted and rejected)
function getAndPopulatePastSubmissions(date1, date2, album, artist) {
    //clear out any rows already in the table
    $("tbody[name='pastAcceptedAndRejectedSubmissions']").empty();

    $.ajax({
        url: "api2/public/submissions/bystatus/archived",
        type: 'GET',
        dataType: 'json',
        data: {
            'date1': date1,
            'date2': date2,
            'album': album,
            'artist': artist
        },
        async: true,
        success: function (data) {
            $("#pastAcceptedAndRejectedSubmissionsTable").DataTable().clear();
            $("#pastAcceptedAndRejectedSubmissionsTable").DataTable().destroy();
            if (data[0] != null) {
                for (var number in data) {
                    var item = (data[number]);

                    var cancon;
                    if (item['cancon'] == 1)
                        cancon = "yes";
                    else
                        cancon = "no";

                    var femcon;
                    if (item['femcon'] == 1)
                        femcon = "yes";
                    else
                        femcon = "no";

                    var local;
                    if (item['local'] == 1)
                        local = "yes";
                    else
                        local = "no";

                    var markup = "<tr class=\"playitem border\" name=\"";
                    markup += item['id'] + "\"  align=\"center\">";
                    markup += "<td class=\"submission_row_element\"> ";
                    markup += item['artist'];
                    markup += " </td><td class=\"submission_row_element\">"
                    markup += item['title'];
                    markup += "</td><td class=\"submission_row_element\">";
                    markup += item['submitted'];
                    markup += "</td><td class=\"submission_row_element\"> ";
                    markup += cancon + " </td><td class=\"submission_row_element\"> ";
                    markup += femcon + " </td><td class=\"submission_row_element\"> ";
                    markup += local + " </td><td class=\"submission_row_element\">";
                    markup += item['contact'];
                    markup += "</td><td class=\"submission_row_element\">Yes</td></tr>";
                    $("tbody[name='pastAcceptedAndRejectedSubmissions']").append(markup);
                }
            }
            if (!($.fn.dataTable.isDataTable("#pastAcceptedAndRejectedSubmissionsTable"))) {
                $("#pastAcceptedAndRejectedSubmissionsTable").DataTable({
                    stateSave: true
                });
            }
        },
        fail: function (data) {
            console.log("Getting past archived submissions failed. Response data: " + data);
        }
    });

    $.ajax({
        url: "api2/public/submissions/bystatus/rejected",
        type: 'GET',
        dataType: 'json',
        data: {
            'date1': date1,
            'date2': date2,
            'album': album,
            'artist': artist
        },
        async: true,
        success: function (data) {
            if (data[0] != null) {
                for (var number in data) {
                    var item = (data[number]);

                    var cancon;
                    if (item['cancon'] == 1)
                        cancon = "yes";
                    else
                        cancon = "no";

                    var femcon;
                    if (item['femcon'] == 1)
                        femcon = "yes";
                    else
                        femcon = "no";

                    var local;
                    if (item['local'] == 1)
                        local = "yes";
                    else
                        local = "no";

                    var markup = "<tr class=\"playitem border\" name=\"";
                    markup += item['id'];
                    markup += "\" align=\"center\"><td class=\"submission_row_element\">";
                    markup += item['artist'];
                    markup += " </td><td class=\"submission_row_element\">";
                    markup += item['title'];
                    markup += "</td><td class=\"submission_row_element\">";
                    markup += item['submitted'];
                    markup += "</td><td class=\"submission_row_element\"> ";
                    markup += cancon + " </td><td class=\"submission_row_element\"> ";
                    markup += femcon + " </td><td class=\"submission_row_element\"> ";
                    markup += local + " </td><td class=\"submission_row_element\">";
                    markup += item['contact'] + "</td><td class=\"submission_row_element\">No</td></tr>";
                    $("tbody[name='pastAcceptedAndRejectedSubmissions']").append(markup);
                }
            }
        },
        fail: function (data) {
            console.log("Getting past rejected submissions failed. Response data: " + data);
        }
    });
}

// Getting data for a specific submission given the ID and call the right function to display it.
function getSubmissionDataAndDisplay(id) {
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/" + id,
        dataType: "json",
        async: true,
        success: function (data) {
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
    var id = data['id'];
    var artist = data['artist'];
    var location = data['location'];
    var album = data['title'];
    var label = data['label'];
    var genre = data['genre'];
    var tags = data['tags'];
    var releasedate = data['releasedate'];
    var submitted = data['submitted'];
    var credit = data['credit'];
    var email = data['email'];
    var description = data['description'];
    var art_url = data['art_url'];
    var songs = data['songs']

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
    $("#albumArt-review-box-a").attr('href', art_url);
    $("#comments-review-box").text("");
    $("#approved_status-review-box").val(0).change();
    tracks = ""
    for (index in songs) {
        song = songs[index];
        tracks = tracks + `

      <div class="containerrow padded">
          <div class="col1"></div>
          <div class="col4">Track ` + song['track_num'] + `: ` + song['song_title'] + `</div>
          <div class="col3"><audio controls><source src="` + song['file_location'] + `" type="audio/mpeg"></audio></div>
      </div>
      <div class="containerrow padded">
          <div class="col6">Track Artist: </div>
          <div class="col6">` + song['artist'] + `</div>
          <div class="col6">Track Credit: </div>
          <div class="col6">` + song['credit'] + `</div>
      </div>

      `
    }
    $("#tracks-review-box").html(tracks)
}

function displayReviewedBox(data) {

    var id = data['id'];
    var artist = data['artist'];
    var location = data['location'];
    var album = data['title'];
    var label = data['label'];
    var genre = data['genre'];
    var tags = data['tags'];
    var releasedate = data['releasedate'];
    var submitted = data['submitted'];
    var credit = data['credit'];
    var email = data['email'];
    var description = data['description'];
    var art_url = data['art_url'];
    var review_comments = data['review_comments'];
    var approved = data['approved'];
    var songs = data['songs'];

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
    $("#albumArt-reviewed-a").attr("href", art_url);
    $("#reviewed_comments").text(review_comments);

    if (approved) $("#reviewed_approved_status").val(1).change();
    else $("#reviewed_approved_status").val(0).change();

    tracks = ""
    for (index in songs) {
        song = songs[index]

        tracks = tracks + `

      <div class="containerrow padded">
          <div class="col1"></div>
          <div class="col4">Track ` + song['track_num'] + `: ` + song['song_title'] + `</div>
          <div class="col3"><audio controls><source src="` + song['file_location'] + `" type="audio/mpeg"></audio></div>
      </div>
      <div class="containerrow padded">
          <div class="col6">Track Artist: </div>
          <div class="col6">Artist Here (defaults to Album artist) </div>
          <div class="col6">Track Credit: </div>
          <div class="col6">Names here (defaults to album credit)</div>
      </div>

      `
    }

    $("#tracks-reviewed-box").html(tracks)
}

function displayApprovedBox(data) {
    //console.log(data);
    var catalog = data['catalog'];
    if (catalog == null) catalog = "";
    var format = data['format_id'];
    //TODO: determine if it's a bad format based on db table
    if (format > 8 || format < 1) {
        console.log("Invalid format detected in tagging box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
        console.log("Setting format to \"Unknown\"");
        var format = 8;
    }
    var album = data['title'];
    var artist = data['artist'];
    var credit = data['credit'];
    var label = data['label'];
    var genre = data['genre'];
    var tags = data['tags'];
    var location = data['location'];
    var cancon = data['cancon'];
    var femcon = data['femcon'];
    var local = data['local'];
    var playlist = data['playlist'];
    var compilation = data['compilation'];
    var in_sam = data['in_SAM'];
    var email = data['email'];
    var description = data['description'];
    var review_comments = data['review_comments'];
    var art_url = data['art_url'];
    var submitted = data['submitted'];
    var releasedate = data['releasedate'];
    //console.log(review_comments);

    //Un-editable fields
    $("#release-approved").text("Album release date: " + releasedate);
    $("#submitted-approved").text("Date submitted: " + submitted);
    $("#contact-approved").text("Band email: " + email);
    if (description == null) {
        $("#description-approved").text("No description given.");
    } else {
        $("#description-approved").text(description);
    }
    if (review_comments == null) {
        $("#review_comments-approved").text("No review comments given.");
    } else {
        $("#review_comments-approved").text(review_comments);
    }
    $("#albumArt-approved").attr("src", art_url);
    $("#albumArt-approved-a").attr("href", art_url);
    //Editable fields
    $("#catalog-approved").val(String(catalog));
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
    if (cancon == 1) {
        $("#cancon-approved").prop('checked', true);
    }
    if (femcon == 1) {
        $("#femcon-approved").prop('checked', true);
    }
    if (local == 1) {
        $("#local-approved").prop('checked', true);
    }
    if (playlist == 1) {
        $("#playlist-approved").prop('checked', true);
    }
    if (compilation == 1) {
        $("#compilation-approved").prop('checked', true);
    }
    if (in_sam == 1) {
        $("#in_sam-approved").prop('checked', true);
    }
}

function displayTaggedBox(data) {
    var catalog = data['catalog'];
    if (catalog == null) catalog = "";
    var format = data['format_id'];
    //TODO: determine if it's a bad format based on db table
    if (format > 8 || format < 1) {
        console.log("Invalid format detected in tagged box. \n The submission id is " + data['id'] + " and the format id is " + data['format_id'] + " .");
        console.log("Setting format to \"Unknown\"");
        var format = 8;
    }
    var album = data['title'];
    var artist = data['artist'];
    var credit = data['credit'];
    var label = data['label'];
    var genre = data['genre'];
    var tags = data['tags'];
    var location = data['location'];
    var cancon = data['cancon'];
    var femcon = data['femcon'];
    var local = data['local'];
    var playlist = data['playlist'];
    var compilation = data['compilation'];
    var in_sam = data['in_SAM'];
    var email = data['email'];
    var description = data['description'];
    var review_comments = data['review_comments'];
    var art_url = data['art_url'];
    var submitted = data['submitted'];
    var releasedate = data['releasedate'];

    //Un-editable fields
    $("#release-tagged").text("Album release date: " + releasedate);
    $("#submitted-tagged").text("Date submitted: " + submitted);
    $("#contact-tagged").text("Band email: " + email);
    if (description == null) {
        $("#description-tagged").text("No description given.");
    } else {
        $("#description-tagged").text(description);
    }
    if (review_comments == null) {
        $("#review_comments-tagged").text("No review comments given.");
    } else {
        $("#review_comments-tagged").text(review_comments);
    }
    $("#albumArt-tagged").attr("src", art_url);
    $("#albumArt-tagged-a").attr("href", art_url);
    //Editable fields
    $("#catalog-tagged").val(String(catalog));
    $("#format-tagged").prop('value', format).change();
    $("#album-tagged").val(album);
    $("#artist-tagged").val(artist);
    $("#credit-tagged").val(credit);
    $("#label-tagged").val(label);
    $("#genre-tagged").prop('value', genre).change();
    $("#subgenre-tagged").val(tags).trigger('change');
    //if(tags != null){
    //	$("#tags-tagged").html("The following subgenre tags were specified by the band: <b>" + tags + "</b>. Specify an appropiate subgenre below:");
    //} else{
    //	$("tags-tagged").text("No subgenre tags were specified by the band. Specify a subgenre, if any are appropiate, below:");
    //}
    $("#location-tagged").val(location);
    if (cancon == 1) {
        $("#cancon-tagged").prop('checked', true);
    }
    if (femcon == 1) {
        $("#femcon-tagged").prop('checked', true);
    }
    if (local == 1) {
        $("#local-tagged").prop('checked', true);
    }
    if (playlist == 1) {
        $("#playlist-tagged").prop('checked', true);
    }
    if (compilation == 1) {
        $("#compilation-tagged").prop('checked', true);
    }
    if (in_sam == 1) {
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
var totalTrackSize = 0;
var files;
var songFiles = [];
var albumFile;

window.addEventListener('load', function () {
    form = document.getElementById("submit-field");
    albumArtButton = document.getElementById("album-art-input-button");
    trackButton = document.getElementById("new-track-button-input");
    submitButton = document.getElementById("submit-button");
    artistField = document.getElementById("artist-name");
    contactField = document.getElementById("contact-email");
    recordField = document.getElementById("record-label");
    cityField = document.getElementById("home-city");
    memberField = document.getElementById("member-names");
    albumField = document.getElementById("album-name");
    genrePicker = document.getElementById("genre-picker");
    dateField = document.getElementById("date-released");
    canadaBox = document.getElementById("canada-artist");
    vancouverBox = document.getElementById("vancouver-artist");
    femArtistBox = document.getElementById("female-artist");
    commentField = document.getElementById("comments-box");
    albumViewer = document.getElementById("album-viewer");
    formatPicker = document.getElementById("format-picker");

    submitButton.addEventListener('click', submitForm);

    albumArtButton.addEventListener('change', handleAlbum, false);

    trackButton.addEventListener('change', handleTracks, false);

});

function submitReview(id, approved_status, review_comments) {

    console.log("Submitting review ... ");
    $.ajax({
        url: "api2/public/submissions/review",
        type: 'PUT',
        dataType: 'json',
        data: {
            'id': id,
            'approved': approved_status,
            'review_comments': review_comments
        },
        async: true,
        success: function (data) {
            $("#comments-review-box").val('');
            $("#approved_status-review-box").val(0).change();
            $('#view_submissions').fadeOut(175);
            $("#view_submissions_row").fadeOut(175);
            var selector = "[name=\'" + id + "\']";
            $(selector).fadeOut(100);
            alert("Review Submitted");
            //TODO: Change the button and show a spinny thing
        } //,
        //commented out to avoid infinite loop
        //fail:function(data){
        //	console.log("Submitting Review Failed. Response data: " + data);
        //	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
        //}
    });
}

function approveReview(id) {
    console.log("Approving review ... ");
    console.log(id);
    $.ajax({
        url: "api2/public/submissions/approve",
        type: 'PUT',
        dataType: 'text json',
        data: {
            'id': id
        },
        async: true,
        success: function (data) {
            //console.log(data);
            alert("Review Approved");
            $("#reviewed_comments").val('');
            $("#reviewed_approved_status").val(0).change();
            $('#reviewed_submissions_view').fadeOut(175);
            $("#reviewed_submissions_view_row").fadeOut(175);
            var selector = "[name=\'" + id + "\']";
            $(selector).fadeOut(100);
            //TODO: Change the button and show a spinny thing
        } //,
        //commented out to avoid infinite loop
        //fail:function(data){
        //	console.log("Submitting Review Failed. Response data: " + data);
        //	alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
        //}
    });
}

function trashReview(id) {
    $.ajax({
        url: "api2/public/submissions/trash",
        type: 'PUT',
        dataType: 'text json',
        data: {
            'id': id
        },
        async: true,
        success: function (data) {
            alert("Submission trashed");
            $("#reviewed_comments").val('');
            $("#reviewed_approved_status").val(0).change();
            $('#reviewed_submissions_view').fadeOut(175);
            $("#reviewed_submissions_view_row").fadeOut(175);
            var selector = "[name=\'" + id + "\']";
            $(selector).fadeOut(100);
        }
    });
}

function tagReview(tag, id, catNo, format, album, artist, credit, label, genre, cancon, femcon, local, compilation, in_sam, playlist) {
    console.log("Tagging review ... ");
    $.ajax({
        url: "api2/public/submissions/tag",
        type: 'PUT',
        dataType: 'json',
        data: {
            'id': id,
            'tags': tag,
            'catalog': catNo,
            'format_id': format,
            'title': album,
            'artist': artist,
            'credit': credit,
            'label': label,
            'genre': genre,
            'cancon': cancon,
            'femcon': femcon,
            'local': local,
            'compilation': compilation,
            'in_sam': in_sam,
            'playlist': playlist
        },
        async: true,
        success: function (data) {
            //console.log(data);
            alert("Submission tagged");
            $('#submissionspopup').fadeOut(175);
            var selector = "[name=\'" + id + "\']";
            $(selector).fadeOut(100);
            //TODO: Change the button and show a spinny thing
        },
        //commented out to avoid infinite loop
        fail: function (data) {
            console.log("Submitting Review Failed. Response data: " + data);
            alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
        }
    });
}

function approveTags(tag, submission_id, catalog, format_id, album_title,
    artist, credit, label, genre, cancon, femcon, local,
    compilation, in_sam, playlist, art_url) {
    console.log(art_url);
    console.log("Approving tags ... ");

    confirmDialog('Do you want to add this submission to SAM Scan');

    $.ajax({
        url: "api2/public/submissions/tolibrary",
        type: 'PUT',
        dataType: 'json',
        data: {
            'id': submission_id,
            'tags': tag,
            'catalog': catalog,
            'format_id': format_id,
            'title': album_title,
            'artist': artist,
            'credit': credit,
            'label': label,
            'genre': genre,
            'cancon': cancon,
            'femcon': femcon,
            'local': local,
            'compilation': compilation,
            'in_sam': in_sam,
            'playlist': playlist
        },
        async: true,
        success: function (data) {
            console.log(data);
            $.ajax({
                url: "/api2/public/library/fromsubmissions",
                type: 'POST',
                dataType: 'json',
                data: {
                    'submission_id': submission_id,
                    'catalog': catalog,
                    'format': format_id,
                    'album_title': album_title,
                    'artist': artist,
                    'label': label,
                    'genre': genre,
                    'cancon': cancon,
                    'femcon': femcon,
                    'local': local,
                    'compilation': compilation,
                    'in_sam': in_sam,
                    'playlist': playlist,
                    'art_url': art_url
                },
                async: true,
                success: function (data) {
                    deleteSubmission(submission_id);
                    console.log(data);
                },
                fail: function (data) {
                    console.log("Submitting to Library failed. Response data: " + data);
                    alert("Submitting to Library failed. Please try again later. \n (is your internet connection ok?)");
                }
            });
            alert("Tags Approved");
            $('#submissionsapprovalpopup').fadeOut(175);
            var selector = "[name=\'" + submission_id + "\']";
            $(selector).fadeOut(100);
            //TODO: Change the button and show a spinny thing
        },
        //commented out to avoid infinite loop
        fail: function (data) {
            console.log("Submitting Review Failed. Response data: " + data);
            alert("Submitting Review Failed. Please try again later. \n (is your internet connection ok?)");
        }
    });
}

function submitForm() {

    $("#submit-button").text("Please Wait...");
    $("#submit-button").prop("disable", true);

    if (totalTrackSize > 525000000) {
        alert("Your submission is too big. For large submissions, please email us.");
        $("#submit-button").text("Submit");
        $("#submit-button").prop("disable", false);
    } else {
        var missing = [];
        var success = true;

        var artist = artistField.value;
        var email = contactField.value;
        var label = recordField.value;
        var location = cityField.value;
        var credit = memberField.value;
        var title = albumField.value;
        var e = document.getElementById('genre-picker');
        var genre = e.options[e.selectedIndex].value;
        var releasedate = dateField.value;
        var cancon = ($('#female-artist').prop('checked', true)) ? 1 : 0;
        var local = ($('#canada-artist').prop('checked', true)) ? 1 : 0;
        var femcon = ($('#vancouver-artist').prop('checked', true)) ? 1 : 0;
        var description = $('#comments-box').val();
        var f = document.getElementById('format-picker');
        var ff = f.options[f.selectedIndex].value;
        var format = 6;

        switch (ff) {
        case "CD":
            format = 1;
            break;
        case "LP":
            format = 2;
            break;
        case "7\"":
            format = 3;
            break;
        case "CASSETTE":
            format = 4;
            break;
        case "CART":
            format = 5;
            break;
        case "MP3":
            format = 6;
            break;
        case "MD":
            format = 7;
            break;
        default:
            format = 8;
        }

        var alertString = "You are missing the following:";

        if (artist == "") {
            success = false;
            alertString += "\n• Artist / Band name";
        }
        if (email == "") {
            success = false;
            alertString += "\n• Contact email";
        }
        if (location == "") {
            success = false;
            alertString += "\n• Home city";
        }
        if (title == "") {
            success = false;
            alertString += "\n• Album name";
        }
        if (genre == "") {
            success = false;
            alertString += "\n• Genre";
        }

        // Check that files have been added
        var tracks = $("#submit-field").children();
        if (tracks.length < 1) {
            alertString += "\n• Music files to upload";
            success = false;
        }

        // Checks that required track info has been added
        var trackNumberCheck = [];
        var missingTrackNumbers = 0;
        var missingTrackNames = 0;
        var trackNumError = false;
        var totalTracksChecked = 0;

        for (var i = 0; i < tracks.length; i++) {

            var thisTrack = $(tracks.get(i));

            var trackNumberValue = thisTrack.find(".track-number-field").val();
            var trackName = thisTrack.find(".input-track-field").val();
            var checked = thisTrack.find(".include-track").is(":checked");

            if (checked) {

                totalTracksChecked++;

                if (trackName == "") {
                    success = false;
                    missingTrackNames++;
                }

                if (trackNumberValue == "") {
                    success = false;
                    missingTrackNumbers++;
                } else if (isNaN(parseInt(trackNumberValue))) {
                    success = false;
                    trackNumError = true;
                } else {
                    trackNumberCheck.push(trackNumberValue);
                }
            }
        }

        if (missingTrackNames == 1) {
            alertString += "\n• 1 Track name";
        } else if (missingTrackNames > 1) {
            alertString += "\n• " + missingTrackNames + " track names";
        }

        if (missingTrackNumbers == 1) {
            alertString += "\n• 1 Track number";
        } else if (missingTrackNumbers > 1) {
            alertString += "\n• " + missingTrackNumbers + " track numbers";
        }

        if (trackNumError) {
            alertString += "\n\n Only numbers may be used in the track number field";
        }

        if ((totalTracksChecked < 1) && (tracks.length > 0)) {
            console.log(totalTracksChecked);
            success = false;
            alertString += "\nPlease add your files to the upload by clicking the checkboxes.";
        }

        if (success) { // possibly add sorting algorithm here in case of large array
            var duplicate = false;
            for (var i = 0; i < trackNumberCheck.length; i++) {
                if (duplicate == true) break;
                for (var j = i + 1; j < trackNumberCheck.length; j++) {
                    if (parseInt(trackNumberCheck[i]) == parseInt(trackNumberCheck[j])) {
                        success = false;
                        alertString = "There are duplicate track numbers — please correct"
                        duplicate = true;
                        break;
                    }
                }
            }
        }

        if (success) {

            var data = new FormData();

            data.append('format_id', format);
            data.append('artist', artist);
            data.append('email', email);
            data.append('label', label);
            data.append('location', location);
            data.append('credit', credit);
            data.append('title', title);
            data.append('genre', genre);
            data.append('releasedate', releasedate);
            data.append('femcon', femcon);
            data.append('cancon', cancon);
            data.append('local', local);
            data.append('description', description);
            data.append('songlist', 10);

            if (cover) data.append('art_url', cover);

            createSubmission(data, songFiles);

        } else {
            console.log(alertString);
            $("#submit-button").text("Submit");
            $("#submit-button").prop("disable", false);
            alert(alertString);
        }
    }

}

function handleAlbum(evt) {
    var files = evt.target.files;
    cover = files[0];

    if (cover.type.match('image.*') && cover.size < 5000000) {
        var reader = new FileReader();

        reader.onload = (function (theFile) {
            return function (e) {
                var span = document.createElement('span');
                span.setAttribute('id', 'thumb-span');
                span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                albumViewer.innerHTML = "";
                albumViewer.insertBefore(span, null);
            };
        })(cover);

        reader.readAsDataURL(cover);
    } else if (cover.type.match('image.*')) {
        cover = null;
        alert("Please choose a smaller image.");
    } else {
        alert("Please choose an image.");
    }
}

function handleTracks(evt) {
    var newFiles = evt.target.files;
    var filesAdded = 0;
    var fileWarning = false;
    var sizeWarning = false;

    for (var i = 0, f; f = newFiles[i]; i++) {

        if (!f.type.match('audio.*')) {
            fileWarning = true;
            continue;
        }

        if (f.size > 175000000) {
            sizeWarning = true;
            continue;
        }

        var fileName = f.name;
        addTrackForm(fileName, (totalTracks + i + 1));
        songFiles[totalTracks + i] = f;
        filesAdded++;

        totalTrackSize += f.size;
    }
    if (fileWarning) alert("Please only upload audio files");
    if (sizeWarning) alert("Please keep file size below 175 megabytes.\nIf you want to submit large files, please email us.");
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
    childNode.appendChild(document.createTextNode("File name: " + fileName));
    divNode.appendChild(childNode);

    // Add the track number field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "track-number-label");
    childNode.appendChild(document.createTextNode("★ Track number:"));
    divNode.appendChild(childNode);

    childNode = document.createt.eatet.createElement("input");
    childNode.setAttribute("class", "track-number-field");
    childNode.setAttribute("value", trackNo);
    divNode.appendChild(childNode);

    // Add the track name field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("★ Track name:"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-name");
    divNode.appendChild(childNode);

    // Add the composer field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("Composer(s):"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-composer");
    var defaultComposer = document.getElementById("default-composer");
    childNode.setAttribute("value", defaultComposer.value);
    divNode.appendChild(childNode);

    // Add the performer field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("Performer(s):"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-performer");
    var defaultPerformer = document.getElementById("default-performer");
    childNode.setAttribute("value", defaultPerformer.value);
    divNode.appendChild(childNode);

    // Add the Include checkbox
    childNode = document.createElement("input");
    childNode.setAttribute("id", "include-" + trackNo);
    childNode.setAttribute("type", "checkbox");
    childNode.setAttribute("class", "include-track");
    childNode.setAttribute("style", "margin-right:15px;margin-left:5%;");
    divNode.appendChild(childNode);

    childNode = document.createElement("label");
    childNode.setAttribute("for", "include-" + trackNo);
    childNode.appendChild(document.createTextNode("Include (de-select to remove track from submission)"));
    divNode.appendChild(childNode);

    form.appendChild(divNode);

    $("#include-" + trackNo).prop('checked', true);
}

function submitForm() {

    $("#submit-button").text("Please Wait...");
    $("#submit-button").prop("disable", true);

    if (totalTrackSize > 525000000) {
        alert("Your submission is too big. For large submissions, please email us.");
        $("#submit-button").text("Submit");
        $("#submit-button").prop("disable", false);
    } else {
        var missing = [];
        var success = true;

        var artist = artistField.value;
        var email = contactField.value;
        var label = recordField.value;
        var location = cityField.value;
        var credit = memberField.value;
        var title = albumField.value;
        var e = document.getElementById('genre-picker');
        var genre = e.options[e.selectedIndex].value;
        var releasedate = dateField.value;
        var cancon = ($('#female-artist').prop('checked', true)) ? 1 : 0;
        var local = ($('#canada-artist').prop('checked', true)) ? 1 : 0;
        var femcon = ($('#vancouver-artist').prop('checked', true)) ? 1 : 0;
        var description = $('#comments-box').val();
        var f = document.getElementById('format-picker');
        var ff = f.options[f.selectedIndex].value;
        var format = 6;

        switch (ff) {
        case "CD":
            format = 1;
            break;
        case "LP":
            format = 2;
            break;
        case "7\"":
            format = 3;
            break;
        case "CASSETTE":
            format = 4;
            break;
        case "CART":
            format = 5;
            break;
        case "MP3":
            format = 6;
            break;
        case "MD":
            format = 7;
            break;
        default:
            format = 8;
        }

        var alertString = "You are missing the following:";

        if (artist == "") {
            success = false;
            alertString += "\n• Artist / Band name";
        }
        if (email == "") {
            success = false;
            alertString += "\n• Contact email";
        }
        if (location == "") {
            success = false;
            alertString += "\n• Home city";
        }
        if (title == "") {
            success = false;
            alertString += "\n• Album name";
        }
        if (genre == "") {
            success = false;
            alertString += "\n• Genre";
        }

        // Check that files have been added
        var tracks = $("#submit-field").children();
        if (tracks.length < 1) {
            alertString += "\n• Music files to upload";
            success = false;
        }

        // Checks that required track info has been added
        var trackNumberCheck = [];
        var missingTrackNumbers = 0;
        var missingTrackNames = 0;
        var trackNumError = false;
        var totalTracksChecked = 0;

        for (var i = 0; i < tracks.length; i++) {

            var thisTrack = $(tracks.get(i));

            var trackNumberValue = thisTrack.find(".track-number-field").val();
            var trackName = thisTrack.find(".input-track-field").val();
            var checked = thisTrack.find(".include-track").is(":checked");

            if (checked) {

                totalTracksChecked++;

                if (trackName == "") {
                    success = false;
                    missingTrackNames++;
                }

                if (trackNumberValue == "") {
                    success = false;
                    missingTrackNumbers++;
                } else if (isNaN(parseInt(trackNumberValue))) {
                    success = false;
                    trackNumError = true;
                } else {
                    trackNumberCheck.push(trackNumberValue);
                }
            }
        }

        if (missingTrackNames == 1) {
            alertString += "\n• 1 Track name";
        } else if (missingTrackNames > 1) {
            alertString += "\n• " + missingTrackNames + " track names";
        }

        if (missingTrackNumbers == 1) {
            alertString += "\n• 1 Track number";
        } else if (missingTrackNumbers > 1) {
            alertString += "\n• " + missingTrackNumbers + " track numbers";
        }

        if (trackNumError) {
            alertString += "\n\n Only numbers may be used in the track number field";
        }

        if ((totalTracksChecked < 1) && (tracks.length > 0)) {
            console.log(totalTracksChecked);
            success = false;
            alertString += "\nPlease add your files to the upload by clicking the checkboxes.";
        }

        if (success) { // possibly add sorting algorithm here in case of large array
            var duplicate = false;
            for (var i = 0; i < trackNumberCheck.length; i++) {
                if (duplicate == true) break;
                for (var j = i + 1; j < trackNumberCheck.length; j++) {
                    if (parseInt(trackNumberCheck[i]) == parseInt(trackNumberCheck[j])) {
                        success = false;
                        alertString = "There are duplicate track numbers — please correct"
                        duplicate = true;
                        break;
                    }
                }
            }
        }

        if (success) {

            var data = new FormData();

            data.append('format_id', format);
            data.append('artist', artist);
            data.append('email', email);
            data.append('label', label);
            data.append('location', location);
            data.append('credit', credit);
            data.append('title', title);
            data.append('genre', genre);
            data.append('releasedate', releasedate);
            data.append('femcon', femcon);
            data.append('cancon', cancon);
            data.append('local', local);
            data.append('description', description);
            data.append('songlist', 10);

            if (cover) data.append('art_url', cover);

            createSubmission(data, songFiles);

        } else {
            console.log(alertString);
            $("#submit-button").text("Submit");
            $("#submit-button").prop("disable", false);
            alert(alertString);
        }
    }

}

function handleAlbum(evt) {
    var files = evt.target.files;
    cover = files[0];

    if (cover.type.match('image.*') && cover.size < 5000000) {
        var reader = new FileReader();

        reader.onload = (function (theFile) {
            return function (e) {
                var span = document.createElement('span');
                span.setAttribute('id', 'thumb-span');
                span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                albumViewer.innerHTML = "";
                albumViewer.insertBefore(span, null);
            };
        })(cover);

        reader.readAsDataURL(cover);
    } else if (cover.type.match('image.*')) {
        cover = null;
        alert("Please choose a smaller image.");
    } else {
        alert("Please choose an image.");
    }
}

function handleTracks(evt) {
    var newFiles = evt.target.files;
    var filesAdded = 0;
    var fileWarning = false;
    var sizeWarning = false;

    for (var i = 0, f; f = newFiles[i]; i++) {

        if (!f.type.match('audio.*')) {
            fileWarning = true;
            continue;
        }

        if (f.size > 175000000) {
            sizeWarning = true;
            continue;
        }

        var fileName = f.name;
        addTrackForm(fileName, (totalTracks + i + 1));
        songFiles[totalTracks + i] = f;
        filesAdded++;

        totalTrackSize += f.size;
    }
    if (fileWarning) alert("Please only upload audio files");
    if (sizeWarning) alert("Please keep file size below 175 megabytes.\nIf you want to submit large files, please email us.");
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
    childNode.appendChild(document.createTextNode("File name: " + fileName));
    divNode.appendChild(childNode);

    // Add the track number field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "track-number-label");
    childNode.appendChild(document.createTextNode("★ Track number:"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "track-number-field");
    childNode.setAttribute("value", trackNo);
    divNode.appendChild(childNode);

    // Add the track name field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("★ Track name:"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-name");
    divNode.appendChild(childNode);

    // Add the composer field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("Composer(s):"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-composer");
    var defaultComposer = document.getElementById("default-composer");
    childNode.setAttribute("value", defaultComposer.value);
    divNode.appendChild(childNode);

    // Add the performer field
    childNode = document.createElement("p");
    childNode.setAttribute("class", "input-track-label");
    childNode.appendChild(document.createTextNode("Performer(s):"));
    divNode.appendChild(childNode);

    childNode = document.createElement("input");
    childNode.setAttribute("class", "input-track-field input-track-field-performer");
    var defaultPerformer = document.getElementById("default-performer");
    childNode.setAttribute("value", defaultPerformer.value);
    divNode.appendChild(childNode);

    // Add the Include checkbox
    childNode = document.createElement("input");
    childNode.setAttribute("id", "include-" + trackNo);
    childNode.setAttribute("type", "checkbox");
    childNode.setAttribute("class", "include-track");
    childNode.setAttribute("style", "margin-right:15px;margin-left:5%;");
    divNode.appendChild(childNode);

    childNode = document.createElement("label");
    childNode.setAttribute("for", "include-" + trackNo);
    childNode.appendChild(document.createTextNode("Include (de-select to remove track from submission)"));
    divNode.appendChild(childNode);

    form.appendChild(divNode);

    $("#include-" + trackNo).prop('checked', true);
}

//Delete from database - "hard" delete
function deleteSubmission(id) {
    $.ajax({
        url: "/api2/public/submissions/" + submission_id,
        type: 'DELETE',
        dataType: 'json',
        data: {
            'id': submission_id,
        },
        async: true,
        success: function (data) {
            console.log("Submission #" + submission_id + " deleted successfully.");
        },
        fail: function (data) {
            console.log("Deleting submission id " + submission_id + " failed: " + data);
        }
    });
}
