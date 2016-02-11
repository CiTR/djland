window.myNameSpace = window.myNameSpace || { };
var donor;


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
			$(this).append("<option value='all'>All Years</option>");

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
