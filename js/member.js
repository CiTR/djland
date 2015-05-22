function Member(){
	//Blank constructor as we want two different constructors, and javascript does not support overloading. Instead use _init(arguments...), and _fromObj(Member)
}

Member.prototype = {
	_init:function(member_id,firstname,lastname,address,city,postalcode,canadian,alumni,since,is_new,member_type,faculty,schoolyear,student_no,integrate,show,show_name,email,primary_phone,secondary_phone,about,skills,exposure,comments){
		this.member_id = id;
		this.firstnane= firstname;
		this.lastname=lastname;
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
	},
	query:function(){
	assertTrue(id != null,"id is null");
	var member = {"id":id};
	$.ajax({
		type:"GET",
		url: "form-handlers/membership/member.php",
		data: {"id":id},
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
	query:function

}