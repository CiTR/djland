(function (){
    var app = angular.module('playsheet',[]);

    app.controller('PlaysheetController',function(){
    	this.playitems = playitems;
    	this.id = id;
    	this.name = name;
    	this.ads = ads;
    });
    //Declares <playitem> tag
    app.directive('playitem',function(){
    	return{
    		restrict: 'E',
    		templateUrl: 'templates/playitem.html'
    	};
    });
    app.directive('ad',function(){
    	return{
    		restrict: 'A',
    		templateUrl: 'templates/ad.html'
    	}
    });

    var playitems = [
	    		{
		    		id:'10202',
					title:'blue suede shoes',
					artist:'some guy with hair',
					album:'swingin times',
					composer:'random composer',
					cancon_category:'20',
					language:'english',
					tags:{
						playlist:'1',
						cancon:'1',
						femcon:'1',
						instrumental:'0',
						partial:'1',
						hit:'0'
					}
				},
				{
		    		id:'10203',
					title:'red shoes',
					artist:'some thing',
					album:'swingin times',
					composer:'random composer',
					cancon_category:'20',
					language:'english',
					tags:{
						playlist:'1',
						cancon:'1',
						femcon:'1',
						instrumental:'0',
						partial:'1',
						hit:'1'
					}
				}
			];
	var id = '101';
	var name = 'Test Show';
	var ads = [
		{
			id:'1',
			name:'ad1',
			time:'20:10',
			type:'PSA',
			played:'0'
		},
		{
			id:'22',
			name:'ad2',
			time:'20:20',
			type:'AD',
			played:'0'
		}
	];
})();