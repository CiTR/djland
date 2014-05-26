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
						if(socan==true){
							$(j + ' input,'+j+' .delrow, '+j+' .copyRow, '+j+ ' .dragHandle,'+j+' label,' +j+ ' div,' +j+ '.nowButton,' +j+ 'span').remove();
						}
						else{
							$(j + ' input,'+j+' .delrow, '+j+' .copyRow, '+j+ ' .dragHandle,'+j+' label,' +j+ ' div,' +j+ '.nowButton').remove();
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
					updateCCcolour();
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
		