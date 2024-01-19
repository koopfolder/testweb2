<?php

use Illuminate\Database\Seeder;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'user_id'          => 1,
            'title'            => 'Home',
            'excerpt'          => 'Home Page',
            'content'          => 'Home Page',
            'type'             => 'page',
            'is_home'          => 1,
            'view'             => 99123,
            'layout'           => 'home',
            'meta_keywords'    => 'TNI',
            'meta_description' => 'TNI',
            'status'           => 'publish'
        ]);

        Post::create([
            'user_id'          => 1,
            'title'            => 'Thai-Nichi Institute of Technology',
            'excerpt'          => '<p>177/1 Pattanakarn Rd.</p><p>Suanluang, Bangkok 10250, Thailand</p><p>Tel: +66-(02)-763-2600</p><p>Fax: +66-(02)</p>',
            'content'          => '<p>177/1 Pattanakarn Rd.</p><p>Suanluang, Bangkok 10250, Thailand</p><p>Tel: +66-(02)-763-2600</p><p>Fax: +66-(02)</p>',
            'type'             => 'address',
            'is_home'          => 0,
            'link'             => 'https://line.me/en-US/',
            'layout'           => 'default',
            'meta_keywords'    => 'TNI',
            'meta_description' => 'TNI',
            'status'           => 'publish'
        ]);

        
    }
}
