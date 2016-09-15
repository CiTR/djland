<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
	protected $table = 'fundrive_donors';
  protected $fillable = array( 'firstname', 'lastname', 'address', 'city', 'province', 'postalcode', 'country', 'phonenumber', 'email', 'donation_amount', 'swag','tax_receipt', 'show_inspired', 'prize', 'mail_yes','payment_method','postage_paid', 'recv_updates_citr', 'recv_updates_alumni', 'donor_recognition_name', 'LP_yes', 'LP_amount','notes', 'paid', 'prize_picked_up');

}
