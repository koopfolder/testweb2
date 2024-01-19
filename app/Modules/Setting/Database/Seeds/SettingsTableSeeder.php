<?php

use Illuminate\Database\Seeder;
use App\Modules\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'name'       => 'Register',
            'name'       => 'register',
            'value'      => 'http://google.com',
			'module'     => 'online',
            'input_type' => 'text',
            'status'     => 'publish',
        ]);
    }
}
