$(document).ready ( function() {
	addHandlers();

});

function addHandlers(){

$('.remove_owner').unbind().click(function(){
	console.log('remove_owner was clicked');
	var id = $(this).closest('.member_owner').attr('id').substr(5);

	$(this).closest('.member_owner').remove();

	var ids = $('#member_access').attr('value').split(',');
	console.log(ids);
	var index = ids.indexOf(id);
	if(index > -1){
		ids.splice(index,1);
	}
	console.log(ids);
	ids = ids.join();
	console.log(ids);
	$('#access_holder').html("<input id='member_access' class='invisible' name='member_access' value='"+ids+"'></input>");
});

$('.add_owner').unbind().click(function(){
	console.log('add_owner was clicked');
	if($('#member_access_select option:selected').text() != 'no one'){
		var member_id = $('#member_access_select').val();
		var member_name = $('#member_access_select option:selected').text();
		$('#member_access_list').append(
			"<li class='member_owner' id="+member_id+"><input class='invisible' name='member_access[]' value='"+member_id+"'></input>"+member_name+"<button type='button' class='remove_owner'>Remove</button></li>"
		);
		addHandlers();
		var ids = $('#member_access').val().split(',');
		ids.push(member_id);
		$('#access_holder').html("<input id='member_access' class='invisible' name='member_access' value='"+ids.join()+"'></input>");

	}
});
}