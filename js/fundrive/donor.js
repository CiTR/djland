$(document).ready ( function() {

	function save(){
		var donor = {};
		var create_request = $.ajax({
			type:"PUT",
			url: "api2/public/fundrive/donor",
			dataType: "json",
			async: true
		});
		$.when(create_request).then(
			function(create_response){
				var update_request = $.ajax({
					type:"POST",
					url: "api2/public/fundrive/donor/"+create_response.id,
					dataType: "json",
					data: {'donor':donor},
					async: true
				});

				$.when(update_request).then(
					function(update_response){
						alert('Success');
					},function(error){
						alert('Fail')
					}
				);
			},function(error){

			}
		);
	}

	function get(target_id,target_class,target_name){
		var target =  $( (target_id != null ? '#'+ target_id : "" ) + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name="+target_name+"]" : ""));
		var tag = target.prop('tagName');
		var result;
		switch(tag){
			case 'DIV':
				result = target.text();
				break;
			case 'INPUT':
				var type = target.attr('type');
				switch(type){
					case 'checkbox':
						if(target.prop('checked')) result = 1;
						else result = 0;
						break;
					default:
						result = target.val();
						break;
				}
				break;
			case 'SELECT':
			case 'TEXTAREA':
				result = target.val();
				break;
			default:
				result = target.val();
				break;
		}
		return result;
	}
});
window.setInterval(checkBlocking,1000);

function checkBlocking(){
		var allOkay = true;
		$('.required').each( function(){
			if( !$.trim( $(this).val() )){
			allOkay=false;
			}
		});
		console.log(allOkay);
	}
