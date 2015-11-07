window.myNameSpace = window.myNameSpace || { };

function Schedule(){
	this.schedule = Array();
	this.initSchedule();
}


Schedule.prototype = {
	initSchedule:function(){
		var date = this.formatDate(new Date());
		$.when(this.getSchedule(date)).then(function(response){
			console.log(response);
		},function(error){
			console.log('err' + error.responseText);
		});
	},
	getSchedule:function(date){
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule/"+date,
			dataType: "json",
			async: true
		});
	},
	formatDate:function(date){
		return [date.getFullYear(),("0" + (date.getMonth()+1)).slice(-2),("0" + date.getDate()).slice(-2)].join('-') + 
				" " + 
				[("0" + date.getHours() ).slice(-2),("0" + date.getMinutes()).slice(-2),("0" + date.getSeconds()).slice(-2)].join(':');
	},
}

