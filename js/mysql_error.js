window.setInterval(getLastError,10000);

function getLastError(){
	var request = $.ajax({
		type:"GET",
		url: "form-handlers/mysql_error.php",
		dataType: "json",
		async: true});

	$.when(request).then(function(reply){
		console.log(reply['error']);
	},function(error){
		console.log(error);
	});
}