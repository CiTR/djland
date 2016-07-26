//Created by Evan Friday, 2014
window.myNameSpace = window.myNameSpace || { };

//PAGE CREATION
$(document).ready ( function() {
	$.when(constants_request).then(function(){
		var permission_level = $('#permission_level').text();
		if(permission_level >= permission_levels['staff']['level']){

			var year_callback = loadYearSelect();

			$.when(year_callback).then(
				function(){
					displayMemberList("name","","both",get(undefined,'year_select','search'),'0','created');
				},function(){

				});
			loadMember($('#member_id').text());
			add_handlers();
			yearlyReport(year_callback);

		}else if(permission_level >= permission_levels['volunteer_leader']['level']){
			var year_callback = loadYearSelect();
			$('.member_action').attr('class','nodrop inactive-tab member_action');
			$('.member_action[name="email"]').attr('class','nodrop active-tab member_action');
			add_handlers();
			$('.membership#email').show();
		}
	});
});

window.setInterval(checkBlocking,1000);

function add_handlers(){


	//This makes page printer/user friendly and toggles on the button trigger
	$('#print_friendly').on('click',function(element){
		if(!$(this).hasClass('print_friendly')){
			$(this).text('Normal View');
			$('#admin-nav, #nav, #tab-nav, #headerrow, #membership_header').hide();

			$('body').removeClass('wallpaper');
			$('.membership').removeClass('grey');
			//make printer friendly
			$('.staff_comment, .delete_member').each(function(element){
				$(this).hide();
			});

			$('.check').each(function(element){
				$(this).removeClass('hidden');
			});


			//$('#search').addClass('inline_block');
			$('#membership_result').removeClass('overflow_auto').removeClass('height_cap').addClass('overflow_visible');
		}else{
			//return to normal
			$(this).text('Print View');
			$('#admin-nav, #nav, #tab-nav, #headerrow, #membership_header').show();


			$('body').addClass('wallpaper');
			$('.membership').addClass('grey');

			$('.staff_comment, .delete_member').each(function(element){
				$(this).show();
			});
			$('.check').each(function(element){
				$(this).addClass('hidden');
			});
			//$('#search').removeClass('inline_block');
			$('#membership_result').removeClass('overflow_visible').addClass('height_cap').addClass('overflow_auto');
		}
		$(this).toggleClass('print_friendly');
	});

	//CHANGING TABS
	$('#tab-nav').off('click','.member_action').on('click','.member_action', function(e){
		$('.member_action').attr('class','nodrop inactive-tab member_action');
		$(this).attr('class','nodrop active-tab member_action');
		$('.membership').hide();
		if($(this).attr('name') == 'search'){
			var search_value;
			$('.search_value').each(function(e){
				if($(this).css('display') != 'none'){
					search_value = $(this).val();
				}
			});
			displayMemberList( getVal('search_by'), search_value || "", getVal('paid_status'), $('.year_select[name="search"]').val(), getVal('order_by'));
		}
		$('.membership#'+$(this).attr('name')).show();

	});
	//Listener for viewing individual members from clicking on their row
    $('#search').off('click','.member_row_element').on('click','.member_row_element',function(e){
        $('.member_action').attr('class','nodrop inactive-tab member_action');
		$(".member_action[name='view']").attr('class','nodrop active-tab member_action');
		loadMember($(this.closest('tr')).attr('id').toString().replace('row',''));
		$('.membership').hide();
		$('.membership#view').show();
    });


    //Listener for adding 'updated' to allow only updated comments to be submitted for saving
    $('#membership_table').off('keyup','.staff_comment').on('keyup','.staff_comment',function(element){
    	$(this).addClass('updated');
    });

    //Listener for saving comments
    $('#search').off('click','#save_comments').on('click','#save_comments',function(element){
    	saveComments();
    });


	//CLICKING A PAGE SUBMISSION BUTTON
	$('.member_submit').unbind().click( function(){
		var action = $(this).attr('name');
		switch(action){
			case 'search':
				var search_value;
				$('.search_value').each(function(e){
					if($(this).css('display') != 'none'){
						search_value = $(this).val();
					}
				});
				displayMemberList( getVal('search_by'), search_value || "", getVal('paid_status'), $('.year_select[name="search"]').val(), getCheckbox('search_has_show'),getVal('order_by'));
				break;
			case 'edit':
				if(confirm("Save changes?")){
					$.when(member.updateInfo(), member.updateInterests(), member.updatePermissions(), member.updatePassword()).then(function(d1,d2,d3,d4){
						alert('Successfully updated');
						$('.member_action').attr('class','nodrop inactive-tab member_action');
						$(".member_action[name=search]").attr('class','nodrop active-tab member_action');
						$('.membership').hide();
						$('#password').val('');
						$('.membership#search').show();
						var search_value;
						$('.search_value').each(function(e){
							if($(this).css('display') != 'none'){
								search_value = $(this).val();
							}
						});
						displayMemberList( getVal('search_by'), search_value || "", getVal('paid_status'), $('.year_select[name="search"]').val(), getVal('order_by'));
					},function(e1,e2,e3,e4){
						console.log(e1);
						console.log(e2);
						console.log(e3);
						console.log(e4);
					});
				}
				break;
			case 'report':
				yearlyReport();
				break;
			case 'email':
				emailList();
				break;
			default:
				break;
		}
	});

	//Email calendar container toggle
	$('#email_date_range').unbind().click( function(){
		$('#email_date_container').toggleClass('hidden');
	});


	//SEARCH TYPE LISTENER
	$('#search_by').unbind().change( function(){
		$('.search_value').addClass('hidden');
		$('.search_value[name="'+getVal('search_by')+'"]').removeClass('hidden');
	});

	//EMAIL LIST TYPE LISTENER
	$('#email_select').unbind().change( function(){
		$('.email_select_value').addClass('hidden');
		$('.email_select_value[name="'+getVal('email_select') +'"]').removeClass('hidden');
	});
    //NOTE: the off/on listener style was the ONLY way this worked. Standard JQuery ".click( function ..." did not work


    //Listener for getting ID's when deleting
    $('.membership').off('click','#delete_button').on('click','#delete_button',function(e){
        var members_to_delete = [];
        var members_names = [];
        $('.delete_member').each( function (){
            if($(this).is(':checked')){
                members_to_delete.push($(this.closest('tr')).attr('name').toString());
                members_names.push( $(this).closest('td').siblings('.name').html() );
            }
        });

        var confirm_string = "Are you sure you want to delete these members forever?\n"+members_names.toString();
        var requests = Array();
        if(confirm(confirm_string)){
        	for(var member in members_to_delete){
        		console.log(members_to_delete[member]);
	        	requests.push(
	        		$.ajax({
						type:"DELETE",
		                url: "api2/public/member/"+members_to_delete[member],
		                data: {"member_id" : members_to_delete[member]},
		                dataType: "json",
		                async: true
        			})
        		);
        	}

	        $.when.apply($,requests).then(function(){
	        	alert("Successfully deleted: "+members_names.toString());
	        },function(err){
	        	 alert("Could not delete: "+members_names.toString()+"\n"+data[0]);
	        });
        }
    });

    //Toggling red bar for showing members you are going to delete
    $('.membership').off('change','.delete_member').on('change','.delete_member',function(e) {
        $(this.closest('tr')).toggleClass('delete');

    });

	//MEMBER YEAR RELOAD
	$('#view').off('click','#membership_year').on('click','#membership_year',function(e){
		member.displayInterests(getVal('membership_year'));
	});

	//RADIO BUTTONS
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

	//MEMBER TYPE: HIDE/SHOW STUDENT
	$('#member_type').unbind().change( function(){
		if($('#member_type').val() == 'Student'){
			$('.student').show();
			$('.student').children().show();
		}else{
			$('.student.containerrow').hide();
		}
	});

	$( "#from" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd 00:00:00",
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate",selectedDate);
      }
    });

	$( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd 00:00:00",
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate);
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
}


function checkBlocking(){
		var allOkay = true;
		$('.required').each( function(){
			if( !$.trim( $(this).val() )){
			allOkay=false;
			}
		});

		if($('#username_ok').text() == 'Username taken'){
			allOkay=false;
		}
		if($('#password_ok').text() == 'Passwords do not match' || $('#password_ok').text() == 'Password must be more than 4 characters'){
			allOkay=false;
		}
		if(getVal('member_type')=='Student'){
			if(!$.trim(getVal('student_no'))){
				allOkay=false;
			}
			if($('#student_no_ok').length > 0 && $('#student_no_ok').text() != "Okay"){
				allOkay=false;
			}
		}
		if (allOkay){
		$('.member_submit[name="edit"]').attr('disabled',false);
		$('.member_submit[name="edit"]').text("Submit");
		$('.member_submit[name="edit"]').removeClass("red");
		}else{
			$('.member_submit[name="edit"]').attr('disabled',true);
			$('.member_submit[name="edit"]').text("Form Not Complete");
			$('.member_submit[name="edit"]').addClass("red");
		}
	}
