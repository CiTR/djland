window.myNameSpace = window.myNameSpace || { };
var re = new RegExp(/^.*\//);
var api_base = "" + re.exec(window.location.origin)['input'] + '/api2/public/';
console.log(api_base);

function Playsheet(id){
	var this_ = this;
	if(id != null){
		$.ajax({
			type:"GET",
			url: api_base + "/playsheet/"+id,
			dataType: "json",
			async: true
		}).then(function(response){
			this_.info = response;
			//Convert date format to use '/' instead of '-' separation as mozilla/safari don't like '-'
			var re = new RegExp('-','g');
            this_.start_time = new Date(this_.info.start_time.replace(re,'/'));
        	this_.end_time = new(this_.info.end_time.replace(re,'/'));

		},function(error){

		});
	}else{
		this.id = -1;
		this.show_id = "";
		this.host = "";
		this.host_id = "";
		this.start_time = "";
		this.end_time = "";
		this.end = "";
		this.title = "";
		this.edit_name = "";
		this.summary = "";
		this.spokenword_duration = "";
		this.status = "";
		this.unix_time = "";
		this.star = "";
		this.crtc = "";
		this.lang = "";
		this.type = "";
		this.show_name = "";
		this.socan = "";
	}
	this.create = function(){
		var this_ = this;
		$.ajax({
			type:"PUT",
			url: api_base+ "/playsheet",
			dataType: "json",
			async: true
		}).then(function(response){
			this_.id = response;
			return true;
		},function(error){
			return false;
		});
	};
	this.update = function(){
		var this_ = this;
		$.ajax({
			type:"POST",
			url: api_base+ "/playsheet/"+this_.id,
			dataType: "json",
			async: true
		}).then(function(response){
			return true;
		},function(error){
			return false;
		});
	};
	this.delete = function(){
		return $.ajax({
			type:"DELETE",
			url:"api2/public/playsheet/"+this.id,
			dataType: "json",
			async: true
		}).then(function(response){
			return true;
		},function(error){
			return false;
		});
	};
	this.checkUnique = function(){
		return $.ajax({
			type:"GET",
			url: "api2/public/playsheet/filter/unixtime/"+this.unix_time,
			dataType: "json",
			async: true
		});
	};
	this.setStartUnix = function(unix){
		this.unix_time = unix;
	};
	this.setStartUnixMilli = function(millsecond_unix){
		this.unix_time = millsecond_unix / 1000;
	};
	this.setStartDate = function(date){
		var date = new Date(date);
		date.setHours(this.start_time.getHours());
		date.setMinutes(this.start_time.getMinutes());
		date.setSeconds(this.start_time.getSeconds());
		this.start_time = date;
	};
	this.setStartHour = function(hour){
		var date = new Date(this.start_time);
		date.setHours(hour);
		this.start_time = date;
	};
	this.setStartMinute = function(minute){
		var date = new Date(this.start_time);
		date.setMinutes(minute);
		this.start_time = date;
	};
	this.setStartSecond = function(second){
		var date = new Date(this.start_time);
		date.setSeconds(second);
		this.start_time = date;	
	};
	this.setEndDate = function(date){
		var date = new Date(date);
		date.setHours(this.end_time.getHours());
		date.setMinutes(this.end_time.getMinutes());
		date.setSeconds(this.end_time.getSeconds());
		this.end_time = date;
	};
	this.setEndHour = function(hour){
		var date = new Date(this.end_time);
		date.setHours(hour);
		this.end_time = date;
	};
	this.setEndMinute = function(minute){
		var date = new Date(this.end_time);
		date.setMinutes(minute);
		this.end_time = date;
	};
	this.setEndSecond = function(second){
		var date = new Date(this.end_time);
		date.setSeconds(second);
		this.end_time = date;	
	};
}

	
	

	
