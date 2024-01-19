<?php

use Illuminate\Database\Seeder;
use App\Modules\Menus\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'About Us',
            'url_internal' => null,
            'url_external' => 'about-us',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Find a Program',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-home',
            'target'       => 0,
            'order'        => 2,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 2,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Business Management',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-home',
            'target'       => 1,
            'order'        => 0,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 2,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Engineering',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-home',
            'target'       => 1,
            'order'        => 2,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 2,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Information Technology',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-home',
            'target'       => 1,
            'order'        => 2,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Requirements TNI',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Scholarship',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Where we are',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Good Reason to study at TNI',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
        Menu::create([
            'parent_id'    => 0,
            'user_id'      => 1,
            'post_id'      => null,
            'title'        => 'Contact Us',
            'url_internal' => null,
            'url_external' => '#',
            'video'        => null,
            'summary'      => null,
            'icon'         => 'fa fa-users',
            'target'       => 0,
            'order'        => 1,
            'status'       => 'publish',
        ]);
    }
}
