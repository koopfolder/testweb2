<?php

namespace App\Modules\Pages\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use App\Modules\Pages\Http\Requests\CreatePageRequest;
use App\Modules\Pages\Http\Requests\EditPageRequest;
use App\Post;
use App\Category;
use Excel;
use Silber\Bouncer\Database\Ability;
use App\Modules\Menus\Models\Menu;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $pages = Post::where('type', 'page')->orderBy('id', 'DESC')->get();
        return view('pages::index', compact('pages'));
    }

    public function getCreate()
    {
        $banners = Category::where('module', 'banners')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        return view('pages::create', compact('banners'));
    }

    public function postCreate(CreatePageRequest $request)
    {

        if( $request->has('categoryBanner')) {
            $prefix = $categoryBanners = '';
            foreach ($request->get('categoryBanner') as $key => $value)
            {
                $categoryBanners .= $prefix . '' . $value . '';
                $prefix = ',';
            }    
            $request->merge(['banners' => $categoryBanners]);
            $request->offsetUnset('categoryBanner');
        }

        if (!$request->has('is_home')) {
            $request->merge(['is_home' => 0]);
        }
        if ($request->has('is_home') && $request->get('is_home') == '1' ) {
            Post::where('is_home', 1)->update(['is_home' => 0]);
        }

        $data = $request->all();
        if ($data['link_type'] == 'external') {
            $data['link'] = $data['link_external'];
            unset($data['link_external']);
            unset($data['link_internal']);
        } else if($data['link_type'] == 'internal') {
            $menu = Menu::find( (int) $data['link_internal']);
            if ($menu) {
                $data['link'] = $menu->url_internal;
            } else {
                $data['link'] = null;
            }
            unset($data['link_internal']);
            unset($data['link_external']);
        } else {
            $data['link'] = null;
            unset($data['link_internal']);
            unset($data['link_external']);
        }

        $page = Post::create($data);
        // $page->tag($request->get('tags'));
        // Add to Permission
        Ability::create(['name' => 'page-' . $page->slug, 'status' => 'publish']);
        
        if ($request->hasFile('image')) {
            $item = Post::find($page->id);
            $item->addMedia($request->file('image'))->toMediaCollection('image');
        }

        if ($request->hasFile('mobile')) {
            $item = Post::find($page->id);
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        if ($request->hasFile('file')) {
            $item = Post::find($page->id);
            $item->addMedia($request->file('file'))->toMediaCollection('file');
        }

        $this->postCreateRevision($request, $page->id);

        return redirect()->route('admin.pages.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $menus = Menu::where('status', 'publish')->get()->pluck('title', 'id');
        $pages = Post::where('type', 'revision-page')->where('revision_id', $id)->get();
        $banners = Category::where('module', 'banners')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        $page = Post::findOrFail($id);
        return view('pages::edit', compact('page', 'banners', 'pages', 'menus'));
    }

    public function postEdit(EditPageRequest $request, $id)
    {
        $redirectBack = null;
        if ($request->has('redirect-back')) {
            $redirectBack = $request->get('redirect-back');
        }

        $page = Post::findOrFail($id);
        $data = $request->all();
        if ($data['link_type'] == 'external') {
            $data['link'] = $data['link_external'];
            unset($data['link_external']);
            unset($data['link_internal']);
        } else if($data['link_type'] == 'internal') {
            $menu = Menu::find( (int) $data['link_internal']);
            if ($menu) {
                $data['link'] = $menu->url_internal;
            } else {
                $data['link'] = null;
            }
            unset($data['link_internal']);
            unset($data['link_external']);
        } else {
            $data['link'] = null;
            unset($data['link_internal']);
            unset($data['link_external']);
        }

        $page->update($data);

        if ($request->hasFile('desktop')) {
            $page->clearMediaCollection('desktop');
            $item = Post::find($id);
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $page->clearMediaCollection('mobile');
            $item = Post::find($id);
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        $this->postCreateRevision($request, $id);

        if ($redirectBack == 'yes') {
            return redirect()->route('admin.pages.edit', ['id' => $id, 'redirect-back' => 'yes'])
                                ->with('status', 'success')
                                ->with('message', 'Successfully');
        }

        return redirect()->route('admin.pages.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $page = Post::findOrFail($id);
        $page->clearMediaCollection();
        $page->delete();

        $entries = Post::where('revision_id', $id)->get();
        foreach ($entries as $entry) {
            $entry->delete();
        }

        return redirect()->route('admin.pages.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDeleteImage($id, $collection)
    {
        $page = Post::find($id);
        if ($page) {
            $page->clearMediaCollection($collection);
            return redirect()
                    ->route('admin.pages.edit', $id)
                    ->with('status', 'success')
                    ->with('message', 'Successfully');
        }

        return redirect()
                    ->route('admin.pages.index')
                    ->with('status', 'error')
                    ->with('message', 'Not data');
    }

    public function getDeleteFile($id)
    {
        $page = Post::find($id);
        if ($page) {
            $page->clearMediaCollection('file');
            return redirect()
                    ->route('admin.pages.edit', $id)
                    ->with('status', 'success')
                    ->with('message', 'Successfully');
        }

        return redirect()
                    ->route('admin.pages.index')
                    ->with('status', 'error')
                    ->with('message', 'Not data');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Post::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
                
                $entriesRevision = Post::where('revision_id', $entry->id)->get();
                foreach ($entriesRevision as $item) {
                    $item->delete();
                }

            }
            return redirect()->route('admin.pages.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.pages.index')
                        ->with('status', 'error')
                        ->with('message', 'Not data');
                        
    }

    public function postCreateRevision($request, $id)
    {
        $page = Post::find($id);
        $data                = $page->toArray();
        $data['revision_id'] = $page->id;
        $data['type']        = 'revision-page';
        
        $revisionPage = Post::create($data);
        if ($request->has('category')) {
            $revisionPage->categories()->sync([$request->get('category')]);
        }
        
        return $revisionPage->id;
    }

    public function getRevision(Request $request, $id)
    {
        $page = Post::findOrFail($id);
        $revisionToId = $page->revision_id;
        $data = [
            'user_id'          => $page->user_id,
            'slug'             => $page->slug,
            'title'            => $page->title,
            'title_en'         => $page->title_en,
            'excerpt'          => $page->excerpt,
            'excerpt_en'       => $page->excerpt_en,
            'content'          => $page->content,
            'content_en'       => $page->content_en,
            'start_published'  => $page->start_published,
            'end_published'    => $page->end_published,
            'parent_id'        => $page->parent_id,
            'order'            => $page->order,
            'is_home'          => $page->is_home,
            'status'           => $page->status,
            'layout'           => $page->layout,
            'link'             => $page->link,
            'video'            => $page->video,
            'banners'          => $page->banners,
            'caption1'         => $page->caption1,
            'caption2'         => $page->caption2,
            'pin'              => $page->pin,
            'meta_title'       => $page->meta_title,
            'meta_keywords'    => $page->meta_keywords,
            'meta_description' => $page->meta_description,
        ];
        Post::where('id', $revisionToId)->update($data);

        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.pages.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('pages', function($excel) {
            $entries = Post::where('type', 'page')->get();
            $excel->sheet('menu', function($sheet) use ($entries) {
                $sheet->fromArray($entries);
            });
        })->export('xls');

    }


}
