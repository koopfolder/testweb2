<?php

namespace App\Modules\Promotion\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Promotion\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Promotion\Models\{Promotion, PromotionRevision};
use Excel;
use Carbon\Carbon;
use LaravelLocalization;
use App\Modules\Menus\Models\Menu;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $promotions = Promotion::all();
        return view('promotion::index', compact('promotions'));
    }

    public function getCreate()
    {
        $menus = Menu::where('status', 'publish')->pluck('name', 'slug');
        $menus = collect($menus->toArray() + ['' => '']);
        return view('promotion::create', compact('menus'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $item = Promotion::create($data);
        $id = $item->id;

        $data['promotion_id'] = $id;
        PromotionRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item = Promotion::find($id);
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item = Promotion::find($id);
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.promotion.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $menus = Menu::where('status', 'publish')->pluck('name', 'slug');
        $menus = collect($menus->toArray() + ['' => '']);
        $promotion = Promotion::findOrFail($id);
        $revisions = PromotionRevision::where('promotion_id', $id)->get();
        return view('promotion::edit', compact('promotion', 'revisions', 'menus'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $data = $request->all();
        $promotion = Promotion::findOrFail($id);
        $promotion->update($data);

        $data['promotion_id'] = $id;
        PromotionRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item = Promotion::find($id);
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item = Promotion::find($id);
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        return redirect()->route('admin.promotion.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promotion.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Promotion::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.promotion.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.promotion.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
                        
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.news.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('news', function($excel) {
            $data = Post::where('type', 'news')->get();
            $excel->sheet('news', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls'); 

    }

    public function getReverse($id)
    {
        $reverse = PromotionRevision::find($id);
        $promotionId = $reverse->promotion_id;
        $fields = array_except($reverse->toArray(), ['promotion_id']);

        $promotion = Promotion::find($promotionId);
        $promotion->name             = $fields['name'];
        $promotion->description      = $fields['description'];
        $promotion->status           = $fields['status'];
        $promotion->meta_title       = $fields['meta_title'];
        $promotion->meta_keywords    = $fields['meta_keywords'];
        $promotion->meta_description = $fields['meta_description'];
        $promotion->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
