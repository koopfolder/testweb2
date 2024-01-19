<?php

namespace App\Modules\Banner\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Banner\Models\{Banner, BannerCategory};
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getBannerByCategoryId($id)
    {
        $agent = new Agent();

        $list = [];
        $category = BannerCategory::where('status', 'publish')->where('id', $id)->first();
        if ($category) {
            $banners = $category->banners()->get()->where('status', 'publish');
            foreach($banners as $banner) {
                $list[$banner->id]['name']        = $banner->name;
                $list[$banner->id]['description'] = $banner->description;
                $list[$banner->id]['link'] = $banner->link ? url($banner->link) : null;
                if ($agent->isDesktop()) {
                    $list[$banner->id]['image'] = $banner->getMedia('desktop')->isNotEmpty() ? asset($banner->getMedia('desktop')->first()->getUrl()) : null;
                } else {
                    $list[$banner->id]['image'] = $banner->getMedia('mobile')->isNotEmpty() ? asset($banner->getMedia('mobile')->first()->getUrl()) : null;
                }
                
            }
            return collect($list);
        }
    }
}
