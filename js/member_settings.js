<<<<<<< HEAD
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

=======
window.myNameSpace = window.myNameSpace || { };

$(document).ready ( function() {
    if(new Date().getMonth() < 4) $('renew').hide();
	var id = getText('member_id');	
	loadMember(id);
	
	addListeners();
>>>>>>> dev
	//periodically check if the user has filled out all fields
	window.setInterval(checkBlocking,1000);
});

<<<<<<< HEAD
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
=======
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
            updateMember(member.getInfo(), member.getInterests());
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
                data: {"student_no": student_no},
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
}
function checkBlocking(){
		var allOkay = true;
        $('.required').each(function(){
            if($.trim($(this).val()).length <=0) allOkay=false;
        });
>>>>>>> dev
		if(getVal('member_type')=='Student'){
			if(!$.trim(getVal('student_no'))){
				allOkay=false;
			}
<<<<<<< HEAD
			if($('#student_no_ok').length > 0 && $('#student_no_ok').text() != "Okay"){
=======
			if($('#student_no_ok').text().length != 8 && $('#student_no_ok').text() != "Okay"){
>>>>>>> dev
				allOkay=false;
			}
		}
		if (allOkay){
		$('#submit_user').attr('disabled',false);
<<<<<<< HEAD
		$('#submit_user').text("Submit");
=======
            if($('#renew').is(':visible')){
                $('#submit_user').text("Submit");
            }else{
                $('#submit_user').text("Renew");
            }
>>>>>>> dev
		$('#submit_user').removeClass("red");
		}else{
			$('#submit_user').attr('disabled',true);
			$('#submit_user').text("Form Not Complete");
			$('#submit_user').addClass("red");
		}
	}

<<<<<<< HEAD
=======
function renew_membership_form(){
    
    var date = new Date().getFullYear()+"/"+ (new Date().getFullYear()+1);
    var exists = false;
    $('#membership_year option').each(function(){
        console.log(this.value + " ?=" +date);
        if (this.value == date) {
            
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

>>>>>>> dev
	