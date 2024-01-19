<?php

namespace App\Modules\Franchise\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Franchise\Http\Requests\{CreateRequest,EditRequest,CreateImportRequest};
use App\Modules\Franchise\Models\{Franchise,FranchiseCategory,FranchiseRevision,FranchiseBranch,Province,District,Subdistrict};
use App\Modules\Documentsdownload\Models\{DocumentsFranchise};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Carbon\Carbon;
use File;
use Excel;

class IndexController extends Controller
{

    public function getIndex()
    {

        $items = Franchise::Data(['status'=>['publish','draft']]);
       // dd($items);
        return view('franchise::index', compact('items'));
    }

    public function getCreate()
    {
        $category = FranchiseCategory::Data(['status'=>['publish']])->pluck('category_name', 'id');
        $category = collect($category)->toArray();
        $category[0] = " เลือกหมวดหมู่ ";
        ksort($category);
        return view('franchise::create',compact('category'));
    }

    public function postCreate(CreateRequest $request)
    {
        $data = $request->all();
        //dd($data,"Success",$request->File('document'));
        $data['contact_latitude'] = $data['lat'];
        $data['contact_longitude'] = $data['lng'];

        $province = Province::Data(['field'=>'province_name_th','val'=>$data['contact_province']]);
        $province = (collect($province)->sum() ? $province->province_code:'');
        $district = District::Data(['field'=>'district_name_th','val'=>$data['contact_district'],'province_code'=>$province]);
        $district = (collect($district)->sum() ? $district->district_code:'');
        $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=>$data['contact_subdistrict'],'province_code'=>$province,'district_code'=>$district]);
        $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');


        $data['contact_province'] = $province;
        $data['contact_district'] = $district;
        $data['contact_subdistrict'] = $subdistrict;

        //dd($data);

        $item = Franchise::create($data);
        $id = $item->id;
        $data['franchise_id'] = $id;
        $location = array();

        if(isset($data['location_lat']) && isset($data['location_lat'])){

            //dd("Case True");
            //dd($data);
            foreach ($data['location_lat'] as $key => $value) {

                $province = Province::Data(['field'=>'province_name_th','val'=>$data['location_province'][$key]]);
                $province = (collect($province)->sum() ? $province->province_code:'');
                $district = District::Data(['field'=>'district_name_th','val'=>$data['location_district'][$key],'province_code'=>$province]);
                $district = (collect($district)->sum() ? $district->district_code:'');
                $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=> $data['location_subdistrict'][$key],'province_code'=>$province,'district_code'=>$district]);
                $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');


                $data_branch = array();
                $data_location = array();

                $data_branch['address'] = $data['location_address'][$key];
                $data_branch['subdistrict'] = $subdistrict;
                $data_branch['district'] = $district;
                $data_branch['province'] = $province;
                $data_branch['zipcode'] = $data['location_zipcode'][$key];
                $data_branch['franchise_id'] = $data['franchise_id'];

                $item_branch = FranchiseBranch::create($data_branch);
                $item_branch_id  = $item_branch->id;

                $data_location['latitude'] = $value;
                $data_location['longitude'] = $data['location_lng'][$key];
                $data_location['branch_id'] = $item_branch_id;

                array_push($location, $data_location);
                //dd($data_branch);
            }
            if(!$request->hasFile('file_branch')){
                File::put(public_path('files/franchise/json/').'location_'.$id.".json",json_encode($location));
            }
        }

        if($request->hasFile('file_branch')){
            //dd("file_branch");
            $destinationPath =  public_path().'/files/branch/import'; // upload path
            $destinationPath2 = '/files/branch/import';

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('file_branch')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_branch')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }else{
                $extension = Input::file('file_branch')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_branch')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }


            Excel::load($path_file, function($reader) use ($data,$location,$id) {
                // Getting all results
                //$results = $reader->get();
                // ->all() is a wrapper for ->get() and will work the same
                $results = $reader->all();
                //dd($results);
                foreach ($results as $key => $value) {
                   
                    //trim($value->address)
                    $province = Province::Data(['field'=>'province_name_th','val'=>trim($value->province)]);
                    $province = (collect($province)->sum() ? $province->province_code:'');
                    $district = District::Data(['field'=>'district_name_th','val'=>trim($value->district),'province_code'=>$province]);
                    $district = (collect($district)->sum() ? $district->district_code:'');
                    $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=>trim($value->subdistrict),'province_code'=>$province,'district_code'=>$district]);
                    $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');

                    //dd($value,$province,$district,$subdistrict);
                    try{
                        $data_branch = array();
                        $data_location = array();
        
                        $data_branch['address'] = trim($value->address);
                        $data_branch['subdistrict'] = $subdistrict;
                        $data_branch['district'] = $district;
                        $data_branch['province'] = $province;
                        $data_branch['zipcode'] = trim($value->zipcode);
                        $data_branch['franchise_id'] = $data['franchise_id'];
        
                        $item_branch = FranchiseBranch::create($data_branch);
                        $item_branch_id  = $item_branch->id;
        
                        $data_location['latitude'] = trim($value->latitude);
                        $data_location['longitude'] = trim($value->longitude);
                        $data_location['branch_id'] = $item_branch_id;
        
                        array_push($location,$data_location);
                    }catch (\Exception $e){

                    }
                }
                //dd($location);
                File::put(public_path('files/franchise/json/').'location_'.$id.".json",json_encode($location));
            });

        }

        //dd($data);

        //dd($data);
        FranchiseRevision::create($data);
        
        if($request->hasFile('logo_desktop')) {
            $item->addMedia($request->file('logo_desktop'))->toMediaCollection('logo_desktop');
        }
        // if($request->hasFile('logo_mobile')) {
        //     $item->addMedia($request->file('logo_mobile'))->toMediaCollection('logo_mobile');
        // }
        if($request->hasFile('cover_desktop')) {
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }
        // if($request->hasFile('cover_mobile')) {
        //     $item->addMedia($request->file('cover_mobile'))->toMediaCollection('cover_mobile');
        // }
        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }

        if($request->hasFile('document')){
           
            $destinationPath =  public_path().'/files/attached_file/'.$item->id; // upload path
            $destinationPath2 = '/files/attached_file/'.$item->id;

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    DocumentsFranchise::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'file_path'=>$path_file,'franchise_id'=>$id]);
                }
            }else{
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    DocumentsFranchise::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'file_path'=>$path_file,'franchise_id'=>$id]);
                }
            }

        }

        //dd($data);
        return redirect()->route('admin.franchise.index')
                            ->with('status', 'success')
                            ->with('message', trans('franchise::backend.successfully'));
    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        
        $data = Franchise::findOrFail($id);
        $province = Province::Data(['field'=>'province_code','val'=>$data->contact_province]);
        $province_name = (collect($province)->sum() ? $province->province_name:'');
        $district = District::Data(['field'=>'district_code','val'=>$data->contact_district,'province_code'=>(collect($province)->sum() ? $province->province_code:'')]);
        $district_name = (collect($district)->sum() ? $district->district_name:'');
        $subdistrict = Subdistrict::Data(['field'=>'subdistrict_code','val'=>$data->contact_subdistrict,'province_code'=>(collect($province)->sum() ? $province->province_code:''),'district_code'=>(collect($district)->sum() ? $district->district_code:'')]);
        $subdistrict_name = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_name:'');

        $data['contact_province'] = str_replace(" ","", $province_name);
        $data['contact_district'] = str_replace(" ","", $district_name);
        $data['contact_subdistrict'] = str_replace(" ","", $subdistrict_name);
        $data['branch'] = '';
        $branch = FranchiseBranch::Data(['franchise_id'=>$id]);
        if($branch !='false'){
            $data['branch'] = $branch;
        }
        $attachments = DocumentsFranchise::Data(['franchise_id'=>$id]);
        $data['attachments'] = collect($attachments);

        $revisions = FranchiseRevision::where('franchise_id', $id)->orderBy('created_at','DESC')->get();
        $category = FranchiseCategory::Data(['status'=>['publish']])->pluck('category_name', 'id');
        $category = collect($category)->toArray();
        $category[0] = " เลือกหมวดหมู่ ";
        ksort($category);
        //dd($data,$revisions);
        return view('franchise::edit', compact('data','revisions','category'));

    }

    public function postEdit(EditRequest $request, $id)
    {

        $item = Franchise::findOrFail($id);
        $data = $request->all();
        $data['contact_latitude'] = $data['lat'];
        $data['contact_longitude'] = $data['lng'];

        $province = Province::Data(['field'=>'province_name_th','val'=>$data['contact_province']]);
        $province = (collect($province)->sum() ? $province->province_code:'');
        $district = District::Data(['field'=>'district_name_th','val'=>$data['contact_district'],'province_code'=>$province]);
        $district = (collect($district)->sum() ? $district->district_code:'');
        $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=>$data['contact_subdistrict'],'province_code'=>$province,'district_code'=>$district]);
        $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');


        $data['contact_province'] = $province;
        $data['contact_district'] = $district;
        $data['contact_subdistrict'] = $subdistrict;

        $item->update($data);
        //dd($data);
        $data['franchise_id'] = $id;
        $location = array();

        if(isset($data['location_lat']) && isset($data['location_lat'])){

            //dd("Case True");
            //dd($data);
            FranchiseBranch::where('franchise_id',$id)->delete();
            //dd("Delete Success");
            foreach ($data['location_lat'] as $key => $value) {

                $province = Province::Data(['field'=>'province_name_th','val'=>$data['location_province'][$key]]);
                $province = (collect($province)->sum() ? $province->province_code:'');
                $district = District::Data(['field'=>'district_name_th','val'=>$data['location_district'][$key],'province_code'=>$province]);
                $district = (collect($district)->sum() ? $district->district_code:'');
                $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=> $data['location_subdistrict'][$key],'province_code'=>$province,'district_code'=>$district]);
                $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');



                $data_branch = array();
                $data_location = array();

                $data_branch['address'] = $data['location_address'][$key];
                $data_branch['subdistrict'] = $subdistrict;
                $data_branch['district'] = $district;
                $data_branch['province'] = $province;
                $data_branch['zipcode'] = $data['location_zipcode'][$key];
                $data_branch['franchise_id'] = $data['franchise_id'];

                $item_branch = FranchiseBranch::create($data_branch);
                $item_branch_id  = $item_branch->id;

                $data_location['latitude'] = $value;
                $data_location['longitude'] = $data['location_lng'][$key];
                $data_location['branch_id'] = $item_branch_id;

                array_push($location, $data_location);
                //dd($data_branch);
            }
            if(!$request->hasFile('file_branch')){
                File::put(public_path('files/franchise/json/').'location_'.$id.".json",json_encode($location));
            }
        }

        if($request->hasFile('file_branch')){
            //dd("file_branch");
            $destinationPath =  public_path().'/files/branch/import'; // upload path
            $destinationPath2 = '/files/branch/import';

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('file_branch')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_branch')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }else{
                $extension = Input::file('file_branch')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_branch')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }


            Excel::load($path_file, function($reader) use ($data,$location,$id) {
                // Getting all results
                //$results = $reader->get();
                // ->all() is a wrapper for ->get() and will work the same
                $results = $reader->all();
                //dd($results);
                foreach ($results as $key => $value) {
                   
                    //trim($value->address)
                    $province = Province::Data(['field'=>'province_name_th','val'=>trim($value->province)]);
                    $province = (collect($province)->sum() ? $province->province_code:'');
                    $district = District::Data(['field'=>'district_name_th','val'=>trim($value->district),'province_code'=>$province]);
                    $district = (collect($district)->sum() ? $district->district_code:'');
                    $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=>trim($value->subdistrict),'province_code'=>$province,'district_code'=>$district]);
                    $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');

                    //dd($value,$province,$district,$subdistrict);
                    try{
                        $data_branch = array();
                        $data_location = array();
        
                        $data_branch['address'] = trim($value->address);
                        $data_branch['subdistrict'] = $subdistrict;
                        $data_branch['district'] = $district;
                        $data_branch['province'] = $province;
                        $data_branch['zipcode'] = trim($value->zipcode);
                        $data_branch['franchise_id'] = $data['franchise_id'];
        
                        $item_branch = FranchiseBranch::create($data_branch);
                        $item_branch_id  = $item_branch->id;
        
                        $data_location['latitude'] = trim($value->latitude);
                        $data_location['longitude'] = trim($value->longitude);
                        $data_location['branch_id'] = $item_branch_id;
        
                        array_push($location,$data_location);
                    }catch (\Exception $e){

                    }
                }
                //dd($location);
                File::put(public_path('files/franchise/json/').'location_'.$id.".json",json_encode($location));
            });

        }

        FranchiseRevision::create($data);

        if($request->hasFile('logo_desktop')) {
            $item->clearMediaCollection('logo_desktop');
            $item->addMedia($request->file('logo_desktop'))->toMediaCollection('logo_desktop');
        }

        if($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
        }

        if($request->file('gallery_desktop')){
            foreach ($request->file('gallery_desktop') as $key => $value) {
                $item->addMedia($value)->toMediaCollection('gallery_desktop');
            }
        }

        if($request->hasFile('document')){
           
            $destinationPath =  public_path().'/files/attached_file/'.$item->id; // upload path
            $destinationPath2 = '/files/attached_file/'.$item->id;

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    DocumentsFranchise::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'file_path'=>$path_file,'franchise_id'=>$id]);
                }
            }else{
                foreach ($data['document'] as $key => $value) {
                    $extension = $value->getClientOriginalExtension(); // getting image extension
                    $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                    $value->move($destinationPath, $fileName); // uploading file to given path
                    $path_file = $destinationPath2."/".$fileName;
                    DocumentsFranchise::create(['title'=>$data['document_name'][$key],'status'=>'publish','file_name'=>$fileName,'file_type'=>$extension,'file_path'=>$path_file,'franchise_id'=>$id]);
                }
            }

        }


        //dd($data);
        return redirect()->route('admin.franchise.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postImport(CreateImportRequest $request)
    {
        //dd($request->all());

        if($request->hasFile('file_franchise')){
            //dd("file_branch");
            $destinationPath =  public_path().'/files/franchise/import'; // upload path
            $destinationPath2 = '/files/franchise/import';

            if(!\File::exists($destinationPath)){
                $result = \File::makeDirectory($destinationPath);
                $extension = Input::file('file_franchise')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_franchise')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }else{
                $extension = Input::file('file_franchise')->getClientOriginalExtension(); // getting image extension
                $fileName = date('YmdHis').rand(0,100).'.'.$extension; // renameing image
                Input::file('file_franchise')->move($destinationPath, $fileName); // uploading file to given path
                $path_file = public_path().$destinationPath2."/".$fileName;
            }


            Excel::load($path_file, function($reader){
                // Getting all results
                //$results = $reader->get();
                // ->all() is a wrapper for ->get() and will work the same
                $results = $reader->all();
                //dd($results);
                foreach ($results as $key => $value) {
                   
                    //trim($value->address)
                    $province = Province::Data(['field'=>'province_name_th','val'=>trim($value->province)]);
                    $province = (collect($province)->sum() ? $province->province_code:'');
                    $district = District::Data(['field'=>'district_name_th','val'=>trim($value->district),'province_code'=>$province]);
                    $district = (collect($district)->sum() ? $district->district_code:'');
                    $subdistrict = Subdistrict::Data(['field'=>'subdistrict_name_th','val'=>trim($value->subdistrict),'province_code'=>$province,'district_code'=>$district]);
                    $subdistrict = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_code:'');

                    //dd($value,$province,$district,$subdistrict);
                    try{

                        $data = array();
                        $data['contact_province'] = $province;
                        $data['contact_district'] = $district;
                        $data['contact_subdistrict'] = $subdistrict;
                        $data['contact_zipcode'] = $value->zipcode;
                        
                        $data['brand_name'] = trim($value->brand);
                        $data['company_name'] = trim($value->companyname);

                        $franchisecategory = FranchiseCategory::DetailId(['category_name'=>trim($value->franchisecategory)]);
                        //dd($franchisecategory->id);
                        $data['category_id'] = (isset($franchisecategory->id) ? $franchisecategory->id:'0');
                        $data['franchise_type'] = trim($value->franchisetype);
                        $data['contact_name'] = trim($value->contacts);
                        $data['phone'] = trim($value->phone);
                        $data['mobile'] = trim($value->mobile);
                        $data['email'] =  trim($value->email);
                        $data['number_of_branches'] = trim($value->numberofbranches);
                        $data['created_by'] = auth()->user()->id;
                        $data['status'] = 'publish';
                        //dd($data);

                        $item = Franchise::create($data);

                    }catch (\Exception $e){
                        //dd($e->getMessage());
                    }
                }

            });

        }

        ///dd($request->all(),'success');

        return redirect()->route('admin.franchise.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');

    }


    public function getDelete($id)
    {
        $item = Franchise::findOrFail($id);
        $item->delete();
        FranchiseBranch::where('franchise_id','=',$id)->delete();
        return redirect()->back()
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }

    public function postDeleteAll(Request $request)
    {
        if ($request->input('ids')) {
            //dd($request->input('ids'));
            $entries = Franchise::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
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

    public function getReverse($id)
    {
        $reverse = FranchiseRevision::find($id);
        $FranchiseId = $reverse->franchise_id;
        $fields = array_except($reverse->toArray(),['franchise_id','created_by']);
        $franchise = Franchise::find($FranchiseId);
        $franchise->title_th = $fields['title_th'];
        $franchise->title_en = $fields['title_en'];
        $franchise->description_th = $fields['description_th'];
        $franchise->description_en = $fields['description_en'];
        $franchise->save();
        return redirect()->back()
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


    public function postAjaxDeleteGallery(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $media_id = $inputs['id'];
                Franchise::whereHas('media', function ($query) use($media_id){
                         $query->whereId($media_id);
                })->first()->deleteMedia($media_id);
                $response['msg'] ='sucess';
                $response['status'] =true;
               // $response['data'] = $directory;
                return  Response::json($response,200);
            }else{
                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);
            }
        }catch (\Exception $e){
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);
        }
    }


    public function postAjaxDeleteDocument(Request $request){
        try{
            if(\Request::Ajax()){

                $inputs = $request->all();
                $id = $inputs['id'];

                $item = DocumentsFranchise::findOrFail($id);
                $item->delete();

                $response['msg'] ='sucess';
                $response['status'] =true;
                $response['data'] =$inputs;
                return  Response::json($response,200);
            }else{
                $response['msg'] ='Method Not Allowed';
                $response['status'] =false;
                $response['data'] = '';
                return  Response::json($response,405);
            }
        }catch (\Exception $e){
            $response['msg'] =$e->getMessage();
            $response['status'] =false;
            $response['data'] = '';
            return  Response::json($response,500);
        }
    }

}
