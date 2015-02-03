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
function setCheckbox(value,id){
	$target = $('#'+id);
	if(value == true){
		$target.attr('checked','checked');
	}else{
		$target.removeAttr('checked');
	}
}
function setText(value,id){
	$target = $('#'+id);
	assertTrue($target != null);
	$target.text(value);
}
function setVal(value,id){
	$target = $('#'+id);
	assertTrue($target != null);
	$target.val(value);
}
function setRadio(value,id){
	//yes
	$radio1 = $('#'+id+'1');
	//no
	$radio2 = $('#'+id+'2');
	assertTrue($radio1 != null && $radio2 != null);
	switch(value){
		case '1': 
			$radio1.attr('checked','checked');
			$radio2.removeAttr('checked');
			break;
		case '0':
			$radio2.attr('checked','checked');
			$radio1.removeAttr('checked');
			break;
		default:
			break; 
	}
}

//Returns member data associated with member id
function getMember(id){
	assertTrue(id != null,"id is null");
	var member = {"id":id};
	$.ajax({
		type:"POST",
		url: "form-handlers/membership-handler.php",
		data: {"action" : 'view', "type" : 'init',"value":id},
		dataType: "json",
		async: false
		}).success(function(data){
			var info = data[0];
			
			//Populate the basic member information 
			member.firstname = info.firstname;
			member.lastname = info.lastname;
			member.name = info.firstname + " " + info.lastname;
			member.address = info.address;
			member.city = info.city;
			member.province = info.province;
			member.postalcode = info.postalcode;
			member.canadian_citizen = info.canadian_citizen;
			member.member_type = info.member_type;
			if(member.member_type == "Student"){
				member.faculty = info.faculty;
				member.schoolyear = info.schoolyear;
				member.student_no = info.student_no;
				member.integrate = info.integrate;	
			}
			member.is_new = info.is_new;
			member.alumni = info.alumni;
			member.since = info.since;
			member.has_show = info.has_show;
			//handling future possibility for multiple shows.
			if(member.has_show == '1'){
				if(info.show_name.constructor === Array){
					member.show_name = "";
					var i;
					for(i = 0 ; i < info.show_name.length; i++){
						member.show_name += info.show_name[i];
						if(i < info.show_name.size() - 1){
							member.show_name += ", "
						}
					}
				}else{
				member.show_name = info.show_name;
				}
			}
			member.email = info.email;
			member.primary_phone = info.primary_phone;
			member.secondary_phone = info.secondary_phone;
			member.about = info.about;
			member.skills = info.skills;
			member.exposure = info.exposure;
			member.comments = info.comments;

		}).fail(function(){
			console.log("Unable to retrieve member information");
		});
	return member;
}

//Returns all membership years present for member id
function getMembershipYears(id){
	assertTrue(id != null,"id is null");
	var member = {"id":id};
	var membership_years = Array();
	$.ajax({
		type:"POST",
		url: "form-handlers/membership-handler.php",
		data: {"action" : 'get', "type" : 'member_year',"value":id},
		dataType: "json",
		async: false
		}).success(function(data){
			var info = data;
			var i;
			for(i = 0 ; i < info.length; i ++){
				membership_years[i] = info[i][0];
			}
		}).fail(function(){
			console.log("Unable to retrieve member information");
		});
	
	member.membership_years = membership_years;
	return member;
}
function getMembershipYear(id,year){
		var m_y = {"id":id,"year":year};
		$.ajax({
		type:"POST",
		url: "form-handlers/membership-handler.php",
		data: {"action" : 'get', "type" : 'member_year_content',"value":id,"year":year},
		dataType: "json",
		async: false
		}).success(function(data){
			var info = data[0];
			m_y.paid = info.paid;
			m_y.sports = info.sports;
			m_y.news = info.news;
			m_y.arts = info.arts;
			m_y.music = info.music;
			m_y.show_hosting = info.show_hosting;
			m_y.live_broadcast = info.live_broadcast;
			m_y.tech = info.tech;
			m_y.programming = info.programming_committee;
			m_y.ads_psa = info.ads_psa;
			m_y.promos = info.promotions_outreach;
			m_y.discorder = info.discorder;
			m_y.discorder_2 = info.discorder_2;
			m_y.digital_library = info.digital_library;
			m_y.photography = info.photography;
			m_y.tabling = info.tabling;
			m_y.dj = info.dj;
			m_y.other = info.other;
		}).fail(function(){
			console.log("Unable to retrieve member information");
		});
		return m_y;
}
function updateMember(member){
	$.ajax({
		type:"POST",
		url: "form-handlers/member-info-update-handler.php",
		data: {
		"member":member
	 	},
		dataType: "json"
	}).success(function(data) {
		if(data[0]=="ERROR"){
			console.log(data);
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
		alert("Submission Failed");
	});
}

function displayMemberInfo(member){
	setText(member.firstname,'firstname');
	setText(member.lastname, 'lastname');
	setVal(member.address,'address');
	setVal(member.city,'city');
	setVal(member.province,'province');
	setVal(member.postalcode,'postalcode');
	setRadio(member.canadian_citizen,'can')
	setRadio(member.alumni,'alumni');
	setText(member.since,'since');
	setVal(member.is_new,'is_new');
	setVal(member.member_type,'member_type');
	if(member.member_type == "Student"){
		setVal(member.faculty,'faculty');
		setVal(member.schoolyear,'schoolyear');
		setVal(member.integrate,'integrate');
	}else{
		$('#row6').hide();
		$('#row7').hide();
	}
	setRadio(member.has_show,'show');
	setVal(member.email,'email');
	setVal(member.primary_phone,'primary_phone');
	setVal(member.secondary_phone,'secondary_phone');
	setVal(member.about,'about');
	setVal(member.skills,'skills');
	setVal(member.exposure,'exposure');

}
function displayMemberInterests(membership_year){
	var m = membership_year;
	setCheckbox(m.music,'music');
	setCheckbox(m.discorder,'discorder');
	setCheckbox(m.discorder_2,'discorder_2');
	setCheckbox(m.dj,'dj');
	setCheckbox(m.show_hosting,'show_hosting');
	setCheckbox(m.sports,'sports');
	setCheckbox(m.news,'news');
	setCheckbox(m.arts,'arts');
	setCheckbox(m.live_broadcast,'live_broadcast');
	setCheckbox(m.tech,'tech');
	setCheckbox(m.programming,'programming');
	setCheckbox(m.ads_psa,'ads_psa');
	setCheckbox(m.promos,'promos');
	setCheckbox(m.photography,'photography');
	setCheckbox(m.digital_library,'digital_library');
	setCheckbox(m.tabling,'tabling');
	setVal(m.other,'other');
}