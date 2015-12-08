window.myNameSpace = window.myNameSpace || { };
var playsheet = new Playsheet(143798);

$(document).ready(function(){
	$('.add_playitem').click(function(){
		playsheet.addPlayitem();
	});

});
	