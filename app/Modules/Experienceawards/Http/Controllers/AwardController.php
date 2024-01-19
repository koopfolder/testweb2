<?php

namespace App\Modules\Experienceawards\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Experienceawards\Http\Requests\{CreatecorporategovpolicyRequest, EditcorporategovpolicyRequest};
use App\Modules\Experienceawards\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use App\Modules\Experienceawards\Http\Requests\CreateRequest;
use App\Modules\Experienceawards\Http\Requests\EditRequest;
use RoosterHelpers;

class AwardController extends Controller
{
    public function getIndex()
    {
          $items = Article::Data(['status'=>['publish','draft'],'page_layout'=>'awards']);

        return view('experienceawards::awards.index', compact('items'));
    }

    public function getCreate()
    {
        return view('experienceawards::awards.create');
    }

    public function postCreate(CreateRequest $request)
    {
      $data = $request->all();

      $data['page_layout'] = 'awards';

      $item = Article::create($data);
      //  dd($data);
      $id = $item->id;
      $data['article_id'] = $id;

      ArticleRevision::create($data);

      if($request->hasFile('cover_desktop')) {
          $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
      }
      if($request->hasFile('cover_mobile')) {
          $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
      }

      return redirect()->route('admin.experience.awards.index')
                          ->with('status', 'success')
                          ->with('message', trans('experienceawards::backend.successfully'));
    }

    public function getEdit($id)
    {
      //dd("Get Edit");
      $data = Article::findOrFail($id);
      $revisions = ArticleRevision::where('article_id', $id)->orderBy('created_at','DESC')->get();
      //dd($data,$revisions);
      return view('experienceawards::awards.edit', compact('data','revisions'));
    }

    public function postEdit(EditRequest $request, $id)
    {
      $item = Article::findOrFail($id);
      $data = $request->all();
      //dd($id,$data);
      $item->update($data);
      $data['article_id'] = $id;

      if ($request->hasFile('cover_desktop')) {
          $item->clearMediaCollection('cover_desktop');
          $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
      }

      if ($request->hasFile('cover_mobile')) {
          $item->clearMediaCollection('cover_mobile');
          $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
      }

      ArticleRevision::create($data);


      return redirect()->route('admin.experience.awards.index')
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

    public function getReverse($bannerId)
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
