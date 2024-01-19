<?php

namespace App\Modules\Link\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Link\Models\Link;

class FrontController extends Controller
{
    public static function getLinkByPosition($position = null)
    {
        $list = [];
        if (!is_null($position)) {
            $menus = Link::where('parent_id', 0)->where('position', $position)->orderBy('order', 'ASC')->get();
            if ($menus) {
                foreach ($menus as $menu) {
                    $list[$menu->id]['name'] = $menu->name;
                    $list[$menu->id]['link'] = url($menu->slug);
                }
            }
        }
        return collect($list);
    }
}
