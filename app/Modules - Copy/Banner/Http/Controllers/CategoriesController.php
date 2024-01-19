<?php

namespace App\Modules\Banner\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Banner\Http\Requests\CreateRequest;
use App\Modules\Banner\Http\Requests\EditRequest;
use App\Modules\Banner\Models\{Banner, BannerCategory, BannerCategoryRevision};

class CategoriesController extends Controller
{
    public function getIndex()
    {
        $items = BannerCategory::orderBy('created_at','DESC')
                                //->toSql();
                                ->get();
        return view('banner::categories.index', compact('items'));
    }

    public function getCreate()
    {
        return view('banner::categories.create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data);
        $item = BannerCategory::create($data);

        $data['banner_category_id'] = $item->id;
        BannerCategoryRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        // if ($request->hasFile('mobile')) {
        //     $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        // }
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'25']);
        return redirect()->route('admin.banner.category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $item = BannerCategory::findOrFail($id);
        $revisions = BannerCategoryRevision::where('banner_category_id', $id)->orderBy('created_at','DESC')->get();
        return view('banner::categories.edit', compact('item', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = BannerCategory::findOrFail($id);
        $data = $request->all();
        $item->update($data);

        $data['banner_category_id'] = $id;
        BannerCategoryRevision::create($data);
        
        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        // if ($request->hasFile('mobile')) {
        //     $item->clearMediaCollection('mobile');
        //     $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        // }
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'25']);
        return redirect()->route('admin.banner.category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $item = BannerCategory::findOrFail($id);
        $item->clearMediaCollection();
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = BannerCategory::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
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

    public function getReverse($id)
    {
        $reverse = BannerCategoryRevision::find($id);
        $categoryId = $reverse->banner_category_id;
        $fields = array_except($reverse->toArray(), ['banner_category_id', 'user_id']);
        $category = BannerCategory::find($categoryId);
        $category->name        = $fields['name'];
        $category->description = $fields['description'];
        $category->status      = $fields['status'];
        $category->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


}
