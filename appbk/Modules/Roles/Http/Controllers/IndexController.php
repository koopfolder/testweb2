<?php

namespace App\Modules\Roles\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;
use Illuminate\Support\Facades\Gate;
use App\Modules\Roles\Http\Requests\CreateRoleRequest;
use App\Modules\Roles\Http\Requests\EditRoleRequest;
use Excel;

class IndexController extends Controller
{
    public function getIndex()
    {
        $roles = Role::where('name', '!=', 'Administrator')->orderBy('id', 'DESC')->get();
        return view('roles::index', compact('roles'));
    }

    public function getCreate()
    {
        $abilities = Ability::where('status', 'publish')->get()->pluck('name', 'name');
        return view('roles::create', compact('abilities'));
    }

    public function postCreate(CreateRoleRequest $request)
    {

        $inputs = $request->all();
        //dd($inputs);
        $role = Role::create($inputs);
        $role->allow($request->input('abilities'));
        self::postLogs(['event'=>'เพิ่มข้อมูลหัวข้อ "'.$inputs['name'].'"','module_id'=>'21']);
        return redirect()->route('admin.roles.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $abilities = Ability::where('status', 'publish')->get()->pluck('name', 'name');
        $role = Role::findOrFail($id);
        return view('roles::edit', compact('role', 'abilities'));
    }

    public function postEdit(EditRoleRequest $request, $id)
    {
        $inputs = $request->all();
        $role = Role::findOrFail($id);
        //dd($inputs);
        $role->update($inputs);

        foreach ($role->getAbilities() as $ability) {
            $role->disallow($ability->name);
        }
        $role->allow($request->get('abilities'));
        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$inputs['name'].'"','module_id'=>'21']);
        return redirect()->route('admin.roles.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.roles.index')
                        ->with('status', 'status')
                        ->with('message', 'Successfully');
    }

    public function getDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Role::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('admin.roles.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.roles.index')
                        ->with('status', 'error')
                        ->with('message', 'Not data');
                        
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.roles.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('Filename', function($excel) {
            $users = Role::all()->toArray();
            $excel->sheet('Sheetname', function($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls'); 

    }

}
