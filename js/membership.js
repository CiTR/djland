

$(document).ready ( function() {
	manage_members(1);
	
	$('#member_action').click( function () {
		var action = $(this).attr('value');
		console.log('Tab clicked: ' + action);
		switch(action){
			case 1:
				//Search Members
				manage_members(1);
				break;
			case 2:
				//Add Member
				manage_members(2);
				break;
			case 3:
				//Delete Member
				manage_members(3);
				break;
			case 4:
				//Email Members
				manage_members(4);
				break;
			default:
				manage_members(1);
				break;
		}
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
		console.log(value);
		//Values: 1 => Search Members, 2 => Add Member, 3 => Delete Member, 4=> Email Members 
		var submenu_value = 1;
		if(value){
			submenu_value = value;
		}
		document.getElementById("membership").innerHTML = " ";
		
		switch(submenu_value){
			case 1: //Search
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
				$('#membership').append("hi");
				$('#membership').append('<input id = name>name</input><button id = "submit" name = "add_member">Add Member</button>');
				break;
			default:
				manage_members(1);
				break;		
		}	
	}
	

