window.myNameSpace = window.myNameSpace || { };
var schedule = new Schedule();
$(document).ready ( function(){
	var ad_types = ['ads','psa','promo','ids','ubc','community','timely'];

	$.when(schedule.ready).then(function(){
		var promises = [];
		for(var i = 0; i < schedule.showtimes.length; i++){
			promises.push(schedule.getHTML(schedule.showtimes[i],i));
		}
		var schedule_element = $('.schedule');
		$.when.apply($,promises).then(function(){
			for(var i = 0; i < schedule.showtimes.length; i++){
				schedule_element.append(arguments[i][0]);
				for(var j = 0; j < schedule.showtimes[i].ads.length; j++){
					schedule.updateDropdown(schedule[schedule.showtimes[i].ads[j].type],schedule.showtimes[i].ads[j].type,i,j);
				}
			}

		});
	});

	$('#tab-nav').off('click','.tab').on('click','.tab', function(e){
		$('.tab').removeClass('active-tab');
		$('.tab').addClass('inactive-tab');
		
		$(this).attr('class','nodrop active-tab tab');
		var date = new Date();
		console.log('offset = '+ $(this).val());
		
	});
});

