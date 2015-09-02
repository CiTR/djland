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

function get(target_id,target_class,target_name){
	var target =  $( (target_id != null ? '#'+ target_id : "" ) + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name="+target_name+"]" : ""));
	var tag = target.prop('tagName');
	var result;
	switch(tag){
		case 'DIV':
			result = target.text();
			break;
		case 'INPUT':
			var type = target.attr('type');
			switch(type){
				case 'checkbox':
					if(target.prop('checked')) result = 1;
					else result = 0;
					break;
				default:
					result = target.val();
					break;
			}
			break;
		case 'SELECT':
		case 'TEXTAREA':
			result = target.val();
			break;
		default:
			result = target.val();
			break;
	}
	return result;
}
function set(value,target_id,target_class,target_name){
	var target =  $( (target_id != null ? '#'+ target_id : "" ) + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name="+target_name+"]" : ""));
	var tag = target.prop('tagName');
	//console.log("Value:"+value+" Target:"+target.attr('id') + "," +target.attr('class') + "," +target.attr('name')+" Tag:"+tag);
	switch(tag){
		case 'DIV':
			target.text(value);
			break;
		case 'SELECT':
			target.val(value).change();
			break;
		case 'INPUT':
			var type = target.attr('type');
			switch(type){
				case 'checkbox':
					if(value == '1'){
						target.prop('checked',true);
					}else{
						target.prop('checked',false);
					}
					break;
				case 'radio':
					var yes = $('#'+target_id+'1');
					var no = $('#'+target_id+'2');
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
					break;
				default:
					target.val(value).change();
					break;
			}
			break;
		case 'TEXTAREA':
		default:
			target.val(value).change();
			break;
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


function decodeHTML(str){
            str = str.replace(new RegExp('&quot;','gi'),'"');
            str = str.replace(new RegExp('&Atilde;','gi'),'Ã');
            str = str.replace(new RegExp('&copy;','gi'),'©');
            return str.replace(/&#(\d+);/g, function(match, dec) {
                return String.fromCharCode(dec);
            });
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
	$('#member_loading[name="view"]').show();
	$('#member_result').hide();
	var new_member = new Member(id);
	$.when(new_member.info_callback,new_member.interest_callback).then(function(info,interests){
		 member = new_member;
		$('#member_loading[name="view"]').hide();
		$('#member_result').show();
	},function(err1,err2){
		console.log("Failed to load member");
	});
}
function displayMemberList(search_by,value,paid,year,order_by){
	$('.member_row').remove();
	$('#search_loading').show();

	if(year == null){
		year = get('year_select',null,'search');
	}
	$.when(queryMembers(search_by ,value ,paid ,year ,order_by)).then(function(data){
		$('#search_loading').hide();
		var member_result_table = $('#membership_table[name="search"]');
		var member_result_header = $('#headerrow');
		member_result_header.show();
		for(var member in data){
			member_result_table.append("<tr id=row"+data[member].member_id+" class='member_row' name='"+data[member].member_id+"'></tr>");
			var row = $('#row'+data[member].member_id);
			
			for(var item in data[member]){
				if(item != 'member_id' && item != 'comments') row.append("<td class='member_row_element "+item+"'>"+ (data[member][item] != null ? data[member][item] : "") +"</td>");
				else if(item == 'comments') row.append("<td><input class='staff_comment' id='comment"+data[member].member_id+"' value='"+ (data[member][item] != null ? data[member][item] : "") +"'></input></td>");
			}	
			row.append("<td><input type='checkbox' class='delete_member' id='delete_"+member+"'></td>");
			row.append("<div class='check hidden'>&#x274F;</div>");
		}
		if(data.length <1){
			member_result_header.hide();
			$('#membership_result[name="search"]').append("<div class='member_row'>No Results</div>");
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

function emailList(){

	var email_value;
	$('.email_select_value').each(function(e){
		if($(this).is(':visible')){
			email_value = $(this).val();
		}
	}); 
	console.log(get('email_select'));
	var request = $.ajax({
		type:"GET",
        url: "form-handlers/membership/email_list.php",
        data: {"type" : get('email_select'),'value': email_value, "year":get(null,'year_select','email'),"from":get('from'),"to":get('to') },
        dataType: "json",
        async: true
	});

	$.when(request).then(
		function(reply){
			console.log(reply['emails']);
			var email_list = $('#email_list');
			for(var email in reply['emails']){
				email_list.append(reply['emails'][email]);
				if(email != reply['emails'][reply['emails'].length-1]){
					email_list.append(", ");
				}
			}
		},
		function(error){
			console.log(error[0]);
		});
	
	console.log(email_value);
}





