<?php

namespace App\Modules\Article\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Article\Http\Requests\{CreateHealthRequest,EditHealthRequest};
use App\Modules\Article\Models\{ArticleCategory};
use App\Modules\Documentsdownload\Models\{Documents};
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Cache;
use ThrcHelpers;


class ArticleCategoryController extends Controller
{
    public function getIndex()
    {
        //dd("ArticleCategory Index");
        $items = ArticleCategory::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('article::backend.article-category.index', compact('items'));
    }

    public function getIndexiframe()
    {
        $items = ArticleCategory::Data(['status'=>['publish']]);
        return view('article::backend.article-category.index-iframe', compact('items'));
    }

    public function getCreate()
    {
        return view('article::backend.article-category.create');
    }

    public function postCreate(CreateHealthRequest $request)
    {
        $data = $request->all();
        //dd($data);
        //dd($data);
        $data['type'] = 'health-literacy';
        $item = ArticleCategory::create($data);

        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'31']);
        return redirect()->route('admin.health-literacy-category.index')
                            ->with('status', 'success')
                            ->with('message', trans('article::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ArticleCategory::findOrFail($id);
        //dd($data);
        return view('article::backend.article-category.edit', compact('data'));
    }

    public function postEdit(EditHealthRequest $request, $id)
    {

        $item = ArticleCategory::findOrFail($id);
        $data = $request->all();
        //dd($id,$data);
        $item->update($data);

        if ($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data['title'].'"','module_id'=>'31']);
        return redirect()->route('admin.health-literacy-category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = ArticleCategory::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = ArticleCategory::whereIn('id', $request->input('ids'))->get();
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


    public static function getDataHealthLiteracy($params)
    {

        $page = (isset($params['page']) ? $params['page']:'1');
        $time_cache  =  ThrcHelpers::time_cache(5);
        $article = '';
        if (Cache::has('data_health_literacy_category_page_'.$page)){
            $article = Cache::get('data_health_literacy_category_page_'.$page);
        }else{
            $article = ArticleCategory::FrontList([]);
            //dd($article);
            //$article = ViewInterestingIssues::FrontList(['page'=>$page]);
            Cache::put('data_health_literacy_category_page_'.$page,$article,$time_cache);
            $article = Cache::get('data_health_literacy_category_page_'.$page);
        }

        $data = array();
        $data['title_h1'] = 'สื่อและเครื่องมือ';
        $data['layout'] = 'list_health_literacy_case_front';
        $data['items'] = $article;

        return $data;
    }



    public static function getListArticleCategory(){
        //dd("getListInterestingissuesCaseFront");
        return view('template.list_health_literacy_cagetory_case_front');
    }




}

