<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\CreateProductRequest;
use App\Modules\Product\Http\Requests\EditProductRequest;
use App\Modules\Categories\Http\Requests\CreateCategoryRequest;
use App\Modules\Categories\Http\Requests\EditCategoryRequest;
use App\Post;
use App\Category;
use Excel;
use Carbon\Carbon;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Silber\Bouncer\Database\Ability;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $categoryName = null;
        $products = Category::with('posts')->get();
        if ($request->has('category')) {
            $posts = Category::find($request->get('category'));
            if ($posts) {
                $categoryName = $posts->title;
                $products = $posts->posts()->get();
            } else {
                $products = array();
            }
            // $products = Category::find($request->get('category'))->posts()->get();
        }

        return view('product::index', compact('products', 'categoryName'));
    }

    public function getCreate()
    {
        $categories = Category::where('module', 'product')->get()->pluck('title', 'id');
        return view('product::create', compact('categories'));
    }

    public function postCreate(CreateProductRequest $request)
    {
        $data = $request->all();

        $redirect = $data['redirect'];
        unset($data['redirect']);

        $post = Post::create($data);
        // Insert to category
        $post->categories()->attach($request->get('category'));

        if ($request->hasFile('desktop')) {
            $item = Post::find($post->id);
            $item->addMedia($request->file('desktop'))->toMediaCollection('desktop');
        }

        if ($request->hasFile('mobile')) {
            $item = Post::find($post->id);
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }

        $this->postCreateRevision($request, $post->id);

        return redirect()->route('admin.product.index', ['category' => $redirect])
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $pages = Post::where('type', 'revision-product')->where('revision_id', $id)->get();
        $categories = Category::where('module', 'product')->get()->pluck('title', 'id');
        $post = Post::findOrFail($id);
        return view('product::edit', compact('post', 'categories', 'pages'));   
    }

    public function postEdit(EditProductRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->all();

        $redirect = $data['redirect'];
        unset($data['redirect']);

        $post->update($data);

        // Insert to category
        $post->categories()->sync($request->get('category'));
        
        if ($request->hasFile('desktop')) {
            $item = Post::find($post->id);
            $item->clearMediaCollection('desktop');
            $pathToImage = $item->addMedia($request->file('desktop'))->toMediaCollection('desktop')->getUrl();
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(public_path($pathToImage));
        }

        if ($request->hasFile('mobile')) {
            $item = Post::find($post->id);
            $item->clearMediaCollection('mobile');
            $item->addMedia($request->file('mobile'))->toMediaCollection('mobile');
        }
        
        $this->postCreateRevision($request, $id);

        return redirect()->route('admin.product.index', ['category' => $redirect])
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $entries = Post::where('revision_id', $id)->get();
        foreach ($entries as $entry) {
            $entry->delete();
        }

        return redirect()->route('admin.product.index', ['category' => $request->get('category')])
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        $redirect = null;
        if ($request->has('redirect')) {
            $redirect = $request->get('redirect');    
        }
        
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
            return redirect()->route('admin.product.index', ['category' => $redirect])
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.news.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
                        
    }

    public function getCategories(Request $request)
    {
        $categories = Category::where('module', 'product')->where('parent_id', 0)->orderBy('order', 'ASC')->get();
        return view('product::categories.index', compact('categories'));
    }

    public function getCategoriesCreate()
    {
        return view('product::categories.create');
    }

    public function postCategoriesCreate(CreateCategoryRequest $request)
    {
        $category = Category::create($request->all());
        Ability::create(['name' => 'page-' . $category->slug, 'status' => 'publish']);
        if ($request->hasFile('bg')) {
            $item = Category::find($category->id);
            $item->clearMediaCollection('bg');
            $item->addMedia($request->file('bg'))->toMediaCollection('bg');
        }

        return redirect()->route('admin.product.categories.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getCategoriesEdit($id)
    {
        $pages = Post::where('type', 'revision-product-category')->where('revision_id', $id)->get();
        $category = Category::findOrFail($id);
        return view('product::categories.edit', compact('category', 'pages'));
    }

    public function postCategoriesEdit(EditCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->hasFile('bg')) {
            $item = Category::find($id);
            $item->clearMediaCollection('bg');
            $item->addMedia($request->file('bg'))->toMediaCollection('bg');
        }
        $category->update($request->all());

        return redirect()->route('admin.product.categories.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getCategoriesDelete(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->clearMediaCollection();
        $category->delete();

        return redirect()
                    ->route('admin.product.categories.index')
                    ->with('status', 'success')
                    ->with('message', 'Successfully');
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

    public function postCreateRevision($request, $id)
    {
        $page = Post::find($id);
        $data                = $page->toArray();
        $data['revision_id'] = $page->id;
        $data['type']        = 'revision-product';
        
        $revisionPage = Post::create($data);
        
        return $revisionPage->id;
    }

    public function getDeleteRevision($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.news.revision.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAllRevision(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Post::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection();
                $entry->delete();
            }
            return redirect()->route('admin.news.revision.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.news.revision.index')
                        ->with('status', 'error')
                        ->with('message', 'Not data');
                        
    }

    public function getReview($id)
    {
        $categories = Category::where('module', 'news')->get()->pluck('title', 'id');
        $page = Post::findOrFail($id);
        $banners = Category::where('module', 'banners')->where('status', 'publish')->orderBy('title', 'ASC')->get();
        return view('news::revision.review', compact('page', 'categories', 'banners'));   
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

        return redirect()->route('admin.news.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getExport(Request $request, $fileType = null)
    {
        $category = $request->get('category');
        if (is_null($fileType)) {
            return redirect()->back()
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        $category = Category::find($category);
        foreach($category->posts()->get() as $post) {
            $data[] = [
                'รหัสสินค้า'         => $post->id,
                'ชื่อสินค้า'         => $post->title,
                'ชื่อสินค้า(อังกฤษ)' => $post->title_en,
                'ราคา'               => $post->excerpt,
                'รายละเอียด'         => $post->content,
                'รายละเอียด(อังกฤษ)' => $post->content_en,
                'สถานะ'              => $post->status,
                'สร้างเมื่อ'         => $post->created_at
            ];
        }

        Excel::create('product', function($excel) use($data) {
            $excel->sheet('product', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls'); 

    }

    public function getExportCategory($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->back()
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('category-of-products', function($excel) {
            $data = Category::where('module', 'product')->get();
            $excel->sheet('category-of-products', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls'); 

    }

    public function getExportRevision($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.news.revision.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('revision-news', function($excel) {
            $data = Post::where('type', 'revision-news')->get();
            $excel->sheet('revision-news', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls'); 

    }

    public function getPublish($id, $status)
    {
        $post = Post::find($id);
        $post->update(['status' => $status]);

        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

}
