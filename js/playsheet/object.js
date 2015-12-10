window.myNameSpace = window.myNameSpace || { };
var re = new RegExp(/^.*\//);
var api_base = "" + re.exec(window.location.origin)['input'] + '/api2/public/';
console.log(api_base);

function Playsheet(){
	var this_ = this;
	var member_id = $('#member_id').text();
	var requests = Array();
		


	this.initialize = function(id){
		this.getMemberShows(member_id)

		$.when(requests['show_load']).then(function(){
			if(id != null && id > 0){
				this.load(id);
			}else{
				this.info = {};
				this.podcast = {};
				this.playitems = Array();
				this.ads = Array();
				//TODO
				
				//Get next show time
				//Get ads
				this.info.id = -1;
				this.podcast.id = -1;
				console.log(this);
			}
		});

	}
	

	this.load = function(id){
		requests['load'] = 
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
	          	this_.start = new Date(this_.info.start_time);
	        	this_.end = new Date(this_.info.end_time);
	        	console.log(this_);
			},function(error){
				return false;
				//TODO: Log Error Message
			})
		
	}
	this.getMemberShows = function(member_id){
		requests['show_load'] = 
			$.ajax({ 
				type:"GET",
				url: api_base + "member/"+member_id + '/active_shows',
				dataType: "json",
				async: true
			}).then(
				function(shows){
					this_.shows = shows;
					this_.show=this_.shows[0];
				},
				function(error){
					//TODO: Log Error
				}
			)
		
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
		var playsheet_save = $.ajax({
			type:"POST",
			url: api_base+ "playsheet/"+this_.id,
			data: {'playsheet':this_.info},
			dataType: "json",
			async: true
		}).then(function(response){
			return true;
		},function(error){
			return false;
		});
		//Save Podcast
		$.ajax({
			type:"POST",
			url: api_base+ "playsheet/"+this_.id+'/podcast',
			dataType: "json",
			data: {'podcast':this_.podcast},
			async: true
		}).then(function(response){
			return true;
		},function(error){
			return false;
		});
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
		this.info.unix_time = unix;
		//TODO: Get promotions
	};
	this.setStartUnixMilli = function(millsecond_unix){
		this.info.unix_time = millsecond_unix / 1000;
	};
	this.setStartDate = function(date){
		var date = new Date(date);
		date.setHours(this.info.start_time.getHours());
		date.setMinutes(this.info.start_time.getMinutes());
		date.setSeconds(this.info.start_time.getSeconds());
		this.info.start_time = date;
	};
	this.setStartHour = function(hour){
		var date = new Date(this.start_time);
		date.setHours(hour);
		this.info.start_time = date;
	};
	this.setStartMinute = function(minute){
		var date = new Date(this.start_time);
		date.setMinutes(minute);
		this.info.start_time = date;
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
		this.info.end_time = date;
	};
	this.setEndHour = function(hour){
		var date = new Date(this.end_time);
		date.setHours(hour);
		this.info.end_time = date;
	};
	this.setEndMinute = function(minute){
		var date = new Date(this.end_time);
		date.setMinutes(minute);
		this.info.end_time = date;
	};
	this.setEndSecond = function(second){
		var date = new Date(this.end_time);
		date.setSeconds(second);
		this.info.end_time = date;	
	};
	this.setShowId = function(id){
		this.info.show_id = id;
	}
	this.setStatus = function(status){
		this.info.status = status;
	}
	//Getters
	this.addPlayitem = function(index){
		var this_ = this;
		$.ajax({
			type:"PUT",
			url: api_base+ "playsheet/"+this_.info.id+"/playitem",
			dataType: "json",
			async: true
		}).then(function(response){

			this_.playitems.splice(index+1,0,response);
		});
	}
	this.removePlayitem = function(index){
		var this_ = this;
		$.ajax({
			type:"DELETE",
			url: api_base+ "playsheet/"+this_.info.id+"/playitem/"+this_.playitems[index].id,
			dataType: "json",
			async: true
		}).then(function(response){
			this_.playitems.splice(index,1);
			if(this_.playitems.length < 1){
                $('#addRows').text("Add Row");
            }
		},function(error_response){
			//LOG ERROR
		});
	}

}

	
	

	
