$(document).ready ( function() {
  
   
   $( "#from" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: 'yy-mm-dd',
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
	
	$( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: 'yy-mm-dd',
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
	
    $('#submitDates').click(function(){
    	var datefrom = $('#from').val();
    	var dateto = $('#to').val();
		loadSamAds(datefrom,dateto);
	});
});
function loadSamAds(from,to){
		$('#loadbar').show();
		$('.samrow').each( function(){
			$(this).remove();
		});
		var adname = $('#adname').val();
		$.ajax({
			type:"POST",
			url: "form-handlers/samAds_handler.php",
			data: {"from":from,"to":to,"adname":adname},
			dataType: "json"
		}).success(function(data) {
			$('#samAds').removeClass('invisible');
			for( $j = 0; $j < Object.keys(data).length; $j++ ){
				$('#samAds').append('<tr id="samrow'+$j+'" class="samrow"></tr>');
				$('#samrow'+$j).append('<td class=samtitle>'+data[$j].filename.replace('O:\\PRIORITY ADs\\',' ').replace('.mp3',' ')+' </td><td class=samplayed>'+data[$j].date_played+'</td>');
			}
			$('#loadbar').hide();
		}).fail(function(){
			$('#samAds').show();
			$('#samAds').html('connection error');
		});
	}






	
	