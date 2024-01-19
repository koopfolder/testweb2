<?php

namespace App\Modules\Documentsdownload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Documentsdownload\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Documentsdownload\Models\{Documents};
use RoosterHelpers;

class BusinessethicsController extends Controller
{
    public function getIndex()
    {
        $items = Documents::Data(['status'=>['publish','draft'],'document_type'=>'business_ethics']);
        return view('documentsdownload::business_ethics.index', compact('items'));
    }

    public function getCreate()
    {
        return view('documentsdownload::business_ethics.create');
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        $data['document_type'] = 'business_ethics';
        $item = Documents::create($data);
        if($request->hasFile('attached_file_th')){
            //dd("File");
            $destinationPath =  public_path().'/files/module_documents/'.$item->id; // upload path
            $destinationPath2 = '/files/module_documents/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file_th')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_th'.'.'.$extension; // renameing image
                Input::file('attached_file_th')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_th'=>$fileName,"file_path_th"=>$path_file,'file_type_th'=>$extension]);
            }else{
                $extension = Input::file('attached_file_th')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_th'.'.'.$extension; // renameing image
                Input::file('attached_file_th')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_th'=>$fileName,"file_path_th"=>$path_file,'file_type_th'=>$extension]);
            }

        }

        if($request->hasFile('attached_file_en')){
            //dd("File");
            $destinationPath =  public_path().'/files/module_documents/'.$item->id; // upload path
            $destinationPath2 = '/files/module_documents/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file_en')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_en'.'.'.$extension; // renameing image
                Input::file('attached_file_en')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_en'=>$fileName,"file_path_en"=>$path_file,'file_type_en'=>$extension]);
            }else{
                $extension = Input::file('attached_file_en')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_en'.'.'.$extension; // renameing image
                Input::file('attached_file_en')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_en'=>$fileName,"file_path_en"=>$path_file,'file_type_en'=>$extension]);
            }
        }
        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        if($request->hasFile('cover_mobile')) {
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }

        return redirect()->route('admin.documents-download.business-ethics.index')
                            ->with('status', 'success')
                            ->with('message', trans('documentsdownload::backend.successfully'));
    }

    public function getEdit($id)
    {
        $data = Documents::findOrFail($id);
        return view('documentsdownload::business_ethics.edit', compact('data'));
    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Documents::findOrFail($id);
        $data = $request->all();
        $item->update($data);

        //dd($data);
        if($request->hasFile('attached_file_th')){
            //dd("File");
            $destinationPath =  public_path().'/files/module_documents/'.$item->id; // upload path
            $destinationPath2 = '/files/module_documents/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file_th')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_th'.'.'.$extension; // renameing image
                Input::file('attached_file_th')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_th'=>$fileName,"file_path_th"=>$path_file,'file_type_th'=>$extension]);
            }else{

                $extension = Input::file('attached_file_th')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_th'.'.'.$extension; // renameing image
                Input::file('attached_file_th')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_th'=>$fileName,"file_path_th"=>$path_file,'file_type_th'=>$extension]);

            }

        }

        if($request->hasFile('attached_file_en')){
            //dd("File EN");
            $destinationPath =  public_path().'/files/module_documents/'.$item->id; // upload path
            $destinationPath2 = '/files/module_documents/'.$item->id;
            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('attached_file_en')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_en'.'.'.$extension; // renameing image
                Input::file('attached_file_en')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_en'=>$fileName,"file_path_en"=>$path_file,'file_type_en'=>$extension]);
            }else{
                $extension = Input::file('attached_file_en')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').'_en'.'.'.$extension; // renameing image
                Input::file('attached_file_en')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = $destinationPath2."/".$fileName;
                $item->update(['file_name_en'=>$fileName,"file_path_en"=>$path_file,'file_type_en'=>$extension]);

            }
        }
        
        //dd("Update Success");
        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if ($request->hasFile('cover_mobile')) {
            $item->clearMediaCollection('cover_mobile');
            $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        }
        return redirect()->route('admin.documents-download.business-ethics.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getDelete($id)
    {
        $item = Documents::findOrFail($id);
        $item->delete();
        $destinationPath =  public_path().'/files/module_documents/'.$id;
        \File::deleteDirectory($destinationPath);
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Documents::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                $destinationPath =  public_path().'/files/module_documents/'.$entry->id; // upload path
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
