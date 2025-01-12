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
		return selectedValue;
	}else{
		return null;
	}
}
function getCheckbox($id){
	var checkbox = $id;
	if($('#'+checkbox).prop('checked')){
		return 1;
	}
	else{
		return 0;
	}
}
function getCheckbox2($id){
	var checkbox = $id;
	if($('#'+checkbox).prop('checked')){
		return true;
	}
	else{
		return false;
	}
}


$(document).ready ( function() {

        // Move the TOS agreement down
        $('div.containerrow:has(#tos)').after(
            $('div.col2:has(#ubc_affairs_collective)')
        );
        $('div.col2:has(#ubc_affairs_collective)').attr('style', 'margin:0 auto; width: 100%; text-align: center;');

	$('#submit_user').click( function() {
		// TODO:
		//* check if all fields are filled out correctly, then highlight incorrectly entered fields.
		//* link to membership-add-handler to submit a new user.
		//

		var faculty = getVal('faculty');
		if(faculty == 'Other'){
			faculty = getVal('faculty2');
		}
		var is_new = getVal('is_new');
		var prompt = "Is this information correct? \nYour full name is "+getVal('firstname') + " " + getVal('lastname') + ". \n";
		prompt += "Your current address is "+getVal('address')+ " "+ getVal('city') + " " + getVal('province')+ " " + getVal('postalcode') + ". \n";
		prompt += "You are ";
		prompt += get('canadian_citizen')  ? "a ":"not a ";
		prompt += "canadian citizen, who is a " + (get('is_new') == 1 ? 'new':'returning');
		if(getSelect('member_type') =='Student'){
			prompt += " student in ";

			switch(get('year')){
				case '1':
					prompt += " their first year of ";
					break;

				case '2':
					prompt += " their second year of ";
					break;

				case '3':
					prompt += " their third year of ";
					break;

				case '4':
					prompt += " their fourth year of ";
					break;

				case '5+':
					prompt += " their fifth or higher year of ";
					break;

				default:
					break;
				}

			prompt += faculty + " with student number " + getVal("student_no") + ". \n";
		}else{
			prompt += ' community member. \n';
		}

		prompt += "You can be reached at " + getVal('email') + ", " + getVal('primary_phone');
		if(getVal('phone2')){
			prompt += ", " + getVal('phone2');
		}

		prompt += ". \n \nIf this is correct, please hit 'OK', or else hit 'cancel' to edit your information.";

		if(confirm(prompt)==true){
			var member = new Member();
				member.create();
		}
	});
	$('#member_type').change(function (){

		if($('#member_type').val() == "Student"){
			$('.student').show();
			$('.student').children().show();
		}else{
			$('.student').hide();
			//$('#row-student-year').hide();
		}
	});
	$('#faculty').change(function (){

		if($('#faculty').val() == "Other"){
			$('#faculty2').show();
		}else{
			$('#faculty2').hide();
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


	window.setInterval(checkBlocking,1000);



	$('#username').keyup(function(){
		var username = getVal('username');
		$.ajax({
			type:"POST",
			url: "form-handlers/username_handler.php",
			data: {"username":username},
			dataType: "json"
		    }).success( function(data){
				if(data[0].bool==1){
					$('#username_ok').remove();
					$('#username_check').append("<div id='username_ok'></div>");
					$('#username_ok').text("username taken");
				}
				else{
					$('#username_ok').remove();
					$('#username_check').append("<div class='green' id='username_ok'></div>");
					$('#username_ok').text("Username okay");
				}
			}).fail( function(){
				$('#username_ok').text('connection error');

			});
	});
	$('#student_no').on('keyup',function(){
		var student_no = getVal('student_no');
		if(student_no != ""){
			$.ajax({
			type:"POST",
			url: "form-handlers/student_no-handler.php",
			data: {"student_no":student_no},
			dataType: "json"
		    }).success( function(data){
				if(data == true){
					$('#student_no_ok').remove();
					$('#student_no_check').append("<div id='student_no_ok'></div>");
					$('#student_no_ok').text("This student number is already registered!");
				}
				else if( student_no.length < 8){
					$('#student_no_ok').remove();
					$('#student_no_check').append("<div id='student_no_ok'></div>");
					$('#student_no_ok').text("Student number must be 8 characters long.");
				}
				else{
					$('#student_no_ok').remove();
					$('#student_no_check').append("<div class='green' id='student_no_ok'></div>");
					$('#student_no_ok').text("Okay");
				}
			}).fail( function(){
				$('#student_no_ok').text('connection error');

			});
		}

	});
	$('#email').on('keyup',function(){
		var email = get('email');
		var div = $('#email_check');
		var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.|[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}com|ca|uk|au|jp|de|fr|nz|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|me|edu|io|co|tv|us|cn|in|es|it|ru|pt|br|mx|ch|pl|se|no|fi|dk|tr|ar|za|kr|th|ph|my|id|sg|hk|qa|ae|sa|gr|ro|bg|sk|cz|hu|lt|lv|ee|vn)\b/;
		// ' here because some IDE cant handle regexes
		if(!re.test(email)){
			div.text('This is not a valid email');
			div.removeClass('invisible');
			div.removeClass('green');
			div.addClass('red');
		}else{
			div.text('Valid email');
			div.removeClass('invisible');
			div.removeClass('red');
			div.addClass('green');
		}
	});
	$('#email').blur(function(){
		var div = $('#email_check');
		if(div.text() == 'Valid email'){
			div.addClass('invisible');
		}
	});



	$('#username').blur(function(){
		var username = getVal('username');
		$.ajax({
			type:"POST",
			url: "form-handlers/username_handler.php",
			data: {"username":username},
			dataType: "json"
		    }).success( function(data){
				if(data[0].bool==1){
					$('#username_ok').remove();
						$('#username_check').append("<div id='username_ok'></div>");

					$('#username_ok').text("Username taken");

				}
				else{
					$('#username_ok').remove();
				}
			}).fail( function(){
				$('#username_ok').text('connection error');

			});

	});
	$('#password2').blur(function(){
		if( $('#password_ok').text() == "Passwords match"){
			$('#password_ok').remove();
		}
	});

	$('#student_no').blur(function(){
		var student_no = getVal('student_no');
		if(student_no != ""){
			$.ajax({
			type:"POST",
			url: "form-handlers/student_no-handler.php",
			data: {"student_no":student_no},
			dataType: "json"
		    }).success( function(data){
				if(data == true){
					$('#student_no_ok').remove();
					$('#student_no_check').append("<div id='student_no_ok'></div>");
					$('#student_no_ok').text("This student number is already registered!");
				}
				else if(student_no.length< 8){

					$('#student_no_ok').remove();
					$('#student_no_check').append("<div id='student_no_ok'></div>");
					$('#student_no_ok').text("Student number must be 8 characters long.");
				}
				else{
					$('#student_no_ok').remove();
				}
			}).fail( function(){
				$('#student_no_ok').text('connection error');

			});
		}

	});

});
	function checkBlocking(){
		var allOkay = true;


		if($('#username_ok').text() == 'Username taken'){
			allOkay=false;
			console.log("username not okay");
		}
		if($('#password_ok').text() == 'Passwords do not match' || $('#password_ok').text() == 'Password must be more than 4 characters'){
			allOkay=false;
			console.log("password not okay");
		}
		if(getVal('member_type')=='Student'){
			if(!$.trim(getVal('student_no'))){
				allOkay=false;
				console.log("Student Number Empty");
			}else if($('#student_no').val().length != 8){
				allOkay=false;
                console.log("Not 8 long");
			}
			if($('#student_no_ok').text().length > 0 && $('#student_no_ok').text() != "Okay"){
				allOkay=false;
				console.log("Student Number Taken");
			}
		}
		if( !$('#email_check').hasClass('green')){
			allOkay=false;
			console.log("Invalid Email");
		}
		if(allOkay){
			$('.required').each( function(){
				if( !$.trim( $(this).val() )){
				allOkay=false;
				console.log("a field is not filled out");
				}
			});
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
		var password = getVal('password');
		var password2 = getVal('password2');
		$('#password_ok').remove();

		if(password.length < 4){
			$('#password_check').append("<div id='password_ok' ></div>");
			$('#password_ok').text("Password must be more than 4 characters");
		}
		else if(password != password2){
			$('#password_check').append("<div id='password_ok' ></div>");
			$('#password_ok').text("Passwords do not match");
		}
		else{
			$('#password_check').append("<div class='green' id='password_ok'></div>");
			$('#password_ok').text("Passwords match");
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

	function alphaOnly(field,event) {
		var key = event.keyCode;
		//Only allowing normal letters
		var result = ((key >= 65 && key <= 90 ) || key >=97 && key <=122) ? true : false;
		return result;
	};

	
  angular.module('membership-signup', [])
    .controller('FormController', ['$scope', function($scope) {
      $scope.userType = 'guest';
    }]);