<?php

namespace App\Modules\Chairmanstatement\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Chairmanstatement\Http\Requests\{CreatecorporategovpolicyRequest, EditcorporategovpolicyRequest};
use App\Modules\Chairmanstatement\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class IndexController extends Controller
{
    public function getIndex()
    {
        $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'chairman-statement']);

        if(collect($items)->isEmpty()){
          return $this->getCreate();
        }else{
          return $this->getEdit($items[0]->id);
        }

    }

    public function getCreate()
    {
      //  dd("getCreate");
        return view('chairmanstatement::create');
    }

    public function postCreate(CreatecorporategovpolicyRequest $request)
    {
        $data = $request->all();
        //dd($data);
        $data['page_layout'] = 'chairman-statement';
        $item = Article::create($data);
        $id = $item->id;
        $data['article_id'] = $id;
        ArticleRevision::create($data);
        return redirect()->route('admin.chairmanstatement.index')
                            ->with('status', 'success')
                            ->with('message', trans('chairmanstatement::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = Article::findOrFail($id);
        $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();
        //dd($data,$revisions);
        return view('chairmanstatement::edit', compact('data','revisions'));
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
        return redirect()->route('admin.chairmanstatement.index')
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
