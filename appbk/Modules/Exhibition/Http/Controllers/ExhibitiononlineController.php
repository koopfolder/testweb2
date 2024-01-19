<?php

namespace App\Modules\Exhibition\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Exhibition\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Exhibition\Models\{Exhibition};
use Zipper;

class ExhibitiononlineController extends Controller
{
    public function getIndex()
    {
        //dd("Get Index");
        $items = Exhibition::Data(['status'=>['publish','draft']]);
        //dd($items);
        return view('exhibition::online_exhibition.index', compact('items'));
    }

    public function getCreate()
    {
        return view('exhibition::online_exhibition.create');
    }

    public function getIndexiframe()
    {
        $items = Exhibition::Data(['status'=>['publish','draft']]);
        return view('exhibition::online_exhibition.index-iframe', compact('items'));
    }


    public function postCreate(CreateRequest $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('max_file_uploads', '50M');
        ini_set('upload_max_filesize', '50M');
        set_time_limit(0);
        $data = $request->all();
        //dd($data);
        $item = Exhibition::create($data);

        if($request->hasFile('attached_file')){
            //dd("File");
            $destinationPath =  public_path().'/files/html/'.$item->id; // upload path
            $destinationPath2 = '/files/html/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('attached_file')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name'=>$fileName,"file_path"=>$path_file,'file_type'=>$extension]);
            }else{
                $extension = Input::file('attached_file')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('attached_file')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name'=>$fileName,"file_path"=>$path_file,'file_type'=>$extension]);
            }

            Zipper::make(public_path($path_file))->extractTo($destinationPath);
        }

       // dd("Insert Success",asset($path_file),public_path($path_file),Zipper::make(public_path($path_file))->getStatus());

        if($request->hasFile('cover_desktop')){
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }


        return redirect()->route('admin.exhibition.online.index')
                            ->with('status', 'success')
                            ->with('message', trans('exhibition::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = Exhibition::findOrFail($id);
       // dd($data);
        return view('exhibition::online_exhibition.edit', compact('data'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Exhibition::findOrFail($id);
        $data = $request->all();
        $item->update($data);

        if($request->hasFile('attached_file')){
            //dd("File");
            $destinationPath =  public_path().'/files/html/'.$item->id; // upload path
            $destinationPath2 = '/files/html/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('attached_file')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name'=>$fileName,"file_path"=>$path_file,'file_type'=>$extension]);
            }else{
                $extension = Input::file('attached_file')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('attached_file')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name'=>$fileName,"file_path"=>$path_file,'file_type'=>$extension]);
            }

            Zipper::make(public_path($path_file))->extractTo($destinationPath);
        }

        if($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        return redirect()->route('admin.exhibition.online.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Exhibition::findOrFail($id);
        $item->delete();
        $destinationPath =  public_path().'/files/html/'.$id;
        \File::deleteDirectory($destinationPath);
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Exhibition::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $destinationPath =  public_path().'/files/html/'.$entry->id; // upload path
                \File::deleteDirectory($destinationPath);
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
            
}
