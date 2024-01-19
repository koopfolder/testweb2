<?php

namespace App\Modules\Joinus\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Joinus\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Joinus\Models\{Joinus};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class IndexController extends Controller
{
    public function getIndex()
    {

        //dd("test");
        $items = Joinus::Data(['publish','draft']);
        //dd($items);
        return view('joinus::index', compact('items'));
    }

    public function getViewmore($id)
    {
        $data = Joinus::Viewmore(['id'=>$id]);
        //dd($data);
        return view('joinus::viewmore', compact('data'));
    }

}

