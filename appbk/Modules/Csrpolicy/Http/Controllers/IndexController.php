<?php

namespace App\Modules\Csrpolicy\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Csrpolicy\Http\Requests\{CreatecorporategovpolicyRequest, EditcorporategovpolicyRequest};
use App\Modules\Csrpolicy\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'csr-policy']);
        //dd($items);
      //  return view('csrpolicy::index', compact('items'));
        if(collect($items)->isEmpty()){
          return $this->getCreate();
        }else{
          return $this->getEdit($items[0]->id);
        }
    }

    public function getCreate()
    {
        //dd("getCreate");
        return view('csrpolicy::create');
    }

    public function postCreate(CreatecorporategovpolicyRequest $request)
    {
        $data = $request->all();
        //dd($data);
        $data['page_layout'] = 'csr-policy';
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        ArticleRevision::create($data);


        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        if($request->hasFile('cover_mobile')) {
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }


        return redirect()->route('admin.csrpolicy.index')
                            ->with('status', 'success')
                            ->with('message', trans('csrpolicy::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Article::findOrFail($id);
        $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();
        //dd($data,$revisions);
        return view('csrpolicy::edit', compact('data','revisions'));
    }

    public function postEdit(EditcorporategovpolicyRequest $request, $id)
    {
        $item = Article::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        $item->update($data);
        $data['article_id'] = $id;
        ArticleRevision::create($data);
        //dd("Update Success");

        if($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        if($request->hasFile('cover_mobile')) {
            $item->clearMediaCollection('cover_mobile');
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }
      


        return redirect()->route('admin.csrpolicy.index')
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
        $article->title_th = $fields['title_th'];
        $article->title_en = $fields['title_en'];
        $article->description_th = $fields['description_th'];
        $article->description_en = $fields['description_en'];
        $article->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }
}
