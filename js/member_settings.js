$(document).ready ( function() {
	var id = 3;
	var member = getMember(id);
	console.log(member);
	var membership_years = getMembershipYears(id);
	console.log(membership_years);
	var membership_year = getMembershipYear(1,membership_years.membership_years[0]);
	console.log(membership_year);
	addListeners(0);
	displayMemberInfo(member);

	
	
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
}
	