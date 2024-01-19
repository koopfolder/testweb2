<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;



class TargetController extends Controller
{
    public function getIndex()
    {
        //dd("Case Issue");
        $items = ListTarget::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.list_target.index', compact('items'));
    }


    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListTarget::findOrFail($id);
        //dd($data);
        return view('api::backend.list_target.edit', compact('data'));
    }

    public function postEdit(Request $request, $id)
    {
        $data = $request->all();
        $item = ListTarget::findOrFail($id);
        $data_update['status'] = $data['status'];
        //dd($data_update);
        $item->update($data_update);
        self::postLogs(['event'=>'ปรับสถานะข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'16']);
        return redirect()->route('admin.api.list-target.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }



}

