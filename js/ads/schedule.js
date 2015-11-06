window.myNameSpace = window.myNameSpace || { };

function Schedule(){
	this.schedule = Array();
}

Schedule.prototype = {
	_initSchedule:function(){
		
		$.when(_getSchedule())

	},
	_getSchedule:function(date){
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule/"+date,
			dataType: "json",
			async: true
		});
	},
}

$(document).ready ( function() {

}