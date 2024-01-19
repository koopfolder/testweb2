<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'user_id' => 1,
            'title'   => 'ข่าวสารและงานอีเว้นท์', 
            'summary' => 'ข่าวสารและงานอีเว้นท์', 
            'status'  => 'publish',
            'module'  => 'news'
        ]);
        Category::create([
            'user_id' => 1,
            'title'   => 'Business Management',
            'status'  => 'publish',
            'module'  => 'program'
        ]);
        Category::create([
            'user_id' => 1,
            'title'   => 'Engineering',
            'status'  => 'publish',
            'module'  => 'program'
        ]);
        Category::create([
            'user_id' => 1,
            'title'   => 'Information Technology',
            'status'  => 'publish',
            'module'  => 'program'
        ]);
    }
}
