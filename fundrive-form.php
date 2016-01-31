<?php
include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

$fundrive_amount = array(
'$30 - Friends of CiTR Card'=>'donate30',
'$60 - Friends of CiTR card + CiTR growler'=>'donate60',
'$101.9 - Friends of CiTR card + CiTR growler + CiTR notebook'=>'donate101.9',
'$175 - Friends of CiTR card + growler + notebook + LP + tote bag'=>'donate175',
'$250 - Friends of CiTR card + growler + notebook + LP + tote bag + framed discorder cover'=>'donate250',
'$500 - all the things + host a show on citr!'=>'donate500',
'$1,000 - all the things + recognition on our donor wall in the new SUB'=>'donate1000',
"Other"=>"other");

$swag_options = array(
  'Swag'=>'swag',
  'Tax Reciept (for donations $10 and more)'=>'tax_reciept');

$payment_options = array(
  'Credit Card'=>'cc',
  'Dropping off or mailing in a Cheque - payable to UBC, mail to LL500 6133 University Blvd, Van BC V6T 1Z1'=>'cheque',
  'Dropping off Cash - visit LL500 6133 University Blvd, Van BC V6T 1Z1'=>'cash');
?>

<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<link rel=stylesheet href=css/style.css type=text/css>"
<title>DJLAND | Fundrive Form</title>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/library-js.js"></script>
</head><body class='wallpaper'>

<?php print_menu(); ?>

<div id='membership' class='wrapper' >
  <h1 class='double-padded-top double-padded-bottom'> Fundrive Form </h1>
  <hr>
  <div class ='col1'>
    <div class=text-center><b>Thank you for calling the CiTR Fundrive pledge line! My name is ____ </b></div>
  </div>
    </br>
  <div class='container'>
    <div class='col5'>How much would you like to donate?:</div>
    <div class='span4col5'>
      <?php foreach($fundrive_amount as $key=>$amount): ?>
      <div class='col1 text-left'>
        <?php if($amount == 'other'): ?>
        <input id='<?php echo $amount ?>' placeholder='Enter amount' maxlength='40'/>
        <?php else: ?>
        <input type='checkbox' id='<?php echo $amount; ?>'>
        <?php endif; ?>
        <label for='<?php echo $amount ?>'><?php echo $key; ?></label>
      </div>
      <?php endforeach; ?>
    </div>
    <div class='col6'>Would you like swag or a tax reciept?</div>
    <div class='span3col4'>
      <?php foreach($swag_options as $key=>$option): ?>
      <div class='col1 text-right'>
        <label for='<?php echo $option ?>'><?php echo $key; ?></label>
        <input type='checkbox' id='<?php echo $option; ?>'>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
    <div class='containerrow'>
      <div class='col16'>Would you like to support a specific show?</div>
      <div class='col5'>If yes: </div>
      <div class='col5'><input id='fundrive_showname' class='required' placeholder='Show Name' maxlength='30'></input></div>
    </div>
    <div class='containerrow'> (If they choose swag) By calling in on [show name], and donating x dollars, you also win _____ (please indicate in the space below what the prize is):
      <div class='col5'><input id='fundrive_promised_gift' class='required' placeholder='Prize' maxlength='30'></input></div>
    </div>
    <div class='containerrow'>Now I'll need to take down your contact information:</div>

  <hr>
  <h2>Contact Information</h2>
  <hr>

  <div class = 'container'>
    <div id='row1' class='containerrow'>
      <div class='col5'> Description: </div>
      <div class='col5'><input id='firstname' class='required' placeholder='Description' maxlength='30'></input></div>
    </div>
    <div id='row2' class='containerrow'>
      <div class='col5'>First Name*: </div>
      <div class='col5'><input id='firstname' class='required' placeholder='First name' maxlength='30'></input></div>
      <div class='col5'>Last Name*: </div>
      <div class='col5'><input id='lastname' class='required' placeholder='Last name' maxlength='30'></input></div>
    </div>
    <div id='row3 'class='containerrow'>
      <div class='col5'>Address*: </div>
      <div class='col5'><input id='address' class='required' placeholder='Address' maxlength='50'></input></div>
      <div class='col5'>City*:</div>
      <div class='col5'><input id='city' class='required' placeholder='City' maxlength='45'></input></div>
    </div>
    <div id='row4 'class='containerrow'>
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
    <div id='row5' class='containerrow'>
      <div class='col5'>Primary Number*:</div>
      <div class='col5'><input id='primary_phone' class='required' placeholder='Phone Number' maxlength='10' onKeyPress="return numbersonly(this, event)"></input></div>
      <div class='col5'>Email Address*: </div>
      <div class='col5'><input id='email' class='required'  name='email' placeholder='Email Address' maxlength='40'></input><div id='email_check' class='text-center invisible'></div></div>
    </div>
  </div>

  <div class='col1'><hr></div>

  <div class='col14'> How would you like to pay? </div>
  <div class='span3col4'>
    <?php foreach($payment_options as $key=>$option): ?>
    <div class='col13 text-right'>
      <input type='checkbox' id='<?php echo $option; ?>'>
      <label for='<?php echo $option ?>'><?php echo $key; ?></label>
    </div>
    <?php endforeach; ?>
  </div>

  <div class='col1'><hr></div>

  <br>
  <div class='col1'>
      Would you like your prize mailed to you? Please be aware that postage costs ___.
      (yes/no button)
      <br>
      CiTR will be recognizing donors on our website, in our annual report and in Discorder Magazine. How would you like your name to be listed? (anonymous is also an option)
      (yes/no/anonymous)
      <br>
  </div>
  <hr>
</br>
  <div class='col1'>
      Thank you for donating to CiTR's Fundrive! We really appreciate it.
      <br>
      This year our Fundrive Finale is on Friday, march 4 at the hindenburg. The event is also a release party for the LP we're putting out with mint records. We hope to see you there! (more info at citr.ca)
      <br>
      If chose swag, you can pick up your prizes between 9 am and 11 pm during the Fundrive, 11 - 5 pm weekdays after the drive and a few evenings and weekends that we'll send you by email. All prizes must be picked up by April 30!
      <br>
  </div>
</br>
  <div class='col1'>
      <div class='col6'>Notes/Extra Stuff:</div>
      <textarea id='about' class='largeinput' placeholder='Text here'rows='3'></textarea>
  </div>

  <br/>

  PAID?

  <div class='containerrow'>
    <center>
      <button id='submit_user' class='red' disabled='true'>Form Not Complete</button>
      <br>* indicates a required field
    </center>
  </div>
  <div class='containerrow'>
    <br/>
  </div>


</div>
</body></html>
