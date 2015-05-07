<?php
session_start();

require_once("headers/security_header.php");
require_once("config.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");
require_once("headers/socan_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=css/style.css type=text/css>");
$now = date("m/d/Y",strtotime('now'));
$twodaysfromnow  = date("m/d/Y", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));

?>

<title>Set Socan</title>
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




echo "<div id='wrapper'>";
echo "<div style='margin-left:15px;'>";
echo '<h1>Set Socan</h1>';
	
	echo "<center>First select a date range: ";
	echo '<form id="adreport">
			<label for="from">Start Date: </label>
			<input type="text" id="from" name="from" value="'.$now.'"/>
			
			<label for="to">End Date: </label>
			<input type="text" id="to" name="to" value="'.$twodaysfromnow.'"/>
			
			</form>
			
			<button id="submitDates">Create this SOCAN period</button><span id="loadStatus">&nbsp;</span>
			</center>
			<div id="result">&nbsp;</div>';
	
	
	
$query="SELECT MAX(idSOCAN) FROM socan";
$result = mysqli_query($db,$query);
$row = mysqli_fetch_row($result);
$num_id = $row[0];
	
	
	echo "<hr><br><center>These are the current SOCAN periods that are set:</center><br>";
	$request_query="SELECT * FROM socan ORDER BY idSOCAN";
	if($result=mysqli_query($db,$request_query))
	{
		
		echo "<div class='socanTable'>".
		//this is the header bar with the labels.
		"<div id=rowHeader class='socanRow'>".		
		"<div class='idSOCAN'>ID</div>".
		"<div class='socanField'>Start Time</div>".
		"<div class='socanField'>End Time</div>".
		"<div class='socanField2'>Delete</div></div>";
		echo "<div id=rowtemplate class='socanRow invisible'>".
		"<div class='idSOCAN'>template</div>".
		"<div class='socanField'>template</div>".
		"<div class='socanField'>template</div>".
		"<div class='socanField2'><button id='socanDeletetemplate' class='socanButton'>Delete Selected Periods</button></div>".
		"</div>";
	//dynamically create a table to show what is in the mySQL database.	
	while($row = mysqli_fetch_row($result)){
		$id=$row[0];
		$socanStart=$row[1];
		$socanEnd=$row[2];
		echo "<div id=row".$id." class='socanRow'>".
		"<div class='idSOCAN'>".$id."</div>".
		"<div class='socanField'>".$socanStart." 00:00</div>".
		"<div class='socanField'>".$socanEnd." 00:00</div>".
		"<div class='socanField2'><button id='socanDelete".$id."' class='socanButton'>Delete Selected Periods</button></div>".
		"</div>";
	}
	echo "</div>";
	echo "Note that in order to end on midnight, you must select the next day at 00:00 as it only selects day, and not time!";
	echo "<div id='result2'>&nbsp;</div><span id='loadStatus2'>&nbsp;</span>";
	}
	else{ echo "Retreiving Socan Periods Failed"; }
	echo "</div></div>";
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
	
	 $('.socanButton').click(function (){
		var id = $(this).attr("id").replace(/\D/g,'');
		
		console.log(id);
		var text = $.ajax({
			type: "POST", // HTTP method POST or GET
			url: "./form-handlers/socan_delete.php", //Where to make Ajax calls
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
		
	

		

	
    $('#submitDates').click(function(){
    	var id;
    	var datefrom = $('#from').val();
    	var dateto = $('#to').val();
		console.log(datefrom);
		console.log(dateto);
    	
	var text = $.ajax({
    	type: "POST",
   		url: "./form-handlers/socan-handler.php",
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
		
    		$('#result').html(text);

 			 }  
		});	
    });
  });
</script>  