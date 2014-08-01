
$(document).ready ( function() {
	
	
});

$(#submit).onclick( function() {
	/* TODO:
	 * check if all fields are filled out correctly, then highlight incorrectly entered fields.
	 * link to membership-add-handler to submit a new user.
	 */
});

$(#username).change( function(){
	var username = getVal('username');
	var password = getVal('password');
	var firstname = getVal('firstname');
	var email = getVal('email');
	var phone = getVal('phone');
	var firstname = getVal('firstname');
	var faculty = getSelect('faculty');
	var member_type = getSelect('member_type');
	var gender = getSelect('gender');
	var can_status = getSelect('can_status');
	$.ajax({
			type:"POST",
			url: "form-handlers/username-handler.php",
			data: {"username":username,"password",password},
			dataType: "json"
		}).success(function(data) {
			
		}).fail(function(){
			$('#samAds').show();
			$('#samAds').html('connection error');
		});
});


function String getVal($varname){
	if($('#'+$varname.val()!=null)){
		return $('#'+$varname).val();
	}
	else{
		return null;
	}
}
function String getSelect($id){
	var selects = document.getElementById($id);
	var selectedValue = selects.options[selects.selectedIndex].value;
}
