(function (){
    var app = angular.module('djland.editPlaysheet',['djland.api','djland.utils','ui.sortable','ui.bootstrap']);
	var shows;
	app.controller('PlaysheetController',function($filter,call){
   
    	this.id = id;
    	this.socan = socan;
    	this.name = name;
    	this.tags = tags;
    	this.help = help;
		this.shows = Array();
		var this_ = this;

		call.getEveryonesPlaysheets(10).then(function(data){
			//this_.shows = data;
			this_.playsheet_id = data.data[0].playsheet_id;
			return call.getFullPlaylistData(data.data[3].playsheet_id)	
		}).then(function(data){
			var playsheet = data.data;
			this_.playsheet = playsheet;
			this_.playitems = playsheet.plays;
			this_.ads = playsheet.ads;
			this_.edit_date = playsheet.edit_date;
			this_.host_name = playsheet.hostname;
		});  
	  	
    });

    //Declares <playitem> tag
    app.directive('playitem',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/playitem.html'
    	};
    });
    app.directive('ad',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/ad.html'
    	}
    });
    var socan = true;
    
	var id = '101';
	var name = 'Test Show';
	var tags = ['cancon','femcon','hit','instrumental','partial','playlist'];
	var help = {
	albumHelp:			"Enter the title of the album, EP, or single that the track is released on."
    +"If playing an mp3 or streaming from youtube, soundcloud etc, please take a moment to find the title of the album,"
    +"EP, or single that the track is released on. If it is unreleased, enter 'unreleased'. "
    +"If you are confused about what to enter here, please contact music@citr.ca This will help the artist chart "
    +"and help provide listeners with information about the release."
    ,artistHelp:		"Enter the name of the artist"
    ,compHelp: 			"Enter the name of the composer or author"
    ,timeHelp1:			"Hit the CUE button when the song starts playing . Or enter the start time. Time Format is HOUR:MIN"
    ,timeHelp2:			"Hit the END button when the song stops playing. Enter the duration of the song.Time Format is MIN:SECOND"
    ,plHelp:			"Playlist (New) Content: Was the song released in the last 6 months? "
    ,ccHelp:			"Cancon: two of the following must apply: Music written by a Canadian, Artist performing it is Canadian, Performance takes place in Canada, Lyrics Are written by a Canadian"
    ,feHelp:			"Femcon: two of the following must apply: Music is written by a female, Performers (at least one) are female, Words are written by a female, Recording is made by a female engineer."
    ,instHelp:			"Is the song instrumental? (no vocals)"
    ,partHelp:			"Partial songs: For a track to count as cancon, you need to play the whole thing and it must be at least 1 minute."
    ,hitHelp:			"Has the song ever been a hit in Canada?  By law, the maximum is 10% Hits played, but we aim for 0% - you really shouldn't play hits!"
    ,themeHelp:			"Is the song your themesong?"
    ,backgroundHelp:	"Is the song playing in the background? Talking over the intro to a song does not count as background"
    ,crtcHelp:			"Category 2: Rock, Pop, Dance, Country, Acoustic, Easy Listening.  Category 3: Concert, Folk, World Beat, Jazz, Blues, Religious, Experimental. <a href':'http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target':'_blank'>Click for more info</a>"
    ,songHelp:			"Enter the name of the song"
    ,langHelp:			"The language of the song"
    ,adsHelp:			"Station IDs must be played or spoken in the first ten minutes of every hour"
    ,guestsHelp:		"Any non-music features on your show.  This helps us to reach our 15% local spoken word minimum"
    ,toolsHelp:			"Tools: [-] Delete the row  [+]Add a new row below"

	} 
})();