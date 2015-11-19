window.myNameSpace = window.myNameSpace || { };
var schedule = new Schedule();
$(document).ready ( function(){
	var ad_types = ['ads','psa','promo','ids','ubc','community','timely'];
	var schedule_element = $('.schedule');

	$.when(schedule.ready).then(function(){
		//Get HTML for the showtimes
		var promises = [];
		
	});

	$('#tab-nav').off('click','.tab').on('click','.tab', function(e){
		$('.tab').removeClass('active-tab');
		$('.tab').addClass('inactive-tab');
		var element = $(this);
		element.attr('class','nodrop active-tab tab');
		var date = new Date();
		console.log('offset = '+ element.attr('name'));
		schedule_element.empty();

		$.when(schedule.getSchedule( element.attr('name'))).then( function(response){
			schedule.showtimes = Array();
			for(var item in response){
				schedule.showtimes.push(response[item]);
			}
			schedule.displaySchedule(schedule_element);
		},function(error){
			console.log(error);
		});

		
	});
});

