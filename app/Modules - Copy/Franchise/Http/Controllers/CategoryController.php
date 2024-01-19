<?php

namespace App\Modules\Franchise\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Franchise\Http\Requests\{CreateCategoryRequest, EditCategoryRequest};
use App\Modules\Franchise\Models\{FranchiseCategory,FranchiseCategoryRevision};
use Illuminate\Support\Facades\Response;
use DbdHelpers;
use Carbon\Carbon;
class CategoryController extends Controller
{
    public function getIndex()
    {
        $items = FranchiseCategory::Data(['status'=>['publish','draft']]);
        return view('franchise::category.index', compact('items'));

    }

    public function getCreate()
    {
        //dd("getCreate");
        return view('franchise::category.create');
    }

    public function postCreate(CreateCategoryRequest $request)
    {
        $data = $request->all();
        $item = FranchiseCategory::create($data);
        $id = $item->id;
        $data['category_id'] = $id;
        FranchiseCategoryRevision::create($data);

        return redirect()->route('admin.franchise.category.index')
                            ->with('status', 'success')
                            ->with('message', trans('franchise::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = FranchiseCategory::findOrFail($id);
        $revisions = FranchiseCategoryRevision::where('category_id', $id)->orderBy('created_at','DESC')->get();
        return view('franchise::category.edit', compact('data','revisions'));
    }

    public function postEdit(EditCategoryRequest $request, $id)
    {

        $item = FranchiseCategory::findOrFail($id);
        $data = $request->all();
        //dd($data);
        $item->update($data);
        $data['category_id'] = $id;
        FranchiseCategoryRevision::create($data);

        //dd($data);
        return redirect()->route('admin.franchise.category.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = FranchiseCategory::findOrFail($id);
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = FranchiseCategory::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
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
        $reverse = FranchiseCategoryRevision::find($id);
        $CategoryId = $reverse->category_id;
        $fields = array_except($reverse->toArray(),['category_id','created_by']);
        $franchise_category = FranchiseCategory::find($CategoryId);
        $franchise_category->category_name = $fields['category_name'];
        $franchise_category->description = $fields['description'];
        $franchise_category->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }

}
