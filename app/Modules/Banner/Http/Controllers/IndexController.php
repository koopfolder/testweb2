<?php

namespace App\Modules\Banner\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Banner\Http\Requests\CreateRequest;
use App\Modules\Banner\Http\Requests\EditRequest;
use App\Modules\Banner\Models\{Banner, BannerCategory, BannerRevision};
use App\Modules\Menus\Models\Menu;
use App\Modules\Room\Models\RoomRevision;

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Banner::orderBy('created_at','DESC')
                        //->toSql();
                        ->get();
        return view('banner::index', compact('items'));
    }

    public function getCreate()
    {
        $categories = BannerCategory::where('status', 'publish')->get()->pluck('name', 'id');
        return view('banner::create', compact('categories'));
    }

    public function postCreate(CreateRequest $request)
    {
        set_time_limit(0);
        ini_set('post_max_size','50M');
        ini_set("max_execution_time","0");
        $data = $request->all();

        if($data['use_content'] ==='news_events'){
            $object = new \StdClass;
            $object->id = $data['params_case_items_id'];
            $object->text = $data['params_case_items'];
            unset($data['params_case_items_id'],$data['params_case_items']);
            $data['use_content_params'] = json_encode($object);
            //dd("Case Use Content",$data,$object);
        }else{
            unset($data['params_case_items_id'],$data['params_case_items']);
        }

        //dd($data);
        $item = Banner::create($data);

        $data['banner_id'] = $item->id;
        BannerRevision::create($data);
        if($request->hasFile('desktop')){
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'24']);
        return redirect()->route('admin.banner.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $categories = BannerCategory::where('status', 'publish')->pluck('name', 'id');
        $item = Banner::findOrFail($id);
        $revisions = BannerRevision::where('banner_id', $id)->orderBy('created_at','DESC')->get();
        return view('banner::edit', compact('item', 'categories','revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        set_time_limit(0);
        ini_set('post_max_size','50M');
        ini_set("max_execution_time","0");
        $item = Banner::findOrFail($id);
        $data = $request->all();
        if($data['use_content'] ==='news_events'){
            $object = new \StdClass;
            $object->id = $data['params_case_items_id'];
            $object->text = $data['params_case_items'];
            unset($data['params_case_items_id'],$data['params_case_items']);
            $data['use_content_params'] = json_encode($object);
        }else{
            unset($data['params_case_items_id'],$data['params_case_items']);
        }

        $item->update($data);
        $data['banner_id'] = $id;
        BannerRevision::create($data);
        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'24']);
        return redirect()->route('admin.banner.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $item = Banner::findOrFail($id);
        $item->clearMediaCollection();
        $item->delete();
        $destinationPath =  public_path().'/files/module_banner_videos/'.$id; // upload path
        \File::deleteDirectory($destinationPath);
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Banner::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $destinationPath =  public_path().'/files/module_banner_videos/'.$entry->id; // upload path
                \File::deleteDirectory($destinationPath);
                $entry->clearMediaCollection();
                $entry->delete();

            }
            return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->back()
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

    public function getReverse($bannerId)
    {
        $reverse = BannerRevision::find($bannerId);
        $bannerId = $reverse->banner_id;
        $banner = Banner::find($bannerId);
        $banner->category_id = $fields['category_id'];
        $banner->name        = $fields['name'];
        $banner->link        = $fields['link'];
        $banner->description = $fields['description'];
        $banner->status      = $fields['status'];
        $banner->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
