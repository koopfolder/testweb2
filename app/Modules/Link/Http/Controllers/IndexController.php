<?php

namespace App\Modules\Link\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Link\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Layout\Models\Layout;
use App\Modules\Room\Models\Room;
use App\Modules\Restaurant\Models\Restaurant;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Recreation\Models\Recreation;
use App\{Post};
use App\Modules\Menus\Models\{Menu, MenuRevision};
use App\Modules\Banner\Models\BannerCategory;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $frontend = Menu::where('parent_id', 0)->where('position', '<>', 'header')->orderBy('order', 'ASC')->get();
        return view('link::index', compact('frontend'));
    }

    public function getCreate(Request $request)
    {
        $bannerCategories = BannerCategory::where('status', 'publish')->pluck('name', 'id');
        $bannerCategories = collect(['' => 'Not use Banner Category'] + $bannerCategories->toArray());

        $layouts = Layout::where('status', 'publish')->pluck('name', 'slug');
        $rooms = Room::where('status', 'publish')->get();
        $restaurants = Restaurant::where('status', 'publish')->get();
        $promotions = Promotion::where('status', 'publish')->get();
        $pages = Post::where('type', 'single-page')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        $menus = Menu::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        $recreations = Recreation::where('status', 'publish')->get();
        $module_slug = null;
        $module_ids = null;
        return view('link::create', compact('pages', 'menus', 'layouts', 'rooms', 'restaurants', 'promotions', 'recreations', 'module_ids', 'module_slug', 'bannerCategories'));
    }

    public function postCreate(CreateRequest $request)
    {
        if (!$request->has('target')) {
            $request->merge(['target' => 0]);
        }

        if (!$request->has('order')) {
            $request->merge(['order' => 0]);
        }

        if (!$request->has('make_reservation')) {
            $request->merge(['make_reservation' => 'no']);
        }
        
        $data = $this->getManageLink($request->all());

        $menu = Menu::create($data);
        $menuId = $menu->id;

        $data['menu_id'] = $menuId;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);

        return redirect()->route('admin.link.index')
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $bannerCategories = BannerCategory::where('status', 'publish')->pluck('name', 'id');
        $bannerCategories = collect(['' => 'Not use Banner Category'] + $bannerCategories->toArray());

        $layouts = Layout::where('status', 'publish')->pluck('name', 'slug');
        $rooms = Room::where('status', 'publish')->get();
        $restaurants = Restaurant::where('status', 'publish')->get();
        $promotions = Promotion::where('status', 'publish')->get();
        $pages = Post::where('type', 'single-page')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        $menus = Menu::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        $recreations = Recreation::where('status', 'publish')->get();

        $revisions = MenuRevision::where('menu_id', $id)->get();

        $menu = Menu::findOrFail($id);

        return view('link::edit', compact('pages', 'menus', 'layouts', 'rooms', 'restaurants', 'promotions', 'recreations', 'menu', 'bannerCategories', 'revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        if (!$request->has('target')) {
            $request->merge(['target' => 0]);
        }
        if (!$request->has('order')) {
            $request->merge(['order' => 0]);
        }

        if (!$request->has('make_reservation')) {
            $request->merge(['make_reservation' => 'no']);
        }

        $menu = Menu::findOrFail($id);

        $data = $this->getManageLink($request->all());
        $menu->update($data);

        $data['menu_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);

        return redirect()->route('admin.link.index')
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->clearMediaCollection();
        $menu->delete();
        return redirect()->route('admin.link.index')
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Menu::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.link.index')
                ->with('status', 'success')
                ->with('message', 'Successfully');
        }

        return redirect()->route('admin.link.index')
            ->with('status', 'error')
            ->with('message', 'Not users');

    }

    public function getPreivew($url)
    {
        return view('menus::preview', compact('url'));
    }

    public function getManageLink($data = array())
    {
        if ($data['link_type'] == 'external') {
            $data['layout'] = null;
            unset($data['room']);
        } else {
            $data['url_external'] = null;
            switch ($data['layout']) {
                case 'home':
                    unset($data['room']);
                    break;
                case 'single-page':
                    $data['module_slug'] = 'singlePage';
                    $data['module_ids'] = collect($data['pages'])->implode(', ');
                    unset($data['pages']);
                    break;
                case 'room':
                    $data['module_slug'] = 'room';
                    $data['module_ids'] = $data['room'];
                    unset($data['pages'], $data['room']);
                    break;
                case 'restaurant':
                    $data['module_slug'] = 'restaurant';
                    $data['module_ids'] = collect($data['restaurants'])->implode(', ');
                    unset($data['pages'], $data['restaurants'], $data['room']);
                    break;
                case 'offers-promotions':
                    $data['module_slug'] = 'promotion';
                    $data['module_ids'] = collect($data['promotions'])->implode(', ');
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions']);
                    break;
                case 'villas-suites':
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions'], $data['room']);
                    break;
                case 'suites':
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions'], $data['room']);
                    break;
                case 'villas':
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions'], $data['room']);
                    break;
                case 'leisure-recreation':
                    $data['module_slug'] = 'recreation';
                    $data['module_ids'] = collect($data['recreations'])->implode(', ');
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions'], $data['room'], $data['recreations']);
                    break;
                default:
                    unset($data['pages'], $data['restaurants'], $data['room'], $data['promotions'], $data['room']);
                    break;
            }
        }
        // dd($data);
        return $data;
    }
}
