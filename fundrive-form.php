<?php
include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

$fundrive_amount = array(
'30'=>'Friends of CiTR Card',
'60'=>'Friends of CiTR card + CiTR growler',
'101.9'=>'Friends of CiTR card + CiTR growler + CiTR notebook',
'175'=>'Friends of CiTR card + growler + notebook + LP + tote bag',
'250'=>'Friends of CiTR card + growler + notebook + LP + tote bag + framed discorder cover',
'500'=>'all the things + host a show on citr!',
'1,000'=>'all the things + recognition on our donor wall in the new SUB'
);
$payment_options = array(
	'Credit Card'=>'credit_card',
	'Dropping off or mailing in a Cheque - payable to UBC, mail to LL500 6133 University Blvd, Van BC V6T 1Z1'=>'cheque',
	'Dropping off Cash - visit LL500 6133 University Blvd, Van BC V6T 1Z1'=>'cash');
$mailing_options = array(
    'Yes'=>'mail_yes',
    'No'=>'mail_no');
$donor_recognition_options = array(
    'Yes - use my name'=>'recognize_yes',
    'Yes - use my other name'=>'recognize_no',
    'No - please leave me as anonymous'=>'recognize_anon');
$custom_province_order = array(
	'BC',
	'AB',
	'MAN',
	'NB',
	'NFL',
	'NS',
	'NVT',
	'NWT',
	'ONT',
	'QUE',
	'SASK',
	'YUK');

?>

<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<link rel=stylesheet href=css/style.css type=text/css>
<title>DJLAND | Fundrive Form</title>
<script src='js/jquery-1.11.3.min.js'></script>
<script src='js/fundrive/donor.js'></script>
<script src="js/library-js.js"></script>
</head><body class='wallpaper'>



<?php print_menu();

if(permission_level() < $djland_permission_levels['staff']['level'] || $_SESSION['sv_username'] != 'fundrive'){
	header("Location: main.php");
}
$shows = array();
$api_base = 'http://'.$_SERVER['HTTP_HOST'];
$shows = CallAPI('GET',$api_base.'/api2/public/show/active');
?>
<script>
var id_in = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
</script>



<div class='wrapper donor_form side-padded big_text'>
	<h1 class='double-padded-top'> Fundrive Donation Form </h1>
	<h3 id="total"></h3>
	<hr>

	<div class='col1'>Thank you for calling the CiTR Fundrive pledge line! My name is __________.</div>

	<div class='col1 double-padded-top'>
	    <div class='col1'>How much would you like to donate?:</div>
		<ul class='clean-list'>
		<?php foreach($fundrive_amount as $amount=>$text): ?>
    		<li>
				<input id='amount_<?php echo $amount; ?>' value='<?php echo $amount; ?>' name='amount' type='radio' class='amount' <?php if($amount == 30) echo 'checked'; ?>>
				<label for='amount_<?php echo $amount; ?>'>
					<?php echo "$".$amount." - ".$text; ?>
				</label>
			</li>
		<?php endforeach; ?>
			<li><input id='amount_alt' value='other' type='radio' name='amount' class='amount'><label for='amount_alt'> Other</label></li>
			<li><input id='amount_other' class='invisible big_text' placeholder='enter $ amount' onKeyPress="return numbersonly(this, event)"></li>
		</ul>

	</div>

	<div class='col1 double-padded-top big_text'>
	    <div class='col1'>Would you like swag or a tax receipt?</div>
		<input id='swag' value='swag' type='radio' name='swag' class='swag' checked ><label for='swag'>Swag</label>
		<input id='tax_receipt' value='tax_receipt' type='radio' name='swag' class='swag'><label for='tax_receipt'>Tax Receipt</label>
	</div>

	<div class='col1 double-padded-top'>
	    <div class='col1'>Was your gift inspiried by a specific show? If yes:</div>
	    <select id='show_inspired' name='show_inspired' class='big_text'>
	    	<option value=""/>
	    <?php foreach($shows as $show): ?>
	        <option value="<?php echo $show->name; ?>"><?php echo $show->name; ?></option>
	    <?php endforeach; ?>
	    </select>
	</div>


	<div class='col1 double-padded-top double-padded-bottom'>
		<div class='col1'> By calling in on [show name], and donating x dollars, you also win _____ (please indicate in the space below what the prize is): </div>
		<textarea id='prize' class='largeinput big_text' name='prize' placeholder='Prize'></textarea>
	</div>


	<hr/>
	<h2>Contact Information</h2>
	<hr/>

		<div class='text-center double-padded-top double-padded-bottom'>Now I'll need to take down your contact information:</div>
    <div class='containerrow'>
		<div class='col2'>
			<div class='col5'>First Name:</div>
			<input id='firstname' class='required wideinput big_text' placeholder='First name' maxlength='30'></input>
		</div>
		<div class='col2'>
    		<div class='col5'>Last Name:</div>
    		<input id='lastname' class='required wideinput big_text' placeholder='Last name' maxlength='30'></input>
		</div>
    </div>
    <div class='containerrow'>
		<div class='col2'>
    		<div class='col5'>Address:</div>
    		<input id='address' class='required wideinput big_text' placeholder='Address' maxlength='50'></input>
		</div>
    	<div class='col2'>
			<div class='col5'>City:</div>
			<input id='city' class='required wideinput big_text' value='Vancouver' maxlength='45'></input>
		</div>
    </div>
    <div class='containerrow'>
		<div class='col2'>
    		<div class='col5'>Province: </div>
    		<select id='province' class='big_text'>
	        <?php
	        	foreach($custom_province_order as $key=>$province){
	            echo "<option value='{$province}'>{$province}</option>";
	        	}
	        ?>
	        </select>
		</div>
		<div class='col2'>
	    	<div class='col5'>Postal Code:</div>
	    	<input id='postalcode' class='required wideinput big_text' placeholder='Postal Code' maxlength='6'></input>
		</div>
    </div>
    <div id='row4' class='containerrow'>
    	<div class='col2'>
			<div class='col5'>Phone:</div>
    		<input id='phonenumber' class='required wideinput big_text' placeholder='Phone Number' maxlength='12' onKeyPress="return numbersonly(this, event)"></input>
		</div>
    	<div class='col2'>
			<div class='col5'>Email : </div>
    		<input id='email' class='required wideinput big_text'  name='email' placeholder='Email Address' maxlength='40'></input><div id='email_check' class='text-center invisible'>
		</div>
    </div>
</div>

	<hr>
	<div class='col1 double-padded-top'>
		<div class='col1'> How would you like to pay? </div>
		<input id='payment_0' name='payment_method' class='payment_method' type='radio' value='credit_card' checked><label for='payment_0'>Credit Card</label>
		<input id='payment_1' name='payment_method' class='payment_method' type='radio' value='cheque'><label for='payment_1'>Drop off or mail in a check</label>
		<input id='payment_2' name='payment_method' class='payment_method' type='radio' value='cash'><label for='payment_2'>Drop off cash</label>

		<div id='cheque_option' class='invisible'>The check is payable to UBC.</div>
		<div id='mailing_option' class='invisible'>Our address is LL500 6133 University Blvd Vancouver BC Canada V6T 1Z1</div>
	</div>

	<div class='col1 double-padded-top'>
		<div class='col1'> Would you like your prize mailed to you? Please be aware that postage costs are as follows... (Look at postage cost chart)</div>
		<div class='col1'>
			<input id='postage_1' name='mailing' class='mailing' type='radio' value='1' checked><label for='postage_1'>Yes</label>
			<input id='postage_2' name='mailing' class='mailing' type='radio' value='0'><label for='postage_2'>No</label>
		</div>
		<div class='postage'><label for='postage_paid'>Postage Amount </label><input id='postage_paid' placeholder='Enter Postage Amount' class='big_text'>$</div>
	</div>
	<div class='col1 double-padded-top'>
		<div class='col1'>Would you like to receive updates (e.g. newsletters, invitations, updates, and fundraising) from:</div>
		<div class='col1'><input type='checkbox' id='citr_update_yes'><label for='citr_update_yes'>CiTR</label></div>
		<div class='col1'><input type='checkbox' id='alumni_update_yes'><label for='alumni_update_yes'>UBC Development and Alumni Engagement?</label></div>
		You can withdraw your consent at any time.
	</div>
	<div class='col1 double-padded-top double-padded-bottom'>
		<div class='col1'> CiTR will be recognizing donors on our website, in our annual report and in Discorder Magazine. How would you like your name to be listed? </div>

		<input id='recognize_0' type='radio' name='recognize' class='recognize' value='name' checked><label for='recognize_0'>Use my full name</label>
		<input id='recognize_1' type='radio' name='recognize' class='recognize' value='pseudonym'><label for='recognize_1'>Use my pseudonym</label>
		<input id='recognize_2' type='radio' name='recognize' class='recognize' value='anon'><label for='recognize_2'>Anonymous</label>
		<input id='pseudonym' class='invisible wideinput big_text' placeholder='Enter Pseudonym'>
	</div>
	<hr>
	<div class='col1 double-padded-bottom'>
			<div class='double-padded-top'>
				Thank you for donating to CiTR's Fundrive! We really appreciate it.
			</div>
			<div class='double-padded-top'>
				This year our Fundrive Finale is on Friday, March 4 at the Hindenburg. The event is also a release party for the LP we're putting out with Mint Records. We hope to see you there! (more info at citr.ca)
			</div>
			<div class='double-padded-top'>
				If you chose swag, you can pick up your prizes between 9 am and 11 pm during the Fundrive, 11 - 5 pm weekdays after the drive and a few evenings and weekends that we'll send you by email. All prizes must be picked up by April 30!
			</div>
			<div class='double-padded-top'>
				Thank you again for donating to CiTR's Fundrive! Your donation makes a huge difference to the CiTR Community!
			</div>
	</div>
	<hr>
	<div class='col1 double-padded-top double-padded-bottom'>
    	<div class='col6'>Notes/Extra Stuff:</div>
    	<textarea id='notes' class='largeinput big_text' placeholder='Text here'rows='3'></textarea>
	</div>

	<hr/>
	<div class='col1 double-padded-top'> Has this person paid? <input type='checkbox' id='paid_status'> </div>
	<div class='col1'>Has this person picked up the prize yet?<input type='checkbox' id='prize_picked_up'></div>

	<div class='containerrow'>
    	<center>
    	<button id='donor_submit' class='red' disabled='true'>Form Not Complete</button>
    	</center>
	</div>
	<div class='containerrow'> <br/> </div>

</div>
</body></html>
