<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ViewMedia,Tags,DataTags,Sex,Age};
use App\Modules\Article\Models\{Article,ArticleRevision};
use App\Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Junity\Hashids\Facades\Hashids;
use Hash;
use Crypt;
use Illuminate\Support\Facades\Log;
use ThrcHelpers;
use App\User;

class MediaController extends Controller
{
    public function getIndex(Request $request)
    {
        //dd("Case Media");
        // $test  = ViewMedia::select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','featured','created_at','updated_at','created_by','updated_by','status','issues_id')
        // ->orderBy('id','desc')
        // ->groupBy('id')
        // ->with('issueName')
        // ->simplePaginate(25);

        //dd($test);
        $items = ListMedia::Data2(['request'=>$request->all()]);
        //dd($items);
        $old = $request->all();
        $issue =  ThrcHelpers::getIssue($request->all());
        $target =ThrcHelpers::getTarget($request->all());
        $template =  ThrcHelpers::getTempalte($request->all());
        $users = User::select('name','id')->orderBy('id', 'DESC')->get()->pluck('name','id')->toArray();
        if($users){
            $users['0'] = trans('api::backend.users');
        }
        ksort($users);

        //dd($users);

       //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        //dd($settings);
        //dd($target,$template);
        return view('api::backend.list_media.index',compact('items','old','issue','target','template','users'));
    }


