

$(document).ready ( function() {
	manage_members(1);
	
	$('.member_action').click( function () {
		console.log("member_action");
		var action = parseInt($(this).attr('value'));
		manage_members(action);
	});
	$('#submit').click( function () {
		var action = $(this).attr('name');
		switch(action){
			case add_member:
				var name=document.getElementByName("name");
				alert('Member added: ' + name);
				manage_members(2);
				break;
			default:
				manage_members(1);
				break;
		}
	});
});

function manage_members(value){
		
		//Values: 1 => Search Members, 2 => Add Member, 3 => Delete Member, 4=> Email Members 
		var submenu_value = 1;
		if(value){
			submenu_value = value;
		}
		document.getElementById("membership").innerHTML = " ";
		
		switch(submenu_value){
			case 1: //Search
				console.log('Search');
				$('#membership').append("<input id='name' class='></input>");
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
				break;
			case 2: // Add Member
				console.log('Add Member');
				$('#membership').append('<input id = name></input><button id ="member_submit" name = "add_member">Add Member</button>');
				break;
			default:
				manage_members(1);
				break;		
		}	
	}
	

