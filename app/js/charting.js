$(document).ready ( function() {
	init();

   $( "#from" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy/mm/dd",
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });

	$( "#to" ).datepicker({
      defaultDate: "+0d",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy/mm/dd",
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $('#load_charts').click(function(){
    	loadCharts($('#from').val(), $('#to').val());
    });

    $('#sort_charts').click(function(){
        sortChart();
    });


});

function init(){

		var week_start;
		var today = new Date();
		var to;
		var from;
		var to_;
		var from_;

		//One day in milliseconds
		var one_day = 24 * 60 * 60 * 1000;

		week_start = new Date();
		week_start.setHours('0');
		week_start.setMinutes('0');
		week_start.setSeconds('0');

		if(today.getDay() >= 4){
			console.log("Today is thursday or later");
			//Last Sunday
			week_start = week_start.getTime() - today.getDay() * one_day;
			//Get thursday of this week
			to_ = week_start + 4 * one_day;
			//Get friday of last week
			from_ = week_start - 2 * one_day;
		}else{
			//Two Sundays Ago
			week_start = week_start.getTime() - (today.getDay() + 7) * one_day;
			//Get thursday of last week
			to_ = week_start + 4 * one_day;
			//get friday of two weeks ago
			from_ = week_start - 2 * one_day;
		}
		to = new Date(to_);
		to = to.getFullYear()+"/"+('0' + (to.getMonth()+1)).slice(-2) + "/" + ('0' + to.getDate()).slice(-2);
		$('#to').val(to);

		from = new Date(from_);
		from = from.getFullYear()+"/"+('0' + (from.getMonth()+1)).slice(-2) + "/" + ('0' + from.getDate()).slice(-2);
		$('#from').val(from);

		loadCharts(from,to);
}
function loadCharts(from,to){
	$('.loading').show();
	$.ajax({
			type:"POST",
			url: "form-handlers/charting_handler.php",
			data: {"from":from,"to":to},
			dataType: "json"
		}).then(
			function(data){
			$('#charting-body').html('');
			//$('#charting-container').prepend('<div id=charting-daterange>Displaying charting information from: '+from+' to: '+to+'</div>');
			for( $j = 0; $j < Object.keys(data).length; $j++ ){
				$('#charting-body').append('<div id="charting-row'+$j+'" class="charting-row"> </div>');
				$('#charting-row'+$j).append('<div id=charting-artist'+$j+' class=charting-artist> '+data[$j].artist+'</div>');
				$('#charting-row'+$j).append('<div id=charting-song'+$j+' class=charting-song> '+data[$j].song+'</div>');
				$('#charting-row'+$j).append('<div id=charting-album'+$j+' class=charting-album> '+data[$j].album+'</div>');
				$('#charting-row'+$j).append('<div id=charting-showname'+$j+' class=charting-showname> '+data[$j].show_name+'</div>');
				$('#charting-row'+$j).append('<div id=charting-date'+$j+' class=charting-date> '+data[$j].date+'</div>');
        $('#charting-row'+$j).append('<div id=charting-cancon'+$j+' class=charting-icon> </div>');
        $('#charting-row'+$j).append('<div id=charting-playlist'+$j+' class=charting-icon> </div>');
        $('#charting-row'+$j).append('<div id=charting-local'+$j+' class=charting-icon> </div>');
				$('#charting-row'+$j).append('<div id=charting-status'+$j+' class=charting-status> </div>');
				if(data[$j].is_canadian=='1'){
					$('#charting-cancon'+$j).append('<img src="./images/tags/canconsolo.png" style="max-width:100%">');
				}
				if(data[$j].is_playlist=='1'){
					$('#charting-playlist'+$j).append('<img src="./images/tags/newsolo.png" style="max-width:100%">');
				}
        if (data[$j].is_local=='1'){
          $('#charting-local'+$j).append('<img src="./images/tags/localsolo.png" style="max-width:100%">');
        }
				if(data[$j].status=='1'){
					$('#charting-status'+$j).append('Draft')
				}
			}
			$('.loading').hide();
		},function(error){
			$('#charting-body').html('connection error');
			console.log(error);
			$('.loading').hide();
		});
}

function sortChart(){

    $('#charting-body-sorted').html('');

    var chart = [];
    var index = -1;

    for(var i=0; i < $('.charting-row').length; i++) {
        if($('.charting-row')[i].children[7].innerHTML.trim().toLowerCase() == 'draft') {
            continue;
        }
        track = [
            $('.charting-row')[i].children[0].innerHTML.trim().toLowerCase(),
            $('.charting-row')[i].children[2].innerHTML.trim().toLowerCase()
        ];

        index = findTrackInChart(track, chart);
        if( index < 0 ) {
            chart.push([
                track[0],
                track[1],
                1
            ]);
        }
        else {
            chart[index][2] ++ ; // track counter in chart
        }
    }

    chart.sort(
        function(a,b) {
            if( a[2] == b[2] ){
                if( a[0].localeCompare(b[0]) == 0 ){
                    return a[1].localeCompare(b[1]);
                }

                return a[0].localeCompare(b[0]);
            }

            return b[2] - a[2];
        }
    );

    for( var i = 0; i < chart.length; i++ ){
        $('#charting-body-sorted').append('<div id="charting-row-sorted'    + i + '" class="charting-row-sorted"> </div>');
        $('#charting-row-sorted'+i).append('<div id=charting-rowid-sorted'  + i + ' class=charting-rowid-sorted> '  + (i+1)       + '</div>');
        $('#charting-row-sorted'+i).append('<div id=charting-artist-sorted' + i + ' class=charting-artist-sorted> ' + chart[i][0] + '</div>');
        $('#charting-row-sorted'+i).append('<div id=charting-album-sorted'  + i + ' class=charting-album-sorted> '  + chart[i][1] + '</div>');
        $('#charting-row-sorted'+i).append('<div id=charting-count-sorted'  + i + ' class=charting-count-sorted> '  + chart[i][2] + '</div>');
    }

    $('body').scrollTop($('#charting-body-sorted').offset().top - 100 );
}

function findTrackInChart(track, chart){
    for(var i=0; i < chart.length; i++) {
        if( track[0]==chart[i][0] && track[1]==chart[i][1]) {
            return i;
        }
    }

    return -1;
}
