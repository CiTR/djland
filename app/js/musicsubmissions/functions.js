/* JavaScript for POST requests to submit new music submissions, from the admin-
 * only Manual Submissions page in DJLand, or the public Submit Online page on
 * CiTR.ca.
 * Michael Adria, Capstone 2016/2017
 */

function createSubmission(data, songs) {
    var tracks = $("#submit-field").children();

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

        .done(function (data) {
            var successBox = document.getElementById("submit-button-div");
            successBox.innerHTML = "<p style='text-align:center;margin-bottom:50px;'>Thanks for submitting! A confirmation email will be sent to you shortly.</p>";

            var tracks = $("#submit-field").children();

            for (var i = 0; i < tracks.length; i++) {

                var trackFile = songs[i];
                var x = $(tracks.get(i));
                if (x.find(".include-track").is(":checked")) {
                    var a = new FormData();
                    a.append('number', x.find(".track-number-field").val());
                    a.append('name', x.find(".input-track-field-name").val());
                    a.append('composer', x.find(".input-track-field-composer").val());
                    a.append('performer', x.find(".input-track-field-performer").val());
                    a.append('file', trackFile);
                    a.append('filename', trackFile.name);

                    createTrackSubmission(a, data, trackFile.name);
                }
            }
            return data;
        })

        .fail(function (data) {
            alert("Submissions failed. Please make sure your email is entered correctly.");

            $("#submit-button").text("Submit");

        });
}

function createTrackSubmission(data, id, filename) {

    $.ajax({
            url: "api2/public/song/" + id,
            data: data,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function (data) {
            console.log("File '" + filename + "' sent.");
        })
        .fail(function (data) {
            alert("Failed to send file: " + filename);
        });
}

function confirmDialog(message) {
    $('<div></div>').appendTo('body')
        .html('<div><h6>' + message + '?</h6></div>')
        .dialog({
            modal: true,
            title: 'Currently does not do anything',
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            buttons: {
                Yes: function () {
                    $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');
                    $(this).dialog("close");
                },
                No: function () {
                    $('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                    $(this).dialog("close");
                }
            },
            close: function (event, ui) {
                $(this).remove();
            }
        });
}

//Unused function
function namesFromMemberId(id) {
    var string = " ";
    $.ajax({
        type: "GET",
        url: "api2/public/member/" + id + "/firstnamelastname",
        dataType: 'json',
        async: true,
        success: function (response) {
            var data = response[0];
            var identifier = "[name=\'names" + id + "\']";
            if (data != undefined) {
                string = data['firstname'] + " " + data['lastname'];
                $(identifier).text(string);
            } else {
                $(identifier).text("Unknown");
                $(identifier).css("color", "navy");
            }
        },
        error: function (err) {
            //var json_response = err.responseJSON.msg;
            console.log("Bad format for AJAX Request with Member ID: " + id + ", the server said:");
            console.log(err);
        }
    });
}

function getMemberListForSelects() {
    $.ajax({
        type: "GET",
        url: "api2/public/member/",
        dataType: 'json',
        async: true,
        success: function (response) {
            var ret = "<option></option>";
            response.forEach(function (member) {
                ret = ret + "<option value='" + member['id'] + "'>" + member['firstname'] + " " + member['lastname'] + "</option>";
            });
            $(".memberList").append(ret);
            $(".memberList").select2({
                placeholder: "Select Member"
            });
            //Set the select2 value
            $(".memberList").each(function () {
                var value = $(this).attr('value');
                //Only change if the value is There
                //Or otherwise it will trigger the change handler on the select
                if ($.isNumeric(value)) {
                    console.log(value);
                    $(this).prop('value', value).change();
                }
            })
            $('.select2').on('click', function (e) {
                e.stopPropagation();
            });
            $('.select2').on('keydown', function (e) {
                e.stopImmediatePropagation();
            });
        },
        error: function (err) {
            console.log("Unable to grab list of members for selects. The server said:");
            console.log(err);
        }
    });
}

function saveComment(id, comment) {
    console.log(id + " " + comment);
    id = id.replace(/\D/g, '');
    $.trim(id);

    $.ajax({
        type: "PUT",
        url: "api2/public/submissions/comment",
        dataType: 'json',
        data: {
            id: id,
            comment: comment
        },
        async: true,
        success: function (response) {
            console.log("Comment for submission id: " + id + " successfully saved.")
        },
        error: function (err) {
            console.log("Unable to save comment for submission id: " + id + ". The server said:");
            console.log(err);
        }
    });
}

function saveAssignee(id, assignee) {
    console.log(id + " " + assignee);
    id = id.replace(/\D/g, '');
    $.trim(id);

    $.ajax({
        type: "PUT",
        url: "api2/public/submissions/assignee",
        dataType: 'json',
        data: {
            id: id,
            assignee: assignee
        },
        async: true,
        success: function (response) {
            console.log("Assignee for submission id: " + id + " successfully saved.")
        },
        error: function (err) {
            console.log("Unable to save assignee for submission id: " + id + ". The server said:");
            console.log(err);
        }
    });
}
