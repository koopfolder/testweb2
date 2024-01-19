<?php

namespace App\Modules\Career\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Career\Models\Career;

class IndexController extends Controller
{
    public function getIndex()
    {
        $careers = Career::all();
    	return view('career::index', compact('careers'));
    }

    public function getDelete($id)
    {
        $item = Career::findOrFail($id);
        if (file_exists($item->file_path)) {
            unlink($item->file_path);
        }
        $item->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Career::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                unlink($entry->file_path);
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
}
