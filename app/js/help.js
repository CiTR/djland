 $(document).ready( function() {

	$('.QAcontainer').click(function(){
		var container = $(this).attr("id").replace(/\D/g,'');
		
		$('.QAelement').each(function(){
				if($(this).attr("id")=='QAelement'+container)
				{ $(this).toggle(); }
			});
		
		});
	
	$('.QAelement').click(function(){
		var container = $(this).attr("id").replace(/\D/g,'');
		var container2 = $(this).attr("name").replace(/\D/g,'');
		
		$('.QAanswer').each(function(){
			if(($(this).attr("id")=='QAanswer'+container) & ($(this).attr("name")=='QAanswer'+container2))
				{ $(this).toggle(); 
				console.log("toggling answer");
				}
			});		
		
		});	
		
	});




