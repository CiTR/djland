//Created by Evan Friday, 2014
window.myNameSpace = window.myNameSpace || { };
	
//PAGE CREATION
$(document).ready ( function() {
	var year_callback = loadYearSelect();

	$.when(year_callback).then(
		function(){
			displayMemberList("name","","both",get(undefined,'year_select','search'),'id');
		},function(){	

		});
	//displayMemberList();
	loadMember(1);

	add_handlers();	
	yearlyReport(year_callback);
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
		console.log("Clicked");
	});

	//CHANGING TABS
	$('#tab-nav').off('click','.member_action').on('click','.member_action', function(e){
		$('.member_action').attr('class','nodrop inactive-tab member_action');
		$(this).attr('class','nodrop active-tab member_action');
		$('.membership').hide();
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
					if($(this).is(':visible')){
						search_value = $(this).val();
					}
				});
				console.log(search_value);
				displayMemberList( getVal('search_by'), search_value, getVal('paid_status'), $('.year_select[name="search"]').val(), getVal('order_by'));
				break;
			case 'edit':
				if(confirm("Save changes?")){
					$.when(member.updateInfo(), member.updateInterests(), member.updatePermissions(), member.updatePassword()).then(function(d1,d2,d3){
						alert('Successfully updated');
						$('.member_action').attr('class','nodrop inactive-tab member_action');
						$(".member_action[name=search]").attr('class','nodrop active-tab member_action');
						$('.membership').hide();
						$('.membership#search').show();
					},function(e1,e2,e3){
						
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
	        	requests.push(
	        		$.ajax({
						type:"DELETE",
		                url: "form-handlers/membership/member.php",
		                data: {"member_id" : members_to_delete[member]},
		                dataType: "json",
		                async: true
        			})
        		);
        	}

	        $.when.apply($,requests).then(function(){
	        	console.log(arguments);
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
			$('.student').hide();
			//console.log("Hide student fields");
		}
	});

	$( "#from" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
		
	$( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
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
		    	console.log('success');
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
				console.log('fail');
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
			console.log("a field is not filled out");
			}
		});
	
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

		/*case 'report':
				switch(type){
					case 'init':
						document.getElementById("membership").innerHTML = " ";
						$('.membership').append('<select id="year_select"></select><button class="member_submit" name="report">Get Yearly Report</button><div id="membership_result"></div>');
						//Populate Dropdown with possible years to report on.
						actiontemp = 'get';
						typetemp = 'year';
						$.ajax({
							type:"POST",
							url: "form-handlers/membership_handler.php",
							data: {"action" : actiontemp, "type" : typetemp},
							dataType: "json",
							async: true
						}).success(function(data){
							for( $j = 0; $j < Object.keys(data).length; $j++ ){
								$('#year_select').append("<option value="+data[$j].membership_year+">"+data[$j].membership_year+"</option>")
							}
						}).fail(function(){
						
						});
					break;
					case 'generate':
						document.getElementById("membership_result").innerHTML = " ";
						year = getVal('year_select');	
						$.ajax({
							type:"POST",
							url: "form-handlers/membership_handler.php",
							data: {"action" : action,"year" : year},
							dataType: "json",
							async: true
						}).success(function(data){
						//console.log(data);
						var titles = ['member_reg_all','member_reg_year','member_paid','student','community','alumni','staff','arts','digital_library','discorder','discorder_2','dj','live_broadcast','music','news','photography','programming_committee','promotions_outreach','show_hosting','sports','tabling'];
						for( $j = 0; $j < titles.length; $j++ ){
								//console.log( data["num_"+titles[$j]][0] + " = " + data["num_"+titles[$j]][1]);
								$('#membership_result').append(data["num_"+titles[$j]][0] + " = " + data["num_"+titles[$j]][1]);
								if($j > 2){
									$('#membership_result').append(" ( "+(data["num_"+titles[$j]][1]/data["num_member_paid"][1]*100).toFixed(2)+"% )");
								}else if($j == 2){
									$('#membership_result').append(" ( "+(data["num_"+titles[$j]][1]/data["num_member_reg_year"][1]*100).toFixed(2)+"% )");
								}
								$('#membership_result').append("<br/>");
						}
						}).fail(function(){
						
						});
					break;
					default:
					break;
				}
				add_handlers();
				break;
			case 'mail':
				switch(type){
					case 'init':
						//console.log('Mail Init');
						var d = new Date();
						var today = ('0' + (d.getMonth()+1)).slice(-2) + "/"+('0' + d.getDate()).slice(-2) + "/" + d.getFullYear();
						var d2 = new Date();
						d2.setDate(d2.getDate() - 7);
						var week_ago = ('0' + (d2.getMonth()+1)).slice(-2) + "/"+('0' + d2.getDate()).slice(-2) + "/" + d2.getFullYear();
						document.getElementById("membership").innerHTML = " ";
						$(".membership").append("<ul id='membership_header'></ul>");
                        var membership_header = $('#membership_header');
                        membership_header.append("<li id='interest'>List:</li>");
                        $('#interest').append("<select id=search_value></select>");
							var title = ['All','Arts','Ads and PSAs','Digital Library','DJ101.9','Illustrate for Discorder','Writing for Discorder','Live Broadcasting','Music','News','Photography','Programming Committee','Promos and Outreach','Show Hosting','Sports','Tabling','Web and Tech'];
							var values =  ['all','arts','ads_psa','digital_library','dj','discorder','discorder_2','live_broadcast','music','news','photography','programming_committee','promotions_outreach','show_hosting','sports','tabling','tech'];
							$searchval = $('#search_value');
							for($i = 0; $i< title.length; $i++){
								$searchval.append("<option value='"+values[$i]+"'>"+title[$i]+"</option>");
							}
                        membership_header.append("<li>Paid Status: <select id='paid_select'><option value='both'>Both</option><option value='1'>Paid</option><option value='0'>Not Paid</option></select></li>");

                        membership_header.append("<li>Year: <select id='year_select'><option value='all'>All</option></select></li>");
                        membership_header.append("<li><ul id='join_filter'></ul></li>");
                        $('#join_filter').append("<li>Joined<input id='date_filter' type='checkbox'/></li>");
                        $('#join_filter').append("<li>from<input type='text' id='from' name='from' value='"+week_ago+"' /></li>");
                        $('#join_filter').append("<li>to<input type=text id='to' name='to' value='"+today+"' /></li>");


						var actiontemp = 'get';
						var typetemp = 'year';
					$.ajax({
						type:"POST",
						url: "form-handlers/membership_handler.php",
						data: {"action" : actiontemp, "type" : typetemp},
						dataType: "json",
						async: true
					}).success(function(data){
							for( $j = 0; $j < Object.keys(data).length; $j++ ){
								$('#year_select').append("<option value="+data[$j].membership_year+">"+data[$j].membership_year+"</option>")
							}
					}).fail(function(){
						
					});
						$('#membership_header').append("<li><button class='member_submit' name='mail'>Generate Email List</button></li>");
						$('.membership').append("<div id='member_result'></div>");
						add_handlers();
						break;
					case 'generate':
						//console.log('mail generate');
						paid = getVal('paid_select');
						//get year
						year = getVal('year_select');	
						sort = 'email';
						//console.log("Date Filter "+getCheckbox('date_filter'));
						if(getCheckbox('date_filter')){
							to = getVal('to');
							from = getVal('from');
						}
						$.ajax({
							type:"POST",
							url: "form-handlers/membership_handler.php",
							data: {"action":'mail', "type":'interest', "value":value, "paid":paid, "year":year, "from":from , "to":to },
							dataType: "json"
						}).done(function(data){
							document.getElementById("member_result").innerHTML = " ";
							$('#member_result').append("<textarea id=email_list></textarea>");
							if(data){
								var email_list = "";
								for( $j = 0; $j < Object.keys(data).length; $j++ ){
									email_list += data[$j].email + "; ";								
								}
								$('#email_list').val(email_list);	
							}
						}).fail(function(){
						});

					break;
				}
                add_handlers();
				break;
			default:
				manage_members('init');
				add_handlers();
				break;		
		}	
	}*/
	

