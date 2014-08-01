

$(document).ready ( function() {
	manage_members('init');
	add_handlers();
	
	
});

function add_handlers(){
	$('.member_action').unbind().click( function () {
		console.log("member_action");
		var action = $(this).attr('value');
		$('.member_action').attr('class','nodrop inactive-tab member_action');
		$(this).attr('class','nodrop active-tab member_action');
		manage_members(action);
	});
	
	$('.member_submit').unbind().click( function () {
		console.log("member_submit");
		var action = $(this).attr('name');
		switch(action){
			case 'search':
				var filter= document.getElementById("search_value").value;
				manage_members(action,null,filter);
				break;
			case 'add':
				var submit_name= document.getElementById("submit_name").value;
				alert("Added: "+submit_name);
				break;
			case 'remove':
				var submit_name= document.getElementById("submit_name").value;
				alert("Removed: "+submit_name);
				break;
			case 'email':
				var submit_name= document.getElementById("submit_name").value;
				var message= document.getElementById("message").value;
				alert("Emailed "+submit_name + ": "+ message);
				break;
			default:
				manage_members("init");
				break;
		}
	});
}
function manage_members(value_,type_,filter_){
		
		//Values: 1 => Search Members, 2 => Add Member, 3 => Delete Member, 4=> Email Members 
		var value = null;
		var type = null;
		var filter = "";
		if(value_){
			value = value_;
		}
		if(type_){
			type = type_;
		}
		if(filter_){
			filter = filter_;
		}		
		switch(value){
			case 'init':
				document.getElementById("membership").innerHTML = " ";
				$('#membership').append("<input id='search_value' type='text' value='Search by Name'></input><button class='member_submit' name='search'>Search</button>");
				$('#membership').append("<div id='member_result'></div>");
				add_handlers();
				break;
			case 'search':
				console.log('Search');
				document.getElementById("member_result").innerHTML = " ";
				
				$.ajax({
				type:"POST",
				url: "form-handlers/membership-handler.php",
				data: {"value" : value, "filter" : filter},
				dataType: "json"
				}).success(function(data) {
					for( $j = 0; $j < Object.keys(data).length; $j++ ){
						$('#member_result').append("<div>"+data[$j].firstname+" "+data[$j].lastname+"</div>")
					}
				}).fail(function(){
					$('#membership').html('connection error');
				});
				
				add_handlers();
				break;
				
			case 'view': // Add Member
				console.log('View Member');
				document.getElementById("membership").innerHTML = " ";
				$('#membership').append('<input id="submit_name" type="text" value="Enter a name" ></input><button class="member_submit" name="add">Add Member</button>');
				add_handlers();
				break;
			case 'report':
				console.log('Report Member');
				document.getElementById("membership").innerHTML = " ";
				$('#membership').append('<input  id="submit_name" type="text" value = "Enter a name"></input><button class="member_submit" name="remove">Remove Member</button>');
				add_handlers();
				break;
			case 'email':
				console.log('Email Members');
				document.getElementById("membership").innerHTML = " ";
				$('#membership').append('<input  id="submit_name" type="text" value ="Enter a name"></input><input id="message" type="text" value="Enter a message"></input><button class="member_submit" name = "email">Email Members</button>');
				add_handlers();
				break;
			default:
				manage_members('init');
				add_handlers();
				break;		
		}	
	}
	

