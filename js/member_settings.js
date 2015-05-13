var faculty_list = ["Arts","Applied Science","Architecture","Archival Studies","Audiology","Business","Community Planning","Continuing Studies","Dentistry","Doctoral Studies",
    "Education","Environmental Health","Forestry","Graduate Studies","Journalism","Kinesiology","Land and Food Systems","Law","Medicine","Music","Nursing","Pharmaceutical","Public Health","Science","Social Work","Other"];
var interests_list = {'Arts':'arts','Ads and PSAs':'ads_psa','Digital Library':'digital_library',
    'DJ101.9':'dj','Illustrate for Discorder':'discorder','Writing for Discorder':'discorder_2','Live Broadcasting':'live_broadcast',
    'Music':'music','News':'news','Photography':'photography','Programming Committee':'programming_committee',
    'Promos and Outreach':'promotions_outreach','Show Hosting':'show_hosting',
    'Sports':'sports','Tabling':'tabling','Web and Tech':'tech',"Other":"other"};
var provinces = ["AB","BC","MAN","NB","NFL","NS","NVT","NWT","ON","QC","SASK","YUK"];
var member_types = {'Student':'UBC Student','Community':'Community Member','Staff':'Staff'};

$(document).ready ( function() {
    if(new Date().getMonth() < 5) $('renew').hide();
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
            renewMember(getMemberInfoFromPage(), getMemberInterestsFromPage());
        } else {
            updateMember(getMemberInfoFromPage(), getMemberInterestsFromPage());
        }

    });

    $('#membership').off('click', '#renew').on('click', '#renew', function (e) {
        renew_membership_form();
    });

//MEMBER TYPE: HIDE/SHOW STUDENT
    $('#member_type').unbind().change(function () {
        if (($('#row6').hasClass('loaded') || $('#row6').css('display') == 'none') && (getVal('member_type') == 'Student' || getVal('member_type') == 'student' )) {
            $('#row6').show();
            $('#row6').children().show();
            $('#row7').show();
            $('#row7').children().show();
        }
        else if (getVal('member_type') == 'Student' || getVal('member_type') == 'student') {
            var row6 = $('#row6');
            var row7 = $('#row7');
            row6.append("<div class='col5'>Faculty*: </div>");
            row6.append("<div class='col5'><select id=faculty></select><input id='faculty2' style='display:none' placeholder='Enter your Faculty'/></div>");
            $faculty_select = $('#faculty');
            for (var faculty in faculty_list) {
                $faculty_select.append("<option value='" + faculty_list[faculty] + "'>" + faculty_list[faculty] + "</option>");
            }
            row6.append("<div id='student_no_container'> \
			<div class='col5'>Student Number*:</div> \
			<div class='col5'><input id='student_no' name='student_no' maxlength='10' placeholder='Student Number' onKeyPress='return numbersonly(this, event)''></input></div> \
			</div>");
            row7.append("<div class='col1'>I would like to incorporate CiTR into my courses(projects, practicums, etc.): \
				<input id='integrate'  name='integrate' type='checkbox'/> \
				<div class='col5'>Year*:</div> \
				<div class='col8'><select id='year' style='z-position=10;'></select></div></div>");
            var year_select = $('#year');
            for (var i = 0; i < 5; i++) {
                if (i < 4) year_select.append("<option value='" + i + "'>" + i + "</option>"); else year_select.append("<option value='" + i + "'>" + i + "+</option>");
            }
        } else {
            $('#row6').hide();
            $('#row7').hide();
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
		if(getVal('member_type')=='Student'){
			if(!$.trim(getVal('student_no'))){
				allOkay=false;
			}
			if($('#student_no_ok').text().length != 8 && $('#student_no_ok').text() != "Okay"){
				allOkay=false;
			}
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
    $('#title').text("Renew Membership");
    $('#subtitle').text("Please update your contact information and interests!");
    $('#submit_user').text("Renew Membership");
    $('#renew').hide();
    $('.renew').each(function(){

        if($(this).attr('type') == 'checkbox'){
            $(this).removeAttr('checked');
        }else{
            $(this).val("");
        }
    });
}

	