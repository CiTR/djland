window.myNameSpace = window.myNameSpace || { };
var schedule = new Schedule();
$(document).ready ( function(){
	
	
	console.log('hi');


	$('#tab-nav').off('click','.tab').on('click','.tab', function(e){
		$('.tab').removeClass('active-tab');
		$('.tab').addClass('inactive-tab');
		
		$(this).attr('class','nodrop active-tab tab');
		var date = new Date();
		console.log('offset = '+ $(this).val());
		date.setDate(date.getDate() + $(this).val());
		$.when(schedule.getSchedule(schedule.formatDate(date))).then(function(response){
			console.log(response.sort(function(x,y){
				return y.start_time - x.start_time;
			}));
		});
	});
});

