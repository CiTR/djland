window.myNameSpace = window.myNameSpace || { };
var re = new RegExp(/^.*\//);
var api_base = "" + re.exec(window.location.origin)['input'] + '/api2/public/';
console.log(api_base);

function Playsheet(id){
	var this_ = this;
	if(id != null){
		$.ajax({
			type:"GET",
			url: api_base + "playsheet/"+id,
			dataType: "json",
			async: true
		}).then(function(response){
			this_.info = response.playsheet;
			this_.playitems = response.playitems;
			this_.podcast = response.podcast;
			//Convert date format to use '/' instead of '-' separation as mozilla/safari don't like '-'
			var re = new RegExp('-','g');
          /*  this_.start = new Date(this_.info.start_time.replace(re,'/'));
        	this_.end = new(this_.info.end_time.replace(re,'/'));*/
		},function(error){
			return false;
			//TODO: Log Error Message
		});

	}else{
		this.info = {};
		this.podcast = {};
		this.playitems = {};
		this.ads = {};
		//TODO
		//Get next show time
		//Get ads
		this.info.id = -1;
		this.podcast.id = -1;
	}
	this.create = function(){
		var this_ = this;
		//Create Playsheet
		$.ajax({
			type:"PUT",
			url: api_base+ "show/playsheet",
			dataType: "json",
			async: true
		}).then(function(response){
			this_.info.id = response;
			//Create Podcast
			$.ajax({
				type:"PUT",
				url: api_base+ "playsheet/"+this_.info.id+"/podcast",
				dataType: "json",
				async: true
			}).then(function(response){
				this_.podcast.id = response;
				return true;
			},function(error){
				return false;
				//TODO: Log Error
			});
		},function(error){
			return false;
			//TODO: Log Error
		});
	};
	this.save = function(){
		var this_ = this;
		//Save Playsheet
		$.ajax({
			type:"POST",
			url: api_base+ "playsheet/"+this_.id,
			dataType: "json",
			async: true
		}).then(function(response){
			return true;
		},function(error){
			return false;
		});
		//Save Podcast

		//Save Playitems
		for(var playitem in this.playitems){
			$.ajax({
				type:"POST",
				url: api_base+"playsheet/"+this_.id+'/ads/'+this.playitems[playitem]['id'],
				data: {"playitem": this_.playitems[playitem]} ,
				dataType: "json",
				async: true,
			}).then(function(response){

			},function(error){
				this_.error = true;
				this_.logError(error);
			});
		}
		//Save Ads
		for(var ad in this.ads){
			$.ajax({
				type:"POST",
				url: api_base+ "playsheet/"+this_.id+'/ads/'+this.ads[ad]['id'],
				dataType: "json",
				async: true,
				data: {'ad':this.ads[ad]}
			}).then(function(response){

			},function(error){
				this_.error = true;
				this_.logError(error);
			});
		}
	};
	this.delete = function(){
		return $.ajax({
			type:"DELETE",
			url: api_base+ "playsheet/"+this.id,
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
			url: api_base+ "playsheet/filter/unixtime/"+this.unix_time,
			dataType: "json",
			async: true
		});
	};

	//Mutators
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
	this.setShowId = function(id){
		this.show_id = id;
	}
	this.setStatus = function(status){
		this.status = status;
	}
	//Getters
	this.addPlayitem = function(){
		var this_ = this;
		$.ajax({
			type:"PUT",
			url: api_base+ "playsheet/"+this_.info.id+"/playitem",
			dataType: "json",
			async: true
		}).then(function(response){
			this_.playitems.push(response);
			console.log(this_.playitems);
		});
	}

}

	
	

	
