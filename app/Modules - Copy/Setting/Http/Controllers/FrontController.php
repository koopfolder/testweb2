<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\Setting;

class FrontController extends Controller
{
    public static function getKeyAndReturnValue($key, $default = '') {
        $setting = Setting::select('value')->where('slug', $key)->first();
        if ($setting) {
            return $setting->value;    
        }
        return null;
    }
}
