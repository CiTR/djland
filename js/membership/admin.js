var cutoff_year_element;
var cutoff_year;
var current_year_element;
var current_year;
var cutoff_month = 5; // Greater or equal to may (start of may is the new membership year)

$(document).ready ( function() {
    cutoff_year_element = $('#current_cutoff');
    current_year_element = $('#current_year');
	var today = new Date();
	if( today.getMonth() >= cutoff_month ) {
		current_year = today.getFullYear() + "/" + (today.getFullYear() + 1);
	}else{
		current_year = (today.getFullYear() - 1) + "/" + (today.getFullYear() + 0);
	}
	current_year_element.text("Current year: "+current_year);
	//load current cutoff year
	getCutoff();
	//Listeners for rollover/rollback
	$(".button_holder").off('click','#year_rollover').on('click','#year_rollover',
		function(){
			console.log('rolling forward');
       		rollover();
    	}
	);
	$(".button_holder").off('click','#year_rollback').on('click','#year_rollback',
		function(){
			console.log('rolling back');
			rollback();
    	}
	);
});
function getCutoff(){
    var request = $.ajax({
        type:"GET",
        url: "api2/public/membershipyear/cutoff",
        dataType: "json",
        async: true
    });
	$.when(request).then(
		function(response){
			cutoff_year = response.cutoff;
        	cutoff_year_element.text("Cutoff: "+cutoff_year);
		},function(error_response){
			cutoff_year_element.text("Failed to load");
		}
	);
}
function rollover(){
	//Is the cutoff year equal to the current membership year already?
	if(cutoff_year.localeCompare(current_year) == 0){
		alert("You have already rolled over membership for this year! You do not need to update the cutoff.");
	}else if( cutoff_year.split('/')[0] < current_year.split('/')[0]){
		//Determine what the rollover will take us to
		var new_cutoff = (parseInt(cutoff_year.split('/')[0])+1) + "/" + (parseInt(cutoff_year.split('/')[1])+1);
		var yes = confirm("This will lock out all accounts not renewed for: "+ new_cutoff);
		//Give alert message informing them this will lock out accounts not renewed for X
		if(yes){
			request = $.ajax({
		        type:"POST",
		        url: "api2/public/membershipyear/rollover",
		        dataType: "json",
		        async: true
		    });
			$.when(request).then(
				function(response){
					cutoff_year = response.cutoff;
					cutoff_year_element.text("Cutoff:"+response.cutoff);
				},
				function(error_response){

				}
			)
        }
	}
}
function rollback(){
    request = $.ajax({
        type:"POST",
        url: "api2/public/membershipyear/rollback",
        dataType: "json",
        async: true
    });
	$.when(request).then(
		function(response){
			cutoff_year = response.cutoff;
			cutoff_year_element.text("Cutoff: "+response.cutoff);
		},
		function(error_response){

		}
	)
}
