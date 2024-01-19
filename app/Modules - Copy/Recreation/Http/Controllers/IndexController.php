<?php

namespace App\Modules\Recreation\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Recreation\Http\Requests\CreateRequest;
use App\Modules\Recreation\Http\Requests\EditRequest;
use App\Modules\Recreation\Models\{Recreation, RecreationRevision};
use App\Modules\Menus\Models\Menu;

class IndexController extends Controller
{
    public function getIndex()
    {
        $recreations = Recreation::all();
        return view('recreation::index', compact('recreations'));
    }

    public function getCreate()
    {
        $menuData = Menu::where('status', 'publish')->get()->pluck('name', 'slug');
        $menus = collect(['' => ''] + $menuData->toArray());

        return view('recreation::create', compact('menus'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        
        $recreation = Recreation::create($data);
        $recreationId = $recreation->id;

        $data['recreation_id'] = $recreationId;
        RecreationRevision::create($data);
        
        if ($request->hasFile('desktop')) {
            $desktop = Recreation::find($recreationId);
            $desktop->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $mobile = Recreation::find($recreationId);
            $mobile->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.recreation.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $menuData = Menu::where('status', 'publish')->get()->pluck('name', 'slug');
        $menus = collect(['' => ''] + $menuData->toArray());
        
        $recreation = Recreation::findOrFail($id);

        $revisions = RecreationRevision::where('recreation_id', $id)->get();
        return view('recreation::edit', compact('recreation', 'menus', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $recreation = Recreation::findOrFail($id);
        $data = $request->all();
        $recreation->update($data);

        $data['recreation_id'] = $id;
        RecreationRevision::create($data);
        
        if ($request->hasFile('desktop')) {
            $recreation->clearMediaCollection('desktop');
            $recreation->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $recreation->clearMediaCollection('mobile');
            $recreation->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.recreation.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $recreation = Recreation::findOrFail($id);
        $recreation->clearMediaCollection();
        $recreation->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Recreation::whereIn('id', $request->input('ids'))->get();
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
        $reverse = RecreationRevision::find($id);
        $recreationId = $reverse->recreation_id;
        $fields = array_except($reverse->toArray(), ['recreation_id']);

        $recreation = Recreation::find($recreationId);
        $recreation->name             = $fields['name'];
        $recreation->description      = $fields['description'];
        $recreation->timing           = $fields['timing'];
        $recreation->link             = $fields['link'];
        $recreation->status           = $fields['status'];
        $recreation->meta_title       = $fields['meta_title'];
        $recreation->meta_keywords    = $fields['meta_keywords'];
        $recreation->meta_description = $fields['meta_description'];
        $recreation->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
