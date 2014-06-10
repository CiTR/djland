$(document).ready ( function() {
	manage_members();
});




function manage_members(value){
		console.log('Switch to "Manage Member" Tab')
		var submenu_value = 1;
		if(value){
			submenu_value = value;
		}
		$.ajax({
			type:"POST",
			url: "form-handlers/membership-handler.php",
			data: {"submenu_value" : submenu_value},
			dataType: "json"
		}).success(function(data) {
			for( $j = 0; $j < Object.keys(data).length; $j++ ){
				$('#membership').append("<div>"+data[$j].firstname+" "+data[$j].lastname+"</div>")
			}
		}).fail(function(){
			$('#membership').html('connection error');
		});
	}