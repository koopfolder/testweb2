<?php

namespace App\Modules\Exhibition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Exhibition\Http\Requests\{CreateExhibitionMasterRequest,EditExhibitionMasterRequest};
use App\Modules\Exhibition\Models\{Exhibition,ExhibitionMaster,BookAnExhibition};
use Zipper;

class BookAnExhibitionController extends Controller
{
    public function getIndex()
    {

        $items = BookAnExhibition::Data(['status'=>['publish']]);
        //dd($items);
        return view('exhibition::book_an_exhibition.index', compact('items'));
    }

            
}
