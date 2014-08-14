function getVal($varname){
	$temp = $varname;
	if( $('#'+$temp).val()!=null){
		return $('#'+$temp).val();
	}
	else{
		return null;
	}
}
function getText($varname){
	$temp = $varname;
	if( $('#'+$temp).text()!=null){
		return $('#'+$temp).text();
	}
	else{
		return null;
	}
}
function getSelect($id){
	var selects;
	if(document.getElementById($id)!=null){
		selects = document.getElementById($id);
		var selectedValue = selects.options[selects.selectedIndex].value;
	}else{
		return null;
	}	
}


$(document).ready ( function() {

	$('#submit_user').click( function() {
	// TODO:
	//* check if all fields are filled out correctly, then highlight incorrectly entered fields.
	//* link to membership-add-handler to submit a new user.
	//
	console.log("submit button clicked")
	var password = getVal('password1');
	var firstname = getVal('firstname');
	var lastname = getVal('lastname');
	var email = getVal('email');
	var phone = getVal('phone');
	var faculty = getSelect('faculty');
	var member_type = getSelect('member_type');
	var gender = getSelect('gender');
	var can_status = getSelect('can_status');
	alert('Username = ' + getVal('username') + 'Password =' + password + "Firstname = " +firstname);
	/*

	$.ajax({
			type:"POST",
			url: "form-handlers/membership-add-handler.php",
			data: {"username":getVal('username'),"password":getVal('password'),"firstname":getVal('firstname'),"lastname":getVal('lastname'),"email":getVal('email'),"phone":getVal("phone"),"cellphone":getVal('cellphone'),"member_type":getSelect("member_type")},
			dataType: "json"
		}).success(function(data) {
			
		}).fail(function(){
			$('#samAds').show();
			$('#samAds').html('connection error');
		});
*/

	
	});
	$('#member_type').change(function (){
		
		if($('#member_type').val() == "student"){
			$('#row6').show();
			$('#row6').children().show();
			$('#student_no_container').show();
			$('#student_no_container').children().show();
		}else{
			$('#row6').hide();	
			$('#student_no_container').hide();
		}	
	});

	window.setInterval(checkBlocking,2000);
	
	

	$('#username').keyup(function(){
		var username = getVal('username');
		console.log(getVal('username'));
		
		$.ajax({
			type:"POST",
			url: "form-handlers/username-handler.php",
			data: {"username":username},
			dataType: "json"
		    }).success( function(data){
				console.log('success');
				console.log(data[0]);
				if(data[0].bool==1){
					$('#username_ok').remove();
						$('#username_check').append("<div id='username_ok'></div>");
					
					$('#username_ok').text("username taken");

				}
				else{
					$('#username_ok').remove();
					$('#username_check').append("<div class='green' id='username_ok'></div>");
					$('#username_ok').text("username okay");
				}
			}).fail( function(){
				console.log('fail');
				$('#username-ok').text('connection error');
			
			});
	});

	$('#username').blur(function(){
		if($('#username_ok').text() == "username okay"){
			$('#username_ok').remove();
		}
		
	});
	$('#password2').blur(function(){
		if( $('#password_ok').text() == "passwords match"){
			$('#password_ok').remove();
		}		
	});


});
	function checkBlocking(){
		var allOkay = true;
		$('.required').each( function(){
			if( !$.trim( $(this).val() )){
			allOkay=false;
			console.log("a field is not filled out");
			}
		});
	
		if($('#username_ok').text() == 'username not okay'){
			allOkay=false;
			console.log("username not okay");
		}
		if($('#password_ok').text() == 'passwords do not match'){
			allOkay=false;
			console.log("password not okay");
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

	function passwordCheck(){
		var password1 = getVal('password1');
		var password2 = getVal('password2');
		$('#password_ok').remove();
		if(password1 != password2){
			$('#password_check').append("<div id='password_ok' ></div>");
			$('#password_ok').text("passwords do not match");
		}
		else{
			$('#password_check').append("<div class='green' id='password_ok'></div>");
			$('#password_ok').text("passwords match");
		}
	}

	function numbersonly(myfield, e, dec)
		{
		var key;
		var keychar;

		if (window.event)
		   key = window.event.keyCode;
		else if (e)
		   key = e.which;
		else
		   return true;
		keychar = String.fromCharCode(key);

		// control keys
		if ((key==null) || (key==0) || (key==8) || 
		    (key==9) || (key==13) || (key==27) )
		   return true;

		// numbers
		else if ((("0123456789").indexOf(keychar) > -1))
		   return true;

		// decimal point jump
		else if (dec && (keychar == "."))
		   {
		   myfield.form.elements[dec].focus();
		   return false;
		   }
		else
		   return false;
		}
