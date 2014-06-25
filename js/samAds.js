$(document).ready ( function() {
  
   
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
			url: "form-handlers/samAds-handler.php",
			data: {"from":from,"to":to,"adname":adname},
			dataType: "json"
		}).success(function(data) {
			$('#samAds').show();
			for( $j = 0; $j < Object.keys(data).length; $j++ ){
				$('#samAds').append('<div id="samrow'+$j+'" class="samrow"></div>');
				$('#samrow'+$j).append('<div class=samtitle>'+data[$j].filename.replace('O:\\PRIORITY ADs\\',' ').replace('.mp3',' ')+' </div><div class=samplayed>'+data[$j].date_played+'</div>');
			}
			$('#loadbar').hide();
		}).fail(function(){
			$('#samAds').show();
			$('#samAds').html('connection error');
		});
	}






	
	