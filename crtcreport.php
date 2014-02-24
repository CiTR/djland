<?php // CRTC REPORT PAGE

//***************************************************
// see handlers/crtcreport.php to change requirement values
//***************************************************

// DEFAULT VALUES FOR BROADCAST DAY SELECTOR (6 am to midnight)
$crtc_min = 6;
$crtc_max = 24;

session_start();

require("headers/security_header.php");

require("headers/function_header.php");

require("headers/menu_header.php");


printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=style.css type=text/css>");

?>

<title><?php

      echo $station_info['call_letters']." ";
      echo $station_info['frequency'].", ";
      echo $station_info['city'].", ";
      echo $station_info['province'].", ";
      echo $station_info['country']." - ";
      echo $station_info['website'];
 ?>

 CiTR 101.9fm, Vancouver, British Columbia, Canada - www.citr.ca</title>

 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script> 

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  
  <script>
  $(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
  </script>
</head>
<body>

<?php

print_menu();
$today = date('m/d/Y');

echo '<h1>CRTC Report</h1>';;
	
	echo "First select a date range:<br/>";
	echo '<form id="adreport">
			<label for="from">Start Date: </label>
			<input type="text" id="from" name="from" value="'.$today.'"/>
			
			<label for="to">End Date: </label>
			<input type="text" id="to" name="to" value="'.$today.'"/>
			<br/>
			<label for="minHr">Broadcast day start:</label>
			<select name="minHr" id="minHr">';
			for ($x = 0; $x <= 24; $x++){
			echo '<option value='.$x;
			if ($x == $crtc_min) echo ' selected="selected" ';
			echo '">'.$x.'</option>';	
			}
	echo    '
			</select>
			<label for="maxHr">end:</label>
			<select name="maxHr" id="maxHr">';
			for ($x = 0; $x <= 36; $x++){
			echo '<option value='.$x;
			if ($x == $crtc_max) echo ' selected="selected" ';
			echo '">'.$x.'</option>';	
			}
	echo    '
			</select>
			</form>
			
			<button id="submitDates">Create Report</button><span id="loadStatus">&nbsp;</span>
			
			<br/><br/>
			<div id="result">&nbsp;</div>';

	
?>
<script>
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


    $('#submitDates').click(function(){
    	
    	var datefrom = $('#from').val();
    	var dateto = $('#to').val();
    	var min_time = $('#minHr').val();
    	var max_time = $('#maxHr').val();
    	
    	
	var text = $.ajax({
    	type: "POST",
   		url: "./form-handlers/crtcreport.php",
    	data: {datePicked:'true',from:datefrom,to:dateto, min_time:min_time, max_time:max_time},
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
		
    		$('#result').html(text);

 			 }  
		});	
    });
  });
</script>  

