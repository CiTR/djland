window.myNameSpace = window.myNameSpace || { };

function Member(id){
	var this_ = this;
	this.member_id = id;

	if(id != null){
		this.info_callback = this._queryInfo();
		this.interest_callback = this._queryInterests();
		this.permission_callback = this._queryPermissions();
		this.user_callback = this._queryUser();
		$.when(this.info_callback, this.interest_callback, this.permission_callback,this.user_callback).then(function(info,interests,permissions,user){
			this_._initInfo(info[0]);
			this_._initInterests(interests[0]);
			this_._initPermissions(permissions[0]);
			this_._initUser(user[0]);
			this_.displayInfo();
			this_.displayInterests();
			this_.displayPermissions();
			this_.displayUser();
			//console.log(this_);
		},function(err,err2){
			console.log("ERROR");
		});
	}else{
		this.membership_years = new Array();
		this.member_info = {
			about:null,
			address: null,
			alumni: null,
			canadian_citizen: null,
			city: null,
			comments: null,
			email: null,
			exposure: null,
			faculty: null,
			firstname: null,
			has_show: null,
			integrate: null,
			is_new: null,
			joined: null,
			lastname: null,
			member_type: null,
			postalcode: null,
			primary_phone: null,
			production_training: null,
			programming_training: null,
			province: null,
			schoolyear: null,
			secondary_phone: null,
			show_name: null,
			since: null,
			skills: null,
			spoken_word_training:null,
			student_no: null,
			technical_training: null
		};
		this.user_info = {};
	}

	//Blank constructor as we want multiple constructors, and javascript does not support overloading. Instead use _init(arguments list...), _fromObj(Member), and query(id) to build the object.
}

