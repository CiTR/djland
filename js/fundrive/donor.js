$(document).ready ( function() {

	function save(){
		var donor = {};
		donor.donation_amount = get(null,null,"amount");
		if(donor.donation_amount == 'other') donor.donation_amount = get('amount_other');
		if($('input[name="swag"]:checked').val() == 'swag'){
			donor.swag = 1;
			donor.tax_receipt = 0;
		}else{
			donor.swag = 0;
			donor.tax_receipt = 1;
		};
		donor.show_inspired = get("fundrive_showname");
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
		if(donor.donor_recognition_name =='name') donor.donor_recognition_name = donor.firstname + " " + donor.lastname;
		else if(donor.donor_recognition_name == 'pseudonym') donor.donor_recognition_name = get('pseudonym');
		else{ donor.donor_recognition_name == 'anonymous';}

		donor.notes = get("notes");
		donor.paid = get("paid_status");
		donor.prize_picked_up = get("prize_picked_up");

		var create_request = $.ajax({
			type:"PUT",
			url: "api2/public/fundrive/donor",
			dataType: "json",
			async: true
		});
		$.when(create_request).then(
			function(create_response){
				var update_request = $.ajax({
					type:"POST",
					url: "api2/public/fundrive/donor/"+create_response.id,
					dataType: "json",
					data: {'donor':donor},
					async: true
				});

				$.when(update_request).then(
					function(update_response){
						var conf = confirm('Success! Would you like to submit another?');
						if(conf == true){
							window.location.reload();
						}else{
							window.location.href ='main.php';
						}
					},function(error){
						alert('Fail')
					}
				);
			},function(error){

			}
		);
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
});
window.setInterval(checkBlocking,1000);

function checkBlocking(){
		var allOkay = true;
		$('.required').each( function(){
			if( !$.trim( $(this).val() )){
			allOkay=false;
			}
		});
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
