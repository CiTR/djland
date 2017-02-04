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
				url: "./form-handlers/library_handler.php",
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

function toggle(source) {
	checkboxes = document.getElementsByName('entry');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}

function editLine(source) {
	var tr = source.parentNode;
	var table = tr.parentNode;

	if(tr.nextSibling.id != "editableLine") {
		var newtr1 = document.createElement("tr");
		newtr1.id = "editableLine";
		newtr1.innerHTML = "<td> </td><td> </td><td><INPUT TYPE=text size=5 style='float:right'></td><td><INPUT TYPE=text size=1 style='float:right'></td><td>Artist: <INPUT TYPE=text size=20> Title: <INPUT TYPE=text size=23></td>";

		var newtr2 = document.createElement("tr");
		newtr2.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Label: <INPUT TYPE=text size=20> Genre: <INPUT TYPE=text size=20></td>";

		var newtr3 = document.createElement("tr");
		newtr3.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Modified: <INPUT TYPE=text size=16>  Added: <INPUT TYPE=text size=20></td>";

		var newtr4 = document.createElement("tr");
		newtr4.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td>Can: <input type=checkbox> Fem: <input type=checkbox> Loc: <input type=checkbox> PL: <input type=checkbox> Comp: <input type=checkbox> SAM: <input type=checkbox></td>";

		var newtr5 = document.createElement("tr");
		newtr5.innerHTML = "<td> </td><td> </td><td> </td><td> </td><td><input type=submit VALUE='Save Changes' onClick='save(this)'> <input type=submit VALUE='Cancel' onClick='cancel(this)'></td>";

		table.insertBefore(newtr5, tr.nextSibling);
		table.insertBefore(newtr4, newtr5);
		table.insertBefore(newtr3, newtr4);
		table.insertBefore(newtr2, newtr3);
		table.insertBefore(newtr1, newtr2);
	}
}

function save(source) {

}

function cancel(source) {
	var td = source.parentNode;
	var tr = td.parentNode;
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.previousSibling.remove();
	tr.remove();
}
