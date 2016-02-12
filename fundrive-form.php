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
'1,000'=>'all the things + recognition on our donor wall in the new SUB');
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
$shows = array();
$api_base = 'http://'.$_SERVER['HTTP_HOST'];
$shows = CallAPI('GET',$api_base.'/api2/public/show/active');
?>

<div class='wrapper side-padded'>
	<h1 class='double-padded-top'> Fundrive Form </h1>
	<hr>
	<h4>Thank you for calling the CiTR Fundrive pledge line! My name is __________. </h4>

	<div class='col1 double-padded-top'>
	    <div class='col1'>How much would you like to donate?:</div>

		<select id="amount" name='donation_amount'>
		<?php foreach($fundrive_amount as $amount=>$text): ?>
    		<option value='<?php echo $amount; ?>'><?php echo $amount."$ - ".$text; ?></option>
		<?php endforeach; ?>
			<option value='other'>Other</option>
		</select>
		<input id='amount_other' class='invisible' placeholder='enter $ amount'>

	</div>

	<div class='col1 double-padded-top'>
	    <div class='col1'>Would you like swag or a tax reciept?</div>
			<select name='swag'>
				<option value='1'>Swag</option>
				<option value='0'>Tax Receipt</option>
			</select>
	</div>

	<div class='col1 double-padded-top'>
	    <div class='col1'>Was your gift inspiried by a specific show? If yes:</div>
	    <select id='fundrive_showname' name='show_inspired'>
	    <?php foreach($shows as $show): ?>
	        <option value="<?php echo $show->id; ?>"><?php echo $show->name; ?></option>
	    <?php endforeach; ?>
	    </select>
	</div>


	<div class='col1 double-padded-top double-padded-bottom'>
		<div class='col1'> By calling in on [show name], and donating x dollars, you also win _____ (please indicate in the space below what the prize is): </div>
		<textarea id='prize' class='largeinput' name='prize' placeholder='Prize'></textarea>
	</div>

	<div class='text-center double-padded-top'>Now I'll need to take down your contact information:</div>

	<hr/>
	<h2>Contact Information</h2>
	<hr/>

    <div class='containerrow'>
    	<div class='col5'>First Name*: </div>
    	<div class='col5'><input id='firstname' class='required' placeholder='First name' maxlength='30'></input></div>
    	<div class='col5'>Last Name*: </div>
    	<div class='col5'><input id='lastname' class='required' placeholder='Last name' maxlength='30'></input></div>
    </div>
    <div class='containerrow'>
    	<div class='col5'>Address*: </div>
    	<div class='col5'><input id='address' class='required' placeholder='Address' maxlength='50'></input></div>
    	<div class='col5'>City*:</div>
		<div class='col5'><input id='city' class='required' value='Vancouver' maxlength='45'></input></div>
    </div>
    <div class='containerrow'>
    	<div class='col5'>Province*: </div>
    	<div class='col5'><select id='province'>
        <?php
        	foreach($djland_provinces as $key=>$province){
            echo "<option value='{$province}'>{$province}</option>";
        	}
        ?>
        </select></div>
    	<div class='col5'>Postal Code*:</div>
    	<div class='col5'><input id='postalcode' class='required' placeholder='Postal Code' maxlength='6'></input></div>
    </div>
    <div id='row4' class='containerrow'>
    	<div class='col5'>Primary Number*:</div>
    	<div class='col5'><input id='primary_phone' class='required' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
    	<div class='col5'>Email Address*: </div>
    	<div class='col5'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'></input><div id='email_check' class='text-center invisible'></div></div>
    </div>

	<hr>
	<div class='col1 double-padded-top'>
		<div class='col1'> How would you like to pay? </div>
		<select id='payment_method'>
			<option value='credit_card'>Credit Card</option>
			<option value='cheque'>Drop of or mail in a check</option>
			<option value='cash'>Drop off cash</option>
		</select>
		<div id='cheque' class='invisible'>The check is payable to UBC.</div>
		<div id='mailing' class='invisible'>Our address is LL500 6133 University Blvd, Van BC V6T 1Z1</div>
	</div>

	<div class='col1 double-padded-top'>
		<div class='col1'> Would you like your prize mailed to you? Please be aware that postage costs <a href='https://www.canadapost.ca/cpotools/apps/far/business/findARate' target='_blank'>Shipping Calculator</a>. </div>
		<select id='mailing' name='mail_yes'>
			<option value='1'>Yes</option>
			<option value='0'>No</option>
		</select>
	</div>
	<div class='col1 double-padded-top'>
		<div class='col1'>Would you like to receive updates (e.g. newsletters, invitations, updates, and fundraising) from:</div>
		<div class='col1'><input type='checkbox' id='citr_update_yes'><label for='citr_update_yes'>CiTR</label></div>
		<div class='col1'><input type='checkbox' id='alumni_update_yes'><label for='alumni_update_yes'>UBC Development and Alumni Engagement?</label></div>
		You can withdraw your consent at any time.
	</div>
	<div class='col1 double-padded-top double-padded-bottom'>
		<div class='col1'> CiTR will be recognizing donors on our website, in our annual report and in Discorder Magazine. How would you like your name to be listed? </div>
		<select id='recognize' name='recognize'>
			<option 'name'>Use my name</option>
			<option 'pseudonym'>Use my pseudonym</option>
			<option 'anon'>Anonymous</option>
		</select>
		<input id='pseudonym' class='invisible'>
	</div>

	<hr>
	<div class='col1 double-padded-bottom double-padded-top'>
			<div class='double-padded-top'>
				Thank you for donating to CiTR's Fundrive! We really appreciate it.
			</div>
			<div class='double-padded-top'>
				This year our Fundrive Finale is on Friday, March 4 at the hindenburg. The event is also a release party for the LP we're putting out with mint records. We hope to see you there! (more info at citr.ca)
			</div>
			<div class='swag invisible double-padded-top'>
				If chose swag, you can pick up your prizes between 9 am and 11 pm during the Fundrive, 11 - 5 pm weekdays after the drive and a few evenings and weekends that we'll send you by email. All prizes must be picked up by April 30!
			</div>
			<div class='double-padded-top'>
				Thank you again for donating to CiTR's Fundrive! Your donation makes a huge difference to the CiTR Community!
			</div>
	</div>
	<hr>
	<div class='col1 double-padded-top'>
    	<div class='col6'>Notes/Extra Stuff:</div>
    	<textarea id='about' class='largeinput' placeholder='Text here'rows='3'></textarea>
	</div>


	<div class='col1 text-center'> Has this person paid? <input type='checkbox' id='paid_status'> </div>
	<div class='col1 text-center'>Has this person picked up the prize yet?<input type='checkbox' id='prize_status'></div>

	<div class='containerrow'>
    	<center>
    	<button id='donor_submit' class='red' disabled='true'>Form Not Complete</button>
    	<br>* indicates a required field
    	</center>
	</div>
	<div class='containerrow'> <br/> </div>

</div>
</body></html>
