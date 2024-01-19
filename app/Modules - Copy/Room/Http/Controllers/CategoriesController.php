<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Room\Http\Requests\{CreateRequest, EditRequest, CreateCategoryRequest, EditCategoryRequest};
use App\Modules\Room\Models\{Room, RoomCategory, RoomCategoryRevision};

class CategoriesController extends Controller
{
    public function getIndex()
    {
        $items = RoomCategory::all();
        return view('room::categories.index', compact('items'));
    }

    public function getCreate()
    {
        $categories = RoomCategory::where('status', 'publish')->pluck('name', 'id');
        $rooms = Room::where('status', 'publish')->pluck('name', 'id');
        $rooms = collect(['' => 'Not Selected'] + $rooms->toArray());
        return view('room::categories.create', compact('categories', 'rooms'));
    }

    public function postCreate(CreateCategoryRequest $request)
    {
        $data = $request->all();
        $item = RoomCategory::create($data);
        $id = $item->id;
        
        $data['room_category_id'] = $id;
        RoomCategoryRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        if ($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if ($request->hasFile('cover_mobile')) {
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }
        
        return redirect()->route('admin.room.category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }

    public function getEdit($id)
    {
        $categories = RoomCategory::pluck('name', 'id');
        $rooms = Room::where('status', 'publish')->where('id', '<>', $id)->pluck('name', 'id');
        $rooms = collect(['' => 'Not Selected'] + $rooms->toArray());

        $category = RoomCategory::findOrFail($id);

        $revisions = RoomCategoryRevision::where('room_category_id', $id)->get();

        return view('room::categories.edit', compact('category', 'rooms', 'categories', 'revisions'));   
    }

    public function postEdit(EditCategoryRequest $request, $id)
    {
        $item = RoomCategory::findOrFail($id);
        $data = $request->all();
        $item->update($data);

        $data['room_category_id'] = $id;
        RoomCategoryRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if ($request->hasFile('cover_mobile')) {
            $item->clearMediaCollection('cover_mobile');
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }

        return redirect()->route('admin.room.category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }


    public function getDelete($id)
    {
        $item = RoomCategory::findOrFail($id);
        $item->clearMediaCollection();
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = RoomCategory::whereIn('id', $request->input('ids'))->get();
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

    public function getReverse($revisionId)
    {
        $reverse = RoomCategoryRevision::find($revisionId);
        $roomCategoryId = $reverse->room_category_id;
        $fields = array_except($reverse->toArray(), ['room_category_id']);
        $category = RoomCategory::find($roomCategoryId);
        $category->name             = $fields['name'];
        $category->description      = $fields['description'];
        $category->use_room_detail  = $fields['use_room_detail'];
        $category->status           = $fields['status'];
        $category->meta_title       = $fields['meta_title'];
        $category->meta_keywords    = $fields['meta_keywords'];
        $category->meta_description = $fields['meta_description'];
        $category->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


}
