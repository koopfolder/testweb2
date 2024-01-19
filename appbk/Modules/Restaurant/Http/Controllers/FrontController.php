<?php
namespace App\Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Restaurant\Http\Requests\{CreateRestaurantRequest, EditRestaurantRequest};
use App\Modules\Restaurant\Models\Restaurant;
use Carbon\Carbon;
use LaravelLocalization;
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getRestaurants() 
    {
        $list = [];
        $restaurants = Restaurant::where('status', 'publish')->get();
        foreach($restaurants as $restaurant) {
            $list[$restaurant->id]['id'] = $restaurant->id;
            $list[$restaurant->id]['name'] = $restaurant->name;

            if ((new Agent)->isDesktop()) {
                $list[$restaurant->id]['image'] = $restaurant->getMedia('desktop')->isNotEmpty() ? asset($restaurant->getMedia('desktop')->first()->getUrl()) : null;
            } else {
                $list[$restaurant->id]['image'] = $restaurant->getMedia('mobile')->isNotEmpty() ? asset($restaurant->getMedia('mobile')->first()->getUrl()) : null;
            }

            $list[$restaurant->id]['description'] = $restaurant->description;
            $list[$restaurant->id]['open_hours'] = $restaurant->open_hours;
        }
        return collect($list);
    }
}
