$(document).ready(function () {
    $('#startScan').on('click', function () {
        $('#startScan').hide();
        $('#loading').show();
        //$('#loading').addClass('center');
        $.ajax({
            type: "GET",
            url: "api2/public/djlandscan/generatescanresults",
            dataType: 'json',
            async: true,
            success: function (response) {
                console.log(response);
                for (var key in response) {
                    item = response[key];
                    var actionsList = "<select>";
                    for (var action in item.actionsList) {
                        actionsList += "<option>" + item.actionsList[action] + "</option>";
                    }
                    actionsList += "</select>";

                    $('#DJLandScanTable > tbody:last-child').append(
                        "<tr><td>" + item.source +
                        "</td><td>" + item.artist +
                        "</td><td>" + item.album +
                        "</td><td>" + item.song +
                        "</td><td>" + item.genre +
                        "</td><td>" + item.year +
                        "</td><td>" + item.matchedString +
                        "</td><td>" + actionsList + "</td></tr>");
                    $('#loading').hide();
                    $('#DJLandScan').show();
                    $('#scanTitle').show();
                    $('#DJLandScanTable').show();
                    $('#submitScan').show();
                    $("#DJLandScanTable").DataTable();
                }
            },
            error: function (err) {
                console.log("There was a problem fetching scan results from the server. The server said:");
                console.log(err);
                alert('There was a problem fetching scan results from the server. Please try again later.');
            },
            timeout: 300000 //5 minutes
        });
    });
    $('#submitScan').on('click', function () {
        //TODO: function to actuallly get the data from the existing table
        var actions = "test";

        $('#DJLandScanTable').DataTable().destroy();
        $('#submitScan').hide(100);
        $('#DJLandScanTable').hide(100);
        $('#scanTitle').hide();
        $('#DJLandScan').hide();
        $('#loading2').show();

        $.ajax({
            type: "POST",
            url: "api2/public/djlandscan/doimport",
            dataType: 'json',
            async: true,
            data: {
                actions: actions
            },
            success: function (response) {
                console.log(response);

                //DISPLAY RESPONSE
                for (var key in response) {
                    item = response[key];
                    //newID is the submisssion ID or library catalog number it was inserted under
                    $('#DJLandScanResultsTable > tbody:last-child').append(
                        "<tr><td>" + item.source +
                        "</td><td>" + item.action +
                        "</td><td>" + item.newID +
                        "</td><td>" + item.destination +
                        "</td></tr>"
                    );
                    $("#DJLandScanResultsTable").DataTable();
                    $("#loading2").hide();
                    $('#DJLandScan').show();
                    $("#scanTitle2").show();
                    $("#DJLandScanResultsTable").show();
                }
            },
            error: function (err) {
                console.log("There was a problem fetching scan results from the server. The server said:");
                console.log(err);
                alert('There was a problem fetching scan results from the server. Please try again later.');
            }
        });
    });
});
