
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
	getMemberShows: function(member_id){
		return $http.get(API_URL_BASE+ '/member/'+member_id+'/shows');
	},
	getShow: function(show_id){
		return $http.get(API_URL_BASE+'/show/'+show_id);
	},
	getMemberPermissions: function(member_id){
		return $http.get(API_URL_BASE+'/member/'+member_id+'/permission');
	},
	saveShow: function(show_object,social_objects){
		return $http.post(API_URL_BASE+'/show/'+show_object.id,angular.toJson({'show':show_object,'social':social_objects}));
	},
	getFullPlaylistData: function (id) {
	return $http.get(API_URL_BASE+ '/playlist/full.php?ID='+id);
	},

	getEpisodes: function () {
	return $http.get(API_URL_BASE + '/episodes/mine.php');
	},

	getSpecialBroadcasts: function () {
	return $http.get(API_URL_BASE + '/specialevents');
	},

	saveSpecialBroadcast: function (data) {
	return $http.post(API_URL_BASE + '/specialevents/save.php', data);
	},

	createSpecialBroadcast: function (data) {
	return $http.post(API_URL_BASE + '/specialevents/create.php', data);
	},

	getRecentSamPlays: function () {
	return $http.get(API_URL_BASE + '/sam/recent.php');
	},

	getSamFromRange: function(min, max) {
	return $http.post(API_URL_BASE + '/sam/range.php',angular.toJson({'min':min,'max':max}));

	},

	savePlaylist: function(data) {
	return $http.post(API_URL_BASE + '/playlist/save.php', data);
	},

	getNextShow: function(time){
	return $http.get(API_URL_BASE + '/schedule/nextshow.php?time='+time)
	},

	getAdsFromBlock: function(time){
	return $http.get(API_URL_BASE + '/ad/scheduled.php?timeblock='+time)
	},

	savePodcast: function(podcast){
	return $http.post(API_URL_BASE + '/episode/create.php')
	},

	getArchiverTime: function(){
	return $http.get('http://archive.citr.ca/time/')
	},


	updatePodcast: function(data, updateAudio){
	data.updateAudio = updateAudio;
	return $http.post(API_URL_BASE + '/podcasting/update_podcast.php', data)
	},

	logout: function(){
	return $http.post('')
	},

	def: function(){
	return $http.post(API_URL_BASE + '/deferMe.php',{data: 'some data'});
	}

	};
});
