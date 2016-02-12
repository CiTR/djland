<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
	protected $table = 'fundrive_donors';
    protected $fillable = array( 'firstname', 'lastname', 'address', 'city', 'province', 'postalcode', 'phonenumber', 'email', 'donation_amount', 'swag', 'show_inspired', 'prize', 'mail_yes','postage_paid', 'recv_updates_citr', 'recv_updates_alumni', 'donor_recognition_name', 'notes', 'paid', 'prize_picked_up');

}
