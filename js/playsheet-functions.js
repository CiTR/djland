
//VAR
	var $clickedButtonId;
	var debug;
	var num_rows; // this is initialized in playsheet-ajax.php

	var stopPop;
	
	var	yOffset = 150;
	
	$hiddenInput = $('input[name="id"]');
	
	
	var noRows = false;
	
//FUNCTIONS
    jQuery.fn.reverse = [].reverse;
    
    
   function updateDates(showID){
   
   }
  
	
	// this function inserts a new blank row in the playsheet after row number [position]
	function insertPlaysheetRow(position){
		var socan=$('#socancheck').val();
		if(socan==1)
		{
			$row = "<li id='row420' name='row420' class='playsheetRow playsheetRowSOCAN' >"+
				$('#rowtemplate').html()+
				"</li>";
		}
		else{
			$row = "<li id='row420' name='row420' class='playsheetRow' >"+
				$('#rowtemplate').html()+
				"</li>";
		}
		$row = $row.replace(/template/g,420);
		if(position<0){
			$('#rowtemplate').before($row);
		}
		else{
			$('#row'+position).after($row);
		}
		num_rows++;

		if(noRows){
			$('#row0').remove();
			noRows = false;
		}
		refreshRowIDs();
	}
	
	function ajaxLoadPlaysheet(psid){
		var playsheetID;
		if(psid){
			playsheetID = psid;
		}
		
		$('#ps-loading-image').show();
		targetShow = $('#showSelector').val();
		targetShowYear = $('#playsheet-year').val();
		targetShowMonth = parseInt($('#playsheet-month').val()) - 1;
		targetShowDay = $('#playsheet-day').val();
		targetShowDate = new Date(targetShowYear, targetShowMonth, targetShowDay);
		targetShowUnix = targetShowDate.getTime()/1000.0;
		var show_block_data;
		
		$.ajax({
			type:"POST",
			url: "form-handlers/show_info_loader.php",
			data: {"showid":targetShow, "unixTime":targetShowUnix, "psid":playsheetID},
			dataType: "json"
		}).success(function(text) {
			show_block_data = text;
			
			if(show_block_data.ads) {
				jsDate = new Date(show_block_data.time*1000);
				dur = show_block_data.duration*60*60*1000;
				jsDateEnd = new Date(show_block_data.time*1000 + dur);
				
				show_h = jsDate.getHours();
				show_m = jsDate.getMinutes();
				
				show_h_end = jsDateEnd.getHours();
				show_m_end = jsDateEnd.getMinutes();
				if(!playsheetID){
					$('#type').val(show_block_data.showtype);
					$('#unixTime').val(show_block_data.unixTime);
				}
				
				$('#showSelector').val(show_block_data.showID);
				$('#pl_date_hour').val(show_block_data.start_hour);
				$('#end_date_hour').val(show_block_data.end_hour);
				$('#pl_date_min').val(show_block_data.start_min);
				$('#end_date_min').val(show_block_data.end_min);
				$('#pl_crtc').val(show_block_data.crtc);
				$('#pl_lang').val(show_block_data.lang);
				$('#host').val(show_block_data.host);
				$('#ads').html(show_block_data.ads);
			} else {
				$('#ads').html(' No ad schedule found. Please mention station ID'+
				' at the top of every hour ');
			}
			console.log('show_block_data obj: '+show_block_data);
			console.log(show_block_data);
			$('#ps-loading-image').hide();
		
		}).fail(function(){
			$('#showOutput').html('connection error');
		});
	}	
	// CRTC HELPERS
	function getCRTC(position){
			if($('#crtcThree'+position).prop('checked')){ 
				return 3;	
			}
			else{ 
				return 2;	
			}
	}
	
	function setCRTC(val,position){
		
			if(val==3 || val==30){
				$('#crtcThree'+position).prop('checked','checked');	
			}
			else{
				$('#crtcTwo'+position).prop('checked','checked');		
			}	
	}

	function getIDFromString(stringID){
		id_num = stringID.replace(/\D/g,'');
		return parseInt(id_num);
	}

	// LANGUAGE HELPERS
	function getLang(position){
			//this is not needed? unless you want to add functionality to check language of a row.
			return $('#pl_lang').val();		
	}
	function setLang(position){
			$('#lang'+position).val(getLang(position));
			}
			
	// Copies the current row, and adds it to the bottom	
	function copyPlaysheetRow(position){					
		checkedCRTC = getCRTC(position);
		$('#row'+position).after($('#row'+position).clone(true));
		refreshRows();
		setCRTC(checkedCRTC,position);
		setCRTC(checkedCRTC,position+1);
		}


// this function adds a row to the end of the table
	function addPlaysheetRow(){
		insertPlaysheetRow(num_rows-1);	
	}
	

	function refreshRowIDs(){
		// row numbers go from 0 to [num rows-1]
		$(".playsheetRow").each(function(actualRowNum){
			$(this).attr("id","row"+actualRowNum);
			$(this).attr("name","row"+actualRowNum);

			$allDescendents = $(this).find('*');
			$allDescendents.filter( function(){ 
				return this.id.match(/[0-9]/); 
				}).each( function(){							
					oldID = $(this).attr("id");
					newID = oldID.replace(/\d/g,'')+actualRowNum;
					$(this).attr("id",newID);
					if ( $(this).attr("name") !== undefined )
					{
						var na = $(this).attr("name");
						if(na!==null && na.match(/crtc./g)){
							$(this).attr("name","crtc"+actualRowNum+"temp");
						}
						else{
							$(this).attr("name",newID);
						}
					}
				});
			num_rows=actualRowNum+1;
		});
			// fix the labels for the inputs - just set the "for" to the next sibling's "id"
			$('label.CRTCicons3').each( function(n){
				forWhat = $(this).next().attr('id');
				$(this).attr('for',forWhat);	
			});

			$(".playsheetRow").each(function(actualRowNum){
				$('#crtcTwo'+actualRowNum).attr("name","crtc"+actualRowNum);
				$('#crtcThree'+actualRowNum).attr("name","crtc"+actualRowNum);
			});
	}
	
function showStatus(status, delay){
		$('#ps_status').remove();
		$('body').append('<div id="ps_status" >'+status+'</div>');

		if(delay){
			setTimeout(function(){
				$('#ps_status').remove();
			},4000);
		}
	}
	

	function autosave(){

		$('#star').val(0);
		$('#autosaver').css('background-color','lightblue');
		showStatus('saving... please wait', false);
		console.log('autosaving');	
		$('#status').val(1);
		playsheetID = parseInt($hiddenInput.attr('value'),10);

		//  first, submit the form, then get the id of the form created
		//  needs to be a check to see if this is a new form or not
		console.log('Saving playsheet id is '+ playsheetID);
		
		if(playsheetID === 0){
			$('#playsheetForm').ajaxSubmit( function(){
				$hiddenInput.load('getLatestPlaysheetID.php', function(){
					$hiddenInput.attr('value',$hiddenInput.text());
				});	
				$('#autosaver').removeAttr('style');
				showStatus('draft saved', true);
				$('#draft').html('(draft)');
			});	
		}
		else{
			// we are making edits to an existing playsheet
			console.log('an existing playsheet');
			$('#playsheetForm').ajaxSubmit( function(){
				$('#autosaver').removeAttr('style');
				showStatus('draft saved', true);
				$('#draft').html('(draft)');
			});	
		}
	}
			
	function helpUp(container, help){
					//getting the height of container, and making it offset by half that value
					yOffset=(container.innerHeight())+15;
					container.html(help);
					container.css('left',mouseX);
					container.css('top',mouseY-yOffset);
					container.stop().fadeTo(200,1);
					container.css('display','block');
				}
	
	function helpDown(container){
					container.stop().fadeTo(100,0, function(){
						container.css('left','-2000'); //get it outta sight - so it doesn't block buttons that are behind it
					});
				//	stopPop = true;
				//	debug.prepend('stopPop true<br/>');
					// disable future hoverovers in the same region
				}
	
	
	function refreshRows(){
				refreshRowIDs();	
				setup();
				refreshCCRatios();
			}
			
