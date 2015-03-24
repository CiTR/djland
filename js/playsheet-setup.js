	albumHelp =			"Enter the title of the album, EP, or single that the track is released on."
						+"If playing an mp3 or streaming from youtube, soundcloud etc, please take a moment to find the title of the album,"
						+"EP, or single that the track is released on. If it is unreleased, enter 'unreleased'. "
						+"If you are confused about what to enter here, please contact music@citr.ca This will help the artist chart "
						+"and help provide listeners with information about the release.";
	artistHelp =		"Enter the name of the <b>artist</b>";
	compHelp = 			"Enter the name of the <b>composer</b> or <b>author</b>";
	timeHelp1 =			"<b>Hit the CUE button when the song starts playing </b>. Or enter the <b>start</b> time. Time Format is HOUR:MIN";
	timeHelp2 =			"<b>Hit the END button when the song stops playing</b>. Enter the <b>duration</b> of the song.Time Format is MIN:SECOND";
	plHelp =			"<b>Playlist</b> (New) Content: Was the song released in the last 6 months? ";
	ccHelp =			"<b>Cancon</b>: two of the following must apply: Music written by a Canadian, Artist performing it is Canadian, Performance takes place in Canada, Lyrics Are written by a Canadian";
	feHelp =			"<b>Femcon</b>: two of the following must apply: Music is written by a female, Performers (at least one) are female, Words are written by a female, Recording is made by a female engineer.";
	instHelp =			"Is the song <b>instrumental</b>? (no vocals)";
	partHelp =			"<b>Partial</b> songs: For a track to count as cancon, you need to play the whole thing and it must be at least 1 minute.";
	hitHelp =			"Has the song ever been a <b>hit</b> in Canada?  By law, the maximum is 10% Hits played, but we aim for 0% - you really shouldn't play hits!";
	themeHelp =			"Is the song your themesong?";
	backgroundHelp =	"Is the song playing in the background? Talking over the intro to a song does not count as background";
	crtcHelp =			"<b>Category 2</b>: Rock, Pop, Dance, Country, Acoustic, Easy Listening.  <b>Category 3</b>: Concert, Folk, World Beat, Jazz, Blues, Religious, Experimental. <a href='http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'>Click for more info</a>";
	songHelp =			"Enter the name of the <b>song</b>";
	langHelp =			"The <b>language</b> of the song";
	adsHelp =			"Station IDs must be played or spoken in the first ten minutes of every hour";
	guestsHelp =		"Any <b>non-music features</b> on your show.  This helps us to reach our 15% local spoken word minimum";
	toolsHelp=			"<b>Tools:</b><br/> [-] Delete the row <br/> [+]Add a new row below <br/> <!-- -Copy Row-->";

	function setup(){
				//  stopPop = false;
				//	debug.prepend('stopPop false<br/>');
	
	
				// ADD ROW
				$('.addRow').unbind('click');
				$('.addRow').click(function(){
					addRowNum = $(this).attr("id").replace(/\D/g,'');
					addRowNum = getIDFromString($(this).attr("id"));
					//num_rows is incremented in insertPlaysheetRow method
					insertPlaysheetRow(addRowNum);
					//refresh rows, or refresh row ID's must be called to update our values visually
					refreshRows();
					var crtc = $('#pl_crtc').val();
					setCRTC(crtc,addRowNum+1);
					setLang(addRowNum+1);
					//addRowNum++;
				});
				// COPY ROW
				$('.copyRow').unbind('click');
				$('.copyRow').click(function(){
					$thisCopyRowNum = $(this).attr("id").replace(/\D/g,'');
					copyPlaysheetRow($thisCopyRowNum);
				});

				
				// DELETE ROW 
				$('.delRow').unbind('click');
				$('.delRow').click(function(){
					var socan=$('#socancheck').val();
					$thisDelRowNum = $(this).attr("id").replace(/\D/g,'');
					if (num_rows>1){
						$("#row"+$thisDelRowNum).remove();
						num_rows--;
					refreshRows();
					} else {
						noRows = true;
						var j = '#row'+$thisDelRowNum;
						if(socan==1){
							$(j + ' input,'+j+' .delrow, '+j+' .copyRow, '+j+ ' .dragHandle,'+j+' label,' +j+ ' div,' +j+ ' .nowButton,' +j+ ' .timeInner,' +j+ ' .CRTCicons,' + j + '.inputBoxesSocan').remove();
						}
						else{
							$(j + ' input,' + j + ' .inputBoxes,'+j+' .delrow, '+j+' .copyRow, '+j+ ' .dragHandle,'+j+' label,' +j+ ' div,' +j+ '.nowButton').remove();
						}
						num_rows = 0;
					}

				});
				// ADD FIVE ROWS
				$('#addfive').unbind('click');
				$('#addfive').click(function(){
					console.log('Danger Will Robinson, Five New Rows Imminent!');
					//num_rows is incremented in insertPlaysheetRow method
					var crtc = $('#pl_crtc').val();
					noRows=false;
					if(num_rows>0){
					/* if rows, add 5 more rows */						
					for(p=0; p<5; p++)
						{
						addPlaysheetRow();
						setCRTC(crtc,num_rows-1);
						setLang(num_rows-1);
						}}
					else{ 
					/* if no rows, add 6 and remove first, as it will only contain the + button  */
					insertPlaysheetRow(num_rows);
					$('#row0').remove();
					
					for(p=0; p<5; p++)
						{
						insertPlaysheetRow(num_rows-1);
						setCRTC(crtc,num_rows-1);
						setLang(num_rows-1);
						}
						
					}
					refreshRows();
				});	
	
				
			//CUE button, to input current time to start time SOCAN field
			$('.getStartTime').click(function (){
			console.log("CUE button clicked");
			var d = new Date();
			TimeRowNum = $(this).attr("id").replace(/\D/g,'');
			var current_day=d.getDate();
			var current_hour=d.getHours();
			var current_minute=d.getMinutes();
			var current_second=d.getSeconds();
			$('#set_song_start_date'+TimeRowNum).val(current_day);
			$('#set_song_start_hour'+TimeRowNum).val(current_hour);
			$('#set_song_start_minute'+TimeRowNum).val(current_minute);
			$('#set_song_start_second'+TimeRowNum).val(current_second);
			});
			
			//END button, to calculate and input song length to duration SOCAN field
			$('.getEndTime').click(function () {
			console.log("END button clicked");
			var d2 = new Date();
			TimeRowNum = $(this).attr("id").replace(/\D/g,'');
			var current_day2=d2.getDate();
			var current_hour2=d2.getHours();
			var current_minute2=d2.getMinutes();
			var current_second2=d2.getSeconds();
			var start_day=$('#set_song_start_day'+TimeRowNum).val();
			var start_hour=$('#set_song_start_hour'+TimeRowNum).val();
			var start_minute=$('#set_song_start_minute'+TimeRowNum).val();
			var start_second=$('#set_song_start_second'+TimeRowNum).val();
			var dur_hour;
			var dur_minute;
			var dur_second;
			
			//Rollover corrections (Day,Hour, Minute) ie. rolling over a day, the hour goes to 24, and must go to 0.
			if((current_day2)>(start_day))
			{	dur_hour=(24-start_hour)+current_hour2;	}
			else
			{	dur_hour=current_hour2-start_hour;	}

			if((dur_hour)>0)
			{	dur_minute=(60-start_minute)+current_minute2;
					}
			else
			{	dur_minute=current_minute2-start_minute;	
					}
			
			if((dur_minute)>0)
			{	dur_second=(60-start_second)+current_second2;
					}
			else
			{	dur_second=current_second2-start_second;
					}

		if(dur_second<=59 && dur_minute>0)
		{
		dur_minute-=1;
		//console.log('keeping below 1 min');
		}
		if(dur_second > 59)
		{
		dur_second-=60;
		//console.log('sub 60 from second');
		}

			
			
			//console.log(dur_minute);
			//console.log(dur_second);
			$('#set_song_length_minute'+TimeRowNum).val(dur_minute);
			$('#set_song_length_second'+TimeRowNum).val(dur_second);
			});
	

				
				
				$("#sortable").sortable({
					update: function(event,ui) {
					// on drop (when a row is dropped into a new location)
					// sort through the rows and re-assign id numbers	
					refreshRows();	
				}
				
				});	
				
				
				$('#numberOfRows').val(num_rows);
				
					
					
				$('.mousedragclick').click(function(){
					
				
				// crazy checkbox logic.. too confusing to handle right now.
				// dragging across checkboxes multiple times doesn't work
				/*	if( ($(this).prop('checked')!='true') || ($(this).attr('checked')!='true') || ($(this).attr('checked')=='false') || ($(this).prop('checked')=='false') ){
						// if this is not checked
					$(this).prop('checked','true');
					} else {$(this).prop('checked','false').removeAttr('checked');}
				*/
				});
				$('.mousedragclick').hover(function(){
					if (mouseDown) {
					$(this).attr('checked','checked');
					$(this).prop('checked','true');
					$(this).change();
					
					
					//$(this).trigger('click');
					//$(this).click();
					console.log('clicky');
					}
				}, function(){ //do nothing
				}
				
				);
				

				// If partial song is checked, gives CC a class called ccLocked
				$("[name*='part']").change(function(){
					
					$thisID = $(this).attr("id").replace(/\D/g,'');
					
				//	if ($(this).attr('checked','true')){
					if ($(this).prop('checked')){
						// the check is active
						
						//Replaced w/ below line, as we can have a Cancon Partial song (long story...)
						// $('#cc'+$thisID).attr('checked','false').removeAttr('checked')
																// .addClass('canconLocked');
						$('#cc'+$thisID).addClass('canconLocked');										
					} else {
						$('#cc'+$thisID).removeClass('canconLocked');
					}
					
					
					
				//	refreshCCRatios();
				});
				//Locks the Cancon if "partial" has been selected and it now has class ccLocked
				$("[name*='cc']").change(function(){

					// if ($(this).hasClass('canconLocked')){
						// $(this).attr('checked','false').removeAttr('checked');
					// }
				});
	
				
			//	$('#pppl').attachHelp(plHelp);
				$('.rowLabel').each(function(){
					$number = parseInt($(this).attr("id").replace(/\D/g,''),10);
					$(this).html(($number+1)+'');
				});
		$('input, select, textarea, #spokenword, #req').unbind('keypress');		


		//Commented out to allow for hitting return key for new row inside textbox, if submit button not disabled. -Evan
		// $('input, select, textarea').keypress(function(e){
			// if( e.which == 13 ){ // 13 is enter
					// console.log('keydown Enter');
		
				// if (!$('#submit').attr('disabled')){
						// console.log('submit not disabled');
							
						// e = e || window.event;//except for older IE versions(?), which we correct here
						// //do whatever, if the form shouldn't be submitted:
						// if (!confirm("Would you like to submit this playsheet?"))
						// {
						// console.log('confirmed!');	
						// e.preventDefault();
						// e.stopPropagation();//<-- important line here
						// e.stopImmediatePropagation();
						// return false;
						
						// }
						// console.log('not confirmed!');
					
					// }
				// }
		// });
		
	}
	var allFilledIn;
	function checkBlocking(){
		
		allFilledIn = true;
		$('.req').each(function(){
		if((!$.trim($(this).val())) & !($(this).css('display')=='none'))
		{
		allFilledIn=false; 
		}
		});
		
		if(noRows)
		{
		allFilledIn=true;
		}
		
		if (allFilledIn){
		$('#submit').attr('disabled',false);
		$('#submitMsg').hide();
//		$('#submitMsg').html('');
		} else {
		$('#submit').attr('disabled',true);
		$('#submitMsg').show();
		}
		
		$('#status').val(2);
	}
//Hoverbox to attach to + button for copyrow
	function copySetup(){
		// $thisCopyRowNum = $(this).attr("id").replace(/\D/g,'');
		
		$copybox = $('div#copybox');
		copyText = "<a class='copyRow'>copy</a>";
		// fadeKeeper = false;

		$copybox.hover( function() { } );
		$('#copyText').hover( function () { copyUp($copybox,copyText); }, function () { copyDown($copybox);});
	
	}


	function helpSetup(){

				$helpboxARTIST = $('div#helpboxARTIST');
				$helpboxALBUM = $('div#helpboxALBUM');
				$helpboxSONG = $('div#helpboxSONG');
				$helpboxCOMP = $('div#helpboxINST');
				$helpboxTIME = $('div#helpboxINST');
				$helpboxPL = $('div#helpboxPL');
				$helpboxCC = $('div#helpboxCC');
				$helpboxFE = $('div#helpboxFE');
				$helpboxINST = $('div#helpboxINST');
				$helpboxPART = $('div#helpboxPART');
				$helpboxHIT = $('div#helpboxHIT');
				$helpboxTHEME = $('div#helpboxARTIST');
				$helpboxBACKGROUND = $('div#helpboxPL');		
				$helpboxCRTC = $('div#helpboxCRTC');
				$helpboxLANG = $('div#helpboxLANG');
				$helpboxTOOLS = $('div#helpboxTOOLS');
				$helpboxGUEST = $('div#helpboxGUEST');
				$helpboxAD = $('div#helpboxAD');
				
				
				fadeKeeper = false;
				
				$('#ppalbum').hover( function(){
					helpUp($helpboxALBUM,albumHelp);
					
					},				function(){
					helpDown($helpboxALBUM);
					});
				
				$('#ppartist').hover( function(){
					helpUp($helpboxARTIST,artistHelp);
					},				function(){
					helpDown($helpboxARTIST);
					});
				
				$('#ppartist').hover( function(){
					helpUp($helpboxARTIST,artistHelp);
					},				function(){
					helpDown($helpboxARTIST);
					});
					
				$('#ppcomp').hover( function(){
					helpUp($helpboxCOMP,compHelp);
					},				function(){
					helpDown($helpboxCOMP);
					});
										
				$('#pptime1').hover( function(){
					helpUp($helpboxPART,timeHelp1);
					},				function(){
					helpDown($helpboxPART);
					});
					
				$('#pptime2').hover( function(){
					helpUp($helpboxPART,timeHelp2);
					},				function(){
					helpDown($helpboxPART);
					});
				
				$('#pppl').hover( function(){
					helpUp($helpboxPL,plHelp);
					},				function(){
					helpDown($helpboxPL);
					});
				
				$('#ppcc').hover( function(){
					helpUp($helpboxCC,ccHelp);
					},				function(){
					helpDown($helpboxCC);
					});
				
				$('#ppfe').hover( function(){
					helpUp($helpboxFE,feHelp);
					},				function(){
					helpDown($helpboxFE);
					});
				
				$('#ppinst').hover( function(){
					helpUp($helpboxINST,instHelp);
					},				function(){
					helpDown($helpboxINST);
					});
				
				$('#pppart').hover( function(){
					helpUp($helpboxPART,partHelp);
					},				function(){
					helpDown($helpboxPART);
					});
				
				$('#pphit').hover( function(){
					helpUp($helpboxHIT,hitHelp);
					},				function(){
					helpDown($helpboxHIT);
					});
				
				$('#ppbackground').hover( function(){
					helpUp($helpboxPART,backgroundHelp);
					},				function(){
					helpDown($helpboxPART);
					});
				
				$('#pptheme').hover( function(){
					helpUp($helpboxTHEME,themeHelp);
					},				function(){
					helpDown($helpboxTHEME);
					});
				
				$('#ppcrtc').hover( function(){
					helpUp($helpboxCRTC,crtcHelp);
					},				function(){
					helpDown($helpboxCRTC);
					});
				
				$('#ppsong').hover( function(){
					helpUp($helpboxSONG,songHelp);
					},				function(){
					helpDown($helpboxSONG);
					});
				
				$('#pplang').hover( function(){
					helpUp($helpboxLANG,langHelp);
					},				function(){
					helpDown($helpboxLANG);
					});
				
				$('#ppAds').hover( function(){
					helpUp($helpboxAD,adsHelp);
					},				function(){
					helpDown($helpboxAD);
					});
				
				$('#ppGuests').hover( function(){
					helpUp($helpboxGUEST,guestsHelp);
					},				function(){
					helpDown($helpboxGUEST);
					});
				$('#pptools').hover( function(){
					helpUp($helpboxTOOLS,toolsHelp);
					},				function(){
					helpDown($helpboxTOOLS);
					});
					
	}















	//READY
	$(document).ready(function() {

		socan = false;/*<?php if ($SOCAN_FLAG) {echo "true";} else {echo "false";} ?>;*/

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
		$('#showSelector').change( function(){ if($('#type').val() !="Rebroadcast") ajaxLoadPlaysheetMetaDataAndAdSchedule(); } );
		$('#playsheet-year').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheetMetaDataAndAdSchedule(); } );
		$('#playsheet-month').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheetMetaDataAndAdSchedule(); } );
		$('#playsheet-day').change( function(){ if($('#type').val()!="Rebroadcast") ajaxLoadPlaysheetMetaDataAndAdSchedule(); } );


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

					$clickedButtonId = $(this).attr("id").replace(/\D/g,'');
					SAMtoPlaysheet('SamListRecent',$clickedButtonId,num_rows-1);
				});

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
			}
			else
			{	var CCnum=0;
				for(CCnum=0;CCnum < num_rows; CCnum++)
				{$('#cc'+CCnum).prop('checked','checked');		}
				CCcheck=true;
			}
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