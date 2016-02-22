$(document).ready ( function() {
	var donor = {};
	//Get from PHP setting via script tag
	var id = id_in;
	console.log(id);
	if(id != null){
		load(id);
	}
	getTotals();

	function getTotals(){
		var load_request = $.ajax({
					type:"GET",
					url: "api2/public/fundrive/total",
					dataType: "json",
					async: true
				});
		$.when( load_request).then(
			function(response){
				console.log(response);
				document.getElementById("total").innerHTML = "Total raised so far: $"+response;
			},function(error){
				console.log(error);
		});
	}

	function load(id){
		var load_request = $.ajax({
					type:"GET",
					url: "api2/public/fundrive/donor/"+id,
					dataType: "json",
					async: true
				});
		$.when( load_request).then(
			function(response){
				console.log(response);

				for(var entry_index in response){
					if( entry_index == 'donation_amount'){
						$('.amount[value="'+response[entry_index]+'"]').prop('checked',true);
					}else if( entry_index == 'payment_method'){
						$('.payment_method[value="'+response[entry_index]+'"]').prop('checked',true);
					}else if( entry_index == 'mail_yes'){
						$('.mailing[value="'+response[entry_index]+'"]').prop('checked',true);
						if(response[entry_index] == 0) $('.postage').addClass('invisible');
					}else if( entry_index == 'donor_recognition_name'){
						var donor_recognition_name = response[entry_index];
						if(donor_recognition_name  == response.firstname+' '+response.lastname){
							$("#recognize_0").prop('checked',true);
						}
						else if( donor_recognition_name == "Anonymous"){
							$("#recognize_2").prop('checked',true);
						}
						else{
							$("#recognize_1").prop('checked',true);
							$('#pseudonym').removeClass('invisible');
							set(donor_recognition_name,"pseudonym");
						}
					}else if( entry_index == "paid"){
						$("#paid_status").prop("checked",true);
					}else if( entry_index == prize_picked_up){
						$("#prize_picked_up").prop("checked",true);
					}else if( entry_index == "swag"){
						$("#swag").prop("checked",true);
					}else if( entry_index == "tax_receipt"){
						$("#tax_receipt").prop("checked",true);
					}else if(entry_index == "recv_updates_citr"){
						$("#citr_update_yes").prop("checked",true);
					}else if(entry_index == "recv_updates_alumni"){
						$("#alumni_update_yes").prop("checked",true);
					}else{
						set(response[entry_index],entry_index);
					}
					$('#email_check').addClass('green');
				}
			},function(error){
				console.log(error);
			}
		);
	}//load

	function save(){
		get_form();

		if(id==null){
			var create_request = $.ajax({
			type:"PUT",
			url: "api2/public/fundrive/donor",
			dataType: "json",
			async: true
			});
			$.when(create_request).then(
				function(create_response){
					id = create_response.id;
					update(true);
				}
			);
		}else{
			update(false);
		}
	}
	function update(is_new){
		var update_request = $.ajax({
			type:"POST",
			url: "api2/public/fundrive/donor/"+id,
			dataType: "json",
			data: {'donor':donor},
			async: true
		});

		$.when(update_request).then(
			function(update_response){
				var conf = confirm('Success! Would you like to '+(is_new ?'submit':'edit')+' another?');
				if(conf == true){
					if(is_new) window.location.reload();
					else window.location.href = 'fundrive-open-form.php';
				}else{
					window.location.href ='main.php';
				}
			},function(error){
				alert('Fail')
			}
		);
	}
	function get_form(){
		donor.donation_amount = $('input[name="amount"]:checked').val();
		if(donor.donation_amount == 'other') donor.donation_amount = get('amount_other');
		if($('input[name="swag"]:checked').val() == 'swag'){
			donor.swag = 1;
			donor.tax_receipt = 0;
		}else{
			donor.swag = 0;
			donor.tax_receipt = 1;
		};
		donor.show_inspired = get("show_inspired");
		donor.prize = get("prize");

		donor.firstname = get("firstname");
		donor.lastname = get("lastname");
		donor.address = get("address");
		donor.city = get("city");
		donor.province = get("province");
		donor.postalcode = get("postalcode");
		donor.phonenumber = get("phonenumber");
		donor.email = get("email");

		donor.payment_method = $('input[name="payment_method"]:checked').val();
		donor.mail_yes = $('input[name="mailing"]:checked').val();
		donor.postage_paid = get("postage_paid");
		donor.recv_updates_citr = get("alumni_update_yes");
		donor.recv_updates_alumni = get("citr_update_yes");
		donor.donor_recognition_name = get(null,null,"recognize");
		switch($('input[name="recognize"]:checked').val()){
			case 'name':
				donor.donor_recognition_name = donor.firstname + " " + donor.lastname;
				break;
			case 'pseudonym':
				donor.donor_recognition_name = get('pseudonym');
				break;
			case 'anon':
				donor.donor_recognition_name = 'Anonymous';
				break;
			default:
				donor.donor_recognition_name = 'Anonymous';
				break;
		}

		donor.notes = get("notes");
		donor.paid = get("paid_status");
		donor.prize_picked_up = get("prize_picked_up");
	}
	$('#donor_submit').click(function(){
		save();
	})
	$('.amount').change(function(){
		if($(this).val() == 'other'){
			$('#amount_other').removeClass('invisible');
		}else{
			$('#amount_other').addClass('invisible');
		}
	});
	$('.mailing').change(function(){
		console.log($(this).val())
		if($(this).val() == '1'){
			$('.postage').removeClass('invisible');
		}else{
			$('.postage').addClass('invisible');
		}
	});
	$('.payment_method').change(function(){
		if( $(this).val() == 'cheque'){
			$('#cheque_option').removeClass('invisible');
			$('#mailing_option').removeClass('invisible');
		}else if( $(this).val() == 'cash'){
			$('#cheque_option').addClass('invisible');
			$('#mailing_option').removeClass('invisible');
		}else{
			$('#cheque_option').addClass('invisible');
			$('#mailing_option').addClass('invisible');
		}
	});
	$('.recognize').change(function(){
		if($(this).val() == 'pseudonym'){
			$('#pseudonym').removeClass('invisible');
		}else{
			$('#pseudonym').addClass('invisible');
		}
	});

	$('#email').on('keyup',function(){
		checkEmail();
	});
	$('#email').blur(function(){
		var div = $('#email_check');
		if(div.text() == 'Valid email'){
			div.addClass('invisible');
		}
	});


});
window.setInterval(checkBlocking,1000);
window.setInterval(checkEmail,1000);

