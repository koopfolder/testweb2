<?php

namespace App\Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Restaurant\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Restaurant\Models\{Restaurant, RestaurantRevision};
use Carbon\Carbon;
use LaravelLocalization;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $restaurants = Restaurant::all();
        return view('restaurant::index', compact('restaurants'));
    }

    public function getCreate()
    {
        return view('restaurant::create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $restaurant = Restaurant::create($request->all());
        $id = $restaurant->id;

        $data['restaurant_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        RestaurantRevision::create($data);
        
        if ($request->hasFile('desktop')) {
            $item = Restaurant::find($id);
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item = Restaurant::find($id);
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        return redirect()->route('admin.restaurant.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $revisions = RestaurantRevision::where('restaurant_id', $id)->get();
        return view('restaurant::edit', compact('restaurant', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $data = $request->all();
        $restaurant->update($data);
        
        $data['restaurant_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        RestaurantRevision::create($data);

        if ($request->hasFile('desktop')) {
            $item = Restaurant::find($id);
            $item->clearMediaCollection('desktop');
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item = Restaurant::find($id);
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        return redirect()->route('admin.restaurant.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return redirect()->route('admin.restaurant.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Restaurant::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection('images');
                $entry->delete();
            }
            return redirect()->route('admin.restaurant.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.restaurant.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
    }

    public function getReverse($id)
    {
        $reverse = RestaurantRevision::find($id);
        $restaurantId = $reverse->restaurant_id;
        $fields = array_except($reverse->toArray(), ['restaurant_id', 'user_id']);
        $restaurant = Restaurant::find($restaurantId);
        $restaurant->name = $fields['name'];
        $restaurant->description = $fields['description'];
        $restaurant->open_hours = $fields['open_hours'];
        $restaurant->status = $fields['status'];
        $restaurant->meta_title = $fields['meta_title'];
        $restaurant->meta_keywords = $fields['meta_keywords'];
        $restaurant->meta_description	 = $fields['meta_description'];
        $restaurant->save();

        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
