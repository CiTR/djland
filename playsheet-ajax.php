<?php 

if($using_sam){

  
?>
			

<div id="SamListOuter">
<div class="closeButton">
<button type="button" class="closeButton" id="closeSamList">X</button>
</div>
<div id="loaderButtons"></div>
<div id="SamList">

<?php

global $samDB_ip, $samDB_user, $samDB_pass, $samDB_dbname;
require_once( './samLoadRecent.php');

?>
</div>
</div>
<div id="loadtimes"><div class="closeButton">
<button type="button" class="closeButton" id="closeLoadTimes">X</button>
</div>
Load multiple SAM plays at once.<br />
Select the time period below.<br />
(Maximum 8 hour duration)<br/>


<form id="loadMulti" method="post">
<script>
 $(function() {
    $( "#from" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
        if(($("#to").datepicker("getDate") === null || $("#to").val() === "end date") && $("#from").datepicker("getDate") !== null) {
         $("#to").datepicker( "setDate" , $("#from").val() );
        }
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
         if(($("#from").datepicker("getDate") === null || $("#from").val() === "start date") && $("#to").datepicker("getDate") !== null) {
         $("#from").datepicker( "setDate" , $("#to").val() );
        }
      }
    });


    
  });
</script>  

<label for="from">Start Date: </label>
<input type="text" id="from" name="from" />
	<?php
		print(" Time: [");
		


print("<SELECT id=hourFrom  >\n<OPTION>");
		for($i=0; $i <= 23; $i++) printf("<OPTION>%02d", $i); 
		print


("</SELECT>:");
		print("<SELECT id=minuteFrom  >");
		for($i=0; $i <= 59; $i++) printf("<OPTION>%02d", $i); 
	



	print("</SELECT>]");
		
		?> <br/><br/>
<label for="to">End Date: </label>
<input type="text" id="to" name="to" />







		<?php
		print(" Time: [");
		

print("<SELECT id=hourTo  >\n<OPTION>");
		for($i=0; $i <= 23; $i++) printf("<OPTION>%02d", $i); 
		print

("</SELECT>:");
		print("<SELECT id=minuteTo  >");
		for($i=0; $i <= 59; $i++) printf("<OPTION>%02d", $i); 
	

	print("</SELECT>]");
		
		?>
<br/>

</form>

<button type="button" id="submitDates">load plays</button> <span id="loadStatus"></span>

</div>
<div id="loadedPlays"></div>

<?php
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
if (count($matches)>1){
  //Then we're using IE
  $version = $matches[1];

  switch(true){
    case ($version<=8):
     
      break;

    default:
      print(" 	<div align='right'><button  type='button' id='SamTab' class='panel-button'>SAM plays</button>
				<button type='button' id='buttonLoadTimes' class='panel-button'>SAM period </button>
				<button type='button' id='autosaver' class='panel-button'>save<br/>draft</button></div> ");
	break;
  }
}
  else
  {
  	print(" 	<div align='right'><button type='button' id='SamTab' class='panel-button'>SAM plays</button>
				<button type='button' id='buttonLoadTimes' class='panel-button'>SAM period </button>
				<button type='button' id='autosaver' class='panel-button'>save<br/>draft</button></div> ");
  }

} // end of if(sam enabled) block

//echo "<div align='right'> <button type='button' id='autosaver' class='panel-button'>save<br/>draft</button></div>";

?>
<!--<button id="SamTab" class="panel-button">SAM plays</button>
<button id="buttonLoadTimes" class="panel-button">SAM period </button>
<button id="autosaver" class="panel-button">save<br/>draft</button>
-->
<!---- END OF BODY TAG -->





