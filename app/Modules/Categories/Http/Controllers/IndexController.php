<?php

namespace App\Modules\Categories\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Silber\Bouncer\Database\Role;
use Illuminate\Support\Facades\Gate;
use App\Modules\Categories\Http\Requests\CreateCategoryRequest;
use App\Modules\Categories\Http\Requests\EditCategoryRequest;
use App\Category;
use Excel;
use Module;

class IndexController extends Controller
{
    public function getIndex(Request $request)
    {
        $modules = Module::enabled()->pluck('slug', 'name');
        $categories = Category::where('parent_id', 0)->orderBy('id', 'DESC')->get();
        return view('categories::index', compact('categories', 'modules'));
    }

    public function postCreate(CreateCategoryRequest $request)
    {
        dd($request->all());
        $category = Category::create($request->all());
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $categories = Category::where('parent_id', 0)->orderBy('id', 'DESC')->get();
        $category = Category::findOrFail($id);
        return view('categories::edit', compact('category', 'categories'));
    }

    public function postEdit(EditCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('admin.' . $request->get('module') . '.categories.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');

    }

    public function getDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Category::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->clearMediaCollection('images');
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

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.categories.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('Filename', function($excel) {
            $entries = Category::all()->toArray();
            $excel->sheet('Sheetname', function($sheet) use ($entries) {
                $sheet->fromArray($entries);
            });
        })->export('xls'); 

    }
}
