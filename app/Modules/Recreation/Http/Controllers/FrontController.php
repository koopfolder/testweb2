<?php

namespace App\Modules\Recreation\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Recreation\Models\Recreation;
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getRecreations()
    {
        $agent = new Agent();

        $list = [];
        $recreations = Recreation::where('status', 'publish')->get();
        if ($recreations->isNotEmpty()) {

            foreach($recreations as $recreation) {
                $list[$recreation->id]['name']        = $recreation->name;
                $list[$recreation->id]['description'] = $recreation->description;
                $list[$recreation->id]['link'] = $recreation->link ? url($recreation->link) : null;
                if ($agent->isDesktop()) {
                    $list[$recreation->id]['image'] = $recreation->getMedia('desktop')->isNotEmpty() ? asset($recreation->getMedia('desktop')->first()->getUrl()) : null;
                } else {
                    $list[$recreation->id]['image'] = $recreation->getMedia('mobile')->isNotEmpty() ? asset($recreation->getMedia('mobile')->first()->getUrl()) : null;
                }
                
            }
            return collect($list);
        }
    }

    public static function getRecreationByIds($ids)
    {
        $agent = new Agent();

        $list = [];
        $items = explode(', ', $ids);
        if (count($items) > 0) {
            foreach($items as $item) {
                $id = (int) $item;
                $post = Recreation::where('status', 'publish')->where('id', $id)->first();
                if ($post) {
                    $list[$id]['name']    = $post->name;
                    $list[$id]['description'] = $post->description;    
                    $list[$id]['timing'] = $post->timing;               
                    if ($agent->isDesktop()) {
                        $list[$id]['image'] = $post->getMedia('desktop')->isNotEmpty() ? asset($post->getMedia('desktop')->first()->getUrl()) : null;
                    } else {
                        $list[$id]['image'] = $post->getMedia('mobile')->isNotEmpty() ? asset($post->getMedia('mobile')->first()->getUrl()) : null;
                    }
                }
            }
        }
        return collect($list);
    }


}
