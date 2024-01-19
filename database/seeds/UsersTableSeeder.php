<?php

use Illuminate\Database\Seeder;
use App\User;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ability::create(['name' => 'users-manage']);
        Ability::create(['name' => 'users-create']);
        Ability::create(['name' => 'users-edit']);
        Ability::create(['name' => 'users-delete']);
        Ability::create(['name' => 'users-export']);

        $role = Role::create(['name' => 'Administrator']);
        $role->allow(['users-manage', 'users-create', 'users-edit', 'users-delete', 'users-export']);
        $role = Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Customer']);
        
        User::create([
            'name'     => 'Itorama',
            'email'    => 'itorama@431.com',
            'status'   => 'publish',
            'password' => bcrypt('11111111'),
        ]);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'status'   => 'publish',
            'password' => bcrypt('secret'),
        ]);

    }
}
