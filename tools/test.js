window.myNameSpace = window.myNameSpace || { };
var playsheet = new Playsheet();
playsheet.initialize(143798);
console.log(playsheet);

$(document).ready(function(){
	$('.add_playitem').click(function(){
		console.log(playsheet);
		playsheet.addPlayitem(0);
	});

});
	