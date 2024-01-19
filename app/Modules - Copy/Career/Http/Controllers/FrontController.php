<?php
namespace App\Modules\Career\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Career\Models\Career;

class FrontController extends Controller
{
    public function postUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:docx,pdf'
        ]);
        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $destinationPath = 'uploads/cv/';
        $file->move($destinationPath, $filename);
        Career::create(['name' => $filename, 'file_path' => $destinationPath . $filename, 'status' => 'publish']);
        return redirect()->back()->with('status', 'success')->with('message', 'Successfully!');
    }
}
