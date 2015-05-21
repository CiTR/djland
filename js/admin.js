/**
 * Created by Evan on 5/6/2015.
 */
var cutoff;
var current_year;
$(document).ready ( function() {
    cutoff = $('#current_cutoff');
    current_year = $('#current_year');
    show_current_cutoff();
    $(".inner").off('click',"#year_rollover").on('click','#year_rollover', function(){
       update_cutoff_year();
    });
});


function show_current_cutoff(){
    var membership_year;
    $.ajax({
        type:"GET",
        url: "form-handlers/membership/year_rollover.php",
        dataType: "json",
        async: true
    }).success(function(data){
        cutoff.text("Cutoff:"+data['year']);
        if( new Date().getMonth() >= 4 ) { //9 is september here
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
    var membership_year;
    //console.log( cutoff.text().substring(7) + ":" + current_year.text().substr(12));
    if(  (cutoff.text()).substring(7) == (current_year.text()).substr(12)  ){
        alert("You have already set the cutoff for this year!");
    }else if( new Date().getMonth() >= 4 ){ //9 is september here
        membership_year = new Date().getFullYear() + "/" + (new Date().getFullYear()+1);

        var yes = confirm("This will lock out all accounts not renewed for: "+ membership_year);
        if(yes){
            console.log("Confirmed");
            $.ajax({
                type:"POST",
                url: "form-handlers/membership/year_rollover.php",
                data: {"year":membership_year},
                dataType: "json",
                async: false
            }).success(function(data){
                membership_years = data.years;
            }).fail(function(){
                console.log("Unable to retrieve member information");
            });
        }else{
            console.log("Cancelled");
        }
    }else{
        alert("It is not yet September "+ new Date().getFullYear()+"! Membership reset can only happen after September");
    }

}