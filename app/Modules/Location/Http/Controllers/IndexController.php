<?php

namespace App\Modules\Location\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Location\Http\Requests\CreateRequest;
use App\Modules\Location\Http\Requests\EditRequest;
use App\Modules\Location\Models\{Location, LocationReverse};
use App\Modules\Location\Models\LocationRevision;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $locations = Location::all();
        return view('location::index', compact('locations'));
    }

    public function getCreate()
    {
        return view('location::create');
    }

    public function postCreate(CreateRequest $request)
    {
        $item = Location::create($request->all());
        $id = $item->id;

        $data['location_id'] = $id;
        LocationRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.location.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $location = Location::findOrFail($id);
        $revisions = LocationRevision::where('location_id', $id)->get();
        return view('location::edit', compact('location', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $data = $request->all();
        $location = Location::findOrFail($id);
        $location->update($data);

        $data['location_id'] = $id;
        LocationRevision::create($data);
        
        if ($request->hasFile('desktop')) {
            $location->clearMediaCollection('desktop');
            $location->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $location->clearMediaCollection('mobile');
            $location->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        return redirect()->route('admin.location.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->route('admin.location.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Location::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.location.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.location.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

    public function getReverse($id)
    {
        $reverse = LocationRevision::find($id);
        $locationId = $reverse->location_id;
        $fields = array_except($reverse->toArray(), ['location_id']);
        $location = Location::find($locationId);
        $location->name             = $fields['name'];
        $location->description      = $fields['description'];
        $location->kilometre        = $fields['kilometre'];
        $location->lat              = $fields['lat'];
        $location->long             = $fields['long'];
        $location->status           = $fields['status'];
        $location->meta_title	      = $fields['meta_title'];
        $location->meta_keywords    = $fields['meta_keywords'];
        $location->meta_description = $fields['meta_description'];
        $location->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


}