    public function postUpdateStatus(Request $request)
    {

        $input = $request->all();
        $check = '';
        $status_data = true;
        $field = '';

        $access_token= '';
        $data_json = '';
        $data_media = '';
        $body = '';
        if(isset($input['field'])){
            $field = $input['field'];
            switch ($input['field']) {

                case 'status':

                    $status = ($input['val'] !='' ? $input['val']:'');
                    if($status !=''){
                        ListMedia::where('id','=',$input['id'])->update(['status'=>$status]);
                    }

                    break;

                case 'api':


                    if($input['media_type'] =='media'){

                        $check = ListMedia::select('id','api')
                                            ->where('id','=',$input['id'])
                                            ->first();
                        if(isset($check->id)){
                            $status = ($check->api =='publish' ? 'draft':'publish');
                            $data_media=ListMedia::findOrFail($input['id']);
                            $data_media->update(['api'=>$status]);
                            $status_data = ($status =='publish' ? true:false);
                        }
                        $data_media->updated_by = 0;
                        $media_json_data = json_decode($data_media->json_data);
                        if($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null'){
                            $media_json_data->SubProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->FileSize == null || $media_json_data->FileSize == 'null'){
                            $media_json_data->FileSize = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null'){
                            $media_json_data->ProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null'){
                            $media_json_data->PublishLevel = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                    
                        if($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null'){
                            $media_json_data->PublishLevelText = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                      
                        if($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null'){
                            $media_json_data->CreativeCommons = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                 
                        if($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null'){
                            $media_json_data->DepartmentID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                        
                        if($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null'){
                            $media_json_data->DepartmentName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                          
                        if($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null'){
                            $media_json_data->PublishedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                       
                        if($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null'){
                            $media_json_data->PublishedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                    
                        if($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null'){
                            $media_json_data->UpdatedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                             
                        if($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null'){
                            $media_json_data->UpdatedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                         
                        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
                        //     $media_json_data->Keywords = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }                                            
                        if($media_json_data->Template == null || $media_json_data->Template == 'null'){
                            $media_json_data->Template = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                         
                        if($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null'){
                            $media_json_data->CategoryID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Category == null || $media_json_data->Category == 'null'){
                            $media_json_data->Category = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        // if($media_json_data->Issues == null || $media_json_data->Issues == 'null'){
                        //     $media_json_data->Issues = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }
                        // if($media_json_data->Targets == null || $media_json_data->Targets == 'null'){
                        //     $media_json_data->Targets = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }
                        if($media_json_data->Settings == null || $media_json_data->Settings == 'null'){
                            $media_json_data->Settings = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                 
                        if($media_json_data->AreaID == null || $media_json_data->AreaID == 'null'){
                            $media_json_data->AreaID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Area == null || $media_json_data->Area == 'null'){
                            $media_json_data->Area = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }          
                        if($media_json_data->Province == null || $media_json_data->Province == 'null'){
                            $media_json_data->Province = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                          
                        if($media_json_data->Source == null || $media_json_data->Source == 'null'){
                            $media_json_data->Source = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null'){
                            $media_json_data->ReleasedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                               
                        if($media_json_data->Creator == null || $media_json_data->Creator == 'null'){
                            $media_json_data->Creator = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Production == null || $media_json_data->Production == 'null'){
                            $media_json_data->Production = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Publisher == null || $media_json_data->Publisher == 'null'){
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Publisher == null || $media_json_data->Publisher == 'null'){
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Contributor == null || $media_json_data->Contributor == 'null'){
                            $media_json_data->Contributor = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                                                                                       
                        if($media_json_data->Identifier == null || $media_json_data->Identifier == 'null'){
                            $media_json_data->Identifier = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Language == null || $media_json_data->Language == 'null'){
                            $media_json_data->Language = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Relation == null || $media_json_data->Relation == 'null'){
                            $media_json_data->Relation = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Format == null || $media_json_data->Format == 'null'){
                            $media_json_data->Format = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null'){
                            $media_json_data->IntellectualProperty = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->OS == null || $media_json_data->OS == 'null'){
                            $media_json_data->OS = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Owner == null || $media_json_data->Owner == 'null'){
                            $media_json_data->Owner = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null'){
                            $media_json_data->PeriodStart = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                                                                                                                                                                                                         
                        if($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null'){
                            $media_json_data->PeriodEnd = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->Duration == null || $media_json_data->Duration == 'null'){
                            $media_json_data->Duration = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->SystemID == null || $media_json_data->SystemID == 'null'){
                            $media_json_data->SystemID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->SystemName == null || $media_json_data->SystemName == 'null'){
                            $media_json_data->SystemName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                         
                        $data_media->json_data = json_encode($media_json_data);


                        /*Login*/
                        $body = '{"username":"'.env('THRC_API_USERNAME').'","password":"'.env('THRC_API_PASSWORD').'","device_token":"thrc_backend"}';
                        //dd($body);
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_LOGIN'), [
                                                            'headers'=>[
                                                                        'Content-Type'=>'application/json; charset=utf-8'
                                                                       ],
                                                            'body' => $body
                                                    ]);    
                        $response_api = $request->getBody()->getContents();
                        $data_json = json_decode($response_api);
                        
                        //dd($data_json);

                        if($data_json->status_code === 200){

                            $access_token = $data_json->data->access_token;
                            //dd($access_token);
                            $body = '{"device_token":"thrc_backend","media_type":"'.$input['media_type'].'","status_media":"'.$status.'","media":'.json_encode($data_media).'}';
                            //dd($body);
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_UPDATE_MEDIA'), [
                                                                'headers'=>[
                                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                                            'authorization'=>$access_token
                                                                           ],
                                                                'body' => $body
                                                        ]);             
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api);
                            //dd($data_json);
                        }                   

                    }else{


                        $check = Article::select('id','api')
                                            ->where('id','=',$input['id'])
                                            ->first();
                        if(isset($check->id)){
                            $status = ($check->api =='publish' ? 'draft':'publish');
                            $data_media=Article::findOrFail($input['id']);
                            $data_media->update(['api'=>$status]);
                            $status_data = ($status =='publish' ? true:false);
                        }
                        $data_media->updated_by = 0;
                        $media_json_data = json_decode($data_media->json_data);
                        if($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null'){
                            $media_json_data->SubProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->FileSize == null || $media_json_data->FileSize == 'null'){
                            $media_json_data->FileSize = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null'){
                            $media_json_data->ProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null'){
                            $media_json_data->PublishLevel = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                    
                        if($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null'){
                            $media_json_data->PublishLevelText = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                      
                        if($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null'){
                            $media_json_data->CreativeCommons = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                 
                        if($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null'){
                            $media_json_data->DepartmentID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                        
                        if($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null'){
                            $media_json_data->DepartmentName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                          
                        if($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null'){
                            $media_json_data->PublishedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                       
                        if($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null'){
                            $media_json_data->PublishedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                    
                        if($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null'){
                            $media_json_data->UpdatedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                             
                        if($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null'){
                            $media_json_data->UpdatedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                         
                        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
                        //     $media_json_data->Keywords = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }                                            
                        if($media_json_data->Template == null || $media_json_data->Template == 'null'){
                            $media_json_data->Template = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                         
                        if($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null'){
                            $media_json_data->CategoryID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Category == null || $media_json_data->Category == 'null'){
                            $media_json_data->Category = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        // if($media_json_data->Issues == null || $media_json_data->Issues == 'null'){
                        //     $media_json_data->Issues = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }
                        // if($media_json_data->Targets == null || $media_json_data->Targets == 'null'){
                        //     $media_json_data->Targets = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }
                        if($media_json_data->Settings == null || $media_json_data->Settings == 'null'){
                            $media_json_data->Settings = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                 
                        if($media_json_data->AreaID == null || $media_json_data->AreaID == 'null'){
                            $media_json_data->AreaID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Area == null || $media_json_data->Area == 'null'){
                            $media_json_data->Area = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }          
                        if($media_json_data->Province == null || $media_json_data->Province == 'null'){
                            $media_json_data->Province = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                          
                        if($media_json_data->Source == null || $media_json_data->Source == 'null'){
                            $media_json_data->Source = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null'){
                            $media_json_data->ReleasedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                               
                        if($media_json_data->Creator == null || $media_json_data->Creator == 'null'){
                            $media_json_data->Creator = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Production == null || $media_json_data->Production == 'null'){
                            $media_json_data->Production = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Publisher == null || $media_json_data->Publisher == 'null'){
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Publisher == null || $media_json_data->Publisher == 'null'){
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        } 
                        if($media_json_data->Contributor == null || $media_json_data->Contributor == 'null'){
                            $media_json_data->Contributor = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                                                                                       
                        if($media_json_data->Identifier == null || $media_json_data->Identifier == 'null'){
                            $media_json_data->Identifier = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Language == null || $media_json_data->Language == 'null'){
                            $media_json_data->Language = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Relation == null || $media_json_data->Relation == 'null'){
                            $media_json_data->Relation = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Format == null || $media_json_data->Format == 'null'){
                            $media_json_data->Format = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null'){
                            $media_json_data->IntellectualProperty = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->OS == null || $media_json_data->OS == 'null'){
                            $media_json_data->OS = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->Owner == null || $media_json_data->Owner == 'null'){
                            $media_json_data->Owner = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }  
                        if($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null'){
                            $media_json_data->PeriodStart = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                                                                                                                                                                                                         
                        if($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null'){
                            $media_json_data->PeriodEnd = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->Duration == null || $media_json_data->Duration == 'null'){
                            $media_json_data->Duration = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->SystemID == null || $media_json_data->SystemID == 'null'){
                            $media_json_data->SystemID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if($media_json_data->SystemName == null || $media_json_data->SystemName == 'null'){
                            $media_json_data->SystemName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }                                                                                                                         
                        $data_media->json_data = json_encode($media_json_data);

                        /*Login*/
                        $body = '{"username":"'.env('THRC_API_USERNAME').'","password":"'.env('THRC_API_PASSWORD').'","device_token":"thrc_backend"}';
                        //dd($body);
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_LOGIN'), [
                                                            'headers'=>[
                                                                        'Content-Type'=>'application/json; charset=utf-8'
                                                                       ],
                                                            'body' => $body
                                                    ]);    
                        $response_api = $request->getBody()->getContents();
                        $data_json = json_decode($response_api);


                        if($data_json->status_code === 200){

                            $access_token = $data_json->data->access_token;
                            //dd($access_token);
                            $body = '{"device_token":"thrc_backend","media_type":"'.$input['media_type'].'","status_media":"'.$status.'","media":'.json_encode($data_media).'}';
                            //dd($body);
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST',env('THRC_URL_API').env('THRC_URL_API_UPDATE_MEDIA'), [
                                                                'headers'=>[
                                                                            'Content-Type'=>'application/json; charset=utf-8',
                                                                            'authorization'=>$access_token
                                                                           ],
                                                                'body' => $body
                                                        ]);             
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api);
                            //dd($data_json);
                        }  
                        


                    }
                    
                    
                    break;
                case 'media_campaign':

                    $check = ListMedia::select('id','media_campaign')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->media_campaign ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['media_campaign'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break;
                case 'knowledges':

                    $check = ListMedia::select('id','knowledges')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->knowledges ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['knowledges'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break;    
                case 'notable_books':

                    $check = ListMedia::select('id','notable_books')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->notable_books ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['notable_books'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break;

                case 'include_statistics':

                    $check = ListMedia::select('id','include_statistics')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->include_statistics ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['include_statistics'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break; 

                case 'articles_research':

                    $check = ListMedia::select('id','articles_research')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->articles_research ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['articles_research'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break;

                case 'interesting_issues':

                    $check = ListMedia::select('id','interesting_issues')
                                        ->where('id','=',$input['id'])
                                        ->first();
                    if(isset($check->id)){
                        $status = ($check->interesting_issues ==2 ? 1:2);
                        ListMedia::where('id','=',$input['id'])->update(['interesting_issues'=>$status]);
                        $status_data = ($status ==2 ? true:false);
                    }

                    break;           

                default:
                    # code...
                    break;
            }

        }


        $response = array();
        $response['msg'] ='200 OK';
        $response['status'] =true;
        $response['status_data'] =$status_data;
        $response['field'] =$field;
        $response['data_json'] =$data_json;
        return  Response::json($response,200);

    }

    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListMedia::findOrFail($id);
        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');

        $tags = Tags::Data(['status'=>['publish']])->pluck('title','title');
        //$tags_select = DataTags::DataId(['data_id'=>$id,'data_type'=>'media'])->pluck('tags_id');
        //dd(json_decode($data->tags));

        $sex = Sex::Data(['status'=>['publish']])->pluck('name','id');
        $age = Age::Data(['status'=>['publish']])->pluck('name','id');

        //dd($sex,$age);
        //dd($tags,$tags_select);
        //dd($data,$settings);
        return view('api::backend.list_media.edit', compact('data','tags','sex','age'));
    }

    public function postEdit(EditRequest $request, $id)
    {
        $data = $request->all();
        //dd($data);
        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        
        $item = ListMedia::findOrFail($id);
        //dd($data,$item);
        $data_update['title'] = $data['title'];
        $data_update['description'] = $data['description'];
        $data_update['status'] = $data['status'];
        $data_update['featured'] = $data['featured'];
        $data_update['interesting_issues'] = $data['interesting_issues'];
        $data_update['recommend'] = $data['recommend'];
        $data_update['articles_research'] = $data['articles_research'];
        $data_update['include_statistics'] = $data['include_statistics'];
        $data_update['health_literacy'] = $data['health_literacy'];
        $data_update['knowledges'] = $data['knowledges'];
        $data_update['media_campaign'] = $data['media_campaign'];
        $data_update['updated_by'] = $data['updated_by'];



        // if($data['knowledges'] == 2){
        //     Setting::where('slug','knowledges')->update(['value' => $id]);
        // }else{
        //     if($settings['knowledges'] == $id){
        //         Setting::where('slug','knowledges')->update(['value' =>'']);
        //     }
        // }

        // if($data['media_campaign'] == 2){
        //     Setting::where('slug','media_campaign')->update(['value' => $id]);
        // }else{
        //     if($settings['media_campaign'] == $id){
        //         Setting::where('slug','media_campaign')->update(['value' =>'']);
        //     }
        // }

        
        //$data_update['notable_books'] = $data['notable_books'];
        //dd($data_update);
        $item->update($data_update);
        if($request->hasFile('cover_desktop')){
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');

            
            $item->update(['image_path'=>$item->getFirstMediaUrl('cover_desktop','thumb1024x618')]);
            //dd($item->getFirstMediaUrl('cover_desktop','thumb1024x618'));
        }





        if(isset($data['tags'])){

            //dd($data['tags']);
            $data_tags = array();
            foreach($data['tags'] AS $key=>$value){

                //dd($value);
                $tag_check = Tags::select('id')->where('title','=',$value)->first();
                //dd($tag_check);
                if(isset($tag_check->id)){
                    array_push($data_tags, $value);
                }else{
                    $data_tag_master = array();
                    $data_tag_master['title'] = $value;
                    $data_tag_master['status'] = 'publish';
                    Tags::create($data_tag_master);
                    array_push($data_tags, $value);
                }
            }
            //dd($data_tags);
            $item->update(['tags'=>json_encode($data_tags)]);
            
        }else{
            $item->update(['tags'=>'']);
        }


        if(isset($data['sex'])){

            //dd($data['tags']);
            $data_sex = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach($data['sex'] AS $key=>$value){

                //dd($value);
                if(is_numeric($value)){
                    array_push($data_sex,(int)$value);
                }else{
                    $data_sex_master = array();
                    $data_sex_master['name'] = $value;
                    $data_sex_master['status'] = 'publish';
                    $sex_id = Sex::create($data_sex_master);
                    //dd($sex_id->id);
                    array_push($data_sex,$sex_id->id);                    

                }

            }
            //dd($data_sex);
            $item->update(['sex'=>json_encode($data_sex)]);
            
        }else{
            $item->update(['sex'=>'']);
        }


        if(isset($data['age'])){

            //dd($data['tags']);
            $data_age = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach($data['age'] AS $key=>$value){

                //dd($value);
                if(is_numeric($value)){
                    array_push($data_age,(int)$value);
                }else{
                    $data_age_master = array();
                    $data_age_master['name'] = $value;
                    $data_age_master['status'] = 'publish';
                    $age_id = Age::create($data_age_master);
                    //dd($age_id->id);
                    array_push($data_age,$age_id->id);                    

                }

            }
            //dd($data_age);
            $item->update(['age'=>json_encode($data_age)]);
            
        }else{
            $item->update(['age'=>'']);
        }

        //dd("Age Success");

        if($data['interesting_issues'] == 2){
            
            $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','interesting_issues')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'interesting_issues';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            $data_article['updated_by'] = $data['updated_by'];

            Article::create($data_article);
            }

            //dd($data['UploadFileID'],$check_data);

        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','interesting_issues')
                    ->delete();
        }

        if($data['articles_research'] == 2){

            $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','articles_research')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'articles_research';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            $data_article['updated_by'] = $data['updated_by'];
            Article::create($data_article);
            }
            
            //dd($data['UploadFileID'],$check_data);
        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','articles_research')
                    ->delete();
        }

        if($data['include_statistics'] == 2){
            
             $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','include_statistics')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'include_statistics';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            $data_article['updated_by'] = $data['updated_by'];
            Article::create($data_article);
            }

        }else{
            Article::where('dol_UploadFileID','=',$data['UploadFileID'])
                    ->where('page_layout','=','include_statistics')
                    ->delete();
        }


        if($data['health_literacy'] == 2){
            
             $check_data =Article::select('id')
                                  ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                  ->where('page_layout','=','health-literacy')
                                  ->first();

            if(!isset($check_data->id)){
         
            $data_article['page_layout'] = 'health-literacy';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $json_decode  = json_decode($item->json_data);


            foreach ($json_decode->Issues as $value_issues) {
                        //dd($value_issues->ID);
                        if($value_issues->ID == 5){
                            #แอลกอฮอล์
                            $data_article['category_id'] = 5;
                        }

                        if($value_issues->ID == 28){
                            #บุหรี่
                            $data_article['category_id'] = 6;
                        }

                        if($value_issues->ID == 39){
                            #อาหาร
                            $data_article['category_id'] = 7;
                        }

                        if($value_issues->ID == 18){
                            #กิจกรรมทางกาย
                            $data_article['category_id'] = 8;
                        }

                        if($value_issues->ID == 41){
                            #อุบัติเหตุ
                            $data_article['category_id'] = 9;
                        }

                        if($value_issues->ID == 37){
                            #เพศ เช่น ท้องไม่พร้อม
                            $data_article['category_id'] = 10;
                        }

                        if($value_issues->ID == 34){
                            #สุขภาพจิต
                            $data_article['category_id'] = 11;
                        }                                                                                                                                             
                        if($value_issues->ID == 35){
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            $data_article['category_id'] = 12;
                        }  

                        if($value_issues->ID == 36){
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            $data_article['category_id'] = 12;
                        } 

                        if($value_issues->ID == 27){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 33){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 49){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 16){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }

                        if($value_issues->ID == 21){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }

                        if($value_issues->ID == 32){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        } 

                        if($value_issues->ID == 42){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }                                                                                                                                                                    
            }

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
            $data_article['updated_by'] = $data['updated_by'];
            //dd($data_article);
            Article::create($data_article);

            }

        }


        if($item->json_data !='' && $data['status'] =='publish'){
            $json_decode  = json_decode($item->json_data);
            //dd($json_decode);

            if(gettype($json_decode) =='object'){
                $check_data = 0;
                $data_array_check = array("healthliteracy");
                                    
                foreach ($json_decode->Keywords as $value) {
                        //echo $value;
                        //echo "<br>";
                    if(array_keys($data_array_check,strtolower($value))){
                        $check_data=1;
                        //echo $value;-
                        //echo "<br>";
                        //exit();
                        //dd("True");
                    }
                }

                if($check_data ==1){

                    $check_article =Article::select('id')
                                    ->where('dol_UploadFileID','=',$data['UploadFileID'])
                                    ->where('page_layout','=','health-literacy')
                                    ->first();

                    if(!isset($check_article->id)){
                 
                    $data_article['page_layout'] = 'health-literacy';
                    $data_article['title'] = $data['title'];
                    $data_article['description'] = $data['description'];
                    $data_article['short_description'] = strip_tags($data['description']);
                    $data_article['dol_cover_image'] = $data['ThumbnailAddress'];
                    $data_article['dol_UploadFileID'] = $data['UploadFileID'];
                    $data_article['dol_url'] = $data['FileAddress'];
                    $data_article['dol_template'] = $data['Template'];
                    $data_article['dol_json_data'] = $item->json_data;
                    $data_article['category_id'] = 0;

                    foreach ($json_decode->Issues as $value_issues) {
                        //dd($value_issues);
                        if($value_issues->ID == 5){
                            #แอลกอฮอล์
                            $data_article['category_id'] = 5;
                        }

                        if($value_issues->ID == 28){
                            #บุหรี่
                            $data_article['category_id'] = 6;
                        }

                        if($value_issues->ID == 39){
                            #อาหาร
                            $data_article['c ategory_id'] = 7;
                        }

                        if($value_issues->ID == 18){
                            #กิจกรรมทางกาย
                            $data_article['category_id'] = 8;
                        }

                        if($value_issues->ID == 41){
                            #อุบัติเหตุ
                            $data_article['category_id'] = 9;
                        }

                        if($value_issues->ID == 37){
                            #เพศ เช่น ท้องไม่พร้อม
                            $data_article['category_id'] = 10;
                        }

                        if($value_issues->ID == 34){
                            #สุขภาพจิต
                            $data_article['category_id'] = 11;
                        }                                                                                                                                             
                        if($value_issues->ID == 35){
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            $data_article['category_id'] = 12;
                        }  

                        if($value_issues->ID == 36){
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            $data_article['category_id'] = 12;
                        } 

                        if($value_issues->ID == 27){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 33){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 49){
                            #สิ่งแวดล้อม
                            $data_article['category_id'] = 13;
                        }

                        if($value_issues->ID == 16){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }

                        if($value_issues->ID == 21){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }

                        if($value_issues->ID == 32){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        } 

                        if($value_issues->ID == 42){
                            #อื่นๆ
                            $data_article['category_id'] = 14;
                        }                                                                                                                                                                    
                    }

                    $date_year = date('Y-m-d');
                    $date_year = strtotime($date_year);
                    $date_year = strtotime("+10 year", $date_year);
                    $data_article['start_date'] = date("Y-m-d H:i:s");
                    $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
                    $data_article['updated_by'] = $data['updated_by'];
                    //dd($data_article);
                    Article::create($data_article);

                    }                                                        
                }
            }

        }
        // else{
        //     Article::where('dol_UploadFileID','=',$data['UploadFileID'])
        //             ->where('page_layout','=','health-literacy')
        //             ->delete();
        // }

        //dd("Success");




        self::postLogs(['event'=>'แก้ไขข้อมูลหัวข้อ "'.$data_update['title'].'"','module_id'=>'13']);
        return redirect()->route('admin.api.list-media.index')
                            ->with('status', 'success')
                            ->with('message', 'Successfully');
    }


    public function getImport()
    {
        dd("Import Success");
        $list_media = ListMedia::select('json_data')
                          ->where('json_data','!=','')
                          ->offset(60000) //2 /6
                          ->limit(10000)
                          ->get();
                          //2+14
        $i = 0;  
        foreach ($list_media as $value) {
            //dd($value);

            $json_decode  = json_decode($value->json_data);
            //dd($json_decode);
            //dd(gettype($json_decode));
            if(gettype($json_decode) =='object'){
                $check_data = 0;
                $data_array_check = array("healthliteracy");
                                    
                foreach ($json_decode->Keywords as $value_search) {
                        //echo strtolower($value_search);
                        //exit();
                        //echo "<br>";
                    if(array_keys($data_array_check,strtolower($value_search))){
                        $check_data=1;
                        //echo $value;-
                        //echo "<br>";
                        //exit();
                        $i++;
                    }
                    //dd("Success");
                }

                if($check_data ==1){
                    //dd($json_decode);
                    $check_article =Article::select('id')
                                    ->where('dol_UploadFileID','=',$json_decode->UploadFileID)
                                    ->where('page_layout','=','health-literacy')
                                    ->first();

                    if(!isset($check_article->id)){
                 
                    $data_article['page_layout'] = 'health-literacy';
                    $data_article['title'] = $json_decode->Title;
                    $data_article['description'] = $json_decode->Description;
                    $data_article['short_description'] = strip_tags($json_decode->Description);
                    $data_article['dol_cover_image'] = $json_decode->ThumbnailAddress;
                    $data_article['dol_UploadFileID'] = $json_decode->UploadFileID;
                    $data_article['dol_url'] = $json_decode->FileAddress;
                    $data_article['dol_template'] = $json_decode->Template;
                    $data_article['dol_json_data'] = $value->json_data;

                    $date_year = date('Y-m-d');
                    $date_year = strtotime($date_year);
                    $date_year = strtotime("+10 year", $date_year);
                    $data_article['start_date'] = date("Y-m-d H:i:s");
                    $data_article['end_date'] = date('Y-m-d H:i:s',$date_year);
                    //dd($data_article);
                    //Article::create($data_article);

                    }                                                        
                }
            }
           //dd("Case Success");
        }

        dd("Test Import",$i);

    }

 

}

