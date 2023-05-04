

	function attachListeners(){
		
		$('.ad-delete').unbind();
		$('.ad-delete').click(function(){
			$(this).parent().parent().remove();
		});
		
		$('.ad-add').unbind();
		$('.ad-add').click(function(){
			$(this).parent().parent().after($('#invisible-template-generic').clone());
			attachListeners();
		});
		
		$('.ad-advert').unbind();
		$('.ad-advert').click(function(){
			
			$theRow = $('#invisible-template-ad').clone();
			adValue = $theRow.find('select').val();
			console.log(adValue);
			$(this).parent().parent().after($theRow);
			attachListeners();
		});
	}
	
	
	
	attachListeners();
	
	$('#save').click(function(){
		
		// PREPARE ARRAY FOR SAVING
		
		saveData = [];
		$('.adSelectGroup').each(function(adG){
			oneShowData = [];
			showName = $(this).attr('name');
			uniqueTimeID = $(this).attr('id');
			//oneShowData.push([blockID,uniqueTimeID]);
			adIDs = '';
			
			yesAdd = true;
	//		if($(this).find('.selectanad').length){
			$(this).find('.selectanad').each(function(adS){
				selectedVal = $(this).val();
				console.log('found a value:'+selectedVal);
				if (selectedVal==="0"){
					yesAdd = false; 
				}
				// the yesAdd flag makes sure that each 'select an ad' in a show block
				// has been selected.  If not, it should not save 
			});
	//		}
				if(yesAdd){
					
					$(this).find('.adRow').each(function(){
					
						adRow_obj = {};
						adRow_obj.time = $(this).find('.adTime').children().val();
						adRow_obj.type = $(this).find('.adType').children().val();
						adRow_obj.name = $(this).find('.adName').children().val();
				
						oneShowData.push(adRow_obj);
					});
					saveData.push([uniqueTimeID,showName,oneShowData]);
					console.log([uniqueTimeID,showName,oneShowData]);
				}
				
		});
		console.log('going to save');
		$.ajax({
		type:'POST',
		url:'./form-handlers/saveAds.php',
		data:{action:'save',ads:saveData},
		beforeSend: function() {
     $('#save').css('background-color','red');
     console.log('saveData:');
     console.log(saveData);
  }
		
		}).done(function(data){
			console.log('done. loaded data: ');
			console.log(data);
			$('#save').css('background-color','beige');
		}).fail(function(){
			console.log('failed');
			$('#save').html('saving failed');
		}).always(function(){
			console.log('the js worked');
		});
		
	});