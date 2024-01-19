<?php

namespace App\Modules\Promotion\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Promotion\Models\Promotion;
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getPromotions()
    {
        $agent = new Agent();
        
        $list = [];

        $promotions = Promotion::where('status', 'publish')->get();
        if ($promotions->isNotEmpty()) {
            foreach($promotions as $promotion) {
                $list[$promotion->id]['name']        = $promotion->name;
                $list[$promotion->id]['description'] = $promotion->description;
                $list[$promotion->id]['link'] = $promotion->link;
                if ($agent->isDesktop()) {
                    $list[$promotion->id]['image'] = $promotion->getMedia('desktop')->isNotEmpty() ? asset($promotion->getMedia('desktop')->first()->getUrl()) : null;
                } else {
                    $list[$promotion->id]['image'] = $promotion->getMedia('mobile')->isNotEmpty() ? asset($promotion->getMedia('mobile')->first()->getUrl()) : null;
                }
            }
            return collect($list);
        }
    }
}
