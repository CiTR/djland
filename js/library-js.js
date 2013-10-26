// JavaScript Document


$(document).ready(function() {

	dopeysecurityval = 'something';
	
	$('.lib-delete').click(function(){
		$(this).replaceWith(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
'<a class=yesdelete>delete forever?</a>');

var id=$(this).attr('id');
		$('.yesdelete').click(function(){
			$thisguy = $(this);
			
			$.ajax({
				type: "POST",
				url: "./form-handlers/library-handler.php",
				data: { dopeysecurity: dopeysecurityval,action: "delete", id: id },
				beforeSend: function(){
					$(this).html('deleting...');	
					}
				}).done( function( msg ) {
					console.log(msg);
					$oldCD = $thisguy.parent();
					$oldCD = $oldCD.parent();
					$oldCD.html(msg);
				}).always(function(){
					console.log('tried to delete');	
				}).fail( function( ) {
					$thisguy.replaceWith('&nbsp;&nbsp;&nbsp; there was a problem - unable to delete ');
				});
					
			
		});
		
	});
	

$('#nukem').click(function(){
	
	
	});

});


/*

$.ajax({
		type:'POST',
		url:'./saveAds.php',
		data:{action:'save',ads:saveData},
		beforeSend: function() {
     $('#save').css('background-color','red');
     console.log('saveData:');
     console.log(saveData);
  },
		
		}).done(function(data){
			console.log('done. loaded data: ');
			console.log(data);
			$('#save').css('background-color','beige');
		}).fail(function(){
			console.log('failed');
		}).always(function(){
			console.log('the js worked');
		});
		
		*/