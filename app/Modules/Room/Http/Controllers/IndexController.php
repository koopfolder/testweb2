<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Room\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Room\Models\{Room, RoomCategory, RoomRevision};

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Room::all();
        return view('room::index', compact('items'));
    }

    public function getCreate()
    {
        $categories = RoomCategory::pluck('name', 'id');
        $rooms = Room::pluck('name', 'id');
        $rooms = collect(['' => 'Not show Room'] + $rooms->toArray());
        return view('room::create', compact('categories', 'rooms'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $item = Room::create($data);
        $id = $item->id;

        $data['room_id'] = $id;
        RoomRevision::create($data);

        if ($request->hasFile('room_desktop')) {
            $item->addMedia($request->file('room_desktop'))->toMediaCollection('room_desktop');
        }

        if ($request->hasFile('room_mobile')) {
            $item->addMedia($request->file('room_mobile'))->toMediaCollection('room_mobile');
        }
        
        if ($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if ($request->hasFile('cover_mobile')) {
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }

        if ($request->hasFile('desktop')) {
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        return redirect()->route('admin.room.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }

    public function getEdit($id)
    {
        $categories = RoomCategory::pluck('name', 'id');
        $rooms = Room::where('id', '<>', $id)->pluck('name', 'id');
        $rooms = collect(['' => 'Not show Room'] + $rooms->toArray());
        $room = Room::findOrFail($id);

        $revisions = RoomRevision::where('room_id', $id)->get();

        return view('room::edit', compact('room', 'rooms', 'categories', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $item = Room::findOrFail($id);
        $data = $request->all();
        $item->update($data);
        
        $data['room_id'] = $id;
        RoomRevision::create($data);

        if ($request->hasFile('room_desktop')) {
            $item->clearMediaCollection('room_desktop');
            $item->addMedia($request->file('room_desktop'))->toMediaCollection('room_desktop');
        }

        if ($request->hasFile('room_mobile')) {
            $item->clearMediaCollection('room_mobile');
            $item->addMedia($request->file('room_mobile'))->toMediaCollection('room_mobile');
        }

        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if ($request->hasFile('cover_mobile')) {
            $item->clearMediaCollection('cover_mobile');
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }

        if ($request->hasFile('desktop')) {
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.room.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        
    }


    public function getDelete($id)
    {
        $item = Room::findOrFail($id);
        $item->clearMediaCollection();
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Room::whereIn('id', $request->input('ids'))->get();
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
        $reverse = RoomRevision::find($id);
        $roomId = $reverse->room_id;
        $fields = array_except($reverse->toArray(), ['room_id', 'user_id']);
        $room = Room::find($roomId);
        $room->category_id      = $fields['category_id'];
        $room->room_type_id     = $fields['room_type_id'];
        $room->name             = $fields['name'];
        $room->description      = $fields['description'];
        $room->features         = $fields['features'];
        $room->amenities        = $fields['amenities'];
        $room->room             = $fields['room'];
        $room->other_features   = $fields['other_features'];
        $room->book_type        = $fields['book_type'];
        $room->guest            = $fields['guest'];
        $room->status           = $fields['status'];
        $room->meta_title       = $fields['meta_title'];
        $room->meta_keywords    = $fields['meta_keywords'];
        $room->meta_description = $fields['meta_description'];
        $room->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
