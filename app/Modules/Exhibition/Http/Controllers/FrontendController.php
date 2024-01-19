<?php

namespace App\Modules\Exhibition\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Exhibition\Http\Requests\{CreateArticleRequest, EditArticleRequest};
use App\Modules\Exhibition\Models\{Exhibition,ExhibitionMaster,ExhibitionHitLogs,BookAnExhibition};
use Illuminate\Support\Facades\Response;
use Junity\Hashids\Facades\Hashids;
use DB;
use Mail;

class FrontendController extends Controller
{
    public function getDetail($slug)
    {
        //dd($slug);
        if(collect($slug)->isNotEmpty()){
            $data = Exhibition::Detail(['slug'=>$slug]);
            
            $token= csrf_token();
            $check =  ExhibitionHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ExhibitionHitLogs::create(['token'=>$token,'exhibition_id'=>$data->id]);
                Exhibition::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Exhibition::Detail(['slug'=>$slug]);
            }
            
            if(collect($data)->isNotEmpty()){
                //Related 
                //dd($data); 
                $related_data = Exhibition::FrontListRelated(['category_id'=>$data->category_id,'related_id'=>$data->id]);
                $most_view_data = Exhibition::FrontListMostView(['category_id'=>$data->category_id]);
                $recommend = Exhibition::FrontListRecommend(['category_id'=>$data->category_id,'related_id'=>$data->id]);
                //dd($recommend);

                return view('template.exhibition_detail_case_front')->with(['data'=>$data,'related_data'=>$related_data,'most_view_data'=>$most_view_data,'recommend'=>$recommend]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

    }

    public static function getDetailID($params){
        
        //dd($params);
        $result = array();
        $result['data'] = collect();
        $result['related_data'] = collect();
        $result['most_view_data'] = collect();
        $result['recommend'] = collect();

        $id =  ($params->layout_params !='' ?json_decode($params->layout_params)->id:'hap');
        $data = Exhibition::DetailId(['id'=>$id]);

        //dd($data);
   
        if(isset($data->title)){
            $result['data'] =$data;
            $token= csrf_token();
            $check =  ExhibitionHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
            if(!isset($check->id)){
                ExhibitionHitLogs::create(['token'=>$token,'exhibition_id'=>$data->id]);
                Exhibition::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
                $data = Exhibition::Detail(['slug'=>$slug]);
            }
            //dd($data); 
            $related_data = Exhibition::FrontListRelated(['category_id'=>$data->category_id,'related_id'=>$data->id]);
            $most_view_data = Exhibition::FrontListMostView(['category_id'=>$data->category_id]);
            $recommend = Exhibition::FrontListRecommend(['category_id'=>$data->category_id,'related_id'=>$data->id]);

            $result['related_data'] = $related_data;
            $result['most_view_data'] = $most_view_data;
            $result['recommend'] = $recommend;
                
        }else{
            Abort(404);
        }
        return $result;
    }


    public function getListRevolvingExhibition(Request $request)
    {
        return view('template.list_exhibition_case_front')->with(['page_layout'=>'revolving_exhibition','title'=>'นิทรรศการหมุนเวียน']);
    }

    public function getListPermanentExhibition(Request $request)
    {
        return view('template.list_exhibition_case_front')->with(['page_layout'=>'permanent_exhibition','title'=>'นิทรรศการถาวร']);
    }

    public function getListTravelingExhibition(Request $request)
    {
        return view('template.list_exhibition_case_front')->with(['page_layout'=>'traveling_exhibition','title'=>'นิทรรศการสัญจร']);
    }

    public function getListOnlineExhibition(Request $request)
    {
        return view('template.list_exhibition_case_front')->with(['page_layout'=>'online_exhibition','title'=>'นิทรรศการออนไลน์']);
    }

    public function getListExhibitionBorrowed(Request $request)
    {
        return view('template.list_exhibition_case_front')->with(['page_layout'=>'exhibition_borrowed','title'=>'นิทรรศการยืมคืน']);
    }


    public static function getDataListExhibition($params){

    
        $json_data = json_decode($params->layout_params);
        //$exhibition_join_data  =  ExhibitionMaster::DataExhibitionJoin(['id'=>$json_data->id]);
        //dd($exhibition_join_data);
        $open_to_watch = Exhibition::FrontListOpenToWatch(['category_id'=>$json_data->id]);
        $is_closed_to_visitors = Exhibition::FrontListExhibitionIsClosedToVisitors(['category_id'=>$json_data->id]);

        //dd($is_closed_to_visitors);
        //dd("getDataListExhibition",$params->layout_params,$json_data,$open_to_watch);
        //dd($open_to_watch);
        $data = array();
        $data['items_open_to_watch'] = collect();
        $data['items_is_closed_to_visitors'] = collect();

        //dd($data['items_open_to_watch']->count());

        if(collect($open_to_watch)->isNotEmpty()){
            $list = [];
            foreach($open_to_watch as $key => $exhibition) {

                //dd($exhibition);
                $array = array();
                $array['id']        = $exhibition->id;
                $array['title']        = $exhibition->title;
                $array['description'] = $exhibition->description;
                $array['created_at'] = $exhibition->created_at;
                $array['updated_at'] = $exhibition->updated_at;
                $array['hit'] = $exhibition->hit;

                if(!empty($exhibition->url_external) && empty($exhibition->file_path)){
                    //dd("Case Url External",$exhibition->title);
                    $array['url'] = $exhibition->url_external;
                }else if(!empty($exhibition->file_path) && empty($exhibition->url_external)){
                    //dd("Case File_path",$exhibition->title);
                    $array['url'] = asset($exhibition->file_path);
                }else if (empty($exhibition->file_path) && empty($exhibition->url_external)){
                    //dd("Case Slug",$exhibition->title);
                    $array['url'] = route('exhibition-detail',$exhibition->slug);
                }
                //dd("test");
                ///$array['url'] = route('revolving-exhibition-detail',$exhibition->slug);
                $array['cover_desktop'] = $exhibition->getMedia('cover_desktop')->isNotEmpty() ? asset($exhibition->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                array_push($list,$array); 
            }
            $data['items_open_to_watch'] = collect($list);
        }


        if(collect($is_closed_to_visitors)->isNotEmpty()){
            $list = [];
            // foreach($is_closed_to_visitors as $key => $exhibition) {

            //     //dd($exhibition);
            //     $array = array();
            //     $array['id']        = $exhibition->id;
            //     $array['title']        = $exhibition->title;
            //     $array['description'] = $exhibition->description;
            //     $array['created_at'] = $exhibition->created_at;
            //     $array['updated_at'] = $exhibition->updated_at;

            //     if(!empty($exhibition->url_external) && empty($exhibition->file_path)){
            //         //dd("Case Url External",$exhibition->title);
            //         $array['url'] = $exhibition->url_external;
            //     }else if(!empty($exhibition->file_path) && empty($exhibition->url_external)){
            //         //dd("Case File_path",$exhibition->title);
            //         $array['url'] = asset($exhibition->file_path);
            //     }else if (empty($exhibition->file_path) && empty($exhibition->url_external)){
            //         //dd("Case Slug",$exhibition->title);
            //         $array['url'] = route('exhibition-detail',$exhibition->slug);
            //     }
            //     //dd("test");
            //     ///$array['url'] = route('revolving-exhibition-detail',$exhibition->slug);
            //     $array['cover_desktop'] = $exhibition->getMedia('cover_desktop')->isNotEmpty() ? asset($exhibition->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
            //     array_push($list,$array); 
            // }
            $data['items_is_closed_to_visitors'] = $is_closed_to_visitors;
        }


        return $data; 
    }


    public static function getDataListExhibitionCaseClosedToVisitors($params){

    
        $json_data = json_decode($params->layout_params);
        //$exhibition_join_data  =  ExhibitionMaster::DataExhibitionJoin(['id'=>$json_data->id]);
        //dd($exhibition_join_data);

        $is_closed_to_visitors = Exhibition::FrontListExhibitionIsClosedToVisitors(['category_id'=>$json_data->id]);

        //dd($is_closed_to_visitors);
        //dd("getDataListExhibition",$params->layout_params,$json_data,$open_to_watch);
        //dd($open_to_watch);
        $data = array();

        $data['items_is_closed_to_visitors'] = collect();

        //dd($data['items_open_to_watch']->count());
        if(collect($is_closed_to_visitors)->isNotEmpty()){
            $list = [];
            // foreach($is_closed_to_visitors as $key => $exhibition) {

            //     //dd($exhibition);
            //     $array = array();
            //     $array['id']        = $exhibition->id;
            //     $array['title']        = $exhibition->title;
            //     $array['description'] = $exhibition->description;
            //     $array['created_at'] = $exhibition->created_at;
            //     $array['updated_at'] = $exhibition->updated_at;

            //     if(!empty($exhibition->url_external) && empty($exhibition->file_path)){
            //         //dd("Case Url External",$exhibition->title);
            //         $array['url'] = $exhibition->url_external;
            //     }else if(!empty($exhibition->file_path) && empty($exhibition->url_external)){
            //         //dd("Case File_path",$exhibition->title);
            //         $array['url'] = asset($exhibition->file_path);
            //     }else if (empty($exhibition->file_path) && empty($exhibition->url_external)){
            //         //dd("Case Slug",$exhibition->title);
            //         $array['url'] = route('exhibition-detail',$exhibition->slug);
            //     }
            //     //dd("test");
            //     ///$array['url'] = route('revolving-exhibition-detail',$exhibition->slug);
            //     $array['cover_desktop'] = $exhibition->getMedia('cover_desktop')->isNotEmpty() ? asset($exhibition->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
            //     array_push($list,$array); 
            // }
            $data['items_is_closed_to_visitors'] = $is_closed_to_visitors;
        }


        return $data; 
    }

    public function getBookAnExhibitionFront($id)
    {
        $id = Hashids::decode($id);
        $data = Exhibition::Title(['id'=>$id]);
        //dd("getBookAnExhibitionFront",$id,$data);
        return view('template.book_an_exhibition_case_front',compact('data'));
    }

    public function postFrontCreate(Request $request)
    {   
        $data = $request->all();
        $data['name'] =$data['firstname']." ".$data['lastname'];
        $data['status'] ='publish';
        //dd($data);
        $item = BookAnExhibition::create($data);
        //$this->sendMail($data);
        //dd("Create success");
        return redirect()->route('home')
                                ->with('book_an_exhibition', 'success')
                                ->with('message', 'Successfully');
    }

    public function sendMail($data = []){

        $fields = $data;
        $email_to = RequestMediaEmail::Email(['status'=>['publish']])->pluck('email')->toArray();
        //dd("Send Mail Function",$fields,$email_to);
        $emailTo = $fields['email'];
     
        $fields['emailTo'] = $emailTo;
        //dd($fields);

        if(is_array($fields)){
            Mail::send('emails.request_media', $fields, function ($message) use ($fields){
                $message->from('info@thrc.or.th','THRC');
                $message->to($fields['emailTo'], 'THRC ขอรับสื่อ')->subject('THRC ขอรับสื่อ');
                //dd($message);
            });
        }
        //dd("Send Mail Success",$fields);
        return true;
    }




}

