<?php

namespace App\Modules\Joinus\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Joinus\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Joinus\Models\{Careers};
use RoosterHelpers;


class PositionController extends Controller
{
    public function getIndex()
    {
        $items = Careers::Data(['publish','draft']);
        return view('joinus::careers.index', compact('items'));
    }

    public function getCreate()
    {
        return view('joinus::careers.create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $item = Careers::create($data);

        if($request->hasFile('icon_desktop')) {
            $item->addMedia($request->file('icon_desktop'))->toMediaCollection('icon_desktop');
        }
        if($request->hasFile('icon_mobile')) {
            $item->addMedia($request->file('icon_mobile'))->toMediaCollection('icon_mobile');
        }

        //dd("getCreate","Save Success");
        return redirect()->route('admin.position.index')
                            ->with('status', 'success')
                            ->with('message', trans('joinus::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = Careers::findOrFail($id);
        return view('joinus::careers.edit', compact('data'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = Careers::findOrFail($id);
        $data = $request->all();
        $item->update($data);

        if ($request->hasFile('icon_desktop')){
            $item->clearMediaCollection('icon_desktop');
            $item->addMedia($request->file('icon_desktop'))->toMediaCollection('icon_desktop');
        }

        if ($request->hasFile('icon_mobile')){
            $item->clearMediaCollection('icon_mobile');
            $item->addMedia($request->file('icon_mobile'))->toMediaCollection('icon_mobile');
        }

        return redirect()->route('admin.position.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Careers::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Careers::whereIn('id', $request->input('ids'))->get();
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
          
}
