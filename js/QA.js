 $(document).ready( function() {

	$('.QAcontainer').click(function(){
		var container = $(this).attr("id").replace(/\D/g,'');
		
		$('.QAicon').each(function() {
				if(($(this).attr("id")=='QAicon'+container) & !($(this).attr('name')))
				{ 
					
					console.log('in container');
					if($(this).hasClass('collapsed'))
					{
					$(this).attr('src','images/expanded.png');
					$(this).removeClass('collapsed');
					console.log('expanding');
					}
					else{
					$(this).addClass('collapsed');
					$(this).attr('src','images/collapsed.png');
					console.log('collapsing');
					}
				}
			});
				
		$('.QAelement').each(function(){
				if($(this).attr("id")=='QAelement'+container)
				{ $(this).toggle();	}
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
		$('.QAicon').each(function() {
				
				// if(($(this).attr("id")=='QAicon'+container) & ($(this).attr("name")=='QAicon'+container2))
				// { $(this).toggle(); 
				
				// }
				
				if(($(this).attr("id")=='QAicon'+container) & ($(this).attr("name")=='QAicon'+container2))
				{ 
					
					if($(this).hasClass('collapsed'))
					{
					$(this).attr('src','images/expanded.png');
					$(this).removeClass('collapsed');
					console.log('expanding');
					}
					else{
					$(this).addClass('collapsed');
					$(this).attr('src','images/collapsed.png');
					console.log('collapsing');
					}
				}

		
				});
		
		});	
		
	});