Member.prototype = {
	_initInfo:function(member){
		var member_info = {};
		this.username=member.username;
		this.fullname = member.firstname + " " + member.lastname;
		for(var item in member){
			if(item == 'member_type' && member['member_type'] == 'Staff' && $("#member_type option[value='Staff']").length == 0) $('#member_type').append("<option value='Staff'>Staff</option>");
			if(item == 'member_type' && member['member_type'] == 'Lifetime' && $("#member_type option[value='Lifetime']").length == 0) $('#member_type').append("<option value='Lifetime'>Lifetime</option>");
			if(item != 'username' && item != 'member_id'){
				if(item == 'firstname' && member[item] != null || item == 'lastname' && item != null) member[item] = decodeHTML(member[item]);
				member_info[item] = member[item];
			}
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
			if(level !='user_id') this.permissions[level] = permissions[level];
		}

	},_initUser:function(user){
		this.user_info = user;
	},
	_queryInfo:function(){
		var this_ = this;
		return $.ajax({
			type:"GET",
			url: "api2/public/member/"+this_.member_id,
			dataType: "json",
			async: true
		});
	},
	_queryInterests:function(){
		var this_ = this;
		return $.ajax({
			type:"GET",
			url: "api2/public/member/"+this_.member_id + "/years",
			dataType: "json",
			async: true
		});
	},
	_queryPermissions:function(){
		var this_ = this;
		return $.ajax({
			type:"GET",
			url: "api2/public/member/"+this_.member_id + "/permission",
			dataType: "json",
			async: true
		});
	},
	_queryUser:function(){
		var this_ = this;
		return $.ajax({
			type:"GET",
			url: "api2/public/member/"+this_.member_id + "/user",
			dataType: "json",
			async: true
		});
	},
	getInfo:function(){
		if($('#member_type').val() != 'Student'){
			delete this['member_info'].student_no;
			delete this['member_info'].schoolyear;
			delete this['member_info'].faculty;
		}
		for(var field in this.member_info){
			if(field == 'faculty' && get(field) == 'Other'){
				this['member_info'][field] = get('faculty2');
			}else{
				this['member_info'][field] = get(field);
			}
		}

	},
	getInterests:function(){
		var membership_year = get('membership_year');
		var my={'membership_year':membership_year};
		for(var interest in interests){
			if(interest != 'Other'){
				my[interests[interest]] = getCheckbox(interests[interest]);
			}else{
				my[interests[interest]] = getVal(interests[interest]);
			}
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
		var this_ = this;
		if(request == null){
			setText(this.username,'username');
			for(var field in this.member_info){
				if(field == 'faculty' && faculties.indexOf(this['member_info'][field]) < 0){
					setVal('Other','faculty');
					set(this['member_info'][field],'faculty2');
				}else{
					set(this['member_info'][field],field);
				}
			}

		}else{
			$.when(request).then(function(data){
				this_.displayInfo();
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
		setCheckbox(m['paid'],'paid');

	},displayPermissions:function(){
		for(var level in permission_levels){
			if(level != 'operator') setCheckbox(this.permissions[level],"level_"+level);
		}

	},displayUser:function(){
		set(this.user_info.username,'username');
	},renew:function(){

        $.when(this.createMembershipYear(),this.updateInfo()).then(
    	function(r1,r2){
        	alert("Successfully Renewed");
        	window.location.href = 'main.php';
        },function(err1,err2){
        	alert("Something went wrong");
        });
    },update:function(){
    	$.when(this.updateInfo(),this.updateInterests()).then(function(){
    		alert("Successfully Updated");
    		window.location.href = 'main.php';
    	},function(err1,err2){
    		alert("Something went wrong");
    	});
    },updateInfo:function(){
    	this.getInfo();
		var this_ = this;
		return $.ajax({
			type:"POST",
			url: "api2/public/member/"+this_.member_id,
			data: {
				"member":JSON.stringify(this_.member_info)
		 	},
			dataType: "json"
		});
	},updateInterests:function(){
		this.getInterests();
		var this_ = this;
		return $.ajax({
			type:"POST",
			url: "api2/public/member/"+this_.member_id+"/years",
			data: {
				"years": JSON.stringify(this_.membership_years)
		 	},
			dataType: "json"
		});
	},updatePermissions:function(){
		this.getPermissions();
		console.log(this);
		var this_ = this;
		return $.ajax({
			type:"POST",
			url: "api2/public/member/"+this_.member_id+"/permission",
			data: {
				"permission":JSON.stringify(this_.permissions)
		 	},
			dataType: "json"
		});
	},updatePassword:function(){
		var this_ = this;
		if($('#password').val().length > 0){
			return $.ajax({
				type:"POST",
				url: "api2/public/member/"+this_.member_id+"/password",
				data: {
					"password": getVal('password')
			 	},
				dataType: "json"
			});
		}
	},create:function(){
		var this_ = this;
		$.when(this.createMember()).then(function(response){
			this_.member_id = response;
			this_.user_info['member_id'] = response;
			$.when(this_.createUser()).then(function(response){
				$.when(this_.createMembershipYear()).then(function(response){
					alert("Successfully Submitted");
					window.location.href = 'index.php';
				})
			});
		});

	},createMember:function(){
		this.getInfo();
		var this_ = this;
		return $.ajax({
			type:"POST",
			url: "api2/public/member",
			data: {
				"member": JSON.stringify(this_.member_info)
		 	},
			dataType: "json"
		});
	},createUser:function(){
		var this_ = this;
		this_.user_info['username'] = get('username');
		this_.user_info['password'] = get('password');
		var this_ = this;
		return $.ajax({
			type:"POST",
			url: "api2/public/user",
			data: {
				"user": JSON.stringify(this_.user_info)
		 	},
			dataType: "json"
		});
	},createMembershipYear:function(){
		var this_ = this;
		this.getInterests();
		if(this.member_info.member_type == 'Lifetime' || this.member_info.member_type == 'Staff') this_.membership_years[get('membership_year')].paid = 1;
		return $.ajax({
			type:"POST",
			url: "api2/public/member/"+this_.member_id + '/year',
			data: {
				"year": JSON.stringify(this_.membership_years[get('membership_year')])
		 	},
			dataType: "json"
		})
	}

}
