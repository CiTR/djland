$(document).ready ( function() {
	var id = getText('member_id');	
	var member = queryMember(id);
	//Query membership years associated with the member
	var membership_years = queryMembershipYears(id);
	//Query the most recent year found for the member
	var membership_year = queryMembershipYear(id,membership_years.membership_years[0]);
	
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
		//TODO: updateMemberInfo(getMemberInfoFromPage());
		updateMemberInterests(getMemberInterestsFromPage());
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
	
	