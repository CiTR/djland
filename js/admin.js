/**
 * Created by Evan on 5/6/2015.
 */
$(document).ready ( function() {
    $(".inner").off('click',"#year_rollover").on('click','#year_rollover', function(){
       new_membership_year();
    });
});

function new_membership_year(){
    var membership_year;
    if( new Date().getMonth() >= 9 ){ //9 is september here
        membership_year = new Date().getFullYear() + "/" + (new Date().getFullYear()+1);
        last_membership_year = (new Date().getFullYear()-1) + "/" + new Date().getFullYear();
        var yes = confirm("This will create a new membership year for: "+ membership_year);
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
        alert("It is not yet September "+ new Date().getFullYear()+"! Membership reset can only happen after September");
    }

}