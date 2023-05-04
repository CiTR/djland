window.myNameSpace = window.myNameSpace || { };

$(document).ready ( function() {
    if(new Date().getMonth() < 4) $('renew').hide();
	var id = getText('member_id');	
	loadMember(id);
	
	addListeners();
	//periodically check if the user has filled out all fields
	window.setInterval(checkBlocking,1000);

    if (isRenewPageRedirect()) {
        renew_membership_form();
    }

    // Move the TOS agreement down
    $('div.containerrow:has(#tos)').after(
        $('div.col2:has(#ubc_affairs_collective)')
    );
    $('div.col2:has(#ubc_affairs_collective)').attr('style', 'margin:0 auto; width: 100%; text-align:center;');
});

function addListeners() {
    //RADIO BUTTON LISTENERS
    $('.can_status').unbind().click(function () {
        if (this.id == 'can1') {
            $('#can2').removeAttr("checked");
        }
        else {
            $('#can1').removeAttr("checked");
        }
    });
    $('.show_select').unbind().click(function () {
        if (this.id == 'show1') {
            $('#show2').removeAttr("checked");
        }
        else {
            $('#show1').removeAttr("checked");
        }
    });
    $('.alumni_select').unbind().click(function () {
        if (this.id == 'alumni1') {
            $('#alumni2').removeAttr("checked");
        }
        else {
            $('#alumni1').removeAttr("checked");
        }
    });
    $('#faculty').change(function () {

        if ($('#faculty').val() == "Other") {
            $('#faculty2').show();
        } else {
            $('#faculty2').hide();
        }
    });

    $('#submit_user').click(function () {
        if ($(this).text() == 'Renew') {
            member.renew();            
        } else {
            member.update();
        }

    });
    $('#renew').click(function(){
        renew_membership_form();
    });

//MEMBER TYPE: HIDE/SHOW STUDENT
    $('#member_type').unbind().change( function(){     
        if($('#member_type').val() == 'Student'){
            $('.student').show();
            $('.student').children().show();
        }else{
            $('.student').hide();
            //console.log("Hide student fields");
        }
    });

    $('#student_no').unbind().on('keyup', function () {
        var student_no = getVal('student_no');
        if (student_no != "") {
            $.ajax({
                type: "POST",
                url: "form-handlers/student_no-handler.php",
                data: {"member_id":member.member_id,"student_no": student_no},
                dataType: "json"
            }).success(function (data) {
                if (data == true) {
                    $('#student_no_ok').remove();
                    $('#student_no_check').append("<div id='student_no_ok'></div>");
                    $('#student_no_ok').text("This student number is already registered!");
                }
                else if (student_no.length < 8) {
                    $('#student_no_ok').remove();
                    $('#student_no_check').append("<div id='student_no_ok'></div>");
                    $('#student_no_ok').text("Student number must be 8 characters long.");
                }
                else {
                    $('#student_no_ok').remove();
                    $('#student_no_check').append("<div class='green' id='student_no_ok'></div>");
                    $('#student_no_ok').text("Okay");
                }
            }).fail(function () {
                console.log('fail');
                $('#student_no_ok').text('connection error');

            });
        }
    });
    $('#student_no').focusout( function(){
        if($('#student_no_ok').text() == 'Okay') $('#student_no_ok').hide();
    });
    $('#email').on('keyup',function(){
        var email = get('email');
        var div = $('#email_check');
        var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.|[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|ca|uk|au|jp|de|fr|nz|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/;
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
}
function checkBlocking(){
		var allOkay = true;
        
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
            $('.required').each(function(){
                if($.trim($(this).val()).length <=0) allOkay=false;
            });
        }

		if (allOkay){
		$('#submit_user').attr('disabled',false);
            if($('#renew').is(':visible')){
                $('#submit_user').text("Submit");
            }else{
                $('#submit_user').text("Renew");
            }
		$('#submit_user').removeClass("red");
		}else{
			$('#submit_user').attr('disabled',true);
			$('#submit_user').text("Form Not Complete");
			$('#submit_user').addClass("red");
		}
	}
function renew_membership_form(){
    if(new Date().getMonth() >= 4){
        var date = new Date().getFullYear()+"/"+ (new Date().getFullYear()+1);
    }else{
        var date = (new Date().getFullYear()-1) + "/"+ new Date().getFullYear();
    }
   
    var exists = false;
    $('#membership_year option').each(function(){
        console.log(this.value + " ?=" +date);
        if (this.value === date) {
            exists = true;
            return false;
        }
    });
    if(!exists){
        $('#title').text("Renew Membership");
        $('#subtitle').text("Please update your contact information and interests!");
        $('#submit_user').text("Renew Membership");
        $('#renew').hide();
        $('#membership_year').append("<option value="+date +">"+date + "</option>").val(date);
        $('.renew').each(function(){
            if($(this).attr('type') == 'checkbox'){
                $(this).removeAttr('checked');
            }else{
                $(this).val("");
            }
        });
    }else{
        alert("You have already renewed for the "+date+" membership year!");
    } 

}

function isRenewPageRedirect() {
    var params = getParams(window.location.href);

    if (params.hasOwnProperty('renew') && stringToBoolean(params.renew)) {
        return true;
    }

    return false;
}