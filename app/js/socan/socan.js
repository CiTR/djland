$(document).ready(function () {

    $("#from").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#to").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#to").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
        }
    });
    $('#createPeriod').click(function () {
        var text = $.ajax({
            type: "PUT",
            url: "api2/public/socan/",
            data: {
                "socanStart": $('#from').val(),
                "socanEnd": $('#to').val()
            },
            //	data: 'hello',
            beforeSend: function () {
                $('#loadStatus').html('<img src="./images/loading.gif" alt="Loading..."/>');
            },
            complete: function () {
                // when either error or success has occurred
                // wait a in case it takes a small amount of time to complete
                // request to give user feedback that action was completed
                setTimeout(function () {
                    $('#loadStatus').html('Done');
                }, 800);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown);
                alert("There was a problem completing the request:\n" + errorThrown)
            },
            success: function (text) {
                //Delete datatable object with destroy
                $("#socanTable").DataTable().destroy();
                $('#loadStatus').html('Success!'); // ALSO CHECK FOR NUM LOADED
                //Add new data to the dom
                $('#socanTable').append("<tr id='row" + text.idSocan + "'><td>" + text.idSocan + "</td><td>" + text.socanStart + "</td><td>" + text.socanEnd + "</td><td><button id='socanDelete" + text.idSocan + "' class='deletePeriod'>Delete this period</button></td></tr>");
                //Add handler to new row so we can delete it right away
                addHandlers();
                //recreate the data with the new dom
                if (!($.fn.dataTable.isDataTable("#socanTable"))) {
                    $("#socanTable").DataTable({
                        stateSave: true
                    });
                }
                $('#result').html(text);
            }
        });
    });

    if (!($.fn.dataTable.isDataTable("#socanTable"))) {
        $("#socanTable").DataTable({
            stateSave: true,
            "initComplete": function (settings, json) {
                addHandlers();
            }
        });
    }

});

function addHandlers() {
    $('.deletePeriod').off('click').on('click', function () {
        //strip the string to get numeric ID
        var id = $(this).attr("id").replace('socanDelete', '');

        console.log("Deleting socan with id:" + id);

        var text = $.ajax({
            type: "DELETE",
            url: "api2/public/socan/" + id, //Where to make Ajax calls
            data: {
                idSocan: id
            },
            beforeSend: function (data) {
                $('#loadStatus2').html('<img src="./images/loading.gif" alt="Loading..."/>');
            },
            success: function (data) {
                $('#loadStatus2').html('Success!'); // ALSO CHECK FOR NUM LOADED
                //Delete row from datatable and dom
                var row = $('#row' + id);
                $('#socanTable').DataTable().row(row).remove().draw(false); //false keeps us on the current page
                $("#row" + id).remove();

                $('#result2').html(text);
            },
            complete: function (data) {
                //when either error or success has occurred
                $('#loadStatus2').html('done');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //On error, we alert user
                alert(thrownError);
            }
        });
    });
}

