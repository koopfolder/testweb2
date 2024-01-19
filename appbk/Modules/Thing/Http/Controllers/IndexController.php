<?php

namespace App\Modules\Thing\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Thing\Http\Requests\CreateRequest;
use App\Modules\Thing\Http\Requests\EditRequest;
use App\Modules\Thing\Models\{Thing, ThingRevision};

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Thing::all();
        return view('thing::index', compact('items'));
    }

    public function getCreate()
    {
        return view('thing::create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $item = Thing::create($data);
        $id = $item->id;

        $data['thing_id'] = $id;
        ThingRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        return redirect()->route('admin.thing.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }

    public function getEdit($id)
    {
        $item = Thing::findOrFail($id);
        $revisions = ThingRevision::where('thing_id', $id)->get();
        return view('thing::edit', compact('item', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $data = $request->all();
        $item = Thing::findOrFail($id);
        $item->update($data);

        $data['thing_id'] = $id;
        ThingRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.thing.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }


    public function getDelete($id)
    {
        $item = Thing::findOrFail($id);
        $item->clearMediaCollection();
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = item::whereIn('id', $request->input('ids'))->get();
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
        $reverse = ThingRevision::find($id);
        $thingId = $reverse->thing_id;
        $fields = array_except($reverse->toArray(), ['thing_id']);
        $thing = Thing::find($thingId);
        $thing->name             = $fields['name'];
        $thing->description      = $fields['description'];
        $thing->phone            = $fields['phone'];
        $thing->status           = $fields['status'];
        $thing->meta_title       = $fields['meta_title'];
        $thing->meta_keywords    = $fields['meta_keywords'];
        $thing->meta_description = $fields['meta_description'];
        $thing->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
