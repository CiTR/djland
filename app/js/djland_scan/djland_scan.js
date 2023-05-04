$(document).ready(function () {
    //Hold the results of the scan here
    var scanData;

    $('#startScan').on('click', function () {
        $('#startScan').hide();
        $('#loading').show();
        $.ajax({
            type: "GET",
            url: "api2/public/djlandscan/generatescanresults",
            dataType: 'json',
            async: true,
            success: function (response) {
                scanData = response;
                //console.log(response);
                if(response.length === 0){
                    alert("No files found to process!");
                }
                for (var key in response) {
                    item = response[key];
                    var actionsList = "<select>";
                    for (var id in item.actionsList) {
                        actionsList += "<option value='" + item.actionsList[id] + "'>" + item.actionsList[id].actionText + "</option>";
                    }
                    actionsList += "</select>";

                    $('#DJLandScanTable > tbody:last-child').append(
                        "<tr><td>" + item.source +
                        "</td><td>" + item.artist +
                        "</td><td>" + item.album +
                        "</td><td>" + item.song +
                        "</td><td>" + item.genre +
                        "</td><td>" + item.year +
                        "</td><td>" + actionsList + "</td></tr>");
                }
                $('#loading').hide();
                $('#DJLandScan').show();
                $('#scanTitle').show();
                $('#DJLandScanTable').show();
                $('#submitScan').show();
                $("#DJLandScanTable").DataTable();
            },
            error: function (err) {
                $('#loading').hide();
                $('#DJLandScan').show();
                $('#scanTitle').show();
                $('#DJLandScanTable').show();
                $('#submitScan').show();
                $("#DJLandScanTable").DataTable();
                console.log("There was a problem fetching scan results from the server. The server said:");
                console.log(err);
                alert('There was a problem fetching scan results from the server. Please try again later.');
            },
            timeout: 300000 //5 minutes
        });
    });

    $('#submitScan').on('click', function () {
        //Build actions list to send back to server
        var actions = [];
        var selectedList = [];
        //Get list of user selected actions
        //Assumes page doesn't have any other selects (shouldn't be a problem?)
        $('select').each(function () {
            selectedList.push($(this).val())
        });
        for (var selection in selectedList) {
            for (var key in scanData) {
                item = scanData[key];
                for (var i in item.actionsList) {
                    //Add action to actions aray
                    if (selection == i) {
                        actions.push(item.actionsList[i]);
                    }
                }
            }
        }

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
                //console.log(response);

                //DISPLAY RESPONSE
                for (var key in response) {
                    item = response[key];
                    //newID is the submisssion ID or library catalog number it was inserted under
                    $('#DJLandScanResultsTable > tbody:last-child').append(
                        "<tr><td>" + item +
                        "</td></tr>"
                    );
                }
                $("#DJLandScanResultsTable").DataTable();
                $("#loading2").hide();
                $('#DJLandScan').show();
                $("#scanTitle2").show();
                $("#DJLandScanResultsTable").show();
            },
            error: function (err) {
                $("#DJLandScanResultsTable").DataTable();
                $("#loading2").hide();
                $('#DJLandScan').show();
                $("#scanTitle2").show();
                $("#DJLandScanResultsTable").show();
                console.log("There was a problem fetching scan results from the server. The server said:");
                console.log(err);
                alert('There was a problem fetching scan results from the server. Please try again later.');
            }
        });
    });
});
