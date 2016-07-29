<html>
	<script type='text/javascript' src="/js/jquery-ui/external/jquery/jquery.js"></script>
	<script>
		var ajax =  $.ajax({
			type:"GET",
			url: "/api2/public/show",
			dataType: "json",
			async: false
		});
		$.when(ajax).then(function(response){
			var shows = response;
			console.log(response);
			var delay = 2000;
			for(var show in shows){

				write_xml(shows[show],delay);
				delay += 2000;

			}

		});
		function write_xml(show,delay){
			window.setTimeout(function(){
				var xml_write =  $.ajax({
					type:"GET",
					url: "/api2/public/show/"+show['id']+"/xml",
					dataType: "json",
					async: true
					});

					$.when(xml_write).then(function(response){
						console.log(show['id'] + "Written? " + response.filename);
					});	
			},delay );
			
		}
	</script>
</html>
		