window.myNameSpace = window.myNameSpace || { };
var schedule = new Schedule();
$(document).ready ( function(){
	var ad_types = ['ads','psa','promo','ids','ubc','community','timely'];
	var schedule_element = $('.schedule');
	var loading_bar = $('.loadingbar_container');
	$.when(schedule.ready).then(function(){
		 addHandlers();
	});
	$('#tab-nav').off('click','.tab').on('click','.tab', function(e){
		loading_bar.show();
		$('.tab').removeClass('active-tab');
		$('.tab').addClass('inactive-tab');
		var element = $(this);
		element.attr('class','nodrop active-tab tab');
		var date = new Date();
		console.log('offset = '+ element.attr('name'));
		

		//TODO: Save tab we are leaving.

		schedule_element.empty();
		$.when(schedule.getSchedule( element.attr('name'))).then( function(response){
			schedule.showtimes = Array();
			for(var item in response){
				schedule.showtimes.push(response[item]);
			}
			schedule.displaySchedule(schedule_element);
			loading_bar.hide();
			addHandlers();
		},function(error){
			loading_bar.hide();
			schedule_element.empty();
			schedule_element.append('Error getting this date')
		});
	});
	
	$('#tab-nav').off('click','.save_button').on('click','.save_button', function(e){

	});

});

function addHandlers(){
	setTimeout(function(){
		$('.type_select').change(function(e){
			console.log('changed' + $(this).val());
		});
 	}, 800);
}

