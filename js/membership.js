//Created by Evan Friday, 2014

//PAGE CREATION
$(document).ready ( function() {
	manage_members('init');
	add_handlers();	
});
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

function add_handlers(){
	
	//CHANGING TABS
	$('.member_action').unbind().click( function () {
		
		var action = $(this).attr('value');
		$('.member_action').attr('class','nodrop inactive-tab member_action');
		$(this).attr('class','nodrop active-tab member_action');
		//console.log("member_action="+action);
		if(action == 'view' || action == 'mail' || action == 'report'){
			manage_members(action,'init');
		}else{
			manage_members(action);
		}		
	});
	
	//CLICKING A PAGE SUBMISSION BUTTON
	$('.member_submit').unbind().click( function(){
		var action = $(this).attr('name');
		switch(action){
			case 'search':
				var search_type = getSelect('search_type');
				var search_value = getVal('search_value');
				manage_members(action,search_type,search_value);
				break;
			case 'edit':
				//console.log(getVal('userid'));
				//console.log("text= "+ getText('userid'));
				if(confirm("Save changes?")){
					var faculty = getVal('faculty');
					if(faculty == 'Other'){
						faculty = getVal('faculty2');
					}
					var is_new = getVal('is_new');
					if(is_new == 'new'){
						is_new = 1;
					}
					else{
						is_new = 0;
					}
					var member_id = $('#view').attr('name');
					$.ajax({
					type:"POST",
					url: "form-handlers/membership-update-handler.php",
					data: {
					"member_id"			:member_id,
					"firstname"			:getVal('firstname'),
					"lastname"			:getVal('lastname'),
					"address"			:getVal('address'),
					"city"				:getVal('city'), 
					"province"			:getVal('province'), 
					"postalcode"		:getVal('postalcode'),
					"canadian_citizen"	:getCheckbox('can1'), 
					"member_type"		:getVal("member_type"),
					"is_new"			:is_new,
					"alumni"			:getCheckbox('alumni1'),
					"since"				:getVal('since'),
					"faculty"			:faculty,
					"student_no"		:getVal('student_no'),
					"schoolyear"		:getVal('schoolyear'),
					"integrate"			:getCheckbox('integrate'),
					"has_show"			:getCheckbox('show1'),
					"show_name"			:getVal("show_name"),
					"email"				:getVal('email'),
					"primary_phone"		:getVal('phone1'),
					"secondary_phone"	:getVal('phone2'),
					"paid"				:getCheckbox('paid'),
					"music"				:getCheckbox('music'),
					"sports"			:getCheckbox("sports"),
					"live_broadcast"	:getCheckbox("live_broadcast"),
					"ads_psa" 			:getCheckbox("ads_psa" ),
					"discorder" 		:getCheckbox( "discorder" ),
					"news" 				:getCheckbox( "news" ),
					"tech" 				:getCheckbox( "tech" ),
					"outreach"			:getCheckbox("promos"),
					"show_hosting" 		:getCheckbox("show_hosting" ),
					"arts"				:getCheckbox( "arts"),
					"prog_comm"			:getCheckbox("prog_comm"),
					"digital_library"	:getCheckbox("digital_library"),
					"photography"		:getCheckbox("photography"),
					"dj"				:getCheckbox('dj'),
					"discorder_2"		:getCheckbox('discorder_2'),
					"tabling"			:getCheckbox('tabling'),
					"other"				:getVal("other"),
					"about"				:getVal('about'),
					"skills"			:getVal('skills'),
					"exposure"			:getVal('exposure'),
					"comments"			:getVal('comments'),
					"membership_year"	:getVal('member_year_select'),
					"userid"			:getText('userid'),
					"is_member"			:getCheckbox('is_member'),
					"is_dj"				:getCheckbox('is_dj'),
					"is_administrator"	:getCheckbox('is_administrator'),
					"is_add_user"		:getCheckbox('is_add_user'),
					"is_add_show"		:getCheckbox('is_add_show'),
					"is_edit_dj"		:getCheckbox('is_edit_dj'),
					"is_library"		:getCheckbox('is_library'),
					"is_membership"		:getCheckbox('is_membership'),
					"is_edit_library"	:getCheckbox('is_edit_library'),
					"password"			:getVal('password')
					 },
					dataType: "json"
					}).success(function(data) {
						if(data[0]=="ERROR"){
							//console.log(data);
							
							$.ajax({
							type:"POST",
							url: "form-handlers/log_handler.php",
							data: {"data":data[2] },
							dataType: "json"
							}).success(function(reply) {
								alert(data[1] + "Please contact Technical Services! This error has been logged.");
							}).fail(function(reply){
								alert(data[1] + "Please contact Technical Services! This error could not be logged. :(");
							});
						}
						else{
							alert("Successful Submission!");
						}
					}).fail(function(data){
						alert("An error occurred, please try saving again!");
						//console.log("error occurred" + JSON.stringify(data));
					});
				}
				break;
			case 'report':
				manage_members(action,'generate');
				break;
			case 'mail':
				var value = getVal('search_value');
				manage_members(action,'generate',value);
				break;
			default:
				//console.log("something went wrong");
				manage_members('init');
				break;
		}
	});
	//SEARCH TYPE LISTEN
	$('#search_type').unbind().change( function(){
		document.getElementById("search_container").innerHTML="";
		if(getVal('search_type')=='name'){
			$('#search_container').append("<input id=search_value placeholder='Enter a name' />");			
		}else{
			$('#search_container').append("<select id=search_value></select>");
				var title = ['All','Arts','Ads and PSAs','Digital Library','Illustrate for Discorder','Writing for Discorder','DJ101.9','Live Broadcasting','Music','News','Photography','Programming Committee','Promos and Outreach','Show Hosting','Sports','Tabling'];
				var values =  ['all','arts','ads_psa','digital_library','discorder','discorder_2','dj','live_broadcast','music','news','photography','programming_committee','promotions_outreach','show_hosting','sports','tabling'];

			$searchval = $('#search_value');
			for($i = 0; $i< title.length; $i++){
				$searchval.append("<option value='"+values[$i]+"'>"+title[$i]+"</option>");
			}
		}
	});


	//PAID SEARCH TOGGLE
	$('.paid_select').unbind().click( function(){
		if( this.id =='paid1'){
			$('#paid2').removeAttr("checked");
			$('#paid3').removeAttr("checked");
		}
		else if(this.id =='paid2'){
			$('#paid1').removeAttr("checked");
			$('#paid3').removeAttr("checked");
		}
		else{
			$('#paid1').removeAttr("checked");
			$('#paid2').removeAttr("checked");
		}
	});

	//MEMBER YEAR RELOAD
	$('#member_year_select').unbind().change( function(){
		var year = getVal('member_year_select');
		var id = document.getElementById("idval").getAttribute('value');
		load_member_year(id,year);
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
		if( ($('#row5').hasClass('loaded') || $('#row5').css('display') == 'none') && (getVal('member_type') == 'Student' || getVal('member_type') == 'student' )){
			$('#row5').show();
			$('#row5').children().show();
			$('#row6').show();
			$('#row6').children().show();
			//console.log("show student");
		}
		else if(getVal('member_type') == 'Student' || getVal('member_type') == 'student' ){ 
			var row5 = $('#row5');
			var row6 = $('#row6');
			row5.append("<div class='col5'>Faculty*: </div>");
			row5.append("<div class='col5'><select id=faculty></select><input id='faculty2' style='display:none' placeholder='Enter your Faculty'/></div>");
			var title = ['Arts','Applied Science','Architecture','Archival Studies','Audiology','Business','Community Planning','Continuing Studies','Dentistry','Doctoral Studies','Education','Environmental Health','Forestry','Graduate Studies','Journalism','Kinesiology','Land and Food Systems','Law','Medicine','Music','Nursing','Pharmaceutical','Public Health','Science','Social Work','Other'];
			var values =  ['Arts','Applied Science','Architecture','Archival Studies','Audiology','Business','Community Planning','Continuing Studies','Dentistry','Doctoral Studies','Education','Environmental Health','Forestry','Graduate Studies','Journalism','Kinesiology','Land and Food Systems','Law','Medicine','Music','Nursing','Pharmaceutical','Public Health','Science','Social Work','Other'];
			$searchval = $('#faculty');
			for($i = 0; $i< title.length; $i++){
				$searchval.append("<option value='"+values[$i]+"'>"+title[$i]+"</option>");
			}
			row5.append("<div id='student_no_container'> \
			<div class='col5'>Student Number*:</div> \
			<div class='col5'><input id='student_no' name='student_no' maxlength='10' placeholder='Student Number' onKeyPress='return numbersonly(this, event)''></input></div> \
			</div>");
			row6.append("<div class='col1'>I would like to incorporate CiTR into my courses(projects, practicums, etc.): \
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
			$('#row5').hide();
			$('#row6').hide();
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
}

function change_view(action,type,value){
	$('.member_action').attr('class','nodrop inactive-tab member_action');
	$("#"+action).attr('class','nodrop active-tab member_action');
	//console.log('manage_members'+action+type+value);
	manage_members(action,type,value);
}

function load_member_year(id,year){
	document.getElementById("membership_year").innerHTML = " ";
	$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : 'get', "type" : 'member_year_content',"value":id,"year":year},
						dataType: "json",
						async: false
						}).success(function(data){
							if( !$('#paid').length ){
								//console.log('first load membership year');


								$('#row16').append("<div class='col8'> Paid:<input type=checkbox id=paid "+(data[0].paid==1 ? "checked=checked" : "") +"/></div>");
								
								$('#row17').append("<div class='col4'> Writing for Discorder <input type=checkbox id=discorder_2 "+(data[0].discorder_2==1 ? "checked=checked" : "")+"/></div>");
								$('#row17').append("<div class='col4'> Illustrate for Discorder:<input type=checkbox id=discorder "+(data[0].discorder==1 ? "checked=checked" : "")+"/></div>");								
								$('#row17').append("<div class='col4'> DJ101.9:<input type=checkbox id=dj "+(data[0].dj==1 ? "checked=checked" : "")+"/></div>");
								$('#row17').append("<div class='col4'> Tabling:<input type=checkbox id=tabling "+(data[0].tabling==1 ? "checked=checked" : "")+"/></div>");


								$('#row18').append("<div class='col4'> Music Department:<input type=checkbox id=music "+(data[0].music==1 ? "checked=checked" : "")+"/></div>");
								$('#row18').append("<div class='col4'> Show Hosting:<input type=checkbox id=show_hosting "+(data[0].show_hosting==1 ? "checked=checked" : "")+"/></div>");
								$('#row18').append("<div class='col4'> Sports:<input type=checkbox id=sports "+(data[0].sports==1 ? "checked=checked" : "")+"/></div>");
								$('#row18').append("<div class='col4'> News 101.9:<input type=checkbox id=news "+(data[0].news==1 ? "checked=checked" : "")+"/></div>");

								$('#row19').append("<div class='col4'> Arts Report:<input type=checkbox id=arts "+(data[0].arts==1 ? "checked=checked" : "")+"/></div>");
								$('#row19').append("<div class='col4'> Live Broadcasting:<input type=checkbox id=live_broadcast "+(data[0].live_broadcast==1 ? "checked=checked" : "")+"/></div>");
								$('#row19').append("<div class='col4'> Web and Tech:<input type=checkbox id=tech "+(data[0].tech==1 ? "checked=checked" : "")+"/></div>");
								$('#row19').append("<div class='col4'> Digital Library:<input type=checkbox id=digital_library "+(data[0].digital_library==1 ? "checked=checked" : "")+"/></div>");
								
								$('#row20').append("<div class='col4'> Ads and PSAs:<input type=checkbox id=ads_psa "+(data[0].ads_psa==1 ? "checked=checked" : "")+"/></div>");
								$('#row20').append("<div class='col4'> Promos and Outreach:<input type=checkbox id=promos "+(data[0].promotions_outreach==1 ? "checked=checked" : "")+"/></div>");
								$('#row20').append("<div class='col4'> Photography:<input type=checkbox id=photography "+(data[0].photography==1 ? "checked=checked" : "")+"/></div>");
								$('#row20').append("<div class='col4'> Programming Committee:<input type=checkbox id=prog_comm "+(data[0].programming_committee==1 ? "checked=checked" : "")+"/></div>");
								
								$('#row21').append("<div class='col4'> Other:<input type=text id=other "+(data[0].other ? ("value='"+data[0].other)+"'" : "")+"/></div><br/>");
							}else{
								//console.log('reloading membership year');
								if(data[0].paid == 0){ $('#paid').removeAttr('checked'); }else{ $('#paid').prop('checked','checked'); }
								if(data[0].music == 0){ $('#music').removeAttr('checked'); }else{ $('#paid').prop('checked','checked'); }
								if(data[0].discorder == 0){ $('#discorder').removeAttr('checked'); }else{ $('#discorder').prop('checked','checked'); }
								if(data[0].show_hosting == 0){ $('#show_hosting').removeAttr('checked'); }else{ $('#show_hosting').prop('checked','checked'); }
								if(data[0].sports == 0){ $('#sports').removeAttr('checked'); }else{ $('#sports').prop('checked','checked'); }
								if(data[0].news == 0){ $('#news').removeAttr('checked'); }else{ $('#news').prop('checked','checked'); }
								if(data[0].arts == 0){ $('#arts').removeAttr('checked'); }else{ $('#arts').prop('checked','checked'); }
								if(data[0].live_broadcast == 0){ $('#live_broadcast').removeAttr('checked'); }else{ $('#live_broadcast').prop('checked','checked'); }
								if(data[0].tech == 0){ $('#tech').removeAttr('checked'); }else{ $('#tech').prop('checked','checked'); }
								if(data[0].programming_committee == 0){ $('#prog_comm').removeAttr('checked'); }else{ $('#prog_comm').prop('checked','checked'); }
								if(data[0].ads_psa == 0){ $('#ads_psa').removeAttr('checked'); }else{ $('#ads_psa').prop('checked','checked'); }
								if(data[0].outreach == 0){ $('#promos').removeAttr('checked'); }else{ $('#promos').prop('checked','checked'); }
								if(data[0].photography == 0){ $('#photography').removeAttr('checked'); }else{ $('#photography').prop('checked','checked'); }
								if(data[0].digital_library == 0){ $('#digital_library').removeAttr('checked'); }else{ $('#digital_library').prop('checked','checked'); }
								if(data[0].music == 0){ $('#ads_psa').removeAttr('checked'); }else{ $('#ads_psa').prop('checked','checked'); }
								$('#other').attr('value',data[0].other);
							}
						}).fail(function(){
							
						});
}

function manage_members(action_,type_,value_){
		var action = null;
		var type = null;
		var value = null;
		var paid = null;
		var year = null;
		var sort = null;
		var to = null;
		var from = null;
		if(action_){
			action = action_;
		}
		if(type_){
			type = type_;
		}
		if(value_){
			value = value_;
		} 
		//console.log("Manage members called, action="+action);
		switch(action){
			case 'init':
					document.getElementById("membership").innerHTML = " ";
					$('#membership').append("<div id='membership_header'></div>");
					$('#membership_header').append("Search by \
						<select id='search_type'> \
						<option value='name'> Name </option> \
						<option value='interest'> Interest </option> \
						</select>\
						<div id='search_container'><input id='search_value' placeholder='Enter a name' /></div>\
						Order By:<select id='sort_select'> \
							<option value='id'> Date Added </option>\
							<option value='lastname'> Last Name </option> \
						</select> \
						Filter by Year: <select id='year_select'><option value='all'>don't filter</option></select><br/> \
						Paid Status: Both<input id='paid1' class='paid_select' type='radio' checked='checked' /> \
						Paid<input id='paid2' class='paid_select' type='radio' /> \
						Not Paid<input id='paid3' class='paid_select' type='radio' />");
						actiontemp = 'get';
						typetemp = 'year';
					$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : actiontemp, "type" : typetemp},
						dataType: "json",
						async: false
					}).success(function(data){
							for( $j = 0; $j < Object.keys(data).length; $j++ ){
								$('#year_select').append("<option value="+data[$j].membership_year+">"+data[$j].membership_year+"</option>")
							}
					}).fail(function(){
						
					});

					$('#membership_header').append("<button class='member_submit' name='search'>Search</button>");
					$('#membership').append("<div id='member_result'></div>");
					add_handlers();
					manage_members('search','name');
				break;
			case 'search':
				if(getCheckbox('paid3')){
					paid='0';
				}else if(getCheckbox('paid2')){
					paid='1';
				}else{
					paid='both';
				}
				year = getVal('year_select');				
				sort = getVal('sort_select');
				$.ajax({
					type:"POST",
					url: "form-handlers/membership-handler.php",
					data: {"action":action, "type":type, "sort":sort, "value":value, "paid":paid, "year":year},
					dataType: "json"
				}).done(function(data){
					document.getElementById("member_result").innerHTML = " ";
					if(data != null){ 
						if(Object.keys(data).length > 0){
							$('#member_result').append("<table id='membership_table'><tr id='headerrow'><th class=data_set>Name</th><th class=data_set>Email</th><th class=data_set>Phone</th></tr></table>");
							if(year != 'all'){
								$('#headerrow').append('<th class=data_set>Paid</th><th class=data_set>Year</th>');
							}
						}else{
							$('#member_result').append("Search returned no results");
						}

						
						for( $j = 0; $j < Object.keys(data).length; $j++ ){
							$('#membership_table').append("<tr id='row"+$j+"' class='member_row' onclick=change_view('view','init','"+data[$j].id+"')><tr>");
							$("#row"+$j).append("<td class=data_set>"+data[$j].firstname+" "+data[$j].lastname+"</td>");
							$("#row"+$j).append("<td class=data_set>"+data[$j].email+"</td>");
							$("#row"+$j).append("<td class=data_set>"+data[$j].primary_phone+"</td>");
							if(year != 'all'){
								if(data[$j].paid == 1){
									$("#row"+$j).append("<td class=data_set>yes</td>");
								}
								else{
									$("#row"+$j).append("<td class=data_set>no</td>");
								}
								$("#row"+$j).append('<td class=data_set>'+data[$j].membership_year+'</td>');
							}
						}
					}
					else{ 
						document.getElementById("member_result").innerHTML = " ";
						$('#member_result').append("Search returned no results");
					}
				}).fail(function(){
					$('#member_result').html('connection error');
				});

				add_handlers();
				break;
				
			case 'view': // View/Edit Member
				switch(type){
					case 'init':
						document.getElementById("membership").innerHTML = " ";
						$('#membership').append("<div id='member_result'></div>");
						if(value == null){
							value = $('#view').attr('name');
						}
						else{
							$('#view').attr('name',value);
						}
						
						$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : action, "type" : type, "value" : value},
						dataType: "json",
						async: false
						}).success(function(data){
							var fields = Object.keys(data[0]);
							
							$('#member_result').append("<div id='idval' value="+data[0][0]+" style='display:none;'></div>");
							for($j = 0; $j<=27 ; $j++){
								if($j>=8 && $j<=16 ){ // pad large text inputs
									$('#member_result').append("<div class ='member_result_row padded' id=row"+$j+"></div>");
								}else{
									$('#member_result').append("<div class = member_result_row id=row"+$j+"></div>");
								}							
							}
							//BASIC INFO
							$('#row0').append("<br><div class=col5>First Name:</div><div class=col5><input id='firstname' name='firstname' value='"+data[0].firstname+"''></div>");
							$('#row0').append("<div class=col5>Last Name:</div><div class=col5><input id='lastname' name='lastname' value='"+data[0].lastname+"''></div>");
							//ADDRESS
							$('#row1').append("<div class=col5>Address:</div><div class=col5><input id='address' name='address' value='"+data[0].address+"''></div>");
							$('#row1').append("<div class=col5> City:</div><div class=col5><input id='city' name='city' value='"+data[0].city+"''></div>");
							$('#row2').append("<div class=col5> Province:</div><div class=col5><select id='province'> \
							<option value='"+data[0].province+"'>"+data[0].province+"</option> \
							<option value='BC'>BC</option> \
							<option value='AB'>AB</option> \
							<option value='SASK'>SASK</option> \
							<option value='MAN'>MAN</option> \
							<option value='ON'>ON</option> \
							<option value='QC'>QC</option> \
							<option value='NB'>NB</option> \
							<option value='NS'>NS</option> \
							<option value='NFL'>NFL</option> \
							<option value='NU'>NU</option> \
							<option value='NWT'>NWT</option> \
							<option value='YUK'>YUK</option> \
							</select></div>");
							$('#row2').append("<div class=col5> Postal Code:</div><div class=col5><input id='postalcode' name='postalcode' value="+data[0].postalcode+" maxlength='6'></div>");
							//CANADIAN CITIZEN
							$('#row3').append("<div class=col5> Canadian Citizen:</div>");
							if(data[0].canadian_citizen == 1){
								$('#row3').append("<div class='col5'> Yes<input id='can1' class='can_status' type='radio' checked='checked' /> \
								No<input id='can2' class='can_status' type='radio' /></div>");
							}
							else{
								$('#row3').append("<div class='col5'> Yes<input id='can1' class='can_status' type='radio'  /> \
								No<input id='can2' class='can_status' type='radio' checked='checked' /></div>");
							}
							//MEMBER TYPE
							$('#row3').append("<div class='col5'>Member Type:</div><div id=membertype class='col4'> \
							<select id='is_new'></select></div>");
							if(data[0].is_new == "0"){
								$('#is_new').append("<option value='returning'>Returning</option><option value='new'>New</option>");
								
							}else{
								$('#is_new').append("<option value='new'>New</option><option value='returning'>Returning</option>");
								
							}
							$('#membertype').append("<select id='member_type'> \
								<option value='"+data[0].member_type+"'>"+data[0].member_type+"</option> \
								<option value='Student'>Student</option> \
								<option value='Community'>Community</option> \
								<option value='Staff'>Staff</option> \
							</select>");
							//ALUMNI STATUS
							if(data[0].alumni == 1){
								$('#row4').append("<div class='col5'>Alumni:</div><div class='col5' > Yes<input id='alumni1' class='alumni_select' type='radio' checked='checked' /> \
								No<input id='alumni2' class='alumni_select' type='radio' /></div>");
							}else{
								$('#row4').append("<div class='col5'>Alumni: </div><div class='col5' >Yes<input id='alumni1' class='alumni_select' type='radio' /> \
								No<input id='alumni2' class='alumni_select' type='radio' checked='checked' /></div>");
							}
							$('#row4').append("<div class='col5'>Member Since</div>");
							$('#row4').append("<div class='col5'><select id=since><option value='"+data[0].since+"'>"+data[0].since+"</input></select></div>");
							var d =new Date();
							var y = parseInt(d.getFullYear(),10);
							var y2 = 1924;
							for($i=y; $i > y2; $i--){
								$('#since').append("<option value='"+$i+"\/"+($i+1)+"'>"+$i+"/"+($i+1)+"</option>");
							}

							
							//BEGIN IF STUDENT
							if(data[0].member_type == 'Student' || data[0].member_type == 'student'){ 
								$('#row5').addClass('loaded');
								$('#row5').append("<div class='col5'>Faculty*: </div> \
								<div class='col5'> \
								<select id='faculty'> \
									<option value='"+data[0].faculty+"'>"+data[0].faculty+"</option> \
									<option value='Arts'>Arts</option> \
									<option value='Applied Science'>Applied Science</option> \
									<option value='Architecture'>Architecture</option> \
									<option value='Archival Studies'>Archival Studies</option> \
									<option value='Audiology'>Audiology</option> \
									<option value='Business'>Business</option> \
									<option value='Community Planning'>Community Planning</option> \
									<option value='Continuing Studies'>Continuing Studies</option> \
									<option value='Dentistry'>Dentistry</option> \
									<option value='Doctoral Studies'>Doctoral Studies</option> \
									<option value='Education'>Education</option> \
									<option value='Environmental Health'>Environmental Health</option> \
									<option value='Forestry'>Forestry</option> \
									<option value='Graduate Studies'>Graduate Studies</option> \
									<option value='Journalism'>Journalism</option> \
									<option value='Kinesiology'>Kinesiology</option> \
									<option value='Land and Food Systems'>Land and Food Systems</option> \
									<option value='Law'>Law</option> \
									<option value='Medicine'>Medicine</option> \
									<option value='Music'>Music</option> \
									<option value='Nursing'>Nursing</option> \
									<option value='Pharmaceutical'>Pharmaceutical</option> \
									<option value='Public Health'>Public Health</option> \
									<option value='Science'>Science</option> \
									<option value='Social Work'>Social Work</option> \
									<option value='Other'>Other</option> \
								</select><input id='faculty2' style='display:none' placeholder='Enter your Faculty'/></div>");
								$('#row5').append("<div id='student_no_container'> \
								<div class='col5'>Student Number*:</div> \
								<div class='col5'><input id='student_no' name='student_no' maxlength='8' value="+data[0].student_no+" onKeyPress='return numbersonly(this, event)''></input></div> \
								</div>");
								$('#row6').append("<div class='col1'>I would like to incorporate CiTR into my courses(projects, practicums, etc.): \
									<input id='integrate'  name='integrate' type='checkbox'"+ (data[0].integrate==1 ? 'checked=checked' : '' ) +" /> \
									<div class='col5'>Year*:</div> \
									<div class='col8'> \
										<select id='schoolyear' style='z-position=10;'> \
											<option value='"+data[0].schoolyear+"'>"+data[0].schoolyear+"</option> \
											<option value='1'>1</option> \
											<option value='2'>2</option> \
											<option value='3'>3</option> \
											<option value='4'>4</option> \
											<option value='5'>5+</option> \
										</select> \
									</div></div>");
							} //END IF STUDENT
							$('#row7').append("<div class='col5'>Has a show:</div>");
							if(data[0].has_show == 1){
								$('#row7').append("<div class='col5' >Yes<input id='show1' class='show_select' type='radio' checked='checked' /> \
								No<input id='show2' class='show_select' type='radio' /></div>");
							}
							else{
								$('#row7').append("<div class='col5' >Yes<input id='show1' class='show_select' type='radio' /> \
								No<input id='show2' class='show_select' type='radio' checked='checked'/></div>");
							}
							$('#row7').append("<div class='col5'>Show Name:</div><div class='col5'><input id=show_name "+(data[0].show_name ? ("value='"+data[0].show_name+"'"):"placeholder='Show name(s)'")+"</div>");
							$('#row8').append("<hr/>");
							//CONTACT INFORMATION
							//console.log("Email = "+data[0].email);
							$('#row9').append("<div class='col7'>Email Address*: </div> \
								<div class='col6'><input id='email' class='required' name='email' value='"+data[0].email+"' maxlength='40'  ></input></div> \
								<div class='col6'>Primary Number*:</div> \
								<div class='col6'><input id='phone1' class='required' name='phone1' value='"+data[0].primary_phone+"' maxlength='10' onKeyPress='return numbersonly(this, event)''></input></div> \
								<div class='col6'>Secondary Number:</div> \
								<div class='col6'><input id='phone2' name='phone2' "+ (data[0].secondary_phone ? ("value='"+data[0].secondary_phone+"'"):"placeholder='Secondary Phone'") +"maxlength='10' onKeyPress='return numbersonly(this, event)''></input></div>");
							$('#row10').append("<hr/>");
							//TEXT FIELDS

							
							$('#row11').append("<div class='col5'>About me:</div> \
							<textarea id='about' class='largeinput' rows='3'>"+(data[0].about ?(data[0].about):"")+"</textarea>")
							$('#row12').append("<div class='col5'>My Skills:</div> \
								<textarea id='skills' class='largeinput' rows='3'>"+(data[0].skills ?(data[0].skills):"")+"</textarea>");
							$('#row13').append("<div class='col5'>How did you hear about us?:</div> \
								<textarea id='exposure' class='largeinput' rows='3'>"+(data[0].exposure ?(data[0].exposure):"")+"</textarea>");
							$('#row14').append("<div class='col5'>Staff Comments:</div> \
								<textarea id='comments' class='largeinput' rows='3'>"+(data[0].comments ?(data[0].comments):"")+"</textarea>");
							add_handlers();
						}).fail(function(){
							
						});

						$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : 'get', "type" : 'member_year',"value":value},
						dataType: "json",
						async: false
						}).success(function(data){
							year = data[0].membership_year;
							$('#row15').append("<hr>");
							$('#row16').append("<div class='col2'> Select Year:<select id='member_year_select'></select></div>");
							for( $j = 0; $j < Object.keys(data).length; $j++ ){
								$('#member_year_select').append("<option value="+data[$j].membership_year+">"+data[$j].membership_year+"</option>")
							}
						}).fail(function(){
							
						});
						$('#member_result').append("<div id=membership_year></div>");
						load_member_year(value,year);
						$('#row22').append("<hr/>");
						$('#row23').append("<div class='col5'>User Priveleges:</div>");
						var username;
						$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : 'get', "type" : 'permission',"value":value},
						dataType: "json",
						async: false
						}).success(function(data){
							$('#row23').append("<div id='userid' style='display:none' value='"+data[0].userid+"'>"+data[0].userid+"</div>");
							$('#row24').append("<div class='col5'> Is a member:<input type=checkbox id=is_member "+(data[0].member==1 ? "checked=checked" : "")+"/></div>");
							$('#row24').append("<div class='col5'> Is a DJ:<input type=checkbox id=is_dj "+(data[0].dj==1 ? "checked=checked" : "")+"/></div>");
							$('#row24').append("<div class='col5'> Is an admin:<input type=checkbox id=is_administrator "+(data[0].administrator==1 ? "checked=checked" : "")+"/></div>");
							$('#row24').append("<div class='col5'> Add users:<input type=checkbox id=is_add_user "+(data[0].adduser==1 ? "checked=checked" : "")+"/></div>");
							$('#row24').append("<div class='col5'> Add shows:<input type=checkbox id=is_add_show "+(data[0].addshow==1 ? "checked=checked" : "")+"/></div>");
						
							$('#row25').append("<div class='col5'> Edit playsheet:<input type=checkbox id=is_edit_dj "+(data[0].editdj==1 ? "checked=checked" : "")+"/></div>");
							$('#row25').append("<div class='col5'> Access Library:<input type=checkbox id=is_library "+(data[0].library==1 ? "checked=checked" : "")+"/></div>");
							$('#row25').append("<div class='col5'> Edit members:<input type=checkbox id=is_membership "+(data[0].membership==1 ? "checked=checked" : "")+"/></div>");
							$('#row25').append("<div class='col5'> Edit library:<input type=checkbox id=is_edit_library "+(data[0].editlibrary==1 ? "checked=checked" : "")+"/></div>");
							username = data[0].username;
						}).fail(function(){
							
						});
							$('#row26').append("<hr>");
						$('#member_result').append("<center>Username: "+username+"  -- New Password:<input id='password' placeholder='Enter a new password' type='password'></input><br/> \
							<button class='member_submit' name='edit'>Save Changes</button></center>");
					default:
						break;
				}
				
				add_handlers();
				break;
			case 'report':
				switch(type){
					case 'init':
						document.getElementById("membership").innerHTML = " ";
						$('#membership').append('<select id="year_select"></select><button class="member_submit" name="report">Get Yearly Report</button><div id="membership_result"></div>');
						//Populate Dropdown with possible years to report on.
						actiontemp = 'get';
						typetemp = 'year';
						$.ajax({
							type:"POST",
							url: "form-handlers/membership-handler.php",
							data: {"action" : actiontemp, "type" : typetemp},
							dataType: "json",
							async: false
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
							url: "form-handlers/membership-handler.php",
							data: {"action" : action,"year" : year},
							dataType: "json",
							async: false
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
						$("#membership").append("<div id='membership_header'>Interest:");

						$('#membership_header').append("<select id=search_value></select>");
							var title = ['All','Arts','Ads and PSAs','Digital Library','DJ101.9','Illustrate for Discorder','Writing for Discorder','Live Broadcasting','Music','News','Photography','Programming Committee','Promos and Outreach','Show Hosting','Sports','Tabling','Web and Tech'];
							var values =  ['all','arts','ads_psa','digital_library','dj','discorder','discorder_2','live_broadcast','music','news','photography','programming_committee','promotions_outreach','show_hosting','sports','tabling','tech'];
							$searchval = $('#search_value');
							for($i = 0; $i< title.length; $i++){
								$searchval.append("<option value='"+values[$i]+"'>"+title[$i]+"</option>");
							}
						
						$("#membership_header").append("Paid Status: Both<input id='paid1' class='paid_select' type='radio' checked='checked' /> \
						Paid<input id='paid2' class='paid_select' type='radio' /> \
						Not Paid<input id='paid3' class='paid_select' type='radio' /> \
						Filter by Year: <select id='year_select'><option value='all'>Don't Filter</option></select><br/>");
						$("#membership_header").append("<laber for='date_filter'>Filter by join date</label><input id='date_filter' type='checkbox'/>");
						$("#membership_header").append("<label for='from'></label><input type='text' id='from' name='from' value='"+week_ago+"' />");
						$("#membership_header").append("<label for='to'> to </label><input type=text id='to' name='to' value='"+today+"' />");
						
						var actiontemp = 'get';
						var typetemp = 'year';
					$.ajax({
						type:"POST",
						url: "form-handlers/membership-handler.php",
						data: {"action" : actiontemp, "type" : typetemp},
						dataType: "json",
						async: false
					}).success(function(data){
							for( $j = 0; $j < Object.keys(data).length; $j++ ){
								$('#year_select').append("<option value="+data[$j].membership_year+">"+data[$j].membership_year+"</option>")
							}
					}).fail(function(){
						
					});
						$('#membership_header').append("<button class='member_submit' name='mail'>Generate Email List</button>");
						$('#membership').append("<div id='member_result'></div>");
						add_handlers();
						break;
					case 'generate':
						//console.log('mail generate');
						if(getCheckbox('paid3')){
						paid='0';
						}else if(getCheckbox('paid2')){
							paid='1';
						}else{
							paid='both';
						}
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
							url: "form-handlers/membership-handler.php",
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
				
				break;
			default:
				manage_members('init');
				add_handlers();
				break;		
		}	
	}
	

