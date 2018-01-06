
$(function() {

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

    $('.deletePeriod').click(function () {
    	console.log($(this).attr('id'));
    	var id = $(this).attr('id').replace('socanDelete','');
		console.log(id);
		var text = $.ajax({
			type: "DELETE",
			url: "api2/public/socan/"+id, //Where to make Ajax calls
			beforeSend: function(data) {
				$('#loadStatus2').html('<img src="./images/loading.gif" alt="Loading..."/>');
			},
			success: function(data){
				$('#loadStatus2').html('Success!');// ALSO CHECK FOR NUM LOADED
				$('#row'+id).remove();
				$('#result2').html(text);
			},
			complete: function(data) {
				//when either error or success has occurred
				$('#loadStatus2').html('done');
			},
			error:function (xhr, ajaxOptions, thrownError){
				//On error, we alert user
				alert(thrownError);
			}
		});
	});

	$('#createPeriod').click(function(){
		var text = $.ajax({
		type: "PUT",
		url: "api2/public/socan/",
		data: {'socan':{"socanStart":$('#from').val(), "socanEnd":$('#to').val()}},
			//	data: 'hello',
			beforeSend: function() {
				$('#loadStatus').html('<img src="./images/loading.gif" alt="Loading..."/>');
			},
			complete: function() {
				// when either error or success has occurred
				$('#loadStatus').html('done');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Status: " + textStatus); alert("Error: " + errorThrown);
			},
			success: function(text){
				$('#loadStatus').html('Success!');// ALSO CHECK FOR NUM LOADED

				$('#socanTable tbody').prepend( "<tr id='row'"+text.id+"><td>"+text.id+"</td><td>"+text.socanStart+"</td><td>"+text.socanEnd+"</td><td><button id='socanDeletetemplate' class='deletePeriod'>Delete this period</button></td></tr>");
				$('#result').html(text);
			}
		});
	});
});
