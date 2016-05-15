window.myNameSpace = window.myNameSpace || { };

function Schedule(date){
	var this_ = this;
	this.schedule_element = $('.schedule');
	this.categories = {'ad':"6",'ubc':"12",'community':"11",'timely':"13",'promo':"21","id":"18"};
	this['ad'] = Array();
	this['ubc'] = Array();
	this['community'] = Array();
	this['timely'] = Array();
	this['psa'] = Array();
	this['promo'] = Array();
	this['id'] = Array();
	this['templates'] = {};
	this['cat-promises'] = Array();
	this['html-promises'] = Array();
	this.showtimes = Array();
	if(!date) var date = $('.active-tab').attr('name');
	console.log(date);
	//Get initial Schedule
	this.ready = this.getSchedule(date);

	$.when(this.getCategory('ad'),this.getCategory('ubc'),this.getCategory('community'),this.getCategory('timely'),this.getCategory('promo'),this.getCategory('id')).then(function(ad,ubc,community,timely,promo,id){
		for(var category in this_.categories){
			this_['cat-promises'].push(this_.createCategoryTemplate(category));
		}
		this_['cat-promises'].push(this_.createPSATemplate(category));
		$.when(this_['cat-promises']).then(function(){
			this_.init();
		});
	});



}


Schedule.prototype = {
	init:function(){
		var this_ = this;

		$.when(this.ready).then(function(response){
 			for(var item in response){
				this_.showtimes.push(response[item]);
			}
			$.when.apply($,this_.categories).then(
				function(){
					console.log("retrieved categories");
					this_.displaySchedule();
					$('.loading_bar').hide();
				},
				function(){
					console.log("Category load had a failure");
					this_init();
				}
			);
		},function(error){
			this_.init();
		});
	},
	getSchedule:function(date){
		var date = this.formatDate(date);
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule/"+ date,
			dataType: "json",
			async: true
		});
	},
	displaySchedule:function(){
		var promises = Array();
		var this_ = this;
		if(this.showtimes.length > 1){
			for(var i = 0; i < this.showtimes.length; i++){
				this['html-promises'][i] = this.getHTML(this.showtimes[i],i);
			}
			//Display the initial showtimes
			$.when.apply($,this['html-promises']).then(function(){
				for(var i = 0; i < this_.showtimes.length; i++){
					this_.displayShowtime(arguments[i][0],i);
				}
			});
		}else if(this.showtimes.length > 0){
			this['html-promises'][0] = this.getHTML(this.showtimes[0],0);
			$.when(this['html-promises'][0]).then(function(response){
				this_.displayShowtime(response,0);
			});
		}

	},
	displayShowtime:function(showtime, index){
		var this_ = this;
		//Append the HTML from the requests
		this.schedule_element.append(showtime);
		var num_ads = this.showtimes[index].ads.length;
		for(var j = 0; j < num_ads; j++){
			var element = $('#show_'+index+"_"+j).find('select.name');
			if(this_.showtimes[index].ads[j].type != 'announcement'){
				element.html($('#'+[this_.showtimes[index].ads[j].type]+"-template").html());
				if(this_.showtimes[index].ads[j].name){
					element.val(this_.showtimes[index].ads[j].name);
				}
			}
		}
	},
	saveSchedule:function(){
		if(this.showtimes.length > 0){
			return $.ajax({
				type:"POST",
				url:"api2/public/adschedule",
				async: true,
				data: {"ads":$('form').serialize()}
			});
		}
	},
	formatDate:function(date){
		console.log("date in:" + date);
		date = new Date(date);
		console.log(date);
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
			promises[item] =
			this.getCategory(item,promises);
		}
		return promises;

	},
	getCategory:function(category){
		var this_ = this;
		var ajax = $.ajax({
				type:"GET",
				url:"api2/public/SAM/categorylist/"+this_.categories[category],
				async: false,
			});
		$.when(ajax).then(
			function(response){
				this_[category] = response.sort(function(a,b){
					if(a.title.toString() > b.title.toString()) return 1;
					if(a.title.toString() < b.title.toString()) return -1;
					return 0;
				});
			},
			function(error){
				this_.getCategory(category);
			}
		);
	},
	createPSATemplate:function(promises){
		var this_ = this;
			for(var item in this.ubc){ this_.psa.push(this.ubc[item]); }
			for(var item in this.community){ this_.psa.push(this.community[item]); }
			for(var item in this.timely){ this_.psa.push(this.timely[item]); }
			return this_['cat-promises'].push(this_.createCategoryTemplate('psa'));
	},
	createCategoryTemplate:function(item){
		var this_ = this;
		var ad_list = this_[item];
		console.log("called");
		var p = $.ajax({
				type:"POST",
				url:"templates/ad_list.php",
				async: false,
				data: {"ad_list":JSON.stringify(this_[item]),'value':null,'type':item,'index':'template','num':'template'},
			});
		$.when(p).then(function(response){
			$('#' + item + '-template').append(response);
		});
		return p;
	},
	updateDropdown:function(list,type,value,index,num){
		var parent = $('#show_'+index+"_"+num).find('td.name');
		if(type != 'announcement'){
			$(parent).html("<select name='show[show_"+index+"_"+num+"][name]' class='name'></select>");
			var element = $('#show_'+index+"_"+num).find('select.name');
			if(type != 'announcement') element.html($('#'+type+"-template").html());

			if(value != null) element.attr('value',value);
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
	}


}
