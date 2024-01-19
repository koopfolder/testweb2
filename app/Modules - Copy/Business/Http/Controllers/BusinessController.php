<?php

namespace App\Modules\Business\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Business\Http\Requests\{CreateRequest};
use App\Modules\Business\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class BusinessController extends Controller
{
    public function getIndex()
    {
        $data = Article::Data(['status'=>['publish'],'page_layout'=>'company-business']);
        //dd($data->count());
        if($data->count()){
            $id = $data->id;
        }else{
            $id = '0';
        }
        $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();
        return view('business::edit', compact('data','revisions'));
    }


    public function postStore(CreateRequest $request)
    {
        $data = $request->all();
        $data['page_layout'] = 'company-business';
        $check =  Article::where('page_layout','=','company-business')->count();
        if($check >0){
            unset($data['_token']);
            unset($data['datatable_length']);
            $item = Article::where('page_layout','=','company-business')->first();
            $id = $item->id;
            //$item->update($data);
            $item = Article::where('page_layout','=','company-business')->update($data);
            //dd($id);
            $data['article_id'] = $id;
            ArticleRevision::create($data);
        }else{
            $item = Article::create($data);
            $data['article_id'] = $item->id;
            ArticleRevision::create($data);
        }
        return redirect()->route('admin.business.index')
                            ->with('status', 'success')
                            ->with('message', trans('business::backend.successfully'));
    }


    public function getReverse($id)
    {
        $reverse = ArticleRevision::find($id);
        $ArticleId = $reverse->article_id;
        $fields = array_except($reverse->toArray(),['article_id','created_by']);
        $article = Article::find($ArticleId);
        $article->description_th = $fields['description_th'];
        $article->description_en = $fields['description_en'];
        $article->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


}

