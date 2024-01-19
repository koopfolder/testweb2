<?php

namespace App\Modules\Export\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use App\User;
use Module;
use DB;

class IndexController extends Controller
{
    public function getIndex(Request $request, $moduleSlug = null, $table = null)
    {
    	$moduleProperties = Module::getManifest($moduleSlug);
    	if (!$moduleProperties) {
            return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    	}

    	$properties = $moduleProperties->toArray();
    	$fields = explode(", ", $properties['export']);
        $items = DB::table($table)->select($fields)->get();
        $data = [];
        if ($items->isNotEmpty()) {
            foreach ($items->toArray() as $item) {
                $data[] = (array) $item;
            }
        }

        Excel::create($moduleSlug, function($excel) use ($data) {
            $excel->sheet('export', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls');

     	return redirect()->back();
    }
}
