<?php

namespace App\Modules\Footermenus\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Footermenus\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Layout\Models\Layout;
use App\Modules\Footermenus\Models\{Menu, MenuRevision};
use App\Modules\Exhibition\Models\{Exhibition,ExhibitionMaster};
use DB;

class RightController extends Controller
{
    public function getIndex(Request $request)
    {
        $frontend = Menu::where('parent_id', 0)->where('position','footer-right')->orderBy('order', 'ASC')->get();
        return view('footermenus::index', compact('frontend'));
    }

    public function getCreate(Request $request)
    {
        $layouts     = Layout::where('status', 'publish')->pluck('name','slug');
        $menus       = Menu::where('parent_id', 0)->where('position','footer-right')->orderBy('order', 'ASC')->get();
        $exhibition_masters = ExhibitionMaster::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        return view('footermenus::create', compact('menus', 'layouts','exhibition_masters'));
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

        $data = $this->getManageLink($request->all());
        //dd($data);
        $menu = Menu::create($data);
        $menuId = $menu->id;

        $data = array_except($data,['_token']);
        $data['menu_id'] = $menuId;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);

        if ($request->hasFile('image_desktop')) {
            $menu->addMedia($request->file('image_desktop'))->toMediaCollection('image_desktop');
        }
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'23']);
        return redirect()->route('admin.footermenus.right.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $layouts     = Layout::where('status', 'publish')->pluck('name', 'slug');
        $menus       = Menu::where('parent_id', 0)->where('position', 'footer-right')->orderBy('order', 'ASC')->get();
        $menu = Menu::findOrFail($id);
        $revisions = MenuRevision::where('menu_id', $id)->latest()->get();
        $exhibition_masters = ExhibitionMaster::where('parent_id', 0)->orderBy('order', 'ASC')->get();
        return view('footermenus::edit', compact('menus', 'layouts','menu','revisions','exhibition_masters'));
    }

    public function getReverse($reverseId)
    {
        $reverse = MenuRevision::find($reverseId);
        $menuId = $reverse->menu_id;
        $fields = array_except($reverse->toArray(), ['menu_id', 'user_id']);
        $menu = Menu::find($menuId);
        $menu->parent_id          = $fields['parent_id'];
        $menu->name               = $fields['name'];
        $menu->link_type          = $fields['link_type'];
        $menu->url_external       = $fields['url_external'];
        $menu->description        = $fields['description'];
        $menu->target             = $fields['target'];
        $menu->order              = $fields['order'];
        $menu->layout             = $fields['layout'];        
        $menu->position           = $fields['position'];        
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

        //dd($request);
        if (!$request->has('target')) {
            $request->merge(['target' => 0]);
        }
        if (!$request->has('order')) {
            $request->merge(['order' => 0]);
        }

        $menu = Menu::findOrFail($id);
        if ($request->hasFile('image_desktop')) {
            $menu->clearMediaCollection('image_desktop');
            $menu->addMedia($request->file('image_desktop'))->toMediaCollection('image_desktop');
        }

        $data = $this->getManageLink($request->all());
        //dd($request->all());
        $menu->update($data);

        $data = array_except($data, ['_token']);
        $data['menu_id'] = $id;
        $data['user_id'] = auth()->user()->id;
        MenuRevision::create($data);
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['name'].'"','module_id'=>'23']);
        return redirect()->route('admin.footermenus.right.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->clearMediaCollection();
        $menu->delete();
        return redirect()->route('admin.footermenus.right.index')
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
            return redirect()->route('admin.footermenus.right.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.footermenus.right.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');

    }

    public function getPreivew($url)
    {
        return view('footermenus::preview', compact('url'));
    }

    public function getManageLink($data = array())
    {

        if($data['link_type'] == 'external'){
            $data['layout'] = null;
        }else{
            $data['url_external'] = null;
            //dd($data);
        
            switch ($data['layout']) {

                case 'single_page':
                    $object = new \StdClass;
                    $object->id = $data['params_case_single_page_items_id'];
                    $object->text = $data['params_case_single_page_items'];
                    $data['layout_params'] = json_encode($object);
                    break;

                case 'exhibition_single_page':
                    $object = new \StdClass;
                    $object->id = $data['params_case_exhibition_single_page_items_id'];
                    $object->text = $data['params_case_exhibition_single_page_items'];
                    $data['layout_params'] = json_encode($object);
                    break;

                case 'exhibition_list':
                    $object = new \StdClass;
                    $object->id = $data['exhibition_category'];
                    $object->text = '';
                    $data['layout_params'] = json_encode($object);
                    break;

                case 'exhibition_list_case_closed_to_visitors':
                    $object = new \StdClass;
                    $object->id = $data['exhibition_category'];
                    $object->text = '';
                    $data['layout_params'] = json_encode($object);
                    break;
                
                case 'rss_feed':
                    $object = new \StdClass;
                    $object->id = '';
                    $object->text = '';
                    $object->url_rss = $data['url_rss'];
                    $data['layout_params'] = json_encode($object);
                    break;

                default:
                    
                    break;
            }
        }

        unset($data['params_case_single_page_items'],
              $data['params_case_single_page_items_id'],
              $data['params_case_exhibition_single_page_items'],
              $data['params_case_exhibition_single_page_items_id'],
              $data['exhibition_category'],
              $data['url_rss']
              );
        $data['position'] = 'footer-right';
        //dd($data);
        return $data;
    }
}
