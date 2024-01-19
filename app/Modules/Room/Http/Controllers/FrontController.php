<?php

namespace App\Modules\Room\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Room\Models\{Room, RoomCategory};
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getRooms()
    {
        return Room::where('status', 'publish')->get();
    }

    public static function getRoomById($id)
    {
        $room = Room::where('id', $id)->where('status', 'publish')->first();
        $agent = new Agent();
        $list = [];
        if ($room) {
            $list['name'] = $room->name;
            $list['description'] = $room->description;
            if ($agent->isDesktop()) {
                $list['image'] = $room->getMedia('desktop')->isNotEmpty() ? asset($room->getMedia('desktop')->first()->getUrl()) : null;
                $list['cover'] = $room->getMedia('cover_desktop')->isNotEmpty() ? asset($room->getMedia('cover_desktop')->first()->getUrl()) : null;
            } else {
                $list['image'] = $room->getMedia('mobile')->isNotEmpty() ? asset($room->getMedia('mobile')->first()->getUrl()) : null;
                $list['cover'] = $room->getMedia('cover_mobile')->isNotEmpty() ? asset($room->getMedia('cover_mobile')->first()->getUrl()) : null;
            }
            $list['discover_more'] = url('room/' . $room->slug);
            $list['book_type'] = $room->book_type;
            $list['booking'] = 'https://google.com';
            $list['room'] = $room->room;
            $list['features'] = $room->features;
            $list['amenities'] = $room->amenities;
            $list['other_features'] = $room->other_features;
            $list['meta_title'] = $room->meta_title;
            $list['meta_keywords'] = $room->meta_keywords;
            $list['meta_description'] = $room->meta_description;
            $list['more_rooms'] = Room::where('id', '<>', $room->id)->where("category_id", $room->category_id)->where('status', 'publish')->get();
        }
        return collect($list);
    }

    public static function getRoomBySlug($slug)
    {
        $room = Room::where('slug', $slug)->where('status', 'publish')->first();
        $agent = new Agent();
        $list = [];
        if ($room) {
            $list['name']        = $room->name;
            $list['description'] = $room->description;
            if ($agent->isDesktop()) {
                $list['image'] = $room->getMedia('desktop')->isNotEmpty() ? asset($room->getMedia('desktop')->first()->getUrl()) : null;
                $list['cover'] = $room->getMedia('cover_desktop')->isNotEmpty() ? asset($room->getMedia('cover_desktop')->first()->getUrl()) : null;
            } else {
                $list['image'] = $room->getMedia('mobile')->isNotEmpty() ? asset($room->getMedia('mobile')->first()->getUrl()) : null;
                $list['cover'] = $room->getMedia('cover_mobile')->isNotEmpty() ? asset($room->getMedia('cover_mobile')->first()->getUrl()) : null;
            }
            $list['discover_more']    = url('room/' . $room->slug);
            $list['book_type']        = $room->book_type;
            $list['booking']          = 'https://google.com';
            $list['room'] = $room->room;
            $list['features']         = $room->features;
            $list['amenities']        = $room->amenities;
            $list['other_features']   = $room->other_features;
            $list['meta_title']       = $room->meta_title;
            $list['meta_keywords']    = $room->meta_keywords;
            $list['meta_description'] = $room->meta_description;
            $list['more_rooms']       = Room::where('id', '<>', $room->id)->where("category_id", $room->category_id)->where('status', 'publish')->get();
        }
        return collect($list);
    }

    public static function getCategories()
    {
        $agent = new Agent();

        $list = [];
        $categories = RoomCategory::where('status', 'publish')->get();
        if ($categories->isNotEmpty()) {

            foreach($categories as $category) {
                $list[$category->id]['name']        = $category->name;
                $list[$category->id]['description'] = $category->description;

                if ($agent->isDesktop()) {
                    $list[$category->id]['image'] = $category->getMedia('desktop')->isNotEmpty() ? asset($category->getMedia('desktop')->first()->getUrl()) : null;
                    $list[$category->id]['cover'] = $category->getMedia('cover_desktop')->isNotEmpty() ? asset($category->getMedia('cover_desktop')->first()->getUrl()) : null;
                } else {
                    $list[$category->id]['image'] = $category->getMedia('mobile')->isNotEmpty() ? asset($category->getMedia('mobile')->first()->getUrl()) : null;
                    $list[$category->id]['cover'] = $category->getMedia('cover_mobile')->isNotEmpty() ? asset($category->getMedia('cover_mobile')->first()->getUrl()) : null;
                }
                $list[$category->id]['discover_more'] = url( $category->slug);
                if ($category->use_room_detail) {
                    $useRoomDetail = Room::find($category->use_room_detail);
                    if ($useRoomDetail) {
                        $list[$category->id]['discover_more'] = url('room/' . $useRoomDetail->slug);
                    }
                }
            }
            return collect($list);
        }
    }

    public static function getRoomByCategoryId($id)
    {
        $list = [];
        $rooms =  Room::where('status', 'publish')->where('category_id', $id)->get();
        if ($rooms) {
            foreach($rooms as $room) {
                $list[$room->id]['id']          = $room->id;
                $list[$room->id]['name']        = $room->name;
                $list[$room->id]['description'] = $room->description;
                if ((new Agent)->isDesktop()) {
                    $list[$room->id]['image'] = $room->getMedia('desktop')->isNotEmpty() ? asset($room->getMedia('desktop')->first()->getUrl()) : null;
                    $list[$room->id]['room_desktop'] = $room->getMedia('room_desktop')->isNotEmpty() ? asset($room->getMedia('room_desktop')->first()->getUrl()) : null;
                } else {
                    $list[$room->id]['image'] = $room->getMedia('mobile')->isNotEmpty() ? asset($room->getMedia('mobile')->first()->getUrl()) : null;
                    $list[$room->id]['room_desktop'] = $room->getMedia('room_mobile')->isNotEmpty() ? asset($room->getMedia('room_mobile')->first()->getUrl()) : null;
                }
                $list[$room->id]['discover_more'] = url('room/' . $room->slug);
            }
        }
        return collect($list);
    }
}
