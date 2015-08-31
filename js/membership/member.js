window.myNameSpace = window.myNameSpace || { };

function Member(id){
	var _this = this;
	this.member_id = id;
	this.info_callback = this._queryInfo();
	this.interest_callback = this._queryInterests();
	this.permission_callback = this._queryPermissions();
	$.when(this.info_callback, this.interest_callback, this.permission_callback).then(function(info,interests,permissions){
		_this._initInfo(info[0]);
		_this._initInterests(interests[0]);
		_this._initPermissions(permissions[0]['permissions']);
		_this.displayInfo();
		_this.displayInterests();
		_this.displayPermissions();
		console.log(_this);
	},function(err,err2){
		console.log("ERROR");
	});

	//Blank constructor as we want multiple constructors, and javascript does not support overloading. Instead use _init(arguments list...), _fromObj(Member), and query(id) to build the object.
}

Member.prototype = {
	_initInfo:function(member){
		var member_info = {};
		this.username=member.username;
		this.fullname = member.firstname + " " + member.lastname;
		for(var item in member){
			if(item != 'username' && item != 'member_id')
				member_info[item] = member[item]
		}
		this.member_info = member_info;
	},
	_initInterests:function(membership_years){
		this.membership_years = membership_years;
		 document.getElementById('membership_year').options.length = 0;
		for(var year in membership_years){
			$('#membership_year').append("<option value="+year+">"+year+"</option>");
		}
		
	},
	_initPermissions:function(permissions){
		this.permissions ={};
		for(level in permissions){
			if(level !='username') this.permissions[level] = permissions[level];
		}
		this.username = permissions.username;
		
	},
	_queryInfo:function(){
		var _this = this;
		return $.ajax({
			type:"GET",
			url: "form-handlers/membership/member.php",
			data: {"member_id":_this.member_id},
			dataType: "json",
			async: true
		});
	},
	_queryInterests:function(){
		var _this = this;
		return $.ajax({
			type:"GET",
			url: "form-handlers/membership/membership_year.php",
			data: {"member_id":_this.member_id},
			dataType: "json",
			async: true
		});
	},
	_queryPermissions:function(){
		var _this = this;
		return $.ajax({
			type:"GET",
			url: "form-handlers/membership/permission.php",
			data: {"member_id":_this.member_id},
			dataType: "json",
			async: true
		});
	},
	getInfo:function(){
		if(this['member_info']['member_type'] != 'Student'){
			delete this['member_info'].student_no;
			delete this['member_info'].schoolyear;
			delete this['member_info'].faculty;
		}
		for(var field in this.member_info){
			this['member_info'][field] = get(field);
		}
	},
	getInterests:function(){
		var membership_year = getVal('membership_year');
		var my={'membership_year':membership_year};
		for(var interest in interests){
			if(interest != 'Other'){my[interests[interest]] = getCheckbox(interests[interest]);}
			else{my[interests[interest]] = getVal(interests[interest]);}
		}
		my['paid'] = get('paid');
		this.membership_years[membership_year] = my;
	},
	getPermissions:function(){

		var permissions = {};
		for(var level in permission_levels){ 
			if(level != 'operator') permissions[level] = getCheckbox('level_'+level);
		}
		this.permissions = permissions;
	},
	displayInfo:function(request){
		_this = this;
		if(request == null){
			setText(this.username,'username');
			for(var field in this.member_info){
				set(this['member_info'][field],field);
			}
			setVal(this)
		}else{
			$.when(request).then(function(data){
				_this.displayInfo();
			},function(error){
				console.log('data was not available');
			});
		}
	},displayInterests:function(year){
		var m;
		if(!year){
			for(year in this.membership_years){
			m = year;
			break;
			}
		}

		m=this.membership_years[year];
		
		for(var interest in interests){
			if(interest != 'Other' && interest != 'membership_year'){ 
				setCheckbox(m[interests[interest]],interests[interest]); 
			}
			else {
				setVal(m['other'],interests[interest]);			
			}
		}
		
	},displayPermissions:function(){
		console.log(permission_levels);
		for(var level in permission_levels){
			if(level != 'operator') setCheckbox(this.permissions[level],"level_"+level);
		}

	},renew:function(){
		$_this = this;
        if(!this.membership_years[membership_year]){
        	this.membership_years[membership_year] = this.getInterests();
        }
    	return $.ajax({
        type:"POST",
        url: "form-handlers/membership/renew.php",
        data: {
           "member_id":$_this.member_id, "membership_year":JSON.stringify(this.membership_years[getVal('membership_year')])
        },
        dataType: "json"
		});
        
    },update:function(){
    	$.when(this.updateInfo(),this.updateInterests()).then(function(r1,r2){
    		alert("Successfully Updated");
    		console.log(r1.data,r2.data);
    	},function(err1,err2){
    		alert("Something Went Wrong");
    		console.log(err1);
    		console.log(err2);
    	});    	
    },updateInfo:function(){
    	this.getInfo();
		$_this = this;
		return $.ajax({
			type:"POST",
			url: "form-handlers/membership/member.php",
			data: {
			"member_id":_this.member_id, "member": JSON.stringify(_this.member_info)
		 	},
			dataType: "json"
		});
	},updateInterests:function(){
		this.getInterests();
		$_this = this;
		return $.ajax({
			type:"POST",
			url: "form-handlers/membership/membership_year.php",
			data: {
			"member_id": _this.member_id, "membership_year":JSON.stringify(_this.membership_years[getVal('membership_year')])
		 	},
			dataType: "json"
		});
	},updatePermissions:function(){
		this.getPermissions();
		$_this = this;
		return $.ajax({
			type:"POST",
			url: "form-handlers/membership/permission.php",
			data: {
			"member_id": _this.member_id,"permissions":JSON.stringify(_this.permissions)
		 	},
			dataType: "json"
		});
	},updatePassword:function(){
		$_this = this;
		if($('#password').val().length > 0){

			return $.ajax({
				type:"POST",
				url: "form-handlers/membership/password.php",
				data: {
				"member_id": _this.member_id,"password":getVal('password')
			 	},
				dataType: "json"
			});
		}else console.log($('#password').length)
		
	}
}