//checks the playsheet rows for the first empty row.
    function checkForEmptyRow(){
		var emptyRowNum=-1;
		var emptyRowAvail=false;
		var tempRowNum=0;
		for(var i=0; i<num_rows; i++){ //check to see if there is an empty row, if there is... shove our values in there!
			if(( !$.trim($('#artist'+i).val() ) && !$.trim($('#album'+i).val()) && !$.trim($('#song'+i).val()) ) || !$('#artist'+i).val() && !$('#album'+i).val() && !$('#song'+i).val()){		
				tempRowNum=i;
				emptyRowAvail=true;
				break;
			}					
		}
		if(emptyRowAvail==false){ //if there are no rows avalable, make one!
			addPlaysheetRow();
			refreshRows();
			tempRowNum=num_rows-1;
		}
		if(tempRowNum==num_rows-1){ //if we are taking up the last visible row, add a new one!
			addPlaysheetRow();
			refreshRows();
		}
		console.log("Empty Row = " + tempRowNum);
   		return tempRowNum;	
    }
	//Allows javascript to handle HTML entities
	function htmlDecode(string) {
		$str = string;
		var ta=document.createElement("textarea");
		ta.innerHTML=$str;
		return ta.value;
	};
	
	function inputPlaysheetRows(){
		var socan=$('#socancheck').val();
		var emptyRowNum;
		$('#ps-loading-image').show();
		var e = document.getElementById("select-playsheet");
		var playsheetID = e.options[e.selectedIndex].value;
		var playsheetDate = e.options[e.selectedIndex].data;
		var emptyRow;
		var playitem_data;
		var targetArtistId;
		var targetSongId;
		var targetAlbumId;
		var targetCanconId;
		var targetFemconId;
		var targetPlaylistId;
		var targetInstId;
		var targetPartId;
		var targetHitId;
		var targetThemeId;
		var targetBGId;
		var targetCrtc2Id;
		var targetCrtc3Id;
		if(socan == 1){
			var targetComposerId;
			var targetStartHourId;
			var targetStartMinId;
			var targetDurMinId;
			var targetDurSecId;
		}
		
		
		$.ajax({
			type:"POST",
			url: "form-handlers/update-playsheet.php",
			data: {"psid":playsheetID,"socan":socan},
			dataType: "json"
		}).success(function(text) {
			/* Format of playitem_data:
			 * id (Playitem ID) , artist , album, track, composer is_pl , is_can , is_fem, is_theme, is_background, song_start_h, song_start_m, song_dur_m, song_dur_s
			 */
			playitem_data=text;
			$('#test-text').val(playitem_data.toString());
				console.log("Numrows " + num_rows);
				var count = num_rows;
				for(var i = 0; i < count; i++){
					$("#row"+i).remove();
					console.log("Removing Row "+i);
					num_rows--;
				}
				refreshRows();
				noRows=true;
				console.log("Numrows After Remove " + num_rows); 

			
			refreshRowIDs();
			if(playitem_data){
				for( $j = 0; $j < Object.keys(playitem_data).length; $j++ ){
					emptyRow = checkForEmptyRow();
					targetArtistId = '#artist' + emptyRow;
					targetSongId = '#song' + emptyRow;
					targetAlbumId = '#album' + emptyRow;
					targetCanconId = '#cc' + emptyRow;
					targetFemconId = '#fem' + emptyRow;
					targetPlaylistId = '#pl' + emptyRow;
					targetInstId = '#inst' + emptyRow;
					targetPartId = '#part' + emptyRow;
					targetHitId = '#hit' + emptyRow;
					targetThemeId = '#theme' + emptyRow;
					targetBGId = '#background' + emptyRow;
					targetCrtc2Id = '#crtcTwo' + emptyRow;
					targetCrtc3Id = '#crtcThree' + emptyRow;
					if(socan == 1){
						targetComposerId='#composer' + emptyRow;
						targetStartHourId= '#set_song_start_hour' + emptyRow;
						targetStartMinId= '#set_song_start_minute' + emptyRow;
						targetPmCheckId= '#pmCheck' + emptyRow;
						targetDurMinId= '#set_song_length_minute' + emptyRow;
						targetDurSecId= '#set_song_length_second' + emptyRow;
					}
					
					/*
					 * Setting Values
					 */
					$(targetArtistId).val(htmlDecode(playitem_data[$j].artist));
					$(targetSongId).val(htmlDecode(playitem_data[$j].track));
					$(targetAlbumId).val(htmlDecode(playitem_data[$j].album));
					if(socan==1){
						$(targetComposerId).val(htmlDecode(playitem_data[$j].composer));
						$(targetStartHourId).val(playitem_data[$j].song_start_h);
						$(targetStartMinId).val(playitem_data[$j].song_start_m);
						$(targetDurMinId).val(playitem_data[$j].song_dur_m);
						$(targetDurSecId).val(playitem_data[$j].song_dur_s);
					}
					// Is it Cancon?
					if(playitem_data[$j].is_can == 1) {
						$(targetCanconId).attr('checked', 'checked');
					}
					else{
						$(targetCanconId).replaceWith('<input class="mousedragclick" type="checkbox" id="cc'+ emptyRow +'" name="cc'+ emptyRow +'">');	
					}
					// Is if Femcon?
					if( playitem_data[$j].is_fem == 1) {
						$(targetFemconId).attr('checked', 'checked');
					} 
					else {
						$(targetFemconId).replaceWith('<input class="mousedragclick" type="checkbox" id="fem'+ emptyRow +'" name="fem'+ emptyRow +'">');	
					}
					// Is it playlisted?
					if( playitem_data[$j].is_pl == 1 ) {
						$(targetPlaylistId).attr('checked', 'checked');
					}
					else{
						$(targetPlaylistId).replaceWith('<input class="mousedragclick" type="checkbox" id="pl'+ emptyRow +'" name="pl'+ emptyRow +'">');	
					}
					// Is it Instrumental?
					if( playitem_data[$j].is_inst == 1 ) {
						$(targetInstId).attr('checked', 'checked');
					}
					else{
						$(targetInstId).replaceWith('<input class="mousedragclick" type="checkbox" id="inst'+ emptyRow +'" name="inst'+ emptyRow +'">');	
					}
					// Is it partial?
					if( playitem_data[$j].is_part == 1 ) {
						$(targetPartId).attr('checked', 'checked');
					}
					else{
						$(targetPartId).replaceWith('<input class="mousedragclick" type="checkbox" id="part'+ emptyRow +'" name="part'+ emptyRow +'">');	
					}
					
					// Is it a hit? (Boooo!)
					if( playitem_data[$j].is_hit == 1 ) {
						$(targetHitId).attr('checked', 'checked');
					}
					else{
						$(targetHitId).replaceWith('<input class="mousedragclick" type="checkbox" id="hit'+ emptyRow +'" name="hit'+ emptyRow +'">');	
					}
					// Is it a themesong?
					if( playitem_data[$j].is_theme == 1 ) {
						$(targetThemeId).attr('checked', 'checked');
					}
					else{
						$(targetThemeId).replaceWith('<input class="mousedragclick" type="checkbox" id="theme'+ emptyRow +'" name="theme'+ emptyRow +'">');	
					}
					// Is it a background?
					if( playitem_data[$j].is_background == 1 ) {
						$(targetBGId).attr('checked', 'checked');
					}
					else{
						$(targetBGId).replaceWith('<input class="mousedragclick" type="checkbox" id="background'+ emptyRow +'" name="background'+ emptyRow +'">');	
					}
					// CRTC category
					if( playitem_data[$j].crtc == 30 ){
						$(targetCrtc3Id).attr('checked','checked');
						$(targetCrtc2Id).replaceWith("<input class='radio mousedragclick CRTCicons3' type='radio' id='crtcTwo" + emptyRow + "' name='crtc" + emptyRow + "' value='20'>");
					}
					else{
						$(targetCrtc2Id).attr('checked','checked');
						$(targetCrtc3Id).replaceWith("<input class='radio mousedragclick CRTCicons3' type='radio' id='crtcThree" + emptyRow + "' name='crtc" + emptyRow + "' value='30'>");
					}					
				}	
			}
			$('#ps-loading-image').hide();
		}).fail(function(){
			$('#showOutput').html('connection error');
			});
			ajaxLoadPlaysheet(playsheetID);
		}
		

	
			
	// sourceList specifies the location from which to pull a SAM song into the playsheet
	// sourceList can be:
	// "SamListRecent" refers to the last 50 played songs
	// "SamListRange" refers to the results of the Load Times command
	function SAMtoPlaysheet(sourceList,srcRowNum,destRowNum){
		var socan=$('#socancheck').val();
		var emptyRowAvail=false;
		var tempRowNum=destRowNum;
		var sourceDivName = 'div#'+sourceList;
		var sourceRowId = sourceDivName+' > #song-'+srcRowNum;
		refreshRows();
		tempRowNum = checkForEmptyRow();
		if(tempRowNum == -1){
			tempRowNum = checkForEmptyRow();
			}
			
		var targetArtistId= '#artist'+tempRowNum;
		var targetSongId= '#song'+tempRowNum;
		var targetAlbumId= '#album'+tempRowNum;
		var targetCanconId= '#cc'+tempRowNum;
		var targetFemconId= '#fem'+tempRowNum;
		var targetPlaylistId = '#pl'+tempRowNum;
		
		var artist = $(sourceRowId).children('#thisArtist').html();
		var song = $(sourceRowId).children('#thisSong').html();
		var album = $(sourceRowId).children('#thisAlbum').html();
		var cancon = $(sourceRowId).children('#cancon').html();
		var femcon = $(sourceRowId).children('#femcon').html();
		var songType = $(sourceRowId).children('#songType').html();
		var songCategory = $(sourceRowId).children('#songCategory').html();
		
		//Socan Attributes
		if(socan==1){
		var targetComposerId='#composer'+tempRowNum;
		var targetStartHourId= '#set_song_start_hour'+tempRowNum;
		var targetStartMinId= '#set_song_start_minute'+tempRowNum;
		var targetPmCheckId= '#pmCheck'+tempRowNum;
		var targetDurMinId= '#set_song_length_minute'+tempRowNum;
		var targetDurSecId= '#set_song_length_second'+tempRowNum;
		var composer = $(sourceRowId).children('#thisComposer').html();
		var hour = $(sourceRowId).children('#thisHour').html();
		var minute = $(sourceRowId).children('#thisMinute').html();
		var pmCheck = $(sourceRowId).children('#pmCheck').html();
		var durMin = $(sourceRowId).children('#durMin').html();
		var durSec = $(sourceRowId).children('#durSec').html();
		}
		minute=parseInt(minute);
		if((pmCheck=='pm')&(hour != 12))
		{
			hourtemp= parseInt(hour);
			hourtemp= hourtemp+12;
			hour=hourtemp;
		}

		$(targetArtistId).attr('value', htmlDecode(artist));
		$(targetSongId).attr('value', htmlDecode(song));
		$(targetAlbumId).attr('value', htmlDecode(album));
		
		if( cancon==1) {
			$(targetCanconId).attr('checked', 'checked');
		} else {
			$(targetCanconId).replaceWith('<input class="mousedragclick" type="checkbox" id="cc'+tempRowNum+'" name="cc'+tempRowNum+'">');	
		}
		
		if( femcon==1) {
			$(targetFemconId).attr('checked', 'checked');
		} else {
			$(targetFemconId).replaceWith('<input class="mousedragclick" type="checkbox" id="fem'+tempRowNum+'" name="fem'+tempRowNum+'">');	
		}
		
		if( songType=='PL' ) {
			$(targetPlaylistId).attr('checked', 'checked');
		}
		if(socan==1){
			$(targetComposerId).val(composer);
			$(targetStartHourId).val(hour);
			$(targetStartMinId).val(minute);
			$(targetDurMinId).val(durMin);
			$(targetDurSecId).val(durSec);
		}
		
		if ( songCategory.indexOf("ategory") !== -1){ // only if the string contains 'ategory' (don't want to worry about capital C or not
			songCategory = songCategory.replace(/\D/g,''); // strip all non numeric digits
			setCRTC(songCategory,tempRowNum);
		} 
}

	function loadTheRange(){
		debug.prepend('loading the range<br/>');
		var count = $('#SamListRange > .samsong').size();
		debug.prepend('count: '+count+'<br/>');
		
//		for (var i = 0; i<count ; i++
		
		$('#SamListRange > .samsong').reverse().each(function(){
		
			var num = $(this).attr('id').replace(/\D/g,'');	
			SAMtoPlaysheet('SamListRange',num,num_rows-1);
			
			
		});
		
			debug.prepend("i'm here<br/>");
	}

	function updateSAMPlays(){

		var pathname = window.location.pathname;
		$("#SamList").load("./samLoadRecent.php  #SamListRecent");

		}
		
		
		
	function refreshCCRatios() {
		
		star = true;
		
		//bogus way to know # rows
		rows=0;
		/*$(".playsheetRow").each(function() { 
			rows++;});
		*/	
		rows = num_rows;
		
		//reset the counts
		cctype2count = 0;
		cctype3count = 0;
		// determine how many CRTC 2 and 3 total
		
		crtc2total = 0;
		crtc3total = 0;
		
		for (i =0; i< rows; i++){
			ccvar = i.toString();
			ccid = "cc" + ccvar;	
			cctype2id = "crtcTwo" + i;
			cctype3id = "crtcThree" + i;
			partid= "part" +i;
			if ($('#' + cctype2id).prop('checked') & !$('#' + ccid).hasClass('canconLocked')) {
				crtc2total++;
			}
			else if( $('#' + cctype2id).prop('checked') & !$('#' + ccid).prop('checked') )
			{
				crtc2total++;
			}
			
			if ($('#' + cctype3id).prop('checked') & !$('#' + ccid).hasClass('canconLocked')) {
			//if it is partial, it does not add it to the cancon total
			

			crtc3total++;
			}
			else if( $('#' + cctype3id).prop('checked') & !$('#' + ccid).prop('checked') )
			{
				crtc3total++;
			}
			
		}
		//determine how many of type 2 and type 3 cc
		for (var i = 0; i < rows; i++) {
			ccvar = i.toString();
			ccid = "cc" + ccvar;
			cctype2id = "crtcTwo" + i;
			cctype3id = "crtcThree" + i;
			
			
			if ($('#' + ccid).prop('checked') & !$('#' + ccid).hasClass('canconLocked')) {
				if ($('#' + cctype2id).prop('checked')) {
					cctype2count++;
				} 
				else  if ($('#' + cctype3id).prop('checked')){
					cctype3count++;
				}
			}
		}

		debug.prepend("Type2 total--> " + cctype2count + "</br>");
		debug.prepend("Type3 total--> " + cctype3count + "</br>");

		if (cctype2count > 0) {
			type2content = cctype2count / crtc2total;
		} else {								
			type2content = 0;
		}

		if (cctype3count > 0) {
			type3content = cctype3count / crtc3total;
		} else {
			type3content = 0;
		}			
		t2 = Math.round(type2content * 100);
		t3 = Math.round(type3content * 100);
		
		if (crtc2total === 0){
			$('#CCType2Ratio').html("--");
			t2 = 100;
		} else {
			$('#CCType2Ratio').html(t2+"%");
		}
		if (crtc3total === 0){
			$('#CCType3Ratio').html("--");
			t3 = 100;
		}
		else {
			$('#CCType3Ratio').html(t3+"%");
		}
		
		$('#CCType2Num').html(cctype2count);
		$('#CCType3Num').html(cctype3count);
		
		$('#Type2Total').html(crtc2total);
		$('#Type3Total').html(crtc3total);
		
		if (t2 >= 35 ) {
			$('#CCType2Ratio').css('color','green');
		
		} else {
			
			$('#CCType2Ratio').css('color','red');
			star = false;
		}
		
		
		if (t3 >= 12 ) {
			$('#CCType3Ratio').css('color','green');
		
		} else {
			
			$('#CCType3Ratio').css('color','red');
			star = false;
		}
		
		
		x = 0;
		for(i=0; i<rows; i++){
			
			hitvar = i.toString();
			hitID = "#hit" + hitvar;
			
			if ( $(hitID).prop('checked')){
			x++;
			}
		}
		
		hitratio = x/rows;
		
		hitratio = Math.round(hitratio * 100);
		
		$('#hitNum').html(x);
		$('#hitRatio').html(hitratio+"%");
		$('#total').html(rows);
		
		if (hitratio > 10 ) {
			$('#hitRatio').css('color','red');
			star = false;
		
		} else {
			
			$('#hitRatio').css('color','green');
		}
		
		
		
		x = 0;
		for(i=0; i<rows; i++){
			
			femvar = i.toString();
			femID = "#fem" + femvar;
			
			if ( $(femID).prop('checked')){
			x++;
			}
		}
		
		femratio = x/rows;
		
		femratio = Math.round(femratio * 100);
		
		$('#femNum').html(x);
		$('#femRatio').html(femratio+"%");
		$('#total').html(rows);
		
		if (femratio < 35 ) {
			$('#femRatio').css('color','red');
			star = false;
		
		} else {
			
			$('#femRatio').css('color','green');
		}
		
		
		x = 0;
		for(i=0; i<rows; i++){
			
			plvar = i.toString();
			plID = "#pl" + plvar;
			
			if ( $(plID).prop('checked')){
			x++;
			}
		}
		
		plratio = x/rows;
		
		plratio = Math.round(plratio * 100);
		
		$('#plNum').html(x);
		$('#plRatio').html(plratio+"%");
		$('#total').html(rows);
		
		if (plratio < 15 ) {
			$('#plRatio').css('color','red');
			star = false;
		
		} else {
			
			$('#plRatio').css('color','green');
		}
		
		if(star){
			$('#star').val(1);
			$('.stars').html('&#9733;&#9733;');
			
		}else{
			$('#star').val(0);
			$('.stars').html('');
		}	
		
	}
	
	