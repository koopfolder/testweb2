<?php

namespace App\Modules\Manager\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Manager\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Manager\Models\{Manager,Categories};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Junity\Hashids\Facades\Hashids;


class FrontController extends Controller
{

    public static function escape(){
        return \DB::connection()->getPdo();
    }

    public static function getData()
    {
        $segment_2 = \Request::segment(2);
        //dd($segment_2);
        $lang=  \App::getLocale();
        $data = array();
        $categories= Categories::FrontData([]);
        if($categories->isNotEmpty()){
            $data['categories'] = $categories;
            foreach ($categories as $key => $value) {
                $manager = Manager::FrontData(['categories_id'=>$value->id]);
                $count_not_management = 0;
                $count_management = 0 ;
                //dd($manager);
                if($manager->isNotEmpty()){
                    $data_array = array();
                    foreach($manager AS $key_manager => $value_manager){
                        $array = array();
                        $array['id'] =$value_manager->id;
                        if($lang =='en'){
                            if($value_manager->name_en !=''){
                                $array['name'] =$value_manager->name_en;
                            }else{
                                $array['name'] =$value_manager->name_th;
                            }
                            if($value_manager->position_en !=''){
                                $array['position'] = $value_manager->position_en;
                            }else{
                                $array['position'] = $value_manager->position_th;
                            }
                        }else{
                            $array['name'] =$value_manager->name_th;
                            $array['position'] = $value_manager->position_th;
                        }

                        $array['order'] = $value_manager->order;
                        $array['bord_and_management_type'] = $value_manager->bord_and_management_type;
                        if($value_manager->bord_and_management_type =='not_management'){
                            $count_not_management++;
                        }else{
                            $count_management++;
                        }
                        $array['categories_id'] = $value_manager->categories_id;
                        $array['image_desktop'] = $value_manager->getMedia('desktop')->isNotEmpty() ? asset($value_manager->getMedia('desktop')->first()->getUrl()) : null;
                        $array['image_mobile'] = $value_manager->getMedia('mobile')->isNotEmpty() ? asset($value_manager->getMedia('mobile')->first()->getUrl()) : null;
                        if($lang =='th'){
                            $array['detail_url']=route($segment_2.'-รายละเอียด',Hashids::encode($value_manager->id));
                        }else{
                            $array['detail_url']=route($segment_2.'-detail',Hashids::encode($value_manager->id));
                        }
                        array_push($data_array, $array);
                    }
                    $data['items'][$value->id]= $data_array;
                    $data['count_not_management'][$value->id]  = $count_not_management;
                    $data['count_management'][$value->id]  = $count_management;
                }
                #echo '<pre>';  print_r($data['items']); echo '</pre>';exit;
            }
        }
        return $data;
    }

    public function getDetail($id){

        $id = Hashids::decode($id);
        if(collect($id)->isNotEmpty()){
            $data = Manager::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            if(collect($data)->isNotEmpty()){
                return view('manager::frontend.index')->with(['data'=>$data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

    }
}

