<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();

        factory(App\User::class, 50)->create()->each(function ($user) use ($roles) {
            $user->assignRole($roles->random());
        });

        $first = App\User::first();
        $first->email = 'technicalmanager@citr.ca'; // Hey, that's my email!
        $first->syncRoles($roles);
        $first->save();
    }
}
