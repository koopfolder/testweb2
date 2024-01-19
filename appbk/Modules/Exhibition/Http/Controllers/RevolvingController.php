<?php

namespace App\Modules\Exhibition\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Exhibition\Http\Requests\{CreateArticleRequest, EditArticleRequest};
use App\Modules\Exhibition\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use Junity\Hashids\Facades\Hashids;

class RevolvingController extends Controller
{
    public function getIndex()
    {
        //dd("RevolvingController");
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'revolving_exhibition']);
        //dd($items);
        return view('exhibition::revolving_exhibition.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = Article::Data(['status'=>['publish'],'page_layout'=>'revolving_exhibition']);
        return view('exhibition::revolving_exhibition.index-iframe', compact('items'));
    }

    public function getCreate()
    {
        return view('exhibition::revolving_exhibition.create');
    }

    public function postCreate(CreateArticleRequest $request)
    {
        $data = $request->all();
        //dd($data);

        $data['page_layout'] = 'revolving_exhibition';
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        ArticleRevision::create($data);

        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }
        return redirect()->route('admin.exhibition.revolving.index')
                            ->with('status', 'success')
                            ->with('message', trans('exhibition::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Article::findOrFail($id);
        $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();

        //dd($data,$revisions);
        return view('exhibition::revolving_exhibition.edit', compact('data','revisions'));
    }

    public function postEdit(EditArticleRequest $request, $id)
    {

        $item = Article::findOrFail($id);
        $data = $request->all();
       // dd($id,$data);

        $item->update($data);
        $data['article_id'] = $id;
        ArticleRevision::create($data);

        if ($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }
        
        return redirect()->route('admin.exhibition.revolving.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Article::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Article::whereIn('id', $request->input('ids'))->get();
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
        $reverse = ArticleRevision::find($id);
        $ArticleId = $reverse->article_id;
        $fields = array_except($reverse->toArray(),['article_id','created_by']);
        $article = Article::find($ArticleId);
        $article->title = $fields['title'];
        $article->description = $fields['description'];
        $article->short_description = $fields['short_description'];
        $article->featured = $fields['featured'];
        $article->meta_title = $fields['meta_title'];
        $article->meta_keywords = $fields['meta_keywords'];
        $article->meta_description = $fields['meta_description'];
        $article->hit = $fields['hit'];
        $article->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


    public function postAjaxDeleteGallery(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $media_id = $inputs['id'];
                Article::whereHas('media', function ($query) use($media_id){
                         $query->whereId($media_id);
                })->first()->deleteMedia($media_id);
                $response['msg'] ='sucess';
                $response['status'] =true;
               // $response['data'] = $directory;
                return  Response::json($response,200);
            }else{
                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);
            }
        }catch (\Exception $e){
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);
        }
    }

}

