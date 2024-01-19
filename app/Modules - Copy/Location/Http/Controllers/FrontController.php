<?php
namespace App\Modules\Location\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Location\Models\Location;
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getLocations()
    {
        $list = [];
        $locations = Location::where('status', 'publish')->get();
        $agent = new Agent;
        if ($locations->isNotEmpty()) {
            foreach ($locations as $location) {
                if ($agent->isDesktop()) {
                    $list[$location->id]['image'] = $location->getMedia('desktop')->isNotEmpty() ? asset($location->getMedia('desktop')->first()->getUrl()) : null;
                } else {
                    $list[$location->id]['image'] = $location->getMedia('mobile')->isNotEmpty() ? asset($location->getMedia('mobile')->first()->getUrl()) : null;
                }
                $list[$location->id]['name'] = $location->name;
                $list[$location->id]['kilometre'] = $location->kilometre;
            }
        }
        return collect($list);
    }
}
