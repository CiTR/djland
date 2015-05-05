$(document).ready ( function() {
	var id = getText('member_id');	
	var member = queryMember(id);

	//Query membership years associated with the member
	var membership_years = queryMembershipYears(id);
	//Query the most recent year found for the member
	var membership_year = queryMembershipYear(id,membership_years[0]);
	
	addListeners();
	
	//Display the information on the page
	displayMemberInfo(member);
	displayMemberInterests(membership_year);

	//periodically check if the user has filled out all fields
	window.setInterval(checkBlocking,1000);
});

function addListeners(){
	//RADIO BUTTON LISTENERS
	$('.can_status').unbind().click( function(){
		if( this.id =='can1'){
			$('#can2').removeAttr("checked");
		}
		else{
			$('#can1').removeAttr("checked");
		}
	});
	$('.show_select').unbind().click( function(){
		if( this.id =='show1'){
			$('#show2').removeAttr("checked");
		}
		else{
			$('#show1').removeAttr("checked");
		}
	});
	$('.alumni_select').unbind().click( function(){
		if( this.id =='alumni1'){ 
			$('#alumni2').removeAttr("checked");
		}
		else{
			$('#alumni1').removeAttr("checked");
		}
	});
	$('#faculty').change(function (){
		
		if($('#faculty').val() == "Other"){
			$('#faculty2').show();
		}else{
			$('#faculty2').hide();
		}	
	});

	$('#submit_user').click( function() {
		updateMember(getMemberInfoFromPage(),getMemberInterestsFromPage());
	});

//MEMBER TYPE: HIDE/SHOW STUDENT
	$('#member_type').unbind().change( function(){
		if( ($('#row6').hasClass('loaded') || $('#row6').css('display') == 'none') && (getVal('member_type') == 'Student' || getVal('member_type') == 'student' )){
			$('#row6').show();
			$('#row6').children().show();
			$('#row7').show();
			$('#row7').children().show();
			//console.log("show student");
		}
		else if(getVal('member_type') == 'Student' || getVal('member_type') == 'student' ){
			var row6 = $('#row6');
			var row7 = $('#row7');
			row6.append("<div class='col5'>Faculty*: </div>");
			row6.append("<div class='col5'><select id=faculty></select><input id='faculty2' style='display:none' placeholder='Enter your Faculty'/></div>");
			var title = ['Arts','Applied Science','Architecture','Archival Studies','Audiology','Business','Community Planning','Continuing Studies','Dentistry','Doctoral Studies','Education','Environmental Health','Forestry','Graduate Studies','Journalism','Kinesiology','Land and Food Systems','Law','Medicine','Music','Nursing','Pharmaceutical','Public Health','Science','Social Work','Other'];
			var values =  ['Arts','Applied Science','Architecture','Archival Studies','Audiology','Business','Community Planning','Continuing Studies','Dentistry','Doctoral Studies','Education','Environmental Health','Forestry','Graduate Studies','Journalism','Kinesiology','Land and Food Systems','Law','Medicine','Music','Nursing','Pharmaceutical','Public Health','Science','Social Work','Other'];
			$searchval = $('#faculty');
			for($i = 0; $i< title.length; $i++){
				$searchval.append("<option value='"+values[$i]+"'>"+title[$i]+"</option>");
			}
			row6.append("<div id='student_no_container'> \
			<div class='col5'>Student Number*:</div> \
			<div class='col5'><input id='student_no' name='student_no' maxlength='10' placeholder='Student Number' onKeyPress='return numbersonly(this, event)''></input></div> \
			</div>");
			row7.append("<div class='col1'>I would like to incorporate CiTR into my courses(projects, practicums, etc.): \
				<input id='integrate'  name='integrate' type='checkbox'/> \
				<div class='col5'>Year*:</div> \
				<div class='col8'> \
					<select id='year' style='z-position=10;'> \
						<option value='1'>1</option> \
						<option value='2'>2</option> \
						<option value='3'>3</option> \
						<option value='4'>4</option> \
						<option value='5'>5+</option> \
					</select> \
				</div></div>");
			//console.log("Create student");
		}else{
			$('#row6').hide();
			$('#row7').hide();
			//console.log("Hide student fields");
		}
	});
	
}
function checkBlocking(){
		var allOkay = true;
		if(getVal('member_type')=='Student'){
			if(!$.trim(getVal('student_no'))){
				allOkay=false;
			}
			if($('#student_no_ok').length > 0 && $('#student_no_ok').text() != "Okay"){
				allOkay=false;
			}
		}
		if (allOkay){
		$('#submit_user').attr('disabled',false);
		$('#submit_user').text("Submit");
		$('#submit_user').removeClass("red");
		}else{
			$('#submit_user').attr('disabled',true);
			$('#submit_user').text("Form Not Complete");
			$('#submit_user').addClass("red");
		}
	}

	