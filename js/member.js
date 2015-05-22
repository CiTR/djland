window.myNameSpace = window.myNameSpace || { };

function Member(){
	this.member_id="";
	this.firstnane="";
	this.lastname="";
	this.fullname="";
	this.address="";
	this.city="";
	this.postalcode="";
	this.canadian="";
	this.alumni="";
	this.since="";
	this.is_new="";
	this.member_type="";
	this.faculty="";
	this.schoolyear="";
	this.student_no="";
	this.integrate="";
	this.show="";
	this.show_name="";
	this.email="";
	this.primary_phone="";
	this.secondary_phone="";
	this.about="";
	this.skills="";
	this.exposure="";
	this.comments="";
	this.membership_years={};
	//Blank constructor as we want multiple constructors, and javascript does not support overloading. Instead use _init(arguments list...), _fromObj(Member), and query(id) to build the object.
}

Member.prototype = {
	_init:function(member_id,firstname,lastname,address,city,postalcode,canadian,alumni,since,is_new,member_type,faculty,schoolyear,student_no,integrate,show,show_name,email,primary_phone,secondary_phone,about,skills,exposure,comments){
		this.member_id = id;
		this.firstnane= firstname;
		this.lastname=lastname;
		this.fullname = firstname + " " + lastname;
		this.address=address;
		this.city=city;
		this.postalcode=postalcode;
		this.canadian=canadian;
		this.alumni=alumni;
		this.since=since;
		this.is_new=is_new;
		this.member_type=member_type;
		this.faculty=faculty;
		this.schoolyear=schoolyear;
		this.student_no = student_no;
		this.integrate=integrate;
		this.show=show;
		this.show_name=show_name;
		this.email=email;
		this.primary_phone=primary_phone;
		this.secondary_phone=secondary_phone;
		this.about=about;
		this.skills=skills;
		this.exposure=exposure;
		this.comments=comments;
	},
	_fromObj:function(member){
		this.member_id =member.id;
		this.firstnane=member.firstname;
		this.lastname=member.lastname;
		this.fullname = member.firstname + " " + member.lastname;
		this.address=member.address;
		this.city=member.city;
		this.postalcode=member.postalcode;
		this.canadian=member.canadian;
		this.alumni=member.alumni;
		this.since=member.since;
		this.is_new=member.is_new;
		this.member_type=member.member_type;
		this.faculty=member.faculty;
		this.schoolyear=member.schoolyear;
		this.student_no =member.student_no;
		this.integrate=member.integrate;
		this.show=member.show;
		this.show_name=member.show_name;
		this.email=member.email;
		this.primary_phone=member.primary_phone;
		this.secondary_phone=member.secondary_phone;
		this.about=member.about;
		this.skills=member.skills;
		this.exposure=member.exposure;
		this.comments=member.comments;
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

		

		$.when(ajax).then(function(data){
			var member_info = data[0];
			if(member_info != null){
				//Populate the basic member member_information 
				this.firstname = member_info.firstname;
				this.lastname = member_info.lastname;
				this.fullname = member_info.firstname + " " + member_info.lastname;
				this.address = member_info.address;
				this.city = member_info.city;
				this.province = member_info.province;
				this.postalcode = member_info.postalcode;
				this.canadian = member_info.canadian_citizen;
				this.member_type = member_info.member_type;
				if(this.member_type == "Student"){
					this.faculty = member_info.faculty;
					this.schoolyear = member_info.schoolyear;
					this.student_no = member_info.student_no;
					this.integrate = member_info.integrate;	
				}
				this.is_new = member_info.is_new;
				this.alumni = member_info.alumni;
				this.since = member_info.since;
				this.has_show = member_info.has_show;
				//handling future possibility for multiple shows.
				
				if(member_info.show_name.constructor === Array){
					this.show_name = "";
					var i;
					for(i = 0 ; i < member_info.show_name.length; i++){
						this.show_name += member_info.show_name[i];
						if(i < member_info.show_name.size() - 1){
							this.show_name += ", "
						}
					}
				}else{
					this.show_name = member_info.show_name;
				}
				this.email = member_info.email;
				this.primary_phone = member_info.primary_phone;
				this.secondary_phone = member_info.secondary_phone;
				this.about = member_info.about;
				this.skills = member_info.skills;
				this.exposure = member_info.exposure;
				this.comments = member_info.comments;
			}
		},function(error){
				console.log("Could not retrieve member data");
		});
	
	},
	displayInfo:function(){
	setText(this.firstname,'firstname');
	setText(this.lastname, 'lastname');
	setVal(this.address,'address');
	setVal(this.city,'city');
	setVal(this.province,'province');
	setVal(this.postalcode,'postalcode');
	setRadio(this.canadian_citizen,'can')
	setRadio(this.alumni,'alumni');
	setText(this.since,'since');
	setVal(this.is_new,'is_new');
	setVal(this.this_type,'this_type');
	if(this.this_type == "Student"){
		setVal(this.faculty,'faculty');
		setVal(this.schoolyear,'schoolyear');
		setVal(this.student_no,'student_no');
		setCheckbox(this.integrate,'integrate');
	}else{
		$('#row6').hide();
		$('#row7').hide();
	}
	setRadio(this.has_show,'show');
	setVal(this.show_name,'show_name');
	setVal(this.email,'email');
	setVal(this.primary_phone,'primary_phone');
	setVal(this.secondary_phone,'secondary_phone');
	setVal(this.about,'about');
	setVal(this.skills,'skills');
	setVal(this.exposure,'exposure');
	}
}