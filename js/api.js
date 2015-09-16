
angular.module('djland.api',[]).factory('call', function ($http, $location) {

	var API_URL_BASE = 'api2/public'; // api.citr.ca when live

	return {
		getConstants: function(){
			return $http.get('/headers/constants.php');
		},
		getMemberPlaysheets: function (member_id) {
			return $http.get(API_URL_BASE + '/playsheet/member/' + member_id);
		},
		getPlaysheets: function (limit) {
			limit = limit || 50;
			return $http.get(API_URL_BASE + '/playsheet/list/' + limit);
		},
		getPlaysheetData: function (playsheet_id) {
			return $http.get(API_URL_BASE+ '/playsheet/' + playsheet_id);
		},
		getAds: function (time){
			return $http.get(API_URL_BASE+ '/ads/' + time);
		},
		getMemberShows: function(member_id){
			return $http.get(API_URL_BASE+ '/member/'+member_id+'/shows');
		},
		getShow: function(show_id){
			return $http.get(API_URL_BASE+'/show/'+show_id);
		},
		getShowPlaysheets: function(show_id){
			return $http.get(API_URL_BASE + '/show/' + show_id + '/playsheets');
		},
		getShowEpisodes: function(show_id){
			return $http.get(API_URL_BASE + '/show/' + show_id + '/episodes');
		},
		getShowOwners: function(show_id){
			return $http.get(API_URL_BASE+"/show/"+show_id+"/owners");
		},
		getShowTimes: function(show_id){
			return $http.get(API_URL_BASE+"/show/"+show_id+"/times");
		},
		getNextShowTime: function(show_id,current_time){
			return $http.get(API_URL_BASE+"/show/"+show_id+"/nextshow/"+current_time);
		},
		getMemberPermissions: function(member_id){
			return $http.get(API_URL_BASE+'/member/'+member_id+'/permission');
		},
		getMemberList: function(){
			return $http.get(API_URL_BASE+"/member/list");
		},
		getSamRecent: function(offset){
			return $http.get(API_URL_BASE+"/SAM/recent/"+offset);
		},
		getSamRange: function(from,to){
			return $http.get(API_URL_BASE+"/SAM/range?from="+from+"&to="+to);
		},
		saveShow: function(show_object,social_objects,owner_objects,show_time_objects){
			return $http.post(API_URL_BASE+'/show/'+show_object.id,angular.toJson({'show':show_object,'social':social_objects,'owners':owner_objects,'showtimes':show_time_objects}) );
		},
		saveNewShow: function(show_object,social_objects,owner_objects,show_time_objects){
			return $http.post(API_URL_BASE+'/show',angular.toJson({'show':show_object,'social':social_objects,'owners':owner_objects,'showtimes':show_time_objects}) );
		},
		savePlaysheet: function(playsheet,playitems,podcast,ads){
			return $http.post(API_URL_BASE+'/playsheet/'+playsheet.id, angular.toJson({'playsheet':playsheet,'playitems':playitems,'podcast':podcast,'ads':ads}));
		},
		saveEpisode: function(playsheet,podcast){
			return $http.post(API_URL_BASE+'/playsheet/'+playsheet.id+'/episode', angular.toJson({'playsheet':playsheet,'podcast':podcast}));
		},
		saveNewPlaysheet: function(playsheet,playitems,podcast,ads){
			return $http.post(API_URL_BASE+'/playsheet', angular.toJson({'playsheet':playsheet,'playitems':playitems,'podcast':podcast,'ads':ads}) );
		},
		makePodcastAudio: function(podcast){
			return $http.post(API_URL_BASE+'/podcast/'+podcast.id+'/audio');
		},
		overwritePodcastAudio: function(podcast){
			return $http.post(API_URL_BASE+'/podcast/'+podcast.id+'/overwrite');
		}
	};
});
