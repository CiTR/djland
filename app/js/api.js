angular.module('djland.api', []).factory('call', function ($http, $location) {

    var API_URL_BASE = 'api2/public'; // api.citr.ca when live

	return {
		getConstants: function(){
			return $http.get('/headers/constants.php');
		},
		getEpisodeImage: function(podcast_id){
			return $http.get(API_URL_BASE + '/podcast/' + podcast_id + '/image');
		},
		getMemberPlaysheets: function (member_id,offset) {
			return $http.get(API_URL_BASE + '/playsheet/member/' + member_id + '/'+offset || 0);
		},
		getPlaysheets: function (limit) {
			limit = limit || 50;
			return $http.get(API_URL_BASE + '/playsheet/list/' + limit);
		},
		getPlaysheetData: function (playsheet_id) {
			return $http.get(API_URL_BASE+ '/playsheet/' + playsheet_id);
		},
		getAds: function (time,duration,show_id){
			return $http.get(API_URL_BASE+ '/ads/' + time + '-' + duration + '/' + show_id);
		},
		getMemberShows: function(member_id){
			return $http.get(API_URL_BASE+ '/member/'+member_id+'/shows');
		},
		getActiveMemberShows: function(member_id){
			return $http.get(API_URL_BASE+ '/member/'+member_id+'/active_shows');
		},
		getActiveShows: function(){
			return $http.get(API_URL_BASE + '/show/active');
		},
		getShow: function(show_id){
			return $http.get(API_URL_BASE+'/show/'+show_id);
		},
		getShowPlaysheets: function(show_id,offset){
			return $http.get(API_URL_BASE + '/show/' + show_id + '/playsheets' +'/'+(offset || 0));
		},
		getMoreShowPlaysheets: function(show_id,offset){
			return $http.get(API_URL_BASE + '/show/' + show_id + '/playsheets' +'/'+(offset || 0) );
		},
		getShowEpisodes: function(show_id,offset){
			return $http.get(API_URL_BASE + '/show/' + show_id + '/episodes/'+(offset || 0));
		},
		getShowOwners: function(show_id){
			return $http.get(API_URL_BASE+"/show/"+show_id+"/owner");
		},
		getShowTimes: function(show_id){
			return $http.get(API_URL_BASE+"/show/"+show_id+"/times");
		},
		getShowImages: function(show_id){
			return $http.get(API_URL_BASE+'/show/'+show_id+'/image');
		},
		getUploads: function(){
			return $http.get(API_URL_BASE+'/upload');
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
			return $http.get(API_URL_BASE+"/SAM/recent/"+offset || 0);
		},
		getSamRange: function(from,to){
            var re = /^\d{4}-\d{2}-\d{2}%20\d{2}:\d{2}:\d{2}$/g;
            if(re.test(from) && re.test(to)){
                return $http.get(API_URL_BASE+"/SAM/range?from="+from+"&to="+to);
            }
            else{
                console.log("Invalid ranges for getSamRange! from : ", from, " to : ", to);
                return null;
            }
		},
		getAdSchedule: function(){
			return $http.get(API_URL_BASE+'/adschedule');
		},
		getReport: function(show_id,from,to,report_type){
			return $http.post(API_URL_BASE+'/playsheet/report',angular.toJson({'show_id':show_id,'from':from,'to':to,'report_type':report_type}));
		},
		getFriends: function(){
			return $http.get(API_URL_BASE+'/friends');
		},
		getForms: function(){
			return $http.get(API_URL_BASE + '/fundrive/donor');
		},
		getFundriveTotals: function(){
			return $http.get(API_URL_BASE + '/fundrive/total');
		},
		getResources: function(){
			return $http.get(API_URL_BASE + '/resource');
		},
		isStaff: function(member_id){
			return $http.get(API_URL_BASE + '/member/'+ member_id + '/staff');
		},
		isAdmin: function(member_id){
			return $http.get(API_URL_BASE + '/member/' + member_id + '/admin');
		},
		addFriend: function(){
			return $http.put(API_URL_BASE+'/friends');
		},
		saveFriends: function(friends){
			return $http.post(API_URL_BASE + '/friends', angular.toJson({'friends':friends}));
		},
		saveResources: function(resources){
			return $http.post(API_URL_BASE + '/resource',angular.toJson({'resources':resources}));
		},
		getBroadcasts: function(){
			return $http.get(API_URL_BASE+'/specialbroadcasts');
		},
		addBroadcast: function(){
			return $http.put(API_URL_BASE+'/specialbroadcasts');
		},
		deleteBroadcast: function(id){
			return $http.delete(API_URL_BASE+'/specialbroadcasts/'+id);
		},
		saveBroadcasts: function(specialbroadcasts){
			return $http.post(API_URL_BASE + '/specialbroadcasts', angular.toJson({'specialbroadcasts':specialbroadcasts}));
		},
		saveAds: function(showtimes){
			return $http.post(API_URL_BASE+'/adschedule',angular.toJson({'showtimes':showtimes}));
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
		savePodcast: function(podcast){
			return $http.put(API_URL_BASE+'/podcast', angular.toJson({'podcast':podcast}));
		},
		saveNewPlaysheet: function(playsheet,playitems,podcast,ads){
			return $http.post(API_URL_BASE+'/playsheet', angular.toJson({'playsheet':playsheet,'playitems':playitems,'podcast':podcast,'ads':ads}) );
		},
		saveNewPodcast: function(podcast){
			return $http.put(API_URL_BASE+'/podcast',angular.toJson({'podcast':podcast}) );
		},
		deleteFriend: function(id){
			return $http.delete(API_URL_BASE+'/friends/'+id);
		},
		deletePlaysheet:function(id){
			return $http.delete(API_URL_BASE+'/playsheet/' + id);
		},
		deleteImage:function(id){
			return $http.delete(API_URL_BASE+'/upload/'+id);
		},
		deleteEpisodeImage:function(podcast_id) {
			return $http.delete(API_URL_BASE+'/podcast/'+podcast_id+'/image');
		},
		makePodcastAudio: function(podcast){
			return $http.post(API_URL_BASE+'/podcast/'+podcast.id+'/audio');
		},
		overwritePodcastAudio: function(podcast){
			return $http.post(API_URL_BASE+'/podcast/'+podcast.id+'/overwrite');
		},
		isSocan : function(unixtime){
			return $http.get(API_URL_BASE+'/socan/check/'+(unixtime||''));
		},
		error: function(error){
			return $http.post(API_URL_BASE+'/error',angular.toJson({'error':error}));
		},
	};
});

angular.module('sam.api', []).factory('sam', function ($http, $location) {
    var API_URL_BASE = 'api2/public/SAM'; // api.citr.ca when live
    return {
        getAdList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'PRIORITY ADs');
        },
        getUBCPSAList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'ubc')
        },
        getCommunityPSAList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'community');
        },
        getTimelyPSAList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'New Timely PSAs');
        },
        getStationIDList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'station IDz');
        },
        getPromosList: function () {
            return $http.get(API_URL_BASE + '/categorylist/' + 'SHOW PROMOS');
        },
    };
});
