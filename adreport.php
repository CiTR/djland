
<?php


session_start();

require_once("headers/security_header.php");

require_once("headers/function_header.php");

require_once("headers/menu_header.php");

require_once("adLib.php");




printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=css/style.css type=text/css>");
//printf("<title>CiTR 101.9</title></head><body>");

?>

<title>Ad Report</title>

 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/jquery.form.js"></script> 

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

$adLib = new AdLib($mysqli_sam,$db);

$today = date('m/d/Y');
echo '<h1>ad  report</h1>';;

	echo "First select a date range:<br/>";
	echo '<form id="adreport">
			<label for="from">Start Date: </label>
			<input type="text" id="from" name="from" value="'.$today.'"/>
			
			<label for="to">End Date: </label>
			<input type="text" id="to" name="to" value="'.$today.'"/>
			<br/>
			(optional) Filter by ad: '.
			
			$adLib->generateAdSelector()
			.'</form>
			
			<button id="submitDates">Get ad report</button><span id="loadStatus">&nbsp;</span>
			
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
    	
		filteredAd = $('.selectanad').val();
		console.log(filteredAd);
    	var datefrom = $('#from').val();
    	var dateto = $('#to').val();
    	
    	
	var text = $.ajax({
    	type: "POST",
   		url: "./form-handlers/adreport-handler.php",
    	data: {datePicked:'true',from:datefrom,to:dateto,filteredAd:filteredAd},
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
		
    		$('#result').html(text);

 			 }  
		});	
    });
  });
</script>  

<?php

?>



