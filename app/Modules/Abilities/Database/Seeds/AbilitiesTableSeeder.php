<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\Database\Ability;

class AbilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Ability::create([
			'name' => 'users-manage',
		]);
    }
}
