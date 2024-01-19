<?php

namespace App\Modules\Contact\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Contact\Models\Contact;

class IndexController extends Controller
{
    public function getIndex()
    {
    	$items = Contact::with('subject')
                            ->orderBy('created_at','DESC')
                            //->toSql();
                            ->get();
        //dd($items);
    	return view('contact::index', compact('items'));
    }

    public function getDelete($id)
    {
        Contact::find($id)->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Contact::whereIn('id', $request->input('ids'))->get();
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
}
