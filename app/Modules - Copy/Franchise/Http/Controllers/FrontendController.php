<?php

namespace App\Modules\Franchise\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Franchise\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Franchise\Models\{Franchise,FranchiseCategory,FranchiseRevision,FranchiseBranch,Province,District,Subdistrict};
use App\Modules\Documentsdownload\Models\{DocumentsFranchise};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Carbon\Carbon;
use File;
use Excel;
use Junity\Hashids\Facades\Hashids;

class FrontendController extends Controller
{

    public static function getDetailFranchise($slug){
        
        //dd('getDetailFranchise',$slug);
        if(collect($slug)->isNotEmpty()){
            $data = Franchise::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            $branch= '';
            $attachments = '';
            if(collect($data)->isNotEmpty()){
                $branch = FranchiseBranch::Data(['franchise_id'=>$data->id]);
                if($branch !='false'){
                    $branch = $branch;
                }
                $attachments = DocumentsFranchise::Data(['franchise_id'=>$data->id]);
                $attachments= collect($attachments);
                //dd($data,$branch);
                return view('franchise::frontend.index')->with(['data'=>$data,'branch'=>$branch,'attachments'=>$attachments]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getPreview($slug){
        
        //dd('getDetailFranchise',$slug);
        if(collect($slug)->isNotEmpty()){
            $data = Franchise::Detail(['slug'=>$slug]);
            //dd(collect($data)->isNotEmpty());
            $branch= '';

            if(collect($data)->isNotEmpty()){
                $branch = FranchiseBranch::Data(['franchise_id'=>$data->id]);
                if($branch !='false'){
                    $branch = $branch;
                }
                //dd($data,$branch);
                return view('franchise::frontend.index')->with(['data'=>$data,'branch'=>$branch]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }


    public static function getDataFranchise($params,$category)
    {
        //dd($params,$category);
        $query = Franchise::FrontList(['page'=>(isset($params['page']) ? $params['page']:'1'),'params'=>$params,'category'=>$category]);
        //dd($query);
        $data = array();
        $data['title_h1'] = 'ค้นหาธุรกิจแฟรนไชส์';
        $data['layout'] = 'list_franchise';
        $data['items'] = $query;

        return $data;
    }

    public static function getDataCategory(){

        $category = FranchiseCategory::Data(['status'=>['publish']])->pluck('category_name', 'id');
        $category = collect($category)->toArray();
        $category[0] = " เลือกหมวดหมู่: ";
        ksort($category);
        return $category;

    }

    public static function getProvince(){


        $province = array();
        $province['0'] = ' เลือกจังหวัด: ';
        $province_data = Province::DataList([])->pluck('province_name', 'province_code');
        // $province_data = collect($province_data)->toArray();
        // $province_select = " เลือกจังหวัด: ";
        // $province = array();
        // array_push($province,$province_select);
        // array_push($province,$province_data);
        // dd($province_data,$province);

        if(collect($province_data)->count() >0){

            foreach($province_data AS $key=>$value){
                    //dd($key,$value);
                    $province[$key] = $value;
            }
        }
        //dd($province);
        //dd(array_merge($province_select,$province_data));
        return $province;

    }

    public static function getDistrict(){

        $district = District::DataList([]);
        //dd($district);
        return $district;
    }

    public static function getSubdistrict(){

        $subdistrict = Subdistrict::DataList([]);
        //dd($subdistrict);
        return $subdistrict;
    }




    public static function getListFranchise(){
        return view('template.list_franchise');
    }

    public static function getListFranchiseCategory($category){
        $category = $category;
        return view('template.list_franchise')->with(['category'=>$category]);
    }

}
