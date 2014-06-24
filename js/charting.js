$(document).ready ( function() {
	loadCharts();
});

function loadCharts(){
		$('#loadbar').show();
		var today = new Date(); //get the current day
		var from_ = today.getDate() - today.getDay() + 5 -14; //two fridays ago
		var start = today.getDate() - today.getDay();
		var friday = today.getDate() - today.getDay() + 5;
		var last_friday = today.getDate() - today.getDay() + 5 -7;
		console.log("start of week is: "+ start);
		console.log("this friday is: "+ friday);
		console.log("one friday ago is: "+ last_friday);
		var to_; 
		if(today.getDay() == 4){
			to_ = today.getDate(); //today is thursday
		}
		else if(today.getDay > 4){
			to_ = today.getDate() - today.getDay() + 4; //last thursday in this week 
		}
		else{
			to_ = today.getDate() - today.getDay() + 4 - 7; //last thursday
		}
		var from = new Date(today.setDate(from_)).toUTCString();
		today = new Date(); //get the current day
		var to = new Date(today.setDate(to_)).toUTCString();
		console.log("From: "+from);
		console.log("To: "+to);
		$.ajax({
			type:"POST",
			url: "form-handlers/charting-handler.php",
			data: {"from":from,"to":to},
			dataType: "json"
		}).success(function(data){
			$('#charting-container').show();
			$('#charting-container').prepend('<div id=charting-daterange>Displaying charting information from: '+from+' to: '+to+'</div>');
			for( $j = 0; $j < Object.keys(data).length; $j++ ){
				$('#charting-body').append('<div id="charting-row'+$j+'" class="charting-row"> </div>');
				$('#charting-row'+$j).append('<div id=charting-artist'+$j+' class=charting-artist> '+data[$j].artist+'</div>');
				$('#charting-row'+$j).append('<div id=charting-song'+$j+' class=charting-song> '+data[$j].song+'</div>');
				$('#charting-row'+$j).append('<div id=charting-album'+$j+' class=charting-album> '+data[$j].album+'</div>');
				$('#charting-row'+$j).append('<div id=charting-showname'+$j+' class=charting-showname> '+data[$j].show_name+'</div>');
				$('#charting-row'+$j).append('<div id=charting-date'+$j+' class=charting-date> '+data[$j].date+'</div>');
				$('#charting-row'+$j).append('<div id=charting-cancon'+$j+' class=charting-cancon> </div>');
				$('#charting-row'+$j).append('<div id=charting-playlist'+$j+' class=charting-playlist> </div>');
				$('#charting-row'+$j).append('<div id=charting-status'+$j+' class=charting-status> </div>');
				if(data[$j].is_can==true){
					$('#charting-cancon'+$j).append('<img src=images/CAN.png>');
				}
				if(data[$j].is_pl==true){
					$('#charting-playlist'+$j).append('<img src=images/PL.png>');
				}
				if(data[$j].status==1){
					$('#charting-status'+$j).append('Draft')
				}
			}
			$('#loadbar').hide();
		}).fail(function(){
			$('#charting-container').show();
			$('#charting-container').html('connection error');
		});
}
