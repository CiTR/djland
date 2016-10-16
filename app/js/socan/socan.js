$(function() {
	$( "#from" ).datepicker({
		defaultDate: "+0d",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#to" ).datepicker({
		defaultDate: "+0d",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

	$('.deletePeriod').click(function (){
		var id = $(this).attr("id").replace('socanDelete','');

		console.log(id);
		var text = $.ajax({
			type: "POST", // HTTP method POST or GET
			url: "form-handlers/socan_delete.php", //Where to make Ajax calls
			data:{id:id},
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
		var id;
		var datefrom = $('#from').val();
		var dateto = $('#to').val();
		console.log(datefrom);
		console.log(dateto);

		var text = $.ajax({
		type: "POST",
		url: "form-handlers/socan-handler.php",
		data: {datePicked:'true',from:datefrom,to:dateto},
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
			//$('#socanTable').append( $('#rowtemplate').cloneNode(true) );
			$('#result').html(text);

			}  
		});	
	});
});