<?php

namespace App\Modules\Search\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Module;

class IndexController extends Controller
{
    public function getIndex(Request $reqeust)
    {
        return view('search::index', compact('search'));
    }

}
