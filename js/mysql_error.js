window.setInterval(getLastError,1000);

function getLastError(){
	var request = $.ajax({
		type:"GET",
		url: "./headers/mysql_error.php",
		dataType: "json",
		async: true});

	$.when(request).then(function(reply){
		console.log(reply['error']);
	},function(error){
		console.log(error);
	});
}