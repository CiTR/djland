	
//READY
	$(document).ready(function() {
	
				
	socan = false;/*<?php if ($SOCAN_FLAG) {echo "true";} else {echo "false";} ?>;*/
	
	debug = $('#debug');
	debug.prepend(" doc ready <br/>");
	var PLcheck=false;
	var CCcheck=false;
	var FEcheck=false;
	var INSTcheck=false;
	var PARTcheck=false;
	var HITcheck=false;
	num_rows = $('#numberOfRows').val();
	playsheetID = $('#psid').val();
	console.log('num_rows default value is: '+num_rows );
	refreshRowIDs();
	if (num_rows==0) noRows = true; 
	else noRows = false;




	setup();

	var star;
	$('.reqtemplate').val('template');
	checkBlocking();


//	$samBox = $('#sam-box');
	$songList = $('#song-list');

	$('#status').val('2');

	targetShow = $('.showTitle').attr('id');
//	generateShowLinks('default',targetShow);

	//Rebra
	$('#showSelector').change( function(){ if($('#type').val() !="Rebroadcast") ajaxLoadPlaysheet(); } );
	$('#playsheet-year').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheet(); } );
	$('#playsheet-month').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheet(); } );
	$('#playsheet-day').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheet(); } );
	
	
	$('#type').change( function(){
		console.log('type value is '+$('#type').val());
		if ($('#type').val() == 'Rebroadcast'){
			$('#select-playsheet').show();
			$('#select-playsheet').children().show();
			$('#load-playsheet').show();
		} else {
			$('#select-playsheet').hide();
			$('#load-playsheet').hide();
		}		
	
	});
	
	$('#load-playsheet').click(function(e){
        e.preventDefault();
        e.stopPropagation();
		inputPlaysheetRows();
	});
	// POPULATE LOAD BUTTONS
	var numLoaderButtons = 50;//<?php echo HISTORY_COUNT; ?>;
	
	$loaderButtons = $("#loaderButtons");
//	var loaderHeight = $('#SamList > #SamListRecent').css('height');
//	$loaderButtons.css('height',loaderHeight);
	
	for (i = 0; i< numLoaderButtons; i++){
		$loaderButtons.append('<div class="loaderButton" id="loaderButton'+i+'"> &nbsp;&nbsp;+</div>');
		}
		
	// activate load buttons
	$('.loaderButton').hover(
		  						function(){$(this).toggleClass("loaderButtonHover");}
		  			).click(function(){
				debug.prepend("clicked on a sam song <br/>");
						
				$clickedButtonId = $(this).attr("id").replace(/\D/g,'');
				SAMtoPlaysheet('SamListRecent',$clickedButtonId,num_rows-1);		
						});	
	debug.prepend('activated loader buttons<br/>');
	// activate other buttons
	$('#SamTab').click(function(){
			$('#SamListouter ').toggle();
	});
	
	$('#buttonLoadTimes').click(function(){
			
    	$('div#loadtimes').toggle();
    
	});
	
	$('#closeSamList').click(function(){
		
			$('#SamListouter ').toggle();	
	});
	
	$('#closeLoadTimes').click(function(){
		
    	$('div#loadtimes').toggle();	
	});
	
	$('#autosaver').click(function(){

		autosave();	
	});
	
	// $('#timeInner').change(function (){
	// TimeRowNum = $(this).attr("id").replace(/\D/g,'');
	// $set_song_start_hour=$('#set_song_start_hour').val();
	// $set_song_start_minute=$('#set_song_start_minute').val();
	// $set_song_start_second=$('#set_song_start_second').val();
	// });
	
	$('#set_song_start_hour').change(function () {
	
	});
	
	$('#set_song_start_minute').change(function () {
	
	});
	

	
					
	$('#pppl').click(function() {
	if(PLcheck===true) 
	{	var num2=0;
		for(num2=0;num2 < num_rows; num2++)
		{	$('#pl'+num2).prop('checked',false);	}
		PLcheck=false;	}
	else
	{	var num=0;
		for(num=0;num < num_rows; num++)
		{	$('#pl'+num).prop('checked','checked');		}
		PLcheck=true;	}
	});
	
	$('#ppcc').click(function() {
	if(CCcheck===true) 
	{	var CCnum2=0;
		for(CCnum2=0;CCnum2 < num_rows; CCnum2++)
		{	$('#cc'+CCnum2).prop('checked',false);	}
		CCcheck=false;	
		updateCCcolour();
		}
	else
	{	var CCnum=0;
		for(CCnum=0;CCnum < num_rows; CCnum++)
		{$('#cc'+CCnum).prop('checked','checked');		}
		CCcheck=true;
		updateCCcolour();}
	});	
	
	$('#ppfe').click(function() {if(FEcheck===true) 
	{	var FEnum2=0;
		for(FEnum2=0;FEnum2 < num_rows; FEnum2++)
		{	$('#fem'+FEnum2).prop('checked',false);		}
		FEcheck=false;	}
	else
	{	var FEnum=0;
		for(FEnum=0;FEnum < num_rows; FEnum++)
		{	$('#fem'+FEnum).prop('checked','checked');	}
		FEcheck=true;	}
	});
	
	$('#ppinst').click(function() {
	if(INSTcheck===true) 
	{	var INSTnum2=0;
		for(INSTnum2=0;INSTnum2 < num_rows; INSTnum2++)
		{	$('#inst'+INSTnum2).prop('checked',false);		}
		INSTcheck=false;	}
	else
	{	var INSTnum=0;
		for(INSTnum=0;INSTnum < num_rows; INSTnum++)
		{	$('#inst'+INSTnum).prop('checked','checked');	}
		INSTcheck=true;		}
	});
	
	$('#pppart').click(function() {
	if(PARTcheck===true) 
	{	var PARTnum2=0;
		for(PARTnum2=0;PARTnum2 < num_rows; PARTnum2++)
		{	$('#part'+PARTnum2).prop('checked',false);
			$('#cc'+PARTnum2).removeClass('canconLocked');		}
		PARTcheck=false;	}
	else
	{	var PARTnum=0;
		for(PARTnum=0;PARTnum < num_rows; PARTnum++)
		{	$('#part'+PARTnum).prop('checked','checked');
			$('#cc'+PARTnum).addClass('canconLocked');
			//$('#cc'+PARTnum).prop('checked',false);	commented out as it can be cancon and partial now	
			}
		PARTcheck=true;		}
	});

	$('#pphit').click(function() {
	if(HITcheck===true) 
	{	var HITnum2=0;
		for(HITnum2=0;HITnum2 < num_rows; HITnum2++)
		{	$('#hit'+HITnum2).prop('checked',false);		}
		HITcheck=false;	}
	else
	{	var HITnum=0;
		for(HITnum=0;HITnum < num_rows; HITnum++)
		{	$('#hit'+HITnum).prop('checked','checked');	}
		HITcheck=true;		}
	});

	numberofAdRows = $('div.adRow').length -1 ;
	$('#numberOfAdRows').val(numberofAdRows);
	console.log('set number of ad rows: '+numberofAdRows);

	// activate mass loader form
	
	$('#podcastMarker').click( function(){
		console.log('podcast marker');						
					
			var text = $.ajax({
				type: "POST",
				url: "./addmarker.php",
				data: { gonsho2433456: 'kurbleziac_q3289476b30894276'},
				
				success: function(text){
				//  alert("success!");
				console.log("<br/> success? sent data:<br/>"+text+"<br/> ");
				$('span#podcastTime').html(text);
				},
	    		error: function(XMLHttpRequest, textStatus, errorThrown) { 
	        		console.log("Status: " + textStatus); alert("Error: " + errorThrown); 

	    		}  
			});
	});

// Fetch data and fill in all fields accordingly
$('#submitDates').click(function(){	
	var startDate = $('#from').datepicker('getDate');
    var endDate   = $('#to').datepicker('getDate');
    var startHour = $("#hourFrom").val();
    var endHour = $("#hourTo").val();
    var startMin = $("#minuteFrom").val();
    var endMin = $("#minuteTo").val();
    var startInHr = Number(startHour)+Number(startMin)/60;
    var endInHr = Number(endHour)+Number(endMin)/60
    var days   = (endDate - startDate)/1000/60/60/24;	//number of days selected
    var totalHr = (endInHr + 24*Number(days))-startInHr;	//total hours selected
    
    // Non of the fields can be empty for the process to start correctly
    if (startHour==="" || endHour==="" || startDate===null || endDate===null) {
    	$('#loadStatus').html('Please fill in all fields.');
    	$("#loadStatus").fadeOut(5000, function() {
 			 $("#loadStatus").html("").fadeIn("fast");
		});  	
    }
    // positive total hours and within 8 hours
    else if (days <= 1 && totalHr >=0 && totalHr <= 8.00) {
    
    	var dayFrom = $("#from").datepicker('getDate').getDate();                 
    	var monthFrom = $("#from").datepicker('getDate').getMonth()+1;
    	if (Number(monthFrom) < 10)
   		{
    		monthFrom = "0" + monthFrom;
    	}
   		var yearFrom = $("#from").datepicker('getDate').getFullYear();
    	var fullDateFrom = yearFrom + "-" + monthFrom + "-" + dayFrom;
    


    	var dateFrom = fullDateFrom;
		dateFrom += " "+$("select#hourFrom").val();
		dateFrom += ":"+$("select#minuteFrom").val()+":00";
	
		var dayTo = $("#to").datepicker('getDate').getDate();                 
    	var monthTo = $("#to").datepicker('getDate').getMonth()+1;
    	if (Number(monthTo) < 10)
    	{
    		monthTo = "0" + monthTo;
    	}
    	var yearTo = $("#to").datepicker('getDate').getFullYear();
    	var fullDateTo = yearTo + "-" + monthTo + "-" + dayTo;
    
    	var dateTo = fullDateTo;
		dateTo += " "+$("select#hourTo").val();
		dateTo += ":"+$("select#minuteTo").val()+":00";
	
    	var dataString = 'from='+ dateFrom + '&to=' + dateTo;

		$submitted = $('#loadMulti').serialize();

		var text = $.ajax({
    	type: "POST",
   		url: "./samLoadRange.php",
    	global: false,
    	//data: $submitted,
    	data: dataString,
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
			debug.prepend("<br/> sent data:<br/>"+dataString+"<br/> serialized form data: "+$submitted+"<br/> result:");
	
    	$('#loadedPlays').after(text);
    	loadTheRange();
    	$('div#samListRange').empty();

 			 }  
		});
    }
    else{
    	$('#loadStatus').html('Maximum 8 hours. Please try again.');
    	$("#loadStatus").fadeOut(5000, function() {
 			 $("#loadStatus").html("").fadeIn("fast");
		});
    }
});

mouseX = 0;
mouseY = 0;
mouseDown = false;

jQuery(document).ready(function(){
   $(document).mousemove(function(e){
   	mouseX = e.pageX;
   	mouseY = e.pageY;
   }); 
   $(document).mousedown(function(f){
   	mouseDown = true;
   });
   
   $(document).mouseup(function(g){
   	mouseDown = false;
   });
   
})

helpSetup();
//copySetup();
});

	window.setInterval(updateSAMPlays, 15000);
//	window.setInterval(autosave,15*60*1000); //every 15 minutes 
	window.setInterval(refreshCCRatios,2000);
	window.setInterval(checkBlocking,2000);
	
	$(function(){
    /*
     * this swallows backspace keys on any non-input element.
     * stops default browser backspace -> back history behaviour
     */
    var rx = /INPUT|SELECT|TEXTAREA/i;
    
       $(":not('input, select, textarea')").keydown(function(e){
    	 if( e.which == 8 ){ // 8 == backspace
    	 
     	console.log('keydown Backspace');   
            if(!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly ){
                e.preventDefault();
            }
        }
        	
    });   
});
	console.log('Initializing Completed');