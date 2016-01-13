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
	this['list'] = Array();	
	this['templates'] = {};

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
		this_.displaySchedule( $('.schedule') );
		$('.loading_bar').hide();
	},function(error){
		this.ready = this.getSchedule(date);
		$.when(this.ready).then(function(response){
 		for(var item in response){
			this_.showtimes.push(response[item]);
		}
		this_.displaySchedule( $('.schedule') );
		$('.loading_bar').hide();
		},function(error){
			console.log('err' + error.responseText);
		});
	});
}


Schedule.prototype = {
	getSchedule:function(date){
		var date = this.formatDate(date);
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule/"+ date,
			dataType: "json",
			async: true
		});
	},
	displaySchedule:function(schedule_element){
		var promises = Array();
		var this_ = this;

		for(var i = 0; i < this.showtimes.length; i++){
			promises.push(this.getHTML(this.showtimes[i],i));
		}


		//Display the initial showtimes
		$.when.apply($,promises).then(function(){
			for(var i = 0; i < this_.showtimes.length; i++){
				//Append the HTML from the requests
				schedule_element.append(arguments[i][0]);
				var num_ads = this_.showtimes[i].ads.length;

				for(var j = 0; j < num_ads; j++){
					var element = $('#show_'+i+"_"+j).find('select.name');
					if(this_.showtimes[i].ads[j].type != 'announcement') element.html(this_['templates'][this_.showtimes[i].ads[j].type].html());
					element.val(this_.showtimes[i].ads[j].name);
				}
			}
		});
	},
	saveSchedule:function(){
		console.log($('form').serialize());
		return $.ajax({
			type:"POST",
			url:"api2/public/adschedule2",
			async: true,
			data: {"ads":$('form').serialize()}
		});
	},
	formatDate:function(date){
		date = new Date(date);
		return [date.getFullYear(),("0" + (date.getMonth()+1)).slice(-2),("0" + date.getDate()).slice(-2)].join('-') + 
				" " + 
				[("0" + date.getHours() ).slice(-2),("0" + date.getMinutes()).slice(-2),("0" + date.getSeconds()).slice(-2)].join(':');
	},
	getHTML:function(showtime,index){
		var this_ = this;
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
				data: {"ad_list":JSON.stringify(ad_list),'value':null,'type':item,'index':'template','num':'template'},
			});
		$.when(p).then(function(response){
			$('#' + item + '-template').append(response);
			this_.templates[item] = $('#'+item+'-template');
		});
	},
	updateDropdown:function(list,type,value,index,num){
		var parent = $('#show_'+index+"_"+num).find('td.name');
		if(type != 'announcement'){
			$(parent).html("<select name='show[show_"+index+"_"+num+"][name]' class='name'></select>");
			var element = $('#show_'+index+"_"+num).find('select.name');
			if(type != 'announcement') element.html(this['templates'][type].html());
			if(value != null) element.val(value);
		}else{
			$(parent).html("<input class='name wideinput' name='show[show_"+index+"_"+num+"][name]' value='Announce the upcoming show'>");
		}
	},
	addElement:function(list,type,time,index,num){
		var count = 0;
		var unix = $('#show_'+index).attr('data');
		$('#show_'+index).find('.ads').find('tr').each(function(i,obj){
			count++;
		});

		console.log(count);
	},
	logError:function(error){
		//Get relevant text from the eloquent error message
		var error = error.data.split('body>')[1].substring(0,error.data.split('body>')[1].length-2 );
        $.ajax({
				type:"POST",
				url:"api2/public/error",
				async: true,
				data: {"error":error},
		});
	},


}

