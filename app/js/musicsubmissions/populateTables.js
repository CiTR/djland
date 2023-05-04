function populateNewSubmissionsTable() {
    $(".reviewrow").remove();
    $(".reviewrowNotFound").remove();
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/unreviewed/cd",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateNewSubmissionsCd(data);
            getMemberListForSelects();
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/unreviewed/mp3",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateNewSubmissionsMP3(data);
            getMemberListForSelects();
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/unreviewed/other",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateNewSubmissionsOther(data);
            getMemberListForSelects();
        }
    });
}

function populateReviewedSubmissionsTable() {
    $(".reviewedrow").remove();
    $(".reviewedrowNotFound").remove();
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/reviewed/cd",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateReviewedSubmissionsCd(data);
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/reviewed/mp3",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateReviewedSubmissionsMP3(data);
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/reviewed/other",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateReviewedSubmissionsOther(data);
        }
    });
}

function populateApprovedSubmissionsTable() {
    $(".tagrow").remove();
    $(".tagrowNotFound").remove();
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/approved/cd",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateToTagSubmissionsCd(data);
            getMemberListForSelects();
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/approved/mp3",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateToTagSubmissionsMP3(data);
            getMemberListForSelects();
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/approved/other",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateToTagSubmissionsOther(data);
            getMemberListForSelects();
        }
    });
}

function populateTaggedSubmissionsTable() {
    $(".approverow").remove();
    $(".approverowNotFound").remove();
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/tagged/cd",
        dataType: 'json',
        async: true,
        success: function (data) {
            populateTaggedSubmissionsCd(data);
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/tagged/mp3",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateTaggedSubmissionsMP3(data);
        }
    });
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/tagged/other",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateTaggedSubmissionsOther(data);
        }
    });
}

