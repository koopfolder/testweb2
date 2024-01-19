<?php

namespace App\Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Module;
use App\User;

class IndexController extends Controller
{
    public function getIndex()
    {
        $users = User::select('id')->get();
        //dd($users);
        return view('dashboard::index', compact('users'));
    }

    public function get404()
    {
        return view('dashboard::404');
    }

    public function getModules()
    {
        return view('dashboard::modules.index');
    }

    public function getModuleDetail($slug)
    {
        return view('dashboard::modules.detail-' . $slug, compact($slug));
    }


}
