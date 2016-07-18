window.myNameSpace = window.myNameSpace || { };

function Schedule(date){
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

	$.when(this.getCategory('ad'),this.getCategory('ubc'),this.getCategory('community'),this.getCategory('timely'),this.getCategory('promo'),this.getCategory('id')).then(
	(
		function(ad,ubc,community,timely,promo,id){
			for(var category in this.categories){
				this['cat-promises'].push(this.createCategoryTemplate(category));
			}

			this['cat-promises'].push(this.createPSATemplate(category));

			$.when(this['cat-promises']).then(
			(
				function(){
					this.init();
				}
			).bind(this);
		}
	).bind(this);
}

Schedule.prototype = {
	init:function(){
		$.when(this.ready).then(
			(
				function(response){
		 			for(var item in response){
						this.showtimes.push(response[item]);
					}
					$.when.apply($,this.categories).then(
						(
							function(){
								this.displaySchedule();
								$('.loading_bar').hide();
							}
						).bind(this),
						function(){
							console.log("Category load had a failure");
							this.init();
						}
					);
				}
			).bind(this)
			,(
				function(error){
					this.init();
				}
			).bind(this)
		);
	},
	getSchedule:function(date){
		var date = this.formatDate(date);
		return $.ajax({
			type:"GET",
			url: "api2/public/adschedule",
			dataType: "json",
			data:{'date':date} ,
			async: true
		});
	},
	displaySchedule:function(){
		var promises = Array();
		if(this.showtimes.length > 1){
			for(var i = 0; i < this.showtimes.length; i++){
				this['html-promises'][i] = this.getHTML(this.showtimes[i],i);
			}
			//Display the initial showtimes
			$.when.apply($,this['html-promises']).then(
				(
					function(){
						for(var i = 0; i < this.showtimes.length; i++){
							this.displayShowtime(arguments[i][0],i);
						}
					}
				).bind(this)
			);
		}else if(this.showtimes.length > 0){
			this['html-promises'][0] = this.getHTML(this.showtimes[0],0);
			$.when(this['html-promises'][0]).then(
				(
					function(response){
						this.displayShowtime(response,0);
					}
				).bind(this)
			);
		}

	},
	displayShowtime:function(showtime, index){
		//Append the HTML from the requests
		this.schedule_element.append(showtime);
		var num_ads = this.showtimes[index].ads.length;
		for(var j = 0; j < num_ads; j++){
			var element = $('#show_'+index+"_"+j).find('select.name');
			if(this.showtimes[index].ads[j].type != 'announcement'){
				element.html($('#'+[this.showtimes[index].ads[j].type]+"-template").html());
				if(this.showtimes[index].ads[j].name){
					element.val(this.showtimes[index].ads[j].name);
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
		var ret = [date.getFullYear(),("0" + (date.getMonth()+1)).slice(-2),("0" + date.getDate()).slice(-2)].join('/');
		console.log(ret);
		return ret;
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
		var categories = {'ad':"6",'ubc':"12",'community':"11",'timely':"13",'promo':"21","id":"18"};
		for(var item in categories){
			promises[item] =
			this.getCategory(item,promises);
		}
		return promises;

	},
	getCategory:function(category){
		var ajax = $.ajax({
				type:"GET",
				url:"api2/public/SAM/categorylist/"+this.categories[category],
				async: false,
			});
		$.when(ajax).then(
			(
				function(response){
					this[category] = response.sort(function(a,b){
						if(a.title.toString() > b.title.toString()) return 1;
						if(a.title.toString() < b.title.toString()) return -1;
						return 0;
					});
				}
			).bind(this)
			,(
				function(error){
					//recursively calls on failure silently
					this.getCategory(category);
				}
			).bind(this)
		);
	},
	createPSATemplate:function(promises){
		for(var item in this.ubc){ this.psa.push(this.ubc[item]); }
		for(var item in this.community){ this.psa.push(this.community[item]); }
		for(var item in this.timely){ this.psa.push(this.timely[item]); }
		return this['cat-promises'].push(this.createCategoryTemplate('psa'));
	},
	createCategoryTemplate:function(item){
		var ad_list = this[item];
		console.log("called");
		var p = $.ajax({
				type:"POST",
				url:"templates/ad_list.php",
				async: false,
				data: {"ad_list":JSON.stringify(this[item]),'value':null,'type':item,'index':'template','num':'template'},
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
