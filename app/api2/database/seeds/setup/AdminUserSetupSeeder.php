<?php

use Illuminate\Database\Seeder;

use App\Member;
use App\MembershipYear;
use App\User;
use App\Permission;
use Carbon\Carbon;

class AdminUserSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member = Member::create(array(
            'lastname' => 'User',
            'firstname' => 'Administrative',
            'canadian_citizen' => '1',
            'address' => '',
            'city' => '',
            'province' => 'Other',
            'postalcode' => 'A1B 2CD',
            'member_type' => 'Staff',
            'is_new' => 0,
            'alumni' => 0,
            'since' => Carbon::now()->year - 1 . '/' . Carbon::now()->year,
            'faculty' => '',
            'primary_phone' => '',
            'email' => ''
        ));
        MembershipYear::create(array(
            'member_id' => $member['id'],
            'membership_year' => Carbon::now()->year - 1 . '/' . Carbon::now()->year,
            'paid' => 1,
            //leave the rest to default values
        ));
        User::create(array(
            'member_id' => $member['id'],
            'username' => 'Admin',
            'password' => '$2y$10$hza8F199V5ADR2yRdXXbs.bs6zOi5.CJeqDJYzyT3yc7/2LSfqePu', //1234
            'status' => 'enabled',
            'login_fails' => '0'
        ));
        Permission::create(array(
            'user_id' => 1,
            'operator' => 1
            //All other group flags default to zero, so don't have to explicitly set them
        ));
    }
}