function populateTrashedSubmissionsTable() {
    $(".trashedrow").remove();
    $(".trashedrowNotFound").remove();
    $.ajax({
        type: "GET",
        url: "api2/public/submissions/bystatus/trashed",
        dataType: 'json',
        async: true,
        success: function (data) {
            //console.log(data);
            populateTrashedSubmissions(data);
        }
    });
}
//
function populateNewSubmissionsCd(submissions) {
    $("#newSubmissionCdTable").DataTable().clear();
    $("#newSubmissionCdTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='newSubmissionCd']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
                console.log("replaced with navy");
            } else {
                releasedateValue = item['releasedate'];
                console.log('date fine');
            }
            var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_cd\" id=\"delete" + item['id'] + "\"><div class=\"check hidden\">❏</div></td></tr>";
            //console.log(markup);
            $("tbody[name='newSubmissionCd']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#newSubmissionCdTable"))) {
            $("#newSubmissionCdTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateNewSubmissionsMP3(submissions) {
    $("#newSubmissionMP3Table").DataTable().clear();
    $("#newSubmissionMP3Table").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='newSubmissionMP3']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='newSubmissionMP3']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#newSubmissionMP3Table"))) {
            $("#newSubmissionMP3Table").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateNewSubmissionsOther(submissions) {
    $("#newSubmissionOtherTable").DataTable().clear();
    $("#newSubmissionOtherTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here ...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='newSubmissionOther']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border reviewrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_new_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='newSubmissionOther']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#newSubmissionOtherTable"))) {
            $("#newSubmissionOtherTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}
//
function populateReviewedSubmissionsCd(submissions) {
    $("#reviewedSubmissionCdTable").DataTable().clear();
    $("#reviewedSubmissionCdTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
        $("tbody[name='reviewedSubmissionCd']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            if (item['approved'] == 1) var approvalYesNo = "Yes";
            else var approvalYesNo = "No";
            if (item['reviewed'] != null) {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td>" + item['reviewed'] + "</td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_cd\" id=\"delete" + item['id'] + "\"></td></tr>";
            } else {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td><span style=\"color:navy\">Unknown</span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_cd\" id=\"delete" + item['id'] + "\"></td></tr>";
            }
            $("tbody[name='reviewedSubmissionCd']").append(markup);
        }
        var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button onclick=\"approve_submission_reviewed_cd()\">Apply Approvals</button></td></tr>"
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#reviewedSubmissionCdTable"))) {
            $("#reviewedSubmissionCdTable").DataTable({
                "order": [
                    [2, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateReviewedSubmissionsMP3(submissions) {
    $("#reviewedSubmissionMP3Table").DataTable().clear();
    $("#reviewedSubmissionMP3Table").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
        $("tbody[name='reviewedSubmissionMP3']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            if (item['approved'] == 1) var approvalYesNo = "Yes";
            else var approvalYesNo = "No";
            if (item['reviewed'] != null) {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td>" + item['reviewed'] + "</td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_mp3\" id=\"delete" + item['id'] + "\"></td></tr>";
            } else {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td><span style=\"color:navy\">Unknown</span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_mp3\" id=\"delete" + item['id'] + "\"></td></tr>";
            }
            $("tbody[name='reviewedSubmissionMP3']").append(markup);
        }
        var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button onclick=\"approve_submission_reviewed_mp3()\">Apply Approvals</button></td></tr>"
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#reviewedSubmissionMP3Table"))) {
            $("#reviewedSubmissionMP3Table").DataTable({
                "order": [
                    [2, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateReviewedSubmissionsOther(submissions) {
    $("#reviewedSubmissionOtherTable").DataTable().clear();
    $("#reviewedSubmissionOtherTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border reviewedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
        $("tbody[name='reviewedSubmissionOther']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            if (item['approved'] == 1) var approvalYesNo = "Yes";
            else var approvalYesNo = "No";
            if (item['reviewed'] != null) {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td>" + item['reviewed'] + "</td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_other\" id=\"delete" + item['id'] + "\"></td></tr>";
            } else {
                var markup = "<tr class=\"playitem border reviewedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td><span style=\"color:navy\">Unknown</span></td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"approve_submission_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div><td><input type=\"checkbox\" class=\"delete_submission_reviewed_other\" id=\"delete" + item['id'] + "\"></td></tr>";
            }
            $("tbody[name='reviewedSubmissionOther']").append(markup);
        }
        var endrow = "<tr class=\'reviewedrowNotFound\'><td></td><td></td><td></td><td></td><td></td><td></td><td><button onclick=\"approve_submission_reviewed_other()\">Apply Approvals</button></td></tr>"
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#reviewedSubmissionOtherTable"))) {
            $("#reviewedSubmissionOtherTable").DataTable({
                "order": [
                    [2, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}
//
function populateToTagSubmissionsCd(submissions) {
    $("#toTagSubmissionCdTable").DataTable().clear();
    $("#toTagSubmissionCdTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='toTagSubmissionCd']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            //<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='toTagSubmissionCd']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#toTagSubmissionCdTable"))) {
            $("#toTagSubmissionCdTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateToTagSubmissionsMP3(submissions) {
    $("#toTagSubmissionMP3Table").DataTable().clear();
    $("#toTagSubmissionMP3Table").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='toTagSubmissionMP3']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='toTagSubmissionMP3']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#toTagSubmissionMP3Table"))) {
            $("#toTagSubmissionMP3Table").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateToTagSubmissionsOther(submissions) {
    $("#toTagSubmissionOtherTable").DataTable().clear();
    $("#toTagSubmissionOtherTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border tagrowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='toTagSubmissionOther']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border tagrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td><select id='assignee" + item['id'] + "' class='memberList' value='" + item['assignee'] + "'></select></td><td><input type=\"checkbox\" class=\"delete_submission_accepted_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='toTagSubmissionOther']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#toTagSubmissionOtherTable"))) {
            $("#toTagSubmissionOtherTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}
//
function populateTaggedSubmissionsCd(submissions) {
    $("#taggedSubmissionCdTable").DataTable().clear();
    $("#taggedSubmissionCdTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='taggedSubmissionCd']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            //<tr class="playitem border tagrow" name="1272"><td class="submission_row_element name">Fuzzy P</td><td class="submission_row_element email">On A Lawn</td><td class="submission_row_element primary_phone">June 10th 2016</td><td class="submission_row_element submission_type">Indie Rock</td><td class="submission_row_element membership_year">June 26th 2016</td><td><input class="staff_comment" id="comment1272" value=""><td><select><option></option><option>Andy Resto</option><option>Hugo Noriega</option><option>Emily Stryker</option></select></td></td><td><input type="checkbox" class="delete_submission" id="delete_5"></td><div class="check hidden">❏</div></tr>
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
            } else {
                releasedateValue = item['releasedate'];
            }

            var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td id='tagged-tagger'>" + item['reviewed'] + "</td><td><input type=\"checkbox\" class=\"delete_submission_tagged_cd\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='taggedSubmissionCd']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#taggedSubmissionCdTable"))) {
            $("#taggedSubmissionCdTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateTaggedSubmissionsMP3(submissions) {
    $("#taggedSubmissionMP3Table").DataTable().clear();
    $("#taggedSubmissionMP3Table").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='taggedSubmissionMP3']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
                //console.log("Releasedate replaced");
            } else {
                releasedateValue = item['releasedate'];
                //console.log("releasedate given");
            }

            var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td 'tagged-tagger'>" + item['reviewed'] + "</td><td><input type=\"checkbox\" class=\"delete_submission_tagged_mp3\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='taggedSubmissionMP3']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#taggedSubmissionMP3Table"))) {
            $("#taggedSubmissionMP3Table").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateTaggedSubmissionsOther(submissions) {
    $("#taggedSubmissionOtherTable").DataTable().clear();
    $("#taggedSubmissionOtherTable").DataTable().destroy();
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border approverowNotFound\"><td></td><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td></tr>";
        $("tbody[name='taggedSubmissionOther']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            var releasedateValue;
            if (item['releasedate'] == "" || item['releasedate'] == null) {
                releasedateValue = "<span style=\"color:navy\">No date</span>";
                //console.log("Releasedate replaced");
            } else {
                releasedateValue = item['releasedate'];
                //console.log("releasedate given");
            }

            var markup = "<tr class=\"playitem border approverow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + releasedateValue + "</td><td class=\"submission_row_element\">" + item['genre'] + "</td><td class=\"submission_row_element\">" +
                item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td><td id='tagged-tagger'>" + item['reviewed'] + "</td><td><input type=\"checkbox\" class=\"delete_submission_tagged_other\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='taggedSubmissionOther']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#taggedSubmissionOtherTable"))) {
            $("#taggedSubmissionOtherTable").DataTable({
                "order": [
                    [4, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    {
                        "orderDataType": "dom-select"
                    },
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}

function populateTrashedSubmissions(submissions) {
    $("#trashedSubmissionsTable").DataTable().clear();
    $("#trashedSubmissionsTable").DataTable().destroy()
    if (submissions[0] == null) {
        var markup = "<tr class=\"playitem border trashedrowNotFound\"><td></td><td></td><td></td><td>Nothing here...</td><td></td><td></td><td></td><td></td></tr>";
        $("tbody[name='trashedSubmissions']").append(markup);
    } else {
        for (var number in submissions) {
            var item = (submissions[number]);
            if (item['approved'] == 1) var approvalYesNo = "Yes";
            else var approvalYesNo = "No";
            var names = namesFromMemberId(item['reviewed']);
            var markup = "<tr class=\"playitem border trashedrow\" name=\"" + item['id'] + "\"><td class=\"submission_row_element\"> " + item['artist'] + " </td><td class=\"submission_row_element\">" + item['title'] + "</td><td class=\"submission_row_element\">" + item['submitted'] + "</td><td><input class=\"staff_comment\" id=\"comment" + item['id'] + "\" value=\"" + item['staff_comment'] + "\"></td></td><td>" + item['reviewed'] + "</td><td>" + approvalYesNo + "</td><td><input type=\"checkbox\" class=\"restore_submission\" id=\"delete" + item['id'] + "\"></td><div class=\"check hidden\">❏</div></tr>";
            $("tbody[name='trashedSubmissions']").append(markup);
        }
        add_submission_handlers();
        if (!($.fn.dataTable.isDataTable("#trashedSubmissionsTable"))) {
            $("#trashedSubmissionsTable").DataTable({
                "order": [
                    [2, "desc"]
                ],
                "columns": [
                    null,
                    null,
                    null,
                    {
                        "orderDataType": "dom-text",
                        type: 'string'
                    },
                    null,
                    null,
                    {
                        "orderDataType": "dom-checkbox"
                    }
                ],
                stateSave: true
            });
        }
    }
}
