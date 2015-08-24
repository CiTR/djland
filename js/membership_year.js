window.myNameSpace = window.myNameSpace || { };

function MembershipYear(){
	this.member_id = "";
	this.membership_year = "";
	for(var interest in interests){
		this.(interests[interest])
	}
	//Blank constructor as we want multiple constructors, and javascript does not support overloading. Instead use _init(arguments list...), _fromObj(Member), and query(id) to build the object.
}

Member.prototype = {
	_init:function(){
	},
	_fromObj:function(member){
		
	},
	_query:function(id){
		var member_info;
		var ajax = $.ajax({
		type:"GET",
		url: "form-handlers/membership/member.php",
		data: {"id":id},
		dataType: "json",
		async: true
		});
	},
	
}