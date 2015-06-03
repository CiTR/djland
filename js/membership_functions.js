window.myNameSpace = window.myNameSpace || { };
var member;

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
function getCheckbox(id){
	var checkbox = id;
	if($('#'+checkbox).prop('checked')){
		return 1;
	}
	else{
		return 0;
	}
}
function setCheckbox(value,id){
	if(value == '1'){
		$('#'+id).prop('checked',true);
	}else{
		$('#'+id).prop('checked',false);
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
	$target.val(value).change();
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
function getRadio(id){
	if($("#"+id+"1").prop('checked')){
		return 1;
	}else{
		return 0;
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
function queryMembers(search_by,value,paid,year,order_by){
	return $.ajax({
		type:"GET",
		url: "form-handlers/membership/search.php",
		data: { 'search_by':search_by,'value':value,'order_by':order_by,'paid':paid,'year':year},
		dataType: "json",
		async: true
		});
}

//Returns all membership years present for member id
function queryMembershipYears(member_id){
    return $.ajax({
		type:"GET",
		url: "form-handlers/membership/membership_years.php",
		data: {"member_id":member_id},
		dataType: "json",
		async: true
		});	
}

function queryMembershipPriveleges(id){
	assertTrue(id != null,"id is null");
	var privileges = {"id":id}
	$.ajax({
		type:"GET",
		url: "form-handlers/membership/permission.php",
		data: {"action" : 'get', "type" : 'permission',"value":value},
		dataType: "json",
		async: true
	}).success(function(data){
	    //TODO: Create form hander, and return permissions array
		
	}).fail(function(){
			
	});
}
function loadYearSelect(){
	var years = queryMembershipYears();
	$.when(years).then(function(data){
		$('.year_select').each(function(element){
			for(var i=0; i<data['years'].length; i++){
				$(this).append("<option value="+data['years'][i]+">"+data['years'][i]+"</option>");
			}
		});
	},function(err){
		console.log("failed to load years");
	});	
	return years;
}

function loadMember(id){
	$('#member_loading').show();
	$('#member_result').hide();
	member = new Member(id);
	$.when(member.info_callback,member.interest_callback).then(function(info,interests){
		$('#member_loading').hide();
		$('#member_result').show();
		member._initInfo(info[0]);
		member._initInterests(interests[0]);
		member.displayInfo();
		member.displayInterests();
	},function(err1,err2){
		console.log("Failed to load member");
	});
}
function displayMemberList(search_by,value,paid,year,order_by){
	$('#search_loading').show();
	$.when(queryMembers(search_by ,value ,paid ,year ,order_by)).then(function(data){
		$('#search_loading').hide();
		var member_result = $('#membership_table[name="search"]');
		var member_result_header = $('#headerrow');
		member_result_header.show();
		$('.member_row').each(function(e){
			$(this).remove();
		});
		
		for(var member in data){
			member_result.append("<tr id=row"+data[member].member_id+" class='member_row' name='"+data[member].member_id+"'></tr>");
			var row = $('#row'+data[member].member_id);
			
			for(var item in data[member]){
				if(item != 'member_id' && item != 'comments') row.append("<td class='member_row_element "+item+"'>"+ (data[member][item] != null ? data[member][item] : "") +"</td>");
				else if(item == 'comments') row.append("<td><input class='staff_comment' id='comment"+data[member].member_id+"' value='"+ (data[member][item] != null ? data[member][item] : "") +"'></input></td>");
			}	
			row.append("<td><input type='checkbox' class='delete_member' id='delete_"+member+"'></td>");
			row.append("<div class='check hidden'>&#x274F;</div>");
		}	
	});	
}

function saveComments(){
	var comments = {};

	$('.staff_comment.updated').each(function(element){
		var id = ($(this).attr('id').toString().replace('comment',''));
		var comment = ($(this).val());
		comments[id] = {'id':id,'comments':comment};
		$(this).removeClass('updated');
	});
	console.log(comments);

	var requests = Array();
	for(var comment in comments){
    	requests.push(
    		$.ajax({
				type:"POST",
                url: "form-handlers/membership/member.php",
                data: {"member_id" : comment, "member": JSON.stringify(comments[comment])},
                dataType: "json",
                async: true
			})
		);
	}

    $.when.apply($,requests).then(function(){
    	console.log(arguments);
    	alert("Successfully updated comments for "+comments.toString());
    },function(err){
    	 alert("Could not delete: "+comments.toString()+"\n"+data[0]);
    });

}

function yearlyReport(year_callback){
	$.when(year_callback).then(function(){
		var year =	$('.year_select[name="report"]').val();
		var ajax = $.ajax({
				type:"GET",
                url: "form-handlers/membership/report.php",
                data: {"year" : year},
                dataType: "json",
                async: true
			}).success(function(data){
				console.log(data);
				for(var item in data){
					setText(data[item],item);
				}
			});

	});
}





