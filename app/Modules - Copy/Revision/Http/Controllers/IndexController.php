<?php

namespace App\Modules\Revision\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use Excel;

class IndexController extends Controller
{
    public function getIndex()
    {
        $pages = Post::where('type', 'revision-page')->get();
        return view('revision::index', compact('pages'));
    }

    public function getReview($id)
    {
        $categories = Category::where('module', 'news')->get()->pluck('title', 'id');
        $page = Post::findOrFail($id);
        $banners = Category::where('module', 'banners')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        return view('revision::review', compact('page', 'categories', 'banners'));   
    }

    public function postReview(Request $request, $id)
    {
        $page = Post::findOrFail($id);
        $revisionToId = $page->revision_id;
        $data = [
			'user_id'          => $page->user_id,
			'slug'             => $page->slug,
			'title'            => $page->title,
			'excerpt'          => $page->excerpt,
			'content'          => $page->content,
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
			'meta_keywords'    => $page->meta_keywords,
			'meta_description' => $page->meta_description,
        ];
        Post::where('id', $revisionToId)->update($data);

        return redirect()->route('admin.pages.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.revision.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Post::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.revision.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.revision.index')
                        ->with('status', 'error')
                        ->with('message', 'Not data');
                        
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.revision.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('revision-page', function($excel) {
            $data = Post::where('type', 'revision-page')->get();
            $excel->sheet('revision-page', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls'); 

    }
}
