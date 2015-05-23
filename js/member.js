window.myNameSpace = window.myNameSpace || { };

function Member(id){
	var _this = this;
	this.member_id = id;
	this.info_callback = this._queryInfo();
	this.interest_callback = this._queryInterests();
	$.when(this.info_callback, this.interest_callback).then(function(info,interests){
		_this._initInfo(info[0]);
		_this._initInterests(interests[0]);
	},function(err,err2){
		console.log("ERROR");
	});

	//Blank constructor as we want multiple constructors, and javascript does not support overloading. Instead use _init(arguments list...), _fromObj(Member), and query(id) to build the object.
}

Member.prototype = {
	_initInfo:function(member){
		this.member_id =member.member_id;
		this.username=member.username;
		this.firstname=member.firstname;
		this.lastname=member.lastname;
		this.fullname = member.firstname + " " + member.lastname;
		this.address=member.address;
		this.province=member.province;
		this.city=member.city;
		this.postalcode=member.postalcode;
		this.canadian_citizen=member.canadian_citizen;
		this.alumni=member.alumni;
		this.since=member.since;
		this.is_new=member.is_new;
		this.member_type=member.member_type;
		this.faculty=member.faculty;
		this.schoolyear=member.schoolyear;
		this.student_no =member.student_no;
		this.integrate=member.integrate;
		this.has_show=member.has_show;
		this.show_name=member.show_name;
		this.email=member.email;
		this.primary_phone=member.primary_phone;
		this.secondary_phone=member.secondary_phone;
		this.about=member.about;
		this.skills=member.skills;
		this.exposure=member.exposure;
		this.comments=member.comments;
	},
	_initInterests:function(membership_years){
		this.membership_years = membership_years;
	},
	_queryInfo:function(id){
		var _this = this;
		return $.ajax({
			type:"GET",
			url: "form-handlers/membership/member.php",
			data: {"id":_this.member_id},
			dataType: "json",
			async: true
		});
	},
	_queryInterests:function(id){
		var _this = this;
		return $.ajax({
			type:"GET",
			url: "form-handlers/membership/year.php",
			data: {"id":_this.member_id},
			dataType: "json",
			async: true
		});
	},	
	displayInfo:function(request){
		_this = this;
		if(request == null){
			setText(this.username,'username');
			setText(this.firstname,'firstname');
			setText(this.lastname, 'lastname');
			setVal(this.address,'address');
			setVal(this.city,'city');
			setVal(this.province,'province');
			setVal(this.postalcode,'postalcode');
			setRadio(this.canadian_citizen,'can')
			setRadio(this.alumni,'alumni');
			setText(this.since,'since');
			if(this.is_new == "1"){
				setVal("New",'is_new');
			}else{
				setVal("Returning","is_new");
			}
			setVal(this.member_type,'member_type');
			if(this.member_type == "Student"){
				setVal(this.faculty,'faculty');
				setVal(this.schoolyear,'schoolyear');
				setVal(this.student_no,'student_no');
				setCheckbox(this.integrate,'integrate');
			}else{
				$('#row6').hide();
				$('#row7').hide();
			}
			setRadio(this.has_show,'has_show');
			setVal(this.show_name,'show_name');
			setVal(this.email,'email');
			setVal(this.primary_phone,'primary_phone');
			setVal(this.secondary_phone,'secondary_phone');
			setVal(this.about,'about');
			setVal(this.skills,'skills');
			setVal(this.exposure,'exposure');
		}else{
			$.when(request).then(function(data){
				_this.displayInfo();
			},function(error){
				console.log('data was not available');
			});
		}
	}
}