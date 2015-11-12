window.myNameSpace = window.myNameSpace || { };

function Schedule(date){
	var this_ = this;
	this['ad'] = Array();
	this['ubc'] = Array();
	this['community'] = Array();
	this['timely'] = Array();
	this['psa'] = Array();
	this['promo'] = Array();
	this['id'] = Array();

	this.showtimes = Array();

	if(!date) var date = this.formatDate(new Date());	

	//Get Categories
	this.cat_ready = this.getCategories();
	//Get initial Schedule
	this.ready = this.getSchedule(date);
	$.when(this.ready).then(function(response){
 		for(var item in response){
			this_.showtimes.push(response[item]);
		}
	},function(error){
		console.log('err' + error.responseText);
	});
}


Schedule.prototype = {
	getSchedule:function(date){
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule/"+date,
			dataType: "json",
			async: true
		});
	},
	formatDate:function(date){
		return [date.getFullYear(),("0" + (date.getMonth()+1)).slice(-2),("0" + date.getDate()).slice(-2)].join('-') + 
				" " + 
				[("0" + date.getHours() ).slice(-2),("0" + date.getMinutes()).slice(-2),("0" + date.getSeconds()).slice(-2)].join(':');
	},
	getHTML:function(showtime,index){
		return $.ajax({
			type:"POST",
			url:"templates/ad_schedule_item.php",
			async: true,
			data: {"show":showtime,"index":index}
		});
	},
	getCategories:function(){
		var promises = [];
		var this_ = this;
		var categories = {'ad':"6",'ubc':"12",'community':"11",'timely':"13",'promo':"21","id":"18"};

		for(var item in categories){
			promises[item] = $.ajax({
				type:"GET",
				url:"api2/public/SAM/categorylist/"+categories[item],
				async: true,
			});
		}
		$.when(promises['ad']).then(function(response){
			this_['ad'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('ad');
		});
		$.when(promises['ubc']).then(function(response){
			this_['ubc'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('ubc');
		});
		$.when(promises['community']).then(function(response){
			this_['community'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('community');
		});
		$.when(promises['timely']).then(function(response){
			this_['timely'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('timely');
		});
		$.when(promises['promo']).then(function(response){
			this_['promo'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('promo');
		});
		$.when(promises['id']).then(function(response){
			this_['id'] = response.sort(function(a,b){
				if(a.title.toString() > b.title.toString()) return 1;
				if(a.title.toString() < b.title.toString()) return -1;
				return 0;
			});
			this_.createCategoryTemplate('id');
		});

		$.when(promises['ubc'],promises['community'],promises['timely']).then(function(ubc,community,timely){
			for(var item in ubc[0]){ this_.psa.push(ubc[0][item]); } 
			for(var item in community[0]){ this_.psa.push(community[0][item]); }
			for(var item in timely[0]){ this_.psa.push(timely[0][item]); }
			this_.psa = this_.psa
			this_.createCategoryTemplate('psa');
		});
		return promises;
	},
	createCategoryTemplate:function(item){
		var this_ = this;
		var ad_list = this[item];
		var p = $.ajax({
				type:"POST",
				url:"templates/ad_list.php",
				async: true,
				data: {"ad_list":JSON.stringify(ad_list),'type':item,'index':'template','num':'template'},
			});
		$.when(p).then(function(response){
			$('#' + item + '-template').append(response);	
		});
	},
	updateDropdown:function(list,type,index,num){
		if(type != 'announcement'){
			var p = $.ajax({
				type:"POST",
				url:"templates/ad_list.php",
				async: true,
				data: {"ad_list":JSON.stringify(list),'type':type,'index':index,'num':(num-1)},
			});
			$.when(p).then(function(response){
				$('#show_'+index+"_"+num).children().find('select.name').append(response);
			});
		}else{
			$('#show_'+index+"_"+num).children().find('select.name').append("<input value='Announce'></input>");
		}
		
	}

}

