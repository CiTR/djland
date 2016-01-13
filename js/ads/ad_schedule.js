window.myNameSpace = window.myNameSpace || { };
var schedule = new Schedule();
$(document).ready ( function(){
	var ad_types = ['ads','psa','promo','ids','ubc','community','timely'];
	var schedule_element = $('.schedule');
	var loading_bar = $('.loadingbar');
	var left_most_index = 0;
	var right_most_index = 9;

	$.when(schedule.ready).then(function(){
		 addHandlers();
	});
	$('#tab-nav').off('click','.tab').on('click','.tab', function(e){
		//Save current schedule before changing view
		schedule.saveSchedule();
		loading_bar.show();
		
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
			//loading_bar.hide();
			addHandlers();
		},function(error){
			//loading_bar.hide();
			schedule_element.empty();
			schedule_element.append('Error getting this date')
		});
	});
	$('#tab-nav').off('click','.nav-left').on('click','.nav-left', function(e){
		if(left_most_index != 0){
			left_most_index --;
			right_most_index --;
			$('.tab').hide();
			for(var i=left_most_index; i <= right_most_index; i++){
				$('.tab[value="'+i+'"').show().removeClass('invisible');
			}
		}
	});
	$('#tab-nav').off('click','.nav-right').on('click','.nav-right', function(e){
		if(right_most_index != 13){
			left_most_index ++;
			right_most_index ++;
			$('.tab').hide();
			console.log('LMI= '+left_most_index + ", RMI= "+right_most_index);
			for(var i=left_most_index; i <= right_most_index; i++){
				$('.tab[value="'+i+'"').show();
				$('.tab[value="'+i+'"').removeClass('invisible');
			}
		}
	});
	$('.save_button').click(function(e){
		schedule.saveSchedule();
	});


	


});

function addHandlers(){
	setTimeout(function(){
		$('.type_select').change(function(e){
			var name_arr = $(this).attr('name').replace(/[^0-9_]/g,'').split('_');
			//updateDropdown:function(list,type,value,index,num);
			schedule.updateDropdown(schedule[$(this).val()],$(this).val(),null,name_arr[1],name_arr[2]);
		});
		$('.insert').click(function(e){
			var time = $('#template_ad_time_'+$(this).attr('data-index')).val();
			var type = $('#template_ad_type_'+$(this).attr('data-index')).val();
			var highest_num;
			var index;
			//Get the item we want to place it after.
			$('#show_'+$(this).attr('data-index')).find('.ad_time').each(function(i,obj){
				if(time == $(this).val() || time > $(this).val()){
					//parse the ID of the parent TR
					index = $(this).closest('tr').attr('id').split('_')[1];
					highest_num = $(this).closest('tr').attr('id').split('_')[2];
				} 
			});
			schedule.addElement(schedule[type],type,time,index,highest_num);
		});
		
 	}, 800);
}

