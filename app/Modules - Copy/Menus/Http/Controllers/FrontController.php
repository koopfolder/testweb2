<?php

namespace App\Modules\Menus\Http\Controllers;

use Illuminate\Http\request;
use App\Http\Controllers\Controller;
use App\Modules\Menus\Models\Menu;
use App\Modules\Room\Models\Room;
use Gmopx\LaravelOWM\LaravelOWM;
use Carbon\Carbon;

class FrontController extends Controller
{
    public static function menuList($request)
    {
        $currentPath = $request->path();
        //dd($currentPath);
        $menus = Menu::where('status', 'publish')->where('position', 'header')->where('site', 'frontend')->where('parent_id', 0)->orderBy('order', 'ASC')->get();
        
        $list = [];

        if ($menus->isNotEmpty()) {
            foreach ($menus as $menu) {
                $list[$menu->id]['name'] = $menu->name;
                $list[$menu->id]['active'] = false;
                if ($menu->url_external) {
                    $list[$menu->id]['url'] = $menu->url_external;
                } else {
                    $list[$menu->id]['url'] = (new \App\Modules\Menus\Http\Controllers\FrontController)->customUrl($menu);
                    $list[$menu->id]['active'] = $menu->slug == $currentPath ? true : false;
                }
                
                if ($currentPath == '/') {
                    $currentPath = 'Home';
                    $list[$menu->id]['active'] = $menu->slug == $currentPath ? true : false;
                }

                $list[$menu->id]['image'] = $menu->getMedia('desktop')->isNotEmpty() ? asset($menu->getMedia('desktop')->first()->getUrl()) : null;
                $list[$menu->id]['target'] = $menu->target;
                $list[$menu->id]['childrens'] = $menu->children()->get();
            }
        }
        return collect($list);
    }

    public function customUrl($menu)
    {
        switch ($menu->layout) {
            case 'room-detail':
                $room = Room::find($menu->module_ids);
                $url  = url('room/' . $room->slug);
                break;
            default:
                $url = url($menu->slug);
                break;
        }
        return $url;
    }

    public static function childrens($request, $menus)
    {
        $currentPath = $request->path();
        $filtered = $menus->filter(function($query) {
            return $query->status == 'publish';
        });
        
        $list = [];
        if ($filtered->isNotEmpty()) {
            foreach ($filtered as $menu) {
                $list[$menu->id]['name'] = $menu->name;
                $list[$menu->id]['active'] = false;
                if ($menu->url_external) {
                    $list[$menu->id]['url'] = $menu->url_external;
                } else {
                    $list[$menu->id]['url'] = (new \App\Modules\Menus\Http\Controllers\FrontController)->customUrl($menu);
                    $list[$menu->id]['active'] = $menu->slug == $currentPath ? true : false;
                }
                $list[$menu->id]['image'] = $menu->getMedia('desktop')->isNotEmpty() ? asset($menu->getMedia('desktop')->first()->getUrl()) : null;
                $list[$menu->id]['target'] = $menu->target;
                $list[$menu->id]['childrens'] = $menu->children()->get();
            }
        }
        return collect($list);
    }

    public static function getLinkByPosition($position = null)
    {
        $list = [];
        if (!is_null($position)) {
            $menus = Menu::where('status', 'publish')->where('parent_id', 0)->where('position', $position)->orderBy('order', 'ASC')->get();
            if ($menus) {
                foreach ($menus as $menu) {
                    $list[$menu->id]['name'] = $menu->name;
                    $list[$menu->id]['link'] = url($menu->slug);
                }
            }
        }
        return collect($list);
    }

    public static function weather()
    {
        $lowm = new LaravelOWM();
        $currentWeather = $lowm->getCurrentWeather('Manama, BH');
        $time = date("H:i", Carbon::now('Asia/Bahrain')->timestamp);
        return "<span style='color: #fad862;'>Weather:</span> {$currentWeather->city->name} {$currentWeather->city->country}, {$currentWeather->temperature->now} ".  
                "<span style='color: #fad862; margin-left:5px;'>Local Time:</span> {$time} AM";
    }


}
