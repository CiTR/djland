/**
 * Created by Evan on 5/6/2015.
 */
var membership_year;
var cutoff;
var current_year;
var cutoff_month = 5; // Greater or equal to may (start of may is the new membership year)
$(document).ready ( function() {
    cutoff = $('#current_cutoff');
    current_year = $('#current_year');
	show_current_cutoff();
    $(".inner").off('click',"#year_rollover").on('click','#year_rollover', function(){
       update_cutoff_year();
    });
});


function show_current_cutoff(){
    $.ajax({
        type:"GET",
        url: "form-handlers/membership/year_rollover.php",
        dataType: "json",
        async: true
    }).success(function(data){
        cutoff.text("Cutoff:"+data['year']);
		var current_date = new Date();
        if( current_date.getMonth() >= cutoff_month ) {
            membership_year = new Date().getFullYear() + "/" + (new Date().getFullYear() + 1);
        }else{
            membership_year = (new Date().getFullYear() - 1) + "/" + (new Date().getFullYear() + 0);
        }
        current_year.text("Current year:"+membership_year);
    }).fail(function(data){
        cutoff.text("Failed to load");
    })
}

function update_cutoff_year(){
    //console.log( cutoff.text().substring(7) + ":" + current_year.text().substr(12));
    if(  (cutoff.text()).substring(7) == (current_year.text()).substr(13)  ){
        alert("You have already rolled over membership for this year! You do not need to update the cutoff.");
    }else if( new Date().getMonth() >= cutoff_month || (cutoff.text()).substring(7) != (current_year.text()).substr(13) ){
        var yes = confirm("This will lock out all accounts not renewed for: "+ membership_year);
        if(yes){
            console.log("Confirmed");
            $.ajax({
                type:"POST",
                url: "form-handlers/membership/year_rollover.php",
                data: {"year":membership_year},
                dataType: "json",
                async: true
            }).success(function(data){
                membership_years = data.years;
            }).fail(function(){
                console.log("Unable to retrieve member information");
            });
        }else{
            console.log("Cancelled");
        }
    }else{
        alert("It is not yet May "+ new Date().getFullYear()+"! Membership reset can only happen after it is may");
    }

}