function checkEmail(){

	var email = get('email');
	if(email.length == 0 || !$('#email').is(':focus')) return;
	else{
		var div = $('#email_check');
		var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.|[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|ca|uk|au|jp|de|fr|nz|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/;
		// ' here because some IDE cant handle regexes
		if(!re.test(email)){
			div.text('This is not a valid email');
			div.removeClass('invisible');
			div.removeClass('green');
			div.addClass('red');
		}else{
			div.text('Valid email');
			div.removeClass('invisible');
			div.removeClass('red');
			div.addClass('green');
		}
	}
}
function numbersonly(myfield, e, dec){
		var key;
		var keychar;

		if (window.event)
		   key = window.event.keyCode;
		else if (e)
		   key = e.which;
		else
		   return true;
		keychar = String.fromCharCode(key);

		// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) )
		   return true;

		// numbers
		else if ((("0123456789").indexOf(keychar) > -1))
		   return true;

		// decimal point jump
		else if (dec && (keychar == ".")) {
		   myfield.form.elements[dec].focus();
		   return false;
		   }
		else  return false;
	}

function checkBlocking(){
		var allOkay = true;
		$('.required').each( function(){
			if( !$.trim( $(this).val() )){
			allOkay=false;
			}
		});
		if( !$('#email_check').hasClass('green')){
			allOkay=false;
		}
		if($('input[name="mailing"]:checked').val() == '1'){
			if($('#postage_paid').val().length == 0){
				allOkay=false;
			}
		}
		if (allOkay){
		$('#donor_submit').attr('disabled',false);
		$('#donor_submit').text("Submit");
		$('#donor_submit').removeClass("red");
		}else{
			$('#donor_submit').attr('disabled',true);
			$('#donor_submit').text("Form Not Complete");
			$('#donor_submit').addClass("red");
		}
		console.log(allOkay);
	}
	function get(target_id,target_class,target_name){
		var target =  $( (target_id != null ? '#'+ target_id : "" ) + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name="+target_name+"]" : ""));
		var tag = target.prop('tagName');
		var result;
		switch(tag){
			case 'DIV':
				result = target.text();
				break;
			case 'INPUT':
				var type = target.attr('type');
				switch(type){
					case 'checkbox':
						if(target.prop('checked')) result = 1;
						else result = 0;
						break;
					default:
						result = target.val();
						break;
				}
				break;
			case 'SELECT':
			case 'TEXTAREA':
				result = target.val();
				break;
			default:
				result = target.val();
				break;
		}
		return result;
	}
	function set(value,target_id,target_class,target_name){
		var target =  $( (target_id != null ? '#'+ target_id : "" ) + (target_class != null ? "." + target_class : "") + (target_name != null ? "[name="+target_name+"]" : ""));
		var tag = target.prop('tagName');
		//console.log("Value:"+value+" Target:"+target.attr('id') + "," +target.attr('class') + "," +target.attr('name')+" Tag:"+tag);
		switch(tag){
			case 'DIV':
				target.text(value);
				break;
			case 'SELECT':
				target.val(value).change();
				break;
			case 'INPUT':
				var type = target.attr('type');
				switch(type){
					case 'checkbox':
						if(value == '1'){
							target.prop('checked',true);
						}else{
							target.prop('checked',false);
						}
						break;
					default:
						target.val(value).change();
						break;
				}
				break;
			case 'TEXTAREA':
			default:
				target.val(value).change();
				break;
		}
	}
