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
		member_info.firstname=member.firstname;
		member_info.lastname=member.lastname;
		this.fullname = member.firstname + " " + member.lastname;
		member_info.address=member.address;
		member_info.province=member.province;
		member_info.city=member.city;
		member_info.postalcode=member.postalcode;
		member_info.canadian_citizen=member.canadian_citizen;
		member_info.alumni=member.alumni;
		member_info.since=member.since;
		member_info.is_new=member.is_new;
		member_info.member_type=member.member_type;
		member_info.faculty=member.faculty;
		member_info.schoolyear=member.schoolyear;
		member_info.student_no =member.student_no;
		member_info.integrate=member.integrate;
		member_info.has_show=member.has_show;
		member_info.show_name=member.show_name;
		member_info.email=member.email;
		member_info.primary_phone=member.primary_phone;
		member_info.secondary_phone=member.secondary_phone;
		member_info.about=member.about;
		member_info.skills=member.skills;
		member_info.exposure=member.exposure;
		member_info.comments=member.comments;
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
		this['member_info'].firstname = getText('firstname');
		this['member_info'].lastname = getText('lastname');
		this['member_info'].address = getVal('address');
		this['member_info'].city = getVal('city');
		this['member_info'].province = getSelect('province');
		this['member_info'].postalcode = getVal('postalcode');
		this['member_info'].canadian_citizen = getRadio('can');
		this['member_info'].alumni = getRadio('alumni');
		this['member_info'].since = getText('since');
		this['member_info'].is_new = getVal('is_new');
		this['member_info'].member_type = getVal('member_type');
		if(this['member_info'].member_type == "Student"){
			this['member_info'].faculty = getVal('faculty');
			if(this['member_info'].faculty == 'Other'){
				this['member_info'].faculty = getVal('faculty2');
			}
			this['member_info'].schoolyear = getVal('schoolyear');
			this['member_info'].student_no = getVal('student_no');
			this['member_info'].integrate = getCheckbox('integrate');
		}
		this['member_info'].has_show = getRadio('show');
		this['member_info'].show_name = getVal('show_name');
		this['member_info'].email = getVal('email');
		this['member_info'].primary_phone = getVal('primary_phone');
		this['member_info'].secondary_phone = getVal('secondary_phone');
		this['member_info'].about = getVal('about');
		this['member_info'].skills = getVal('skills');
		this['member_info'].exposure = getVal('exposure');
		this['member_info'].comments = getVal('comments');
	},
	getInterests:function(){
		var membership_year = getVal('membership_year');
		var my={'membership_year':membership_year};
		for(var interest in interests){
			if(interest != 'Other'){my[interests[interest]] = getCheckbox(interests[interest]);}
			else{my[interests[interest]] = getVal(interests[interest]);}
		}
		this.membership_years[membership_year] = my;
	},
	getPermissions:function(){
		var permissions = {};
		for(var level in this.permissions){
			if(level != 'operator') permissions[level] = getCheckbox(level);
		}
		this.permissions = permissions;
	},
	displayInfo:function(request){
		_this = this;
		if(request == null){
			setText(this.username,'username');
			setText(this['member_info'].firstname,'firstname');
			setText(this['member_info'].lastname, 'lastname');
			setVal(this['member_info'].address,'address');
			setVal(this['member_info'].city,'city');
			setVal(this['member_info'].province,'province');
			setVal(this['member_info'].postalcode,'postalcode');
			setRadio(this['member_info'].canadian_citizen,'can')
			setRadio(this['member_info'].alumni,'alumni');
			setText(this['member_info'].since,'since');
			setVal(this['member_info'].is_new,'is_new');
			setVal(this['member_info'].member_type,'member_type');
			if(this['member_info'].member_type == "Student"){
				setVal(this['member_info'].faculty,'faculty');
				setVal(this['member_info'].schoolyear,'schoolyear');
				setVal(this['member_info'].student_no,'student_no');
				setCheckbox(this['member_info'].integrate,'integrate');
			}else{
				$('#row6').hide();
				$('#row7').hide();
			}
			setRadio(this['member_info'].has_show,'has_show');
			setVal(this['member_info'].show_name,'show_name');
			setVal(this['member_info'].email,'email');
			setVal(this['member_info'].primary_phone,'primary_phone');
			setVal(this['member_info'].secondary_phone,'secondary_phone');
			setVal(this['member_info'].about,'about');
			setVal(this['member_info'].skills,'skills');
			setVal(this.exposure,'exposure');
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
		setVal(year,'membership_year');
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
		setCheckbox(m.programming_committee,'programming_committee');
		setCheckbox(m.ads_psa,'ads_psa');
		setCheckbox(m.promotions_outreach,'promos');
		setCheckbox(m.photography,'photography');
		setCheckbox(m.digital_library,'digital_library');
		setCheckbox(m.tabling,'tabling');
		setVal(m.other,'other');
	},displayPermissions:function(){
		for(var level in this.permissions){
			setCheckbox(this.permissions[level],level);
		}

	},renew:function(){
		$_this = this;
        if(!this.membership_years[membership_year]){
        	this.membership_years[membership_year] = getInterestse();
        }
    	return $.ajax({
        type:"POST",
        url: "form-handlers/membership/renew.php",
        data: {
           "member_id":$_this.member_id, "membership_year":JSON.stringify(this.membership_years[getVal('membership_year')])
        },
        dataType: "json"
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
	},updateInterests:function(membership_year){
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
	},
	updatePassword:function(){
		$_this = this;
		return $.ajax({
			type:"POST",
			url: "form-handlers/membership/password.php",
			data: {
			"member_id": _this.member_id,"password":getVal('password')
		 	},
			dataType: "json"
		});
	}
}
