<?php

namespace App\Modules\Abilities\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use Silber\Bouncer\Database\Ability;
use App\Modules\Abilities\Http\Requests\CreateAbilityRequest;
use App\Modules\Abilities\Http\Requests\EditAbilityRequest;
use Excel;

class IndexController extends Controller
{
    public function getIndex()
    {
        $abilities = Ability::orderBy('id', 'DESC')->get();
        return view('abilities::index', compact('abilities'));
    }

    public function getCreate()
    {
        return view('abilities::create');
    }

    public function postCreate(CreateAbilityRequest $request)
    {
        $ability = Ability::create($request->all());
        return redirect()->route('admin.abilities.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getEdit($id)
    {
        $ability = Ability::findOrFail($id);
        return view('abilities::edit', compact('ability'));
    }

    public function postEdit(EditAbilityRequest $request, $id)
    {
        $ability = Ability::findOrFail($id);
        $ability->update($request->all());
        return redirect()->route('admin.abilities.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function getDelete($id)
    {
        $ability = Ability::findOrFail($id);
        $ability->delete();
        return redirect()->route('admin.abilities.index')
                        ->with('status', 'status')
                        ->with('message', 'Successfully');
    }

    public function getDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Ability::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('admin.abilities.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
        }

        return redirect()->route('admin.abilities.index')
                        ->with('status', 'error')
                        ->with('message', 'Not users');
                        
    }

    public function getExport($fileType = null)
    {
        if (is_null($fileType)) {
            return redirect()->route('admin.abilities.index')
                        ->with('status', 'error')
                        ->with('message', 'Not File type.');
        }

        Excel::create('Filename', function($excel) {
            $users = Ability::all()->toArray();
            $excel->sheet('Sheetname', function($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls'); 

    }
}
