<?php

namespace App\Modules\Footermenus\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Footermenus\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Layout\Models\Layout;
use App\Modules\Room\Models\{Room, RoomCategory};
use App\Modules\Restaurant\Models\Restaurant;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Recreation\Models\Recreation;
use App\{Post};
use App\Modules\Footermenus\Models\{Menu, MenuRevision};
use App\Modules\Banner\Models\BannerCategory;
use App\Modules\Documentsdownload\Models\{Documents};
use DB;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $menus = Menu::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        $backend = Menu::where('parent_id', 0)->where('site', 'backend')->orderBy('order', 'ASC')->get();
        $frontend = Menu::where('parent_id', 0)->whereIn('position',['footer-left','footer-center','footer-right'])->orderBy('order', 'ASC')->get();
        return view('footermenus::index', compact('menus', 'backend', 'frontend'));
    }

    public function getCreate(Request $request)
    {
        dd("asdasasdsasd");
        $bannerCategories = BannerCategory::where('status', 'publish')->pluck('name', 'id');
        $bannerCategories = collect(['' => 'Not use Banner Category'] + $bannerCategories->toArray());

        $layouts     = Layout::where('status', 'publish')->pluck('name', 'slug');
        //dd($layouts);
        $rooms       = Room::where('status', 'publish')->get();
        $restaurants = Restaurant::where('status', 'publish')->get();
        $promotions  = Promotion::where('status', 'publish')->get();
        $pages       = Post::where('type', 'single-page')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        $menus       = Menu::where('parent_id', 0)->where('position',['footer-left','footer-center','footer-right'])->orderBy('order', 'ASC')->get();
        $recreations = Recreation::where('status', 'publish')->get();
        $room_categories = RoomCategory::where('status', 'publish')->get();
        $module_slug = null;
        $module_ids  = null;
        return view('footermenus::create', compact('pages', 'menus', 'layouts', 'rooms', 'restaurants', 'promotions', 'recreations', 'module_ids', 'module_slug', 'bannerCategories', 'room_categories'));
    }

    public function postCreate(CreateRequest $request)
    {

        //dd($request->all()," Request Method Post");

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

        $data = array_except($data, ['_token', 'room_category']);
        $data['menu_id'] = $menuId;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);

        if ($request->hasFile('desktop')) {
            $menu->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        return redirect()->route('admin.footermenus.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $bannerCategories = BannerCategory::where('status', 'publish')->pluck('name', 'id');
        $bannerCategories = collect(['' => 'Not use Banner Category'] + $bannerCategories->toArray());

        $layouts     = Layout::where('status', 'publish')->pluck('name', 'slug');
        $rooms       = Room::where('status', 'publish')->get();
        $restaurants = Restaurant::where('status', 'publish')->get();
        $promotions  = Promotion::where('status', 'publish')->get();
        $pages       = Post::where('type', 'single-page')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        $menus       = Menu::where('parent_id', 0)->where('position', 'header')->orderBy('order', 'ASC')->get();
        $recreations = Recreation::where('status', 'publish')->get();
        $room_categories = RoomCategory::where('status', 'publish')->get();

        $menu = Menu::findOrFail($id);
        //dd($menu);
        $revisions = MenuRevision::where('menu_id', $id)->latest()->get();

        return view('footermenus::edit', compact('pages', 'menus', 'layouts', 'rooms', 'restaurants', 'promotions', 'recreations', 'menu', 'bannerCategories', 'room_categories', 'revisions'));
    }

    public function getReverse($reverseId)
    {
        $reverse = MenuRevision::find($reverseId);
        $menuId = $reverse->menu_id;
        $fields = array_except($reverse->toArray(), ['menu_id', 'user_id']);
        $menu = Menu::find($menuId);
        $menu->parent_id          = $fields['parent_id'];
        $menu->name_th               = $fields['name_th'];
        $menu->name_en              = $fields['name_en'];
        $menu->link_type          = $fields['link_type'];
        $menu->url_internal       = $fields['url_internal'];
        $menu->url_external       = $fields['url_external'];
        $menu->video              = $fields['video'];
        $menu->excerpt               = $fields['excerpt'];
        $menu->description_th        = $fields['description_th'];
        $menu->description_en        = $fields['description_en'];
        $menu->icon               = $fields['icon'];
        $menu->target             = $fields['target'];
        $menu->order              = $fields['order'];
        $menu->classes            = $fields['classes'];
        $menu->layout             = $fields['layout'];        
        $menu->position           = $fields['position'];        
        $menu->module_slug        = $fields['module_slug'];       
        $menu->module_ids         = $fields['module_ids'];
        $menu->make_reservation   = $fields['make_reservation']; 
        $menu->banner_category_id = $fields['banner_category_id'];
        $menu->status             = $fields['status'];
        $menu->meta_title         = $fields['meta_title'];
        $menu->meta_keywords      = $fields['meta_keywords'];
        $menu->meta_description   = $fields['meta_description'];
        $menu->save();
        
        return redirect()->back()
                        ->with('status', 'success')
                        ->with('message', 'Successfully');
    }

    public function postEdit(EditRequest $request, $id)
    {

       // dd($request);
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

        if ($request->hasFile('desktop')) {
            $menu->clearMediaCollection('desktop');
            $menu->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }


        $data = $this->getManageLink($request->all());
        //dd($request->all());
        $menu->update($data);

        $data = array_except($data, ['_token', 'room_category']);
        $data['menu_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);

        return redirect()->route('admin.footermenus.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->clearMediaCollection();
        $menu->delete();
        return redirect()->route('admin.footermenus.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDeleteImage($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->clearMediaCollection('desktop');
        return redirect()->back()
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
            return redirect()->route('admin.footermenus.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.footermenus.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');

    }

    public function getPreivew($url)
    {
        return view('footermenus::preview', compact('url'));
    }

    public function getManageLink($data = array())
    {

        if ($data['link_type'] == 'external') {
            $data['layout'] = null;

        }else if($data['link_type'] == 'document'){

            $data['layout'] = null;
            $link = Documents::DataLink(['status'=>['publish'],'document_type'=>$data['document']]);
            //dd($link);

            if($link->count()){

                $data['document_path_th'] = ($link->file_path_th !='' ? $link->file_path_th:'#');
                $data['document_path_en'] =  ($link->file_path_en !='' ? $link->file_path_en:($link->file_path_th !='' ? $link->file_path_th:'#'));
            }else{

                $data['url_external'] = null;
                $data['document_path_th'] = null;
                $data['document_path_en'] = null;

            }

            //dd($data,$link->count());
            //unset($data['document']);
        }else{
            $data['url_external'] = null;
        }
        $data['position'] = 'header';
        //dd($data);
        return $data;
    }
}
