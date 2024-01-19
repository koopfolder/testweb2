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



class CategoryController extends Controller
{
    public function getIndex()
    {
        //dd("Case Category");
        $items = ListCategory::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('api::backend.list_category.index', compact('items'));
    }


    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListCategory::findOrFail($id);
        //dd($data);
        return view('api::backend.list_category.edit', compact('data'));
    }

    public function postEdit(Request $request, $id)
    {
        $data = $request->all();
        //dd($data);
        $item = ListCategory::findOrFail($id);
        $data_update['status'] = $data['status'];
        //dd($data_update);
        $item->update($data_update);
        self::postLogs(['event'=>'ปรับสถานะข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'14']);
        return redirect()->route('admin.api.list-category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function CategoryData(){
        $category_data = ListCategory::select('category_id','name')->where('status','publish')->get()->toArray();

        if(empty($category_data)){
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => []
            ];
        }else{
            $response = [
                'res_code' => "00",
                'res_text' => "success",
                'res_result' => $category_data,
            ];
        }
        return $response;
    }
 

}

