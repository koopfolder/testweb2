<?php

namespace App\Modules\Thing\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Thing\Models\Thing;
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getThings()
    {
        $list = [];
        $things = Thing::where('status', 'publish')->get();
        $agent = new Agent;
        if ($things->isNotEmpty()) {
            foreach ($things as $thing) {
                if ($agent->isDesktop()) {
                    $list[$thing->id]['image'] = $thing->getMedia('desktop')->isNotEmpty() ? asset($thing->getMedia('desktop')->first()->getUrl()) : null;
                } else {
                    $list[$thing->id]['image'] = $thing->getMedia('mobile')->isNotEmpty() ? asset($thing->getMedia('mobile')->first()->getUrl()) : null;
                }
                $list[$thing->id]['name'] = $thing->name;
                $list[$thing->id]['description'] = $thing->description;
                $list[$thing->id]['phone'] = $thing->phone;
            }
        }
        return collect($list);
    }
}
