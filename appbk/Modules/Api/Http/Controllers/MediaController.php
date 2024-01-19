<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia, ListArea, ListCategory, ListIssue, ListProvince, ListSetting, ListTarget, ListMediaIssues, ListMediaKeywords, ListMediaTargets, ViewMedia, Tags, DataTags, Sex, Age};
use App\Modules\Article\Models\{Article, ArticleRevision};
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
use Excel;
use DB;

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
        $items = ListMedia::Data2(['request' => $request->all()]);
        //dd($items);
        $old = $request->all();
        $issue =  ThrcHelpers::getIssue($request->all());
        $target = ThrcHelpers::getTarget($request->all());
        $template =  ThrcHelpers::getTempalte($request->all());
        $users = User::select('name', 'id')->orderBy('id', 'DESC')->get()->pluck('name', 'id')->toArray();
        if ($users) {
            $users['0'] = trans('api::backend.users');
        }
        ksort($users);

        $tags = Tags::Data(['status' => ['publish']])->pluck('title', 'title');
        $sex = Sex::Data(['status' => ['publish']])->pluck('name', 'id');
        $age = Age::Data(['status' => ['publish']])->pluck('name', 'id');
        $target2 = ListTarget::Data(['status' => ['publish']]);
        $issue2 = ListIssue::Data(['status' => ['publish']]);
        $setting = ListSetting::Data(['status' => ['publish']]);
        $area = ListArea::Data(['status' => ['publish']]);
        $provice = ListProvince::Data(['status' => ['publish']]);
        $category = ListCategory::Data(['status' => ['publish']]);
        $language = ['ไทย (Thai)', 'อังกฤษ (English)'];
        //$template = ['Text','Visual','Multimedia','KnowledgePackage','Application'];

        //dd($template);

        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        //dd($settings);
        //dd($target,$template);
        return view('api::backend.list_media.index', compact('items', 'old', 'issue', 'issue2', 'target', 'target2', 'template', 'users', 'tags', 'sex', 'age', 'setting', 'area', 'provice', 'category', 'language'));
    }


    public function postUpdateStatus(Request $request)
    {

        $input = $request->all();
        $check = '';
        $status_data = true;
        $field = '';

        $access_token = '';
        $data_json = '';
        $data_media = '';
        $body = '';
        if (isset($input['field'])) {
            $field = $input['field'];
            switch ($input['field']) {

                case 'status':

                    $status = ($input['val'] != '' ? $input['val'] : '');
                    if ($status != '') {
                        ListMedia::where('id', '=', $input['id'])->update(['status' => $status]);
                    }

                    break;

                case 'api':


                    if ($input['media_type'] == 'media') {

                        $check = ListMedia::select('id', 'api')
                            ->where('id', '=', $input['id'])
                            ->first();
                        if (isset($check->id)) {
                            $status = ($check->api == 'publish' ? 'draft' : 'publish');
                            $data_media = ListMedia::findOrFail($input['id']);
                            $data_media->update(['api' => $status]);
                            $status_data = ($status == 'publish' ? true : false);
                        }
                        $data_media->updated_by = 0;
                        $media_json_data = json_decode($data_media->json_data);
                        if ($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null') {
                            $media_json_data->SubProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->FileSize == null || $media_json_data->FileSize == 'null') {
                            $media_json_data->FileSize = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null') {
                            $media_json_data->ProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null') {
                            $media_json_data->PublishLevel = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null') {
                            $media_json_data->PublishLevelText = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null') {
                            $media_json_data->CreativeCommons = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null') {
                            $media_json_data->DepartmentID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null') {
                            $media_json_data->DepartmentName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null') {
                            $media_json_data->PublishedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null') {
                            $media_json_data->PublishedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null') {
                            $media_json_data->UpdatedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null') {
                            $media_json_data->UpdatedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
                        //     $media_json_data->Keywords = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }                                            
                        if ($media_json_data->Template == null || $media_json_data->Template == 'null') {
                            $media_json_data->Template = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null') {
                            $media_json_data->CategoryID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Category == null || $media_json_data->Category == 'null') {
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
                        if ($media_json_data->Settings == null || $media_json_data->Settings == 'null') {
                            $media_json_data->Settings = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->AreaID == null || $media_json_data->AreaID == 'null') {
                            $media_json_data->AreaID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Area == null || $media_json_data->Area == 'null') {
                            $media_json_data->Area = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Province == null || $media_json_data->Province == 'null') {
                            $media_json_data->Province = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Source == null || $media_json_data->Source == 'null') {
                            $media_json_data->Source = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null') {
                            $media_json_data->ReleasedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Creator == null || $media_json_data->Creator == 'null') {
                            $media_json_data->Creator = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Production == null || $media_json_data->Production == 'null') {
                            $media_json_data->Production = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Contributor == null || $media_json_data->Contributor == 'null') {
                            $media_json_data->Contributor = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Identifier == null || $media_json_data->Identifier == 'null') {
                            $media_json_data->Identifier = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Language == null || $media_json_data->Language == 'null') {
                            $media_json_data->Language = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Relation == null || $media_json_data->Relation == 'null') {
                            $media_json_data->Relation = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Format == null || $media_json_data->Format == 'null') {
                            $media_json_data->Format = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null') {
                            $media_json_data->IntellectualProperty = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->OS == null || $media_json_data->OS == 'null') {
                            $media_json_data->OS = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Owner == null || $media_json_data->Owner == 'null') {
                            $media_json_data->Owner = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null') {
                            $media_json_data->PeriodStart = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null') {
                            $media_json_data->PeriodEnd = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Duration == null || $media_json_data->Duration == 'null') {
                            $media_json_data->Duration = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->SystemID == null || $media_json_data->SystemID == 'null') {
                            $media_json_data->SystemID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->SystemName == null || $media_json_data->SystemName == 'null') {
                            $media_json_data->SystemName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        //$data_media->json_data = json_encode($media_json_data);
                        $data_media->json_data = $media_json_data;
                        //dd($data_media,$data_media->json_data);

                        /*Login*/
                        $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
                        //dd($body);
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
                            'headers' => [
                                'Content-Type' => 'application/json; charset=utf-8'
                            ],
                            'body' => $body
                        ]);
                        $response_api = $request->getBody()->getContents();
                        $data_json = json_decode($response_api);

                        //dd($data_json);

                        if ($data_json->status_code === 200) {

                            $access_token = $data_json->data->access_token;
                            //dd($access_token);
                            $body = '{"device_token":"thrc_backend","media_type":"' . $input['media_type'] . '","status_media":"' . $status . '","media":' . json_encode($data_media) . '}';
                            //dd($body);
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_UPDATE_MEDIA'), [
                                'headers' => [
                                    'Content-Type' => 'application/json; charset=utf-8',
                                    'authorization' => $access_token
                                ],
                                'body' => $body
                            ]);
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api);
                            //dd($data_json);
                        }
                    } else {


                        $check = Article::select('id', 'api')
                            ->where('id', '=', $input['id'])
                            ->first();
                        if (isset($check->id)) {
                            $status = ($check->api == 'publish' ? 'draft' : 'publish');
                            $data_media = Article::findOrFail($input['id']);
                            $data_media->update(['api' => $status]);
                            $status_data = ($status == 'publish' ? true : false);
                        }
                        $data_media->updated_by = 0;
                        $media_json_data = json_decode($data_media->json_data);
                        if ($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null') {
                            $media_json_data->SubProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->FileSize == null || $media_json_data->FileSize == 'null') {
                            $media_json_data->FileSize = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null') {
                            $media_json_data->ProjectCode = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null') {
                            $media_json_data->PublishLevel = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null') {
                            $media_json_data->PublishLevelText = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null') {
                            $media_json_data->CreativeCommons = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null') {
                            $media_json_data->DepartmentID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null') {
                            $media_json_data->DepartmentName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null') {
                            $media_json_data->PublishedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null') {
                            $media_json_data->PublishedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null') {
                            $media_json_data->UpdatedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null') {
                            $media_json_data->UpdatedByName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
                        //     $media_json_data->Keywords = 'not-specified';
                        //     //dd("Case True",$media_json_data);
                        // }                                            
                        if ($media_json_data->Template == null || $media_json_data->Template == 'null') {
                            $media_json_data->Template = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null') {
                            $media_json_data->CategoryID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Category == null || $media_json_data->Category == 'null') {
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
                        if ($media_json_data->Settings == null || $media_json_data->Settings == 'null') {
                            $media_json_data->Settings = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->AreaID == null || $media_json_data->AreaID == 'null') {
                            $media_json_data->AreaID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Area == null || $media_json_data->Area == 'null') {
                            $media_json_data->Area = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Province == null || $media_json_data->Province == 'null') {
                            $media_json_data->Province = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Source == null || $media_json_data->Source == 'null') {
                            $media_json_data->Source = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null') {
                            $media_json_data->ReleasedDate = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Creator == null || $media_json_data->Creator == 'null') {
                            $media_json_data->Creator = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Production == null || $media_json_data->Production == 'null') {
                            $media_json_data->Production = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Publisher == null || $media_json_data->Publisher == 'null') {
                            $media_json_data->Publisher = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Contributor == null || $media_json_data->Contributor == 'null') {
                            $media_json_data->Contributor = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Identifier == null || $media_json_data->Identifier == 'null') {
                            $media_json_data->Identifier = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Language == null || $media_json_data->Language == 'null') {
                            $media_json_data->Language = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Relation == null || $media_json_data->Relation == 'null') {
                            $media_json_data->Relation = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Format == null || $media_json_data->Format == 'null') {
                            $media_json_data->Format = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null') {
                            $media_json_data->IntellectualProperty = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->OS == null || $media_json_data->OS == 'null') {
                            $media_json_data->OS = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Owner == null || $media_json_data->Owner == 'null') {
                            $media_json_data->Owner = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null') {
                            $media_json_data->PeriodStart = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null') {
                            $media_json_data->PeriodEnd = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->Duration == null || $media_json_data->Duration == 'null') {
                            $media_json_data->Duration = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->SystemID == null || $media_json_data->SystemID == 'null') {
                            $media_json_data->SystemID = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        if ($media_json_data->SystemName == null || $media_json_data->SystemName == 'null') {
                            $media_json_data->SystemName = 'not-specified';
                            //dd("Case True",$media_json_data);
                        }
                        //$data_media->json_data = json_encode($media_json_data);
                        $data_media->json_data = $media_json_data;

                        /*Login*/
                        $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
                        //dd($body);
                        $client = new \GuzzleHttp\Client();
                        $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
                            'headers' => [
                                'Content-Type' => 'application/json; charset=utf-8'
                            ],
                            'body' => $body
                        ]);
                        $response_api = $request->getBody()->getContents();
                        $data_json = json_decode($response_api);


                        if ($data_json->status_code === 200) {

                            $access_token = $data_json->data->access_token;
                            //dd($access_token);
                            $body = '{"device_token":"thrc_backend","media_type":"' . $input['media_type'] . '","status_media":"' . $status . '","media":' . json_encode($data_media) . '}';
                            //dd($body);
                            $client = new \GuzzleHttp\Client();
                            $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_UPDATE_MEDIA'), [
                                'headers' => [
                                    'Content-Type' => 'application/json; charset=utf-8',
                                    'authorization' => $access_token
                                ],
                                'body' => $body
                            ]);
                            $response_api = $request->getBody()->getContents();
                            $data_json = json_decode($response_api);
                            //dd($data_json);
                        }
                    }


                    break;
                case 'SendMediaTermStatus':

                    $check = ListMedia::select('id', 'SendMediaTermStatus')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = (is_null($check->SendMediaTermStatus) ? 50 : null);
                        $dd = ListMedia::where('id', '=', $input['id'])->update(['SendMediaTermStatus' => $status]);
                        $status_data = ($status == 50 ? true : false);
                    }
                    break;
                case 'media_campaign':

                    $check = ListMedia::select('id', 'media_campaign')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->media_campaign == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['media_campaign' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;
                case 'knowledges':

                    $check = ListMedia::select('id', 'knowledges')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->knowledges == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['knowledges' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;
                case 'notable_books':

                    $check = ListMedia::select('id', 'notable_books')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->notable_books == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['notable_books' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;

                case 'include_statistics':

                    $check = ListMedia::select('id', 'include_statistics')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->include_statistics == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['include_statistics' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;

                case 'articles_research':

                    $check = ListMedia::select('id', 'articles_research')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->articles_research == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['articles_research' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;

                case 'interesting_issues':

                    $check = ListMedia::select('id', 'interesting_issues')
                        ->where('id', '=', $input['id'])
                        ->first();
                    if (isset($check->id)) {
                        $status = ($check->interesting_issues == 2 ? 1 : 2);
                        ListMedia::where('id', '=', $input['id'])->update(['interesting_issues' => $status]);
                        $status_data = ($status == 2 ? true : false);
                    }

                    break;

                default:
                    # code...
                    break;
            }
        }


        $response = array();
        $response['msg'] = '200 OK';
        $response['status'] = true;
        $response['status_data'] = $status_data;
        $response['field'] = $field;
        $response['data_json'] = $data_json;
        return  Response::json($response, 200);
    }

    public function get_learningpartner(Request $request)
    {

        $list_media_api = array();
        $date = date("Y-m-d");
        $arr_tar = array();
        $list_media = ListMedia::where('status', '=', 'publish')
            ->where('api', '=', 'publish')->whereIn('show_learning', [0, 2])
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->get();

        foreach ($list_media as $key => $item) {
            $sub_array = array();
            $list_media[$key]['json_data'] = json_decode($item['json_data']);
            $res = $list_media[$key]['json_data'];
            $list_media[$key]['UploadFileID'] = strtoupper($item['UploadFileID']);
            $were = $list_media[$key]['UploadFileID'];
            $arr_issue = array();
            $arr_tar = array();
            $arr_setting = array();
            if (is_array($res->Targets)) {
                foreach ($res->Targets as $key1 => $val1) {
                    //$sub_array1= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_tar, $val1->ID);
                }
            }

            if (is_array($res->Issues)) {
                foreach ($res->Issues as $key1 => $val_issue) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_issue, $val_issue->ID);
                }
            }

            if (is_array($res->Settings)) {
                foreach ($res->Settings as $key1 => $val_setting) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_setting, $val_setting->ID);
                }
            }

            $sub_array['media_id'] = $item['id'];
            $sub_array['media_title'] = $item['title'];
            $sub_array['media_short_description'] = $item['description'];
            $sub_array['media_thumbnail_address'] = $res->ThumbnailAddress;
            $sub_array['media_file_share'] = $res->FileAddress;
            //	 $sub_array['media_file_path']=$resArr['UploadFile']['FileAddress'];
            $sub_array['media_type'] = $item['template'];
            $sub_array['Issues'] = json_encode($arr_issue);
            $sub_array['Targets'] = json_encode($arr_tar);
            $sub_array['Settings'] = json_encode($arr_setting);
            $sub_array['Sex'] = $item['sex'];
            $sub_array['Ages'] = $item['age'];
            $list_media_api[$key] = $sub_array;
        }

        if (count($list_media_api) > 0) {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => true,
                'res_result'  =>  $list_media_api,
            ]);
        } else {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => 'Data not found',
            ]);
        }
    }

    public function get_persona(Request $request)
    {

        $list_media_api = array();
        $date = date("Y-m-d");
        $arr_tar = array();
        $list_media = ListMedia::where('status', '=', 'publish')
            ->where(function ($list_media) {
                $list_media->whereIn('show_dol', [2])
                    ->orwhereIn('show_learning', [2]);
            })
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->get();

        foreach ($list_media as $key => $item) {

            $sub_array = array();
            $list_media[$key]['json_data'] = json_decode($item['json_data']);
            $res = $list_media[$key]['json_data'];
            $list_media[$key]['UploadFileID'] = strtoupper($item['UploadFileID']);
            $were = $list_media[$key]['UploadFileID'];
            $arr_issue = array();
            $arr_tar = array();
            $arr_setting = array();

            if (is_array($res->Targets)) {
                foreach ($res->Targets as $key1 => $val1) {
                    //$sub_array1= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_tar, $val1->ID);
                }
            }

            if (is_array($res->Issues)) {
                foreach ($res->Issues as $key1 => $val_issue) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_issue, $val_issue->ID);
                }
            }

            if (is_array($res->Settings)) {
                foreach ($res->Settings as $key1 => $val_setting) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_setting, $val_setting->ID);
                }
            }


            $media_thumbnail_address = (is_null($item['thumbnail_address'])) ?  $res->ThumbnailAddress : "https://resourcecenter.thaihealth.or.th/mediadol/" . $item['UploadFileID'] . "/" . $item['thumbnail_address'];
            $media_file_path = (is_null($item['local_path'])) ?  $res->ThumbnailAddress : "https://resourcecenter.thaihealth.or.th/mediadol/" . $item['UploadFileID'] . "/" . $item['local_path'];

            // $list_media = ListMedia::find($item['id']);
            // $item->getMedia('cover_desktop')->first()->getUrl()

            $sub_array['media_id'] = $item['id'];
            $sub_array['media_title'] = $item['title'];
            $sub_array['media_dol'] = ($item['show_dol'] == 1 ? "N" : "Y");
            $sub_array['media_learning'] = ($item['show_learning'] == 1 ? "N" : "Y");
            $sub_array['media_short_description'] = $item['description'];
            $sub_array['media_thumbnail_address'] = $media_thumbnail_address;
            $sub_array['media_thumbnail_address_change'] = "";
            $sub_array['media_type'] = $item['template'];
            $sub_array['media_file_path'] = $media_file_path;
            $sub_array['Issues'] = json_encode($arr_issue);
            $sub_array['Targets'] = json_encode($arr_tar);
            $sub_array['Settings'] = json_encode($arr_setting);
            $sub_array['Sex'] = $item['sex'];
            $sub_array['Ages'] = $item['age'];

            $list_media_api[$key] = $sub_array;
        }
        if (count($list_media_api) > 0) {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => true,
                'res_result'  =>  $list_media_api,
            ]);
        } else {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => 'Data not found',
            ]);
        }
    }
    public function get_resourcecenter_all(Request $request)
    {

        $page = (isset($request->page)) ? $request->page : 1;
        $limit = (isset($request->limit)) ? $request->limit : 1000;
        $offset = ($page - 1) * $limit;

        $list_media_count = ListMedia::wherenull('media_trash')->count();
        $list_media =ListMedia::wherenull('media_trash')->limit($limit)->offset($offset)->get();


        $total_page = ceil($list_media_count / $limit);





        foreach ($list_media as $key => $item) {
            $sub_array = array();
            $sub_array['media_id'] = $item['id'];
            $sub_array['title'] = $item['title'];
            $sub_array['description'] = $item['description'];
            // $sub_array['interesting_issues'] = $item['interesting_issues'];
            // $sub_array['featured'] = $item['featured'];
            //$sub_array['category_id'] = $item['category_id'];
            // $sub_array['province'] = $item['province'];
            // $sub_array['template'] = $item['province'];
            // $sub_array['area_id'] = $item['UploadFileID'];
            $sub_array['created_at'] = $item['created_at'];
            $sub_array['updated_at'] = $item['updated_at'];
            //$sub_array['hit'] = $item['hit'];
            // $sub_array['recommend'] = $item['recommend'];
            // $sub_array['articles_research'] = $item['articles_research'];
            // $sub_array['include_statistics'] = $item['include_statistics'];
            // $sub_array['department_id'] = $item['department_id'];
            // $sub_array['download'] = $item['download'];
            // $sub_array['health_literacy'] = $item['health_literacy'];
            // $sub_array['knowledges'] = $item['knowledges'];
            // $sub_array['media_campaign'] = $item['media_campaign'];
            // $sub_array['api'] = $item['api'];
            $sub_array['sex'] = $item['sex'];
            $sub_array['age'] = $item['age'];
            $sub_array['tags'] = $item['tags'];
            //$sub_array['web_view'] = $item['web_view'];
            $sub_array['image_path'] =  (isset($item['image_path']) ? URL($item['image_path']) : null);
            $sub_array['status'] = $item['status'];
            //$sub_array['ncds_2'] = $item['ncds_2'];
            //$sub_array['ncds_4'] = $item['ncds_4'];
            //$sub_array['ncds_6'] = $item['ncds_6'];
            //$sub_array['panel_discussion'] = $item['panel_discussion'];
            //$sub_array['health_trends'] = $item['health_trends'];
            //$sub_array['points_to_watch_article'] = $item['points_to_watch_article'];
            //$sub_array['points_to_watch_video'] = $item['points_to_watch_video'];
            //$sub_array['points_to_watch_gallery'] = $item['points_to_watch_gallery'];
            //$sub_array['ncds_2_situation'] = $item['ncds_2_situation'];




            $list_media_api[$key] = $sub_array;
        }

        if (count($list_media_api) > 0) {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => true,
                'total_page'  => $total_page,
                'res_result'  =>  $list_media_api,
            ]);
        } else {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => 'Data not found',
            ]);
        }
    }

    public function get_data_resourcecenter(Request $request)
    {


        $page = (isset($request->page)) ? $request->page : 1;
        $limit = (isset($request->limit)) ? $request->limit : 1000;
        $offset = ($page - 1) * $limit;



        $list_media_count = ListMedia::where('status', '=', 'publish')
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->count();
        $list_media = ListMedia::where('status', '=', 'publish')
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->limit($limit)
            ->offset($offset)
            ->get();

        $total_page = ceil($list_media_count / $limit);





        foreach ($list_media as $key => $item) {
            $sub_array = array();
            $sub_array['media_id'] = $item['id'];
            $sub_array['title'] = $item['title'];
            $sub_array['description'] = $item['description'];
            // $sub_array['interesting_issues'] = $item['interesting_issues'];
            // $sub_array['featured'] = $item['featured'];
            //$sub_array['category_id'] = $item['category_id'];
            // $sub_array['province'] = $item['province'];
            // $sub_array['template'] = $item['province'];
            // $sub_array['area_id'] = $item['UploadFileID'];
            $sub_array['created_at'] = $item['created_at'];
            $sub_array['updated_at'] = $item['updated_at'];
            //$sub_array['hit'] = $item['hit'];
            // $sub_array['recommend'] = $item['recommend'];
            // $sub_array['articles_research'] = $item['articles_research'];
            // $sub_array['include_statistics'] = $item['include_statistics'];
            // $sub_array['department_id'] = $item['department_id'];
            // $sub_array['download'] = $item['download'];
            // $sub_array['health_literacy'] = $item['health_literacy'];
            // $sub_array['knowledges'] = $item['knowledges'];
            // $sub_array['media_campaign'] = $item['media_campaign'];
            // $sub_array['api'] = $item['api'];
            $sub_array['sex'] = $item['sex'];
            $sub_array['age'] = $item['age'];
            $sub_array['tags'] = $item['tags'];
            //$sub_array['web_view'] = $item['web_view'];
            $sub_array['image_path'] =  (isset($item['image_path']) ? URL($item['image_path']) : null);
            //$sub_array['ncds_2'] = $item['ncds_2'];
            //$sub_array['ncds_4'] = $item['ncds_4'];
            //$sub_array['ncds_6'] = $item['ncds_6'];
            //$sub_array['panel_discussion'] = $item['panel_discussion'];
            //$sub_array['health_trends'] = $item['health_trends'];
            //$sub_array['points_to_watch_article'] = $item['points_to_watch_article'];
            //$sub_array['points_to_watch_video'] = $item['points_to_watch_video'];
            //$sub_array['points_to_watch_gallery'] = $item['points_to_watch_gallery'];
            //$sub_array['ncds_2_situation'] = $item['ncds_2_situation'];






            $list_media_api[$key] = $sub_array;
        }

        if (count($list_media_api) > 0) {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => true,
                'total_page'  => $total_page,
                'res_result'  =>  $list_media_api,
            ]);
        } else {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => 'Data not found',
            ]);
        }
    }

    public function get_resourcecenter(Request $request)
    {

        $list_media_api = array();
        $date = date("Y-m-d");
        $arr_tar = array();
        $list_media = ListMedia::where('status', '=', 'publish')
            ->where('api', '=', 'publish')->whereIn('show_rc', [0, 2])
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->get();


        foreach ($list_media as $key => $item) {

            $sub_array = array();
            $list_media[$key]['json_data'] = json_decode($item['json_data']);
            $res = $list_media[$key]['json_data'];
            $list_media[$key]['UploadFileID'] = strtoupper($item['UploadFileID']);
            $were = $list_media[$key]['UploadFileID'];
            $arr_issue = array();
            $arr_tar = array();
            $arr_setting = array();
            if (is_array($res->Targets)) {
                foreach ($res->Targets as $key1 => $val1) {
                    //$sub_array1= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_tar, $val1->ID);
                }
            }

            if (is_array($res->Issues)) {
                foreach ($res->Issues as $key1 => $val_issue) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_issue, $val_issue->ID);
                }
            }

            if (is_array($res->Settings)) {
                foreach ($res->Settings as $key1 => $val_setting) {
                    //  $sub_array_issue= array();
                    // $arr_tar[$key1]=$val1->ID;
                    array_push($arr_setting, $val_setting->ID);
                }
            }

            $sub_array['media_id'] = $item['id'];
            $sub_array['media_title'] = $item['title'];
            $sub_array['media_short_description'] = $item['description'];
            $sub_array['media_thumbnail_address'] = $res->ThumbnailAddress;
            $sub_array['media_file_share'] = $res->FileAddress;
            //	 $sub_array['media_file_path']=$resArr['UploadFile']['FileAddress'];
            $sub_array['media_type'] = $item['template'];
            $sub_array['Issues'] = json_encode($arr_issue);
            $sub_array['Targets'] = json_encode($arr_tar);
            $sub_array['Settings'] = json_encode($arr_setting);
            $sub_array['Sex'] = $item['sex'];
            $sub_array['Ages'] = $item['age'];
            $list_media_api[$key] = $sub_array;
        }

        if (count($list_media_api) > 0) {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => true,
                'res_result'  =>  $list_media_api,
            ]);
        } else {
            return response()->json([
                'res_code'    => '00',
                'res_text'    => 'Data not found',
            ]);
        }
    }

    public function updol_media($list_data)
    {
    
        // $tmp['img_ext_th'] = $img_ext_th;
        // $tmp['image_base64'] = $image_base64;

        //   if($list_data->hasFile('cover_desktop')==true){
        //       $service_img_th=$list_data->file('cover_desktop');
        //       $img_ext_th=strtolower($service_img_th->getClientOriginalExtension());
        //       $image1 = "data:image/".$img_ext_th.";base64,".base64_encode(file_get_contents($list_data->file('cover_desktop')->path()));
        //       dd($image1);
        //   }else{
        //           $img_ext_th=null;
        //           $image1=null;
        //   }

        if ($list_data['tags_dol'] != null) {
            $array_keyword = explode(",", $list_data['tags_dol']);
            $res_keyword = json_encode($array_keyword);
        } else {
            $res_keyword = "null";
        }

        if ($list_data['issue'] != null) {
            $array_issue = explode(",", $list_data['issue']);
            $res_issue = json_encode($array_issue);
        } else {
            $res_issue = "null";
        }

        if ($list_data['target'] != null) {
            $array_target = explode(",", $list_data['target']);
            $res_target = json_encode($array_target);
        } else {
            $res_target = "null";
        }

        $list_data['setting'] = null;
        if ($list_data['setting'] != null) {
            $array_setting = explode(",", $list_data['setting']);
            $res_setting = json_encode($array_setting);
        } else {
            $res_setting = "null";
        }

        $list_media = ListMedia::where('UploadFileID', '=', $list_data['UploadFileID'])->get();

        foreach ($list_media as $key => $item) {
            $sub_array = array();
            $list_media[$key]['json_data'] = json_decode($item['json_data']);
            $res = $list_media[$key]['json_data'];
        }

        //	if($res->Province!=null){
        //		$array_Province = explode(",",$res->Province);
        //		$res_Province=json_encode($array_Province);
        //	}else{
        //		$res_Province="null";
        //	}

        if ($res->Language != null) {
            $res_Language = json_encode($res->Language);
        } else {
            $res_Language = "null";
        }


        if ($res->ProjectCode == null) {
            $res->ProjectCode = 0;
        }

        if (is_array($res->Province)) {
            $res->Province = 0;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://dol.thaihealth.or.th/WCF/DOLService.svc/json/UpdateMediaDol',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
			"UserName":"thaihealthweb",
			"Password":"mdYZhKgVQz#mDPLebgAcjYD984UgfEe_RF_Ek9",
			"UploadFileID":"' . $list_data['UploadFileID'] . '",
			"CalledBy": "nuttamon@thaihealth.or.th",
			"CalledByType":"ThaiHealth",
			"FileAddress":"' . $list_data['FileAddress'] . '",
			"ThumbnailAddress":"' . $list_data['ThumbnailAddress'] . '",
			"Keywords":' . $res_keyword . ',
			"Title":"' . $list_data['title'] . '",
			"Description":"' . $list_data['description'] . '",
			"Template":"' . $list_data['Template'] . '",
			"TemplateDetail":"' . $list_data['template_detail'] . '",
			"PublishLevel":"' . $res->PublishLevel . '",
			"CreativeCommons":"' . $res->CreativeCommons . '",
			"Category":' . $res->CategoryID . ',
			"Issues":' . $res_issue . ',
			"OtherIssueText":"Other Issue",
			"Targets":' . $res_target . ',
			"OtherTargetText":"Other Target", 
			"Settings":' . $res_setting . ',
			"OtherSettingText":"Other Setting", 
			"Area":1,
			"Province":' . $res->Province . ',
			"Source":"' . $res->Source . '",
			"ReleasedDate":"' . $res->ReleasedDate . '",
			"Creator":"' . $list_data['creator'] . '",
			"Production":"' . $list_data['production'] . '",
			"Publisher":"' . $list_data['publisher'] . '",
			"Contributor":"' . $res->Contributor . '",
			"Identifier":"' . $res->Identifier . '",
			"Language":' . $res_Language . ',
			"Relation":"' . $res->Relation . '",
			"Format":"' . $list_data['format'] . '",
			"IntellectualProperty":"' . $res->IntellectualProperty . '",
			"OS":"' . $list_data['os'] . '",
			"Owner":"' . $list_data['owner'] . '",
			"PeriodStart":"' . $res->PeriodStart . '",
			"PeriodEnd":"' . $res->PeriodEnd . '",
			"Duration":"' . $res->Duration . '",
			"IsSubProject":' . $res->ProjectCode . ',
			"SubProjectCode":"' . $res->SubProjectCode . '",
            "ThumfileByte":"' . $list_data['image_base64'] . '",
            "ThumfileType":"' . $list_data['img_ext_th'] . '",
		}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $resArr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',  $response), true);


        return $resArr;
    }

    public function UpdateMediaThai(Request $request)
    {


        $item = ListMedia::where('UploadFileID', $request['UploadFileID'])->first();
        $data_new = json_decode($item->json_data, true);

        $res_issue_data = $request['Issues'];
        $res_target_data = $request['Targets'];
        $res_Settings_data = $request['Settings'];
        $tmparr = array();
        $tmparr_target = array();
        $tmparr_Settings = array();


        foreach ($res_Settings_data as $item_Settings) {
            $were_Settings = DB::table('list_setting')->where('setting_id', $item_Settings)->first();
            $tmp_set = [
                "ID" => $were_Settings->setting_id,
                "Name" => $were_Settings->name
            ];
            array_push($tmparr_Settings, $tmp_set);
        }
        $setting_arr = $tmparr_Settings;

        foreach ($res_target_data as $item_target) {
            $were_target = DB::table('list_target')->where('target_id', $item_target)->first();
            $tmp_tar = [
                "ID" => $were_target->target_id,
                "Name" => $were_target->name,
                "TargetGuoupID" => $were_target->TargetGuoupID

            ];
            array_push($tmparr_target, $tmp_tar);
        }
        $target_arr = $tmparr_target;



        foreach ($res_issue_data as $item_issue) {
            $were_issue = DB::table('list_issue')->where('issues_id', $item_issue)->first();
            $tmp = [
                "ID" => $were_issue->issues_id,
                "Name" => $were_issue->name
            ];
            array_push($tmparr, $tmp);
        }
        $issue_arr = $tmparr;
        $array = array();

        $array['UploadFileID'] = $item->UploadFileID;
        $array['FileAddress'] = $request['FileAddress'];
        $array['Media_ref'] = $data_new['Media_ref'];
        $array['ThumbnailAddress'] = $request['ThumbnailAddress'];
        $array['FileSize'] = $data_new['FileSize'];
        $array['ProjectCode'] = $request['IsSubProject'];
        $array['SubProjectCode'] = $request['SubProjectCode'];
        $array['PublishLevel'] = $request['PublishLevel'];
        $array['PublishLevelText'] = $data_new['PublishLevelText'];
        $array['CreativeCommons'] = $request['CreativeCommons'];
        $array['DepartmentID'] = $data_new['DepartmentID'];
        $array['DepartmentName'] = $data_new['DepartmentName'];
        $array['Title'] = $request['Title'];
        $array['Description'] = $request['Description'];
        $array['PublishedDate'] = $data_new['PublishedDate'];
        $array['PublishedByName'] = $data_new['PublishedByName'];
        $array['UpdatedDate'] = $data_new['UpdatedDate'];
        $array['UpdatedByName'] = $data_new['UpdatedByName'];
        $array['Keywords'] = $request['Keywords'];
        $array['Template'] = $request['Template'];
        $array['TemplateDetail'] = $request['TemplateDetail'];
        $array['TemplateDetailName'] = $data_new['TemplateDetailName'];
        $array['CategoryID'] = $request['Category'];
        $array['Category'] = $data_new['Category'];
        $array['Issues'] = $issue_arr;
        $array['Targets'] = $target_arr;
        $array['Settings'] = $setting_arr;
        $array['AreaID'] = $request['Area'];
        $array['Area'] = $data_new['Area'];
        $array['Province'] = $request['Province'];
        $array['Source'] = $request['Source'];
        $array['ReleasedDate'] = $request['ReleasedDate'];
        $array['Creator'] = $request['Creator'];
        $array['Production'] = $request['Production'];
        $array['Publisher'] = $request['Publisher'];
        $array['Contributor'] = $request['Contributor'];
        $array['Identifier'] = $request['Identifier'];
        $array['Language'] = $request['Language'];
        $array['Relation'] = $request['Relation'];
        $array['Format'] = $request['Format'];
        $array['IntellectualProperty'] = $request['IntellectualProperty'];
        $array['OS'] = $request['OS'];
        $array['Owner'] = $request['Owner'];
        $array['PeriodStart'] = $request['PeriodStart'];
        $array['PeriodEnd'] = $request['PeriodEnd'];
        $array['Duration'] = $request['Duration'];
        $array['SystemID'] = $data_new['SystemID'];
        $array['SystemName'] = $data_new['SystemName'];
        $array['SendMediaTermStatus'] = $data_new['SendMediaTermStatus'];


        $data = [
            'title' => $request['Title'],
            'Description' => $request['Description'],
            'province' => $request['province'],
            'template' => $request['template'],
            'area_id' => $request['Area'],
            'json_data' => json_encode($array),
            'category_id' => $request['Category'],
        ];



        $update_new = ListMedia::where('UploadFileID', $request['UploadFileID'])->update($data);

        $status = 'false';
        if ($update_new == '1') {
            $status = 'true';
        }

        return response()->json(['status' => $status]);
    }

    public function postTransfer(Request $request)
    {

        if ($request->hasFile('cover_desktop') == true) {
            $service_img_th = $request->file('cover_desktop');
            $img_ext_th = strtolower($service_img_th->getClientOriginalExtension());
            $image_base64 = base64_encode(file_get_contents($request->file('cover_desktop')->path()));
        } else {
            $img_ext_th = null;
            $image_base64 = null;
        }

        $tmp = $request->all();
        $tmp['img_ext_th'] = $img_ext_th;
        $tmp['image_base64'] = $image_base64;


        if ($request->show_dol == 'undefined') {
            $status_res = 1;
        } else {
            $status_res = 2;
        }

        // update data to dol
        $list_data = $this->updol_media($tmp);

        // $item = ListMedia::findOrFail($request['id']);
        // dd(json_decode($item->json_data));

        //dd($data);
        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        $item = ListMedia::findOrFail($request['id']);
        $json_decode_update  = json_decode($item->json_data);
        $data = $request->all();

        /* UpdateMedia Dol */

        // update json_data

        // $res_issue_data = $data['issue'];
        // $res_target_data = $data['target'];
        // $res_Settings_data = $request['setting'];

        // dd([$res_issue_data, $res_target_data, $res_Settings_data]);

        $res_issue_data = explode(",", $data['issue']);
        $res_target_data = explode(",", $data['target']);
        $res_Settings_data = explode(",", $request['setting']);

        // dd([$res_issue_data, $res_target_data, $res_Settings_data]);

        $tmparr = array();
        $tmparr_target = array();
        $tmparr_Settings = array();


        foreach ($res_Settings_data as $item_Settings) {
            $were_Settings = DB::table('list_setting')->where('setting_id', $item_Settings)->first();
            if (!is_null($were_Settings)) {
                $tmp_set = [
                    "ID" => $were_Settings->setting_id,
                    "Name" => $were_Settings->name
                ];
                array_push($tmparr_Settings, $tmp_set);
            }
        }
        $setting_arr = $tmparr_Settings;

        foreach ($res_target_data as $item_target) {
            $were_target = DB::table('list_target')->where('target_id', $item_target)->first();
            if (!is_null($were_target)) {
                $tmp_tar = [
                    "ID" => $were_target->target_id,
                    "Name" => $were_target->name,
                    "TargetGuoupID" => $were_target->TargetGuoupID

                ];
                array_push($tmparr_target, $tmp_tar);
            }
        }
        $target_arr = $tmparr_target;



        foreach ($res_issue_data as $item_issue) {
            $were_issue = DB::table('list_issue')->where('issues_id', $item_issue)->first();
            if (!is_null($were_issue)) {
                $tmp = [
                    "ID" => $were_issue->issues_id,
                    "Name" => $were_issue->name
                ];
                array_push($tmparr, $tmp);
            }
        }
        $issue_arr = $tmparr;
        $array = array();

        $array['UploadFileID'] = $item->UploadFileID;
        $array['FileAddress'] = isset($json_decode_update->FileAddress) ? $json_decode_update->FileAddress : "";
        $array['Media_ref'] = isset($json_decode_update->Media_ref) ? $json_decode_update->Media_ref : "";
        $array['ThumbnailAddress'] = isset($json_decode_update->ThumbnailAddress) ? $json_decode_update->ThumbnailAddress : "";
        $array['FileSize'] = isset($json_decode_update->FileSize) ? $json_decode_update->FileSize : "";
        $array['ProjectCode'] = isset($json_decode_update->ProjectCode) ? $json_decode_update->ProjectCode : "";
        $array['SubProjectCode'] = isset($json_decode_update->SubProjectCode) ? $json_decode_update->SubProjectCode : "";
        $array['PublishLevel'] = isset($json_decode_update->PublishLevel) ? $json_decode_update->PublishLevel : "";
        $array['PublishLevelText'] = isset($json_decode_update->PublishLevelText) ? $json_decode_update->PublishLevelText : "";
        $array['CreativeCommons'] = isset($json_decode_update->CreativeCommons) ? $json_decode_update->CreativeCommons : "";
        $array['DepartmentID'] = isset($json_decode_update->DepartmentID) ? $json_decode_update->DepartmentID : "";
        $array['DepartmentName'] = isset($json_decode_update->DepartmentName) ? $json_decode_update->DepartmentName : "";
        $array['Title'] = $json_decode_update->Title;
        $array['Description'] = $json_decode_update->Description;
        $array['PublishedDate'] = isset($json_decode_update->PublishedDate) ? $json_decode_update->PublishedDate : "";
        $array['PublishedByName'] = isset($json_decode_update->PublishedByName) ? $json_decode_update->PublishedByName : "";
        $array['UpdatedDate'] = isset($json_decode_update->UpdatedDate) ? $json_decode_update->UpdatedDate : "";
        $array['UpdatedByName'] = isset($json_decode_update->UpdatedByName) ? $json_decode_update->UpdatedByName : "";
        $array['Keywords'] = isset($json_decode_update->Keywords) ? $json_decode_update->Keywords : "";
        $array['Template'] = isset($json_decode_update->Template) ? $json_decode_update->Template : "";
        $array['TemplateDetail'] = isset($json_decode_update->TemplateDetail) ? $json_decode_update->TemplateDetail : "";
        $array['TemplateDetailName'] = isset($json_decode_update->TemplateDetailName) ? $json_decode_update->TemplateDetailName : "";
        $array['CategoryID'] = isset($json_decode_update->CategoryID) ? $json_decode_update->CategoryID : "";
        $array['Category'] = isset($json_decode_update->Category) ? $json_decode_update->Category : "";
        $array['Issues'] = $issue_arr;
        $array['Targets'] = $target_arr;
        $array['Settings'] = $setting_arr;
        $array['AreaID'] = isset($json_decode_update->AreaID) ? $json_decode_update->AreaID : "";
        $array['Area'] = isset($json_decode_update->Area) ? $json_decode_update->Area : "";
        $array['Province'] = isset($json_decode_update->Province) ? $json_decode_update->Province : "";
        $array['Source'] = isset($json_decode_update->Source) ? $json_decode_update->Source : "";
        $array['ReleasedDate'] = isset($json_decode_update->ReleasedDate) ? $json_decode_update->ReleasedDate : "";
        $array['Creator'] = isset($json_decode_update->Creator) ? $json_decode_update->Creator : "";
        $array['Production'] = isset($json_decode_update->Production) ? $json_decode_update->Production : "";
        $array['Publisher'] = isset($json_decode_update->Publisher) ? $json_decode_update->Publisher : "";
        $array['Contributor'] = isset($json_decode_update->Contributor) ? $json_decode_update->Contributor : "";
        $array['Identifier'] = isset($json_decode_update->Identifier) ? $json_decode_update->Identifier : "";
        $array['Language'] = isset($json_decode_update->Language) ? $json_decode_update->Language : "";
        $array['Relation'] = isset($json_decode_update->Relation) ? $json_decode_update->Relation : "";
        $array['Format'] = isset($json_decode_update->Format) ? $json_decode_update->Format : "";
        $array['IntellectualProperty'] = isset($json_decode_update->IntellectualProperty) ? $json_decode_update->IntellectualProperty : "";
        $array['OS'] = isset($json_decode_update->OS) ? $json_decode_update->OS : "";
        $array['Owner'] = isset($json_decode_update->Owner) ? $json_decode_update->Owner : "";
        $array['PeriodStart'] = isset($json_decode_update->PeriodStart) ? $json_decode_update->PeriodStart : "";
        $array['PeriodEnd'] = isset($json_decode_update->PeriodEnd) ? $json_decode_update->PeriodEnd : "";
        $array['Duration'] = isset($json_decode_update->Duration) ? $json_decode_update->Duration : "";
        $array['SystemID'] = isset($json_decode_update->SystemID) ? $json_decode_update->SystemID : "";
        $array['SystemName'] = isset($json_decode_update->SystemName) ? $json_decode_update->SystemName : "";
        $array['SendMediaTermStatus'] = isset($json_decode_update->SendMediaTermStatus) ? $json_decode_update->SendMediaTermStatus : "";

        if (isset($data['tags_dol'])) {
            $data_tags_dol = [];
            foreach (explode(",", $data['tags_dol']) as $key => $value) {
                array_push($data_tags_dol, $value);
            }
        }
        // update tag dol to data
        $array['Keywords'] = isset($data_tags_dol) ? $data_tags_dol : $array['Keywords'];


        $issue_dol = ($data['issue'] != '' ? '[' . $data['issue'] . ']' : '""');
        $setting_dol = ($data['setting'] != '' ? '[' . $data['setting'] . ']' : '""');

        $ReleasedDate = date('c');
        //$test2 = date('Y-m-d\TH:i:sO');
        //$test3 = date(DateTime::ISO8601);

        //dd("Case Media",$test,$test2);
        //$json_decode_update->ReleasedDate
        //$data['UploadFileID']= '';
        $body_update = '{"UserName":"' . env('API_USER', 'thrc-pro') . '",
                  "Password":"' . env('API_PASSWORD', 'sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3') . '",
                  "UploadFileID":"' . $data['UploadFileID'] . '",
                  "CalledBy":"nuttamon@thaihealth.or.th",
                  "CalledByType":"ThaiHealth",
                  "FileAddress":"' . $data['FileAddress'] . '",
                  "Keywords":["' . implode('","', $data_tags_dol) . '"],';

        // $body_update .= '"Title":"'.$data['title'].'",';
        // $body_update .= '"Description":"'.$data['description'].'",';
        $body_update .= "'Title':'" . str_replace("'", "", $data['title']) . "',";
        $body_update .= "'Description':'" . str_replace("'", "", $data['description']) . "',";
        $body_update .= '"Template":"' . $data['Template'] . '",
                  "TemplateDetail":"' . $data['template_detail'] . '",
                  "Production":"' . $data['production'] . '",
                  "CreativeCommons":"BY_NC_ND",
                  "Issues":' . $issue_dol . ',
                  "Targets":[' . $data['target'] . '],
                  "ReleasedDate":"' . $ReleasedDate . '",
                  "Source":"' . $data['source'] . '",
                  "Creator":"' . $data['creator'] . '",
                  "Format":"' . $data['format'] . '",
                  "OS":"' . $data['os'] . '",
                  "Owner":"' . $data['owner'] . '",
                  "publisher":"' . $data['publisher'] . '",
                  "ThumbnailAddress":"' . $json_decode_update->ThumbnailAddress . '",
                  "ProjectCode":"' . $json_decode_update->ProjectCode . '",
                  "PublishLevel":"1",
                  "Settings":' . $setting_dol . '}';

        // ส่ง mail ไปแจ้งเตือน สื่อวาระกลาง
        if (is_null($data['term'])) {


            // กรณีไม่เป็นสื่อวาระกลางถึงจะมีหารอัพเดท
            $data_update['SendMediaTermStatus'] = $data['term'];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://dol.thaihealth.or.th/WCF/DOLService.svc/json/GetMediaDol',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                        "UserName" : "thaihealthweb",
                        "Password" : "mdYZhKgVQz#mDPLebgAcjYD984UgfEe_RF_Ek9",
                        "UploadFileID" : "'  . $data['UploadFileID'] . '"
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Cookie: lastRequestTime='
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);


            if ($response) {
                $resArr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',  $response), true);
                // สงเมล์

                // dd($resArr);

                $name = (isset($resArr['UpdatedByName'])) ? $resArr['UpdatedByName'] : "ไม่ระบุชื่อ";
                $email = (isset($resArr['CreateByEmail'])) ? $resArr['CreateByEmail'] : "";


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://persona.thaihealth.or.th/api/dol_send_report',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                            "name" : "' . $name . '",
                            "email" : "' . $email  . '",
                            "media_title" : "' . $data['title']  . '",
                            "dol_link" : "' . $data['FileAddress'] . '",
                            "reason" : "' . $data['not_term'] . '",
                            "comment" : "' . $data['detail_not_trem'] . '"
                        }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
        }

        // ส่ง mail ไปแจ้งเตือน สื่อวาระกลาง


        // update ไปที่ persona

        $curl = curl_init();

        $media_dol = (isset($data['show_dol']) && $data['show_dol'] == '2') ? "Y" : "N";
        $media_learning = (isset($data['show_learning']) && $data['show_learning'] == '2') ? "Y" : "N";
        $media_thrc = (isset($data['show_rc']) && $data['show_rc'] == '2') ? "Y" : "N";

        $today = date('Y-m-d');

        if ($data['start_date'] != "" && $data['start_date'] > $today) {
            $media_dol = 'N';
            $media_learning  = 'N';
            $media_thrc = 'N';
        }

        if ($data['end_date'] != "" && $today > $data['end_date']) {
            $media_dol = 'N';
            $media_learning  = 'N';
            $media_thrc = 'N';
        }


        // dd([$media_dol, $media_learning]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://persona.thaihealth.or.th/api/thrc/update_media_from_thrc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "media_id": ' . $request['id'] . ',
                    "media_title": "' . $data['title'] . '",
                    "media_dol": "' . $media_dol . '",
                    "media_learning": "' . $media_learning . '",
                    "media_thrc": "' . $media_thrc . '",
                    "media_short_description": "' . str_replace("'", "", $data['description']) . '",
                    "media_thumbnail_address": "' . $json_decode_update->ThumbnailAddress . '",
                    "media_thumbnail_address_change": "",
                    "media_type": "' . $item->template . '",
                    "media_file_path": "' . $data['FileAddress'] . '",
                    "Issues": "' . $issue_dol . '",
                    "Targets": "[' . $data['target'] . ']",
                    "Settings": "' . $setting_dol . '",
                    "Sex": "[' . $item->sex . ']",
                    "Ages": "[' . $item->age . ']"
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        // update ไปที่ persona


        /*old*/
        // $body_update = '{"UserName":"'.env('API_USER','thrc-pro').'",
        //           "Password":"'.env('API_PASSWORD','sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3').'",
        //           "UploadFileID":"'.$data['UploadFileID'].'",
        //           "CalledBy":"nuttamon@thaihealth.or.th",
        //           "CalledByType":"ThaiHealth",
        //           "FileAddress":"'.$data['FileAddress'].'",
        //           "Keywords":["'.implode('","',$data_tags_dol).'"],
        //           "Title":"'.str_replace('"','',$data['title']).'",
        //           "Description":"'.str_replace('"','',$data['description']).'",
        //           "Template":"'.$data['Template'].'",
        //           "TemplateDetail":"'.$data['template_detail'].'",
        //           "Production":"'.$data['production'].'",
        //           "CreativeCommons":"BY_NC_ND",
        //           "Issues":'.$issue_dol.',
        //           "Targets":['.$data['target'].'],
        //           "ReleasedDate":"'.$ReleasedDate.'",
        //           "Source":"'.$data['source'].'",
        //           "Creator":"'.$data['creator'].'",
        //           "Format":"'.$data['format'].'",
        //           "OS":"'.$data['os'].'",
        //           "Owner":"'.$data['owner'].'",
        //           "publisher":"'.$data['publisher'].'",
        //           "ThumbnailAddress":"'.$json_decode_update->ThumbnailAddress.'",
        //           "ProjectCode":"'.$json_decode_update->ProjectCode.'",
        //           "PublishLevel":"1",
        //           "Settings":'.$setting_dol.'}';


        //dd($body_update);
        //"ThumbnailAddress":"'.$json_decode_update->ThumbnailAddress.'",

        // $body_update = '{"UserName":"'.env('API_USER').'",
        //     "Password":"'.env('API_PASSWORD').'",
        //     "UploadFileID":"'.$data['UploadFileID'].'",
        //     "CalledBy": "nuttamon@thaihealth.or.th",
        //     "CalledByType":"ThaiHealth",
        //     "FileAddress":"https://dol-uat.thaihealth.or.th/Media/Index/ded37df1-5544-e811-80df-00155dddb750",
        //     "Keywords":["'.implode('","',$data_tags_dol).'"],
        //     "Title":"'.$data['title'].'",
        //     "Description":"'.$data['description'].'",
        //     "Template":"'.$data['Template'].'",
        //     "CreativeCommons":"BY_NC_ND",
        //     "Issues":'.$issue_dol.',
        //     "Targets":['.$data['target'].'],
        //     "ReleasedDate":"2018-04-20T00:00:00",
        //     "Source":"สสส",
        //     "Creator":"ทดสอบ",
        //     "Format":"pdf",
        //     "Settings":'.$setting_dol.'}';

        // "{"UserName":"thrc-uat",
        //     "Password":"-8hKazfcwxG-WqA@7WH/MRxatyrdcCS3qt^DrTE-TA",
        //     "UploadFileID":"7118b6b4-fe83-ec11-80fa-00155db45613",
        //     "CalledBy": "nuttamon@thaihealth.or.th",
        //     "CalledByType":"ThaiHealth",
        //     "FileAddress":"https://dol-uat.thaihealth.or.th/Media/Index/ded37df1-5544-e811-80df-00155dddb750",
        //     "Keywords":["test","test2","r2","Web","ทดสอบ"],
        //     "Title":"LearningTogetherVol21",
        //     "Description":"LearningTogetherเป็นจดหมายข่าวเพื่อภาคีเครือข่ายสุขภาวะโดยฉบับนี้เป็นฉบับที่21ซึ่งภายในจะนําเสนอเรื่องการเดินทางของความสุขที่พลิกหลากหลายมิติของสังคมสุขภาวะการสร้างกลไกการทํางานสุขภาวะแนวใหม่และเปิดพื้นที่แลกเปลี่ยนเรียนรู้กระบวนขับเคลื่อนจากผลงานที่เป็นรูปธรรม",
        //     "Template":"Text",
        //     "CreativeCommons":"BY_NC_ND",
        //     "Issues":[19],
        //     "Targets":[32,15,22,4],
        //     "ReleasedDate":"2018-04-20T00:00:00",
        //     "Source":"สสส",
        //     "Creator":"ทดสอบ",
        //     "Format":"pdf",
        //     "Settings":[3]}"


        //-v
        // $client = new \GuzzleHttp\Client();
        // $request_update = $client->request('POST', env('URL_LIST_UPDATE_DOL','http://dol-uat.thaihealth.or.th/WCF/DOLService.svc/json/UpdateMediaDol'), ['body' => $body_update]);    
        // $response_api = $request_update->getBody()->getContents();
        // $response_api = str_replace(" ","",substr($response_api,3));
        // $data_json_update_media = json_decode($response_api, true);
        // $check_status_update = '0';
        // $data_dol_new = '';
        // $array = array();
        // if(isset($data_json_update_media['Code']) && $data_json_update_media['Code'] =="00"){
        //     $check_status_update = '1';
        //     $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"'.$data['UploadFileID'].'"}';
        //     $body = '{"UserName":"'.env('API_USER').'","Password":"'.env('API_PASSWORD').'","UploadFileID":"e63e4091-929a-e611-80db-00155d3d0608"}';
        //     $client = new \GuzzleHttp\Client();
        //     $request_media = $client->request('POST', env('URL_GET_MEDIA'), ['body' => $body]);    
        //     $response_api = $request_media->getBody()->getContents();
        //     $response_api = str_replace(" ","",substr($response_api,3));
        //     $data_dol_new = json_decode($response_api, true);
        //     $array['title'] = $data_dol_new['UploadFile']['Title'];
        //     $array['description'] = $data_dol_new['UploadFile']['Description'];
        //     $array['category_id'] = $data_dol_new['UploadFile']['CategoryID'];
        //     $array['province'] = (isset($data_dol_new['UploadFile']['Province']['0']) ? $data_dol_new['UploadFile']['Province']['0']:'');
        //     $array['template'] = $data_dol_new['UploadFile']['Template'];
        //     $array['area_id'] = $data_dol_new['UploadFile']['AreaID'];
        //     $array['json_data'] = json_encode($data_dol_new['UploadFile']);
        //      dd($array,isset($data_json['UploadFile']['Province']['0']),$data_json['UploadFile']['Province'],gettype($data_json['UploadFile']['Province']),"Case True2");
        //     ListMedia::where('UploadFileID','=',$data_dol_new['UploadFileID'])->update($array);
        //
        // }
        // 
        /* End UpdateMedia DOl  */

        $data_json_update_media = $list_data['Code'];
        $check_status_update = $list_data['Code'];


        if (is_null($item->media_trash)) {
            if (($data['show_rc'] == 'undefined')) {
                $data['show_rc'] = 1;
            } else {
                $data['show_rc'] = 2;
            }

            if (($data['show_dol'] == 'undefined')) {
                $data['show_dol'] = 1;
            } else {
                $data['show_dol'] = 2;
            }

            if (($data['show_learning'] == 'undefined')) {
                $data['show_learning'] = 1;
            } else {
                $data['show_learning'] = 2;
            }
        } else {
            $data['show_learning'] = 1;
            $data['show_dol'] = 1;
            $data['show_rc'] = 1;
        }


        //$item = ListMedia::findOrFail($data['id']);

        if ($data['status'] == 'publish') {
            $data['not_publish_reason'] = null;
        }


        if ($data['articles_research'] == '2') {
            $data['research_not_publish_reason'] = null;
        }
        if ($data['include_statistics'] == '2') {
            $data['stat_not_publish_reason'] = null;
        }
        if ($data['knowledges'] == '2') {
            $data['knowledge_not_publish_reason'] = null;
        }
        if ($data['media_campaign'] == '2') {
            $data['campaign_not_publish_reason'] = null;
        }


        // if(($data['show_dol']=='undefined')){
        //     $data['show_dol']=1;
        // }else{
        //     $data['show_dol']=2;
        // }
        // dd($request->cover_desktop);
        // $json_decode_update
        $data_update['json_data'] = json_encode($array);
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
        $data_update['web_view'] = $data['web_view'];
        $data_update['api'] = $data['api'];/* ขาดตรงนี้ */
        $data_update['ncds_2'] = $data['ncds_2'];
        $data_update['ncds_4'] = $data['ncds_4'];
        $data_update['ncds_6'] = $data['ncds_6'];
        $data_update['panel_discussion'] = $data['panel_discussion'];
        $data_update['health_trends'] = $data['health_trends'];
        $data_update['points_to_watch_article'] = $data['points_to_watch_article'];
        $data_update['points_to_watch_video'] = $data['points_to_watch_video'];
        $data_update['points_to_watch_gallery'] = $data['points_to_watch_gallery'];
        $data_update['ncds_2_situation'] = $data['ncds_2_situation'];
        $data_update['updated_by'] = $data['updated_by'];
        $data_update['show_rc'] = $data['show_rc'];
        $data_update['show_dol'] = $data['show_dol'];
        $data_update['show_learning'] = $data['show_learning'];
        $data_update['start_date'] = $data['start_date'];
        $data_update['end_date'] = $data['end_date'];
        $data_update['not_publish_reason'] = $data['not_publish_reason'];

        // not reason 
        // $data_update['research_not_publish_reason'] = $data['research_not_publish_reason'];
        // $data_update['stat_not_publish_reason'] = $data['stat_not_publish_reason'];
        // $data_update['knowledge_not_publish_reason'] = $data['knowledge_not_publish_reason'];   
        // $data_update['campaign_not_publish_reason'] = $data['campaign_not_publish_reason'];
        // $data_update['interesting_issues_not_publish_reason'] = $data['interesting_issues_not_publish_reason'];

        $item->update($data_update);
        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
            $item->update(['image_path' => $item->getFirstMediaUrl('cover_desktop', 'thumb1024x618')]);
            //dd($item->getFirstMediaUrl('cover_desktop','thumb1024x618'));
        }

        if (isset($data['tags'])) {
            $data_tags = array();
            foreach (explode(",", $data['tags']) as $key => $value) {

                //dd($value);
                $tag_check = Tags::select('id')->where('title', '=', $value)->first();
                //dd($tag_check);
                if (isset($tag_check->id)) {
                    array_push($data_tags, $value);
                } else {
                    $data_tag_master = array();
                    $data_tag_master['title'] = $value;
                    $data_tag_master['status'] = 'publish';
                    Tags::create($data_tag_master);
                    array_push($data_tags, $value);
                }
            }
            //dd($data_tags);
            $item->update(['tags' => json_encode($data_tags)]);
        } else {
            $item->update(['tags' => '']);
        }


        if (isset($data['sex'])) {

            //dd($data['tags']);
            $data_sex = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach (explode(",", $data['sex']) as $key => $value) {

                //dd($value);
                if (is_numeric($value)) {
                    array_push($data_sex, (int)$value);
                } else {
                    $data_sex_master = array();
                    $data_sex_master['name'] = $value;
                    $data_sex_master['status'] = 'publish';
                    $sex_id = Sex::create($data_sex_master);
                    //dd($sex_id->id);
                    array_push($data_sex, $sex_id->id);
                }
            }
            //dd($data_sex);
            $item->update(['sex' => json_encode($data_sex)]);
        } else {
            $item->update(['sex' => '']);
        }


        if (isset($data['age'])) {

            //dd($data['tags']);
            $data_age = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach (explode(",", $data['age']) as $key => $value) {

                //dd($value);
                if (is_numeric($value)) {
                    array_push($data_age, (int)$value);
                } else {
                    $data_age_master = array();
                    $data_age_master['name'] = $value;
                    $data_age_master['status'] = 'publish';
                    $age_id = Age::create($data_age_master);
                    //dd($age_id->id);
                    array_push($data_age, $age_id->id);
                }
            }
            //dd($data_age);
            $item->update(['age' => json_encode($data_age)]);
        } else {
            $item->update(['age' => '']);
        }

        //dd("Age Success");

        if ($data['interesting_issues'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'interesting_issues')
                ->first();


            $data_article['page_layout'] = 'interesting_issues';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];

            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'interesting_issues')
                    ->update($data_article);
            }

            //dd($data['UploadFileID'],$check_data);

        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'interesting_issues')
                ->delete();
        }

        if ($data['articles_research'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'articles_research')
                ->first();

            $data_article['page_layout'] = 'articles_research';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];

            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'articles_research')
                    ->update($data_article);
            }

            //dd($data['UploadFileID'],$check_data);
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'articles_research')
                ->delete();
        }

        if ($data['include_statistics'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'include_statistics')
                ->first();

            $data_article['page_layout'] = 'include_statistics';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];
            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'include_statistics')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'include_statistics')
                ->delete();
        }


        if ($data['health_literacy'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health-literacy')
                ->first();

            $data_article['page_layout'] = 'health-literacy';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;
            $json_decode  = json_decode($item->json_data);

            $array_category = [];
            foreach ($json_decode->Issues as $value_issues) {
                //dd($value_issues);
                if ($value_issues->ID == 5) {
                    #แอลกอฮอล์
                    //$data_article['category_id'] = 5;
                    array_push($array_category, 5);
                }

                if ($value_issues->ID == 28) {
                    #บุหรี่
                    //$data_article['category_id'] = 6;
                    array_push($array_category, 6);
                }

                if ($value_issues->ID == 39) {
                    #อาหาร
                    //$data_article['category_id'] = 7;
                    array_push($array_category, 7);
                }

                if ($value_issues->ID == 18) {
                    #กิจกรรมทางกาย
                    //$data_article['category_id'] = 8;
                    array_push($array_category, 8);
                }

                if ($value_issues->ID == 41) {
                    #อุบัติเหตุ
                    //$data_article['category_id'] = 9;
                    array_push($array_category, 9);
                }

                if ($value_issues->ID == 37) {
                    #เพศ เช่น ท้องไม่พร้อม
                    //$data_article['category_id'] = 10;
                    array_push($array_category, 10);
                }

                if ($value_issues->ID == 34) {
                    #สุขภาพจิต
                    //$data_article['category_id'] = 11;
                    array_push($array_category, 11);
                }
                if ($value_issues->ID == 35) {
                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                    //$data_article['category_id'] = 12;
                    array_push($array_category, 12);
                }

                if ($value_issues->ID == 36) {
                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                    //$data_article['category_id'] = [12];
                    array_push($array_category, 12);
                }

                if ($value_issues->ID == 27) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 33) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 49) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 16) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 21) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 32) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 42) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }
            }
            $data_article['category_id'] = json_encode($array_category);
            //$data_article['category_id'] 
            //dd($data_article,"Case 1");

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                //dd($data_article);
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'health-literacy')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health-literacy')
                ->delete();
        }


        if ($item->json_data != '' && $data['status'] == 'publish') {
            $json_decode  = json_decode($item->json_data);
            //dd($json_decode);

            if (gettype($json_decode) == 'object') {
                $check_data = 0;
                $data_array_check = array("healthliteracy");

                foreach ($json_decode->Keywords as $value) {
                    //echo $value;
                    //echo "<br>";
                    if (array_keys($data_array_check, strtolower($value))) {
                        $check_data = 1;
                        //echo $value;-
                        //echo "<br>";
                        //exit();
                        //dd("True");
                    }
                }

                if ($check_data == 1) {

                    $check_article = Article::select('id')
                        ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                        ->where('page_layout', '=', 'health-literacy')
                        ->first();

                    $data_article['page_layout'] = 'health-literacy';
                    $data_article['title'] = $data['title'];
                    $data_article['description'] = $data['description'];
                    $data_article['short_description'] = strip_tags($data['description']);
                    $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
                    $data_article['dol_UploadFileID'] = $data['UploadFileID'];
                    $data_article['dol_url'] = $data['FileAddress'];
                    $data_article['dol_template'] = $data['Template'];
                    $data_article['dol_json_data'] = $item->json_data;
                    $data_article['category_id'] = 0;

                    $array_category = [];
                    foreach ($json_decode->Issues as $value_issues) {
                        //dd($value_issues);
                        if ($value_issues->ID == 5) {
                            #แอลกอฮอล์
                            //$data_article['category_id'] = 5;
                            array_push($array_category, 5);
                        }

                        if ($value_issues->ID == 28) {
                            #บุหรี่
                            //$data_article['category_id'] = 6;
                            array_push($array_category, 6);
                        }

                        if ($value_issues->ID == 39) {
                            #อาหาร
                            //$data_article['category_id'] = 7;
                            array_push($array_category, 7);
                        }

                        if ($value_issues->ID == 18) {
                            #กิจกรรมทางกาย
                            //$data_article['category_id'] = 8;
                            array_push($array_category, 8);
                        }

                        if ($value_issues->ID == 41) {
                            #อุบัติเหตุ
                            //$data_article['category_id'] = 9;
                            array_push($array_category, 9);
                        }

                        if ($value_issues->ID == 37) {
                            #เพศ เช่น ท้องไม่พร้อม
                            //$data_article['category_id'] = 10;
                            array_push($array_category, 10);
                        }

                        if ($value_issues->ID == 34) {
                            #สุขภาพจิต
                            //$data_article['category_id'] = 11;
                            array_push($array_category, 11);
                        }
                        if ($value_issues->ID == 35) {
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            //$data_article['category_id'] = 12;
                            array_push($array_category, 12);
                        }

                        if ($value_issues->ID == 36) {
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            //$data_article['category_id'] = [12];
                            array_push($array_category, 12);
                        }

                        if ($value_issues->ID == 27) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 33) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 49) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 16) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 21) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 32) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 42) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }
                    }
                    $data_article['category_id'] = json_encode($array_category);
                    //$data_article['category_id'] 
                    //dd($data_article,"Case 2");

                    $date_year = date('Y-m-d');
                    $date_year = strtotime($date_year);
                    $date_year = strtotime("+10 year", $date_year);
                    $data_article['start_date'] = date("Y-m-d H:i:s");
                    $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                    $data_article['updated_by'] = $data['updated_by'];
                    //dd($data_article);



                    if (!isset($check_article->id)) {
                        Article::create($data_article);
                    } else {
                        Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                            ->where('page_layout', '=', 'health-literacy')
                            ->update($data_article);
                    }
                }
            }
        }
        // else{
        //     Article::where('dol_UploadFileID','=',$data['UploadFileID'])
        //             ->where('page_layout','=','health-literacy')
        //             ->delete();
        // }


        /* API */
        // if($data['api'] == 'publish'){

        // }else{

        // }




        /* Start Api */
        $data_media = ListMedia::findOrFail($data['id']);
        $data_media->updated_by = 0;
        $media_json_data = json_decode($data_media->json_data);
        $media_json_data_new = new \stdClass();
        // $media_json_data->Title = str_replace('"','',$media_json_data->Title);
        // $media_json_data->Description = str_replace('"','',$media_json_data->Description);
        // $media_json_data->Title = str_replace(' ','',$media_json_data->Title);
        // $media_json_data->Description = str_replace(' ','',$media_json_data->Description);
        // if($media_json_data->SubProjectCode == null || $media_json_data->SubProjectCode == 'null'){
        // $media_json_data->SubProjectCode = 'not-specified';
        // //dd("Case True",$media_json_data);
        //}
        // if($media_json_data->FileSize == null || $media_json_data->FileSize == 'null'){
        // $media_json_data->FileSize = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        // if($media_json_data->ProjectCode == null || $media_json_data->ProjectCode == 'null'){
        // $media_json_data->ProjectCode = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        // if($media_json_data->PublishLevel == null || $media_json_data->PublishLevel == 'null'){
        // $media_json_data->PublishLevel = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                    
        // if($media_json_data->PublishLevelText == null || $media_json_data->PublishLevelText == 'null'){
        // $media_json_data->PublishLevelText = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                      
        // if($media_json_data->CreativeCommons == null || $media_json_data->CreativeCommons == 'null'){
        // $media_json_data->CreativeCommons = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                 
        // if($media_json_data->DepartmentID == null || $media_json_data->DepartmentID == 'null'){
        // $media_json_data->DepartmentID = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                        
        // if($media_json_data->DepartmentName == null || $media_json_data->DepartmentName == 'null'){
        // $media_json_data->DepartmentName = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                          
        // if($media_json_data->PublishedDate == null || $media_json_data->PublishedDate == 'null'){
        // $media_json_data->PublishedDate = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                       
        // if($media_json_data->PublishedByName == null || $media_json_data->PublishedByName == 'null'){
        // $media_json_data->PublishedByName = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                    
        // if($media_json_data->UpdatedDate == null || $media_json_data->UpdatedDate == 'null'){
        // $media_json_data->UpdatedDate = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                             
        // if($media_json_data->UpdatedByName == null || $media_json_data->UpdatedByName == 'null'){
        // $media_json_data->UpdatedByName = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                         
        // if($media_json_data->Keywords == null || $media_json_data->Keywords == 'null'){
        //     $media_json_data->Keywords = 'not-specified';
        //     //dd("Case True",$media_json_data);
        // }                                            
        // if($media_json_data->Template == null || $media_json_data->Template == 'null'){
        // $media_json_data->Template = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                         
        // if($media_json_data->CategoryID == null || $media_json_data->CategoryID == 'null'){
        // $media_json_data->CategoryID = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->Category == null || $media_json_data->Category == 'null'){
        // $media_json_data->Category = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        if ($media_json_data->Issues == null || $media_json_data->Issues == 'null') {
            $media_json_data_new->Issues = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->Issues = $media_json_data->Issues;
        }
        if ($media_json_data->Targets == null || $media_json_data->Targets == 'null') {
            $media_json_data_new->Targets = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->Targets = $media_json_data->Targets;
        }
        if ($media_json_data->Settings == null || $media_json_data->Settings == 'null') {
            $media_json_data_new->Settings = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->Settings = $media_json_data->Settings;
        }
        if ($media_json_data->Keywords == null || $media_json_data->Keywords == 'null') {
            $media_json_data_new->Keywords = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->Keywords = $media_json_data->Keywords;
        }
        if ($media_json_data->ThumbnailAddress == null || $media_json_data->ThumbnailAddress == 'null') {
            $media_json_data_new->ThumbnailAddress = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->ThumbnailAddress = $media_json_data->ThumbnailAddress;
        }
        if ($media_json_data->UploadFileID == null || $media_json_data->UploadFileID == 'null') {
            $media_json_data_new->UploadFileID = 'not-specified';
            //dd("Case True",$media_json_data);
        } else {
            $media_json_data_new->UploadFileID = $media_json_data->UploadFileID;
        }



        // if($media_json_data->AreaID == null || $media_json_data->AreaID == 'null'){
        // $media_json_data->AreaID = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->Area == null || $media_json_data->Area == 'null'){
        // $media_json_data->Area = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }          
        // if($media_json_data->Province == null || $media_json_data->Province == 'null'){
        // $media_json_data->Province = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                          
        // if($media_json_data->Source == null || $media_json_data->Source == 'null'){
        // $media_json_data->Source = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->ReleasedDate == null || $media_json_data->ReleasedDate == 'null'){
        // $media_json_data->ReleasedDate = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                                                               
        // if($media_json_data->Creator == null || $media_json_data->Creator == 'null'){
        // $media_json_data->Creator = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->Production == null || $media_json_data->Production == 'null'){
        // $media_json_data->Production = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->Publisher == null || $media_json_data->Publisher == 'null'){
        // $media_json_data->Publisher = 'not-specified';
        // //dd("Case True",$media_json_data);
        // } 
        // if($media_json_data->Contributor == null || $media_json_data->Contributor == 'null'){
        // $media_json_data->Contributor = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                                                                                                                                                                       
        // if($media_json_data->Identifier == null || $media_json_data->Identifier == 'null'){
        // $media_json_data->Identifier = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->Language == null || $media_json_data->Language == 'null'){
        // $media_json_data->Language = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->Relation == null || $media_json_data->Relation == 'null'){
        // $media_json_data->Relation = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->Format == null || $media_json_data->Format == 'null'){
        // $media_json_data->Format = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->IntellectualProperty == null || $media_json_data->IntellectualProperty == 'null'){
        // $media_json_data->IntellectualProperty = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->OS == null || $media_json_data->OS == 'null'){
        // $media_json_data->OS = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->Owner == null || $media_json_data->Owner == 'null'){
        // $media_json_data->Owner = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }  
        // if($media_json_data->PeriodStart == null || $media_json_data->PeriodStart == 'null'){
        // $media_json_data->PeriodStart = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                                                                                                                                                                                                                                                                                         
        // if($media_json_data->PeriodEnd == null || $media_json_data->PeriodEnd == 'null'){
        // $media_json_data->PeriodEnd = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        // if($media_json_data->Duration == null || $media_json_data->Duration == 'null'){
        // $media_json_data->Duration = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        // if($media_json_data->SystemID == null || $media_json_data->SystemID == 'null'){
        // $media_json_data->SystemID = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }
        // if($media_json_data->SystemName == null || $media_json_data->SystemName == 'null'){
        // $media_json_data->SystemName = 'not-specified';
        // //dd("Case True",$media_json_data);
        // }                                                                                                                         
        //$data_media->json_data = json_encode($media_json_data);
        $data_media->json_data = $media_json_data_new;
        //dd($data_media,$data_media->json_data);

        /*Login*/
        $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
        //dd($body);
        $client = new \GuzzleHttp\Client();
        $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body' => $body
        ]);
        $response_api = $request->getBody()->getContents();
        $data_json = json_decode($response_api);

        //dd($data_json);

        if ($data_json->status_code === 200) {

            $access_token = $data_json->data->access_token;
            //dd($access_token);
            $body = '{"device_token":"thrc_backend","media_type":"media","status_media":"' . $data['api'] . '","media":' . json_encode($data_media) . '}';
            //dd($body,$media_json_data_new,env('THRC_URL_API','https://api.thaihealth.or.th'));
            $client = new \GuzzleHttp\Client();
            $request = $client->request('POST', env('THRC_URL_API', 'https://api.thaihealth.or.th') . env('THRC_URL_API_UPDATE_MEDIA', '/api/update-media'), [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'authorization' => $access_token
                ],
                'body' => $body
            ]);
            $response_api = $request->getBody()->getContents();
            $data_json = json_decode($response_api);
            //dd($data_json);
        }
        /* End Api */



        /*ncds-2 ncds_2_keywords อัพเดทสถานการณ์ NCDs */
        if ($data['ncds_2'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-2')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'ncds-2';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $ncd2_json_decode  = json_decode($item->json_data);
            $ncd2_json_decode->situation_ncds = $data['ncds_2_situation'];
            $data_article['dol_json_data'] = json_encode($ncd2_json_decode);
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'ncds-2')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-2')
                ->delete();
        }


        /*ncds-4 ncds_4_keywords แบบทดสอบทักษะความรอบรู้สุขภาพ*/
        if ($data['ncds_4'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-4')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'ncds-4';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'ncds-4')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-4')
                ->delete();
        }



        /*ncds_6_keywords เครื่องมืออื่นๆ ที่น่าสนใจ*/
        if ($data['ncds_6'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-6')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'ncds-6';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'ncds-6')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'ncds-6')
                ->delete();
        }


        /*panel_discussion*/
        if ($data['panel_discussion'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'panel_discussion')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'panel_discussion';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;
            $data_article['year'] = date('Y');

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'panel_discussion')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'panel_discussion')
                ->delete();
        }


        /*health_trends*/
        if ($data['health_trends'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health_trends')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'health_trends';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'health_trends')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health_trends')
                ->delete();
        }


        /*points_to_watch_article*/
        if ($data['points_to_watch_article'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_article')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'points_to_watch_article';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'points_to_watch_article')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_article')
                ->delete();
        }


        /*points_to_watch_video*/
        if ($data['points_to_watch_video'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_video')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'points_to_watch_video';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'points_to_watch_video')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_video')
                ->delete();
        }


        /*points_to_watch_gallery*/
        if ($data['points_to_watch_gallery'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_gallery')
                ->first();

            $data_article = array();
            $data_article['page_layout'] = 'points_to_watch_gallery';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'points_to_watch_gallery')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'points_to_watch_gallery')
                ->delete();
        }


        if (isset($data['tags_dol'])) {
            //dd($data_tags);
            $json_decode  = json_decode($item->json_data);
            $data_tags = [];
            foreach (explode(",", $data['tags_dol']) as $key => $value) {
                array_push($data_tags, $value);
            }
            $json_decode->Keywords = $data_tags;
            $new_json = $json_decode;
            $item->update(['json_data' => json_encode($new_json)]);
        } else {
            $json_decode  = json_decode($item->json_data);
            $json_decode->Keywords = [];
            $new_json = $json_decode;
            $item->update(['json_data' => json_encode($new_json)]);
        }

        //dd("Success");

        self::postLogs(['event' => 'แก้ไขข้อมูลหัวข้อ "' . $data_update['title'] . '"', 'module_id' => '13']);
        $response = array();
        $response['msg'] = '200 OK';
        $response['status'] = true;
        $response['input'] = $data;
        $response['data_json'] = $data_json_update_media;
        $response['check_status_update'] = $check_status_update;
        //$response['data_dol_new'] =$data_dol_new;
        //$response['body'] =$body_update;
        //$response['array'] =$array;




        //$response['file'] =$item->image_path;
        //$response['new_json'] =$new_json;


        return  Response::json($response, 200);
    }



    public function getEdit($id)
    {
        //dd("Get Edit");
        $data = ListMedia::findOrFail($id);
        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');

        $tags = Tags::Data(['status' => ['publish']])->pluck('title', 'title');
        //$tags_select = DataTags::DataId(['data_id'=>$id,'data_type'=>'media'])->pluck('tags_id');
        //dd(json_decode($data->tags));

        $sex = Sex::Data(['status' => ['publish']])->pluck('name', 'id');
        $age = Age::Data(['status' => ['publish']])->pluck('name', 'id');

        //dd($sex,$age);
        //dd($tags,$tags_select);
        //dd($data,$settings);
        return view('api::backend.list_media.edit', compact('data', 'tags', 'sex', 'age'));
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
        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
            $item->update(['image_path' => $item->getFirstMediaUrl('cover_desktop', 'thumb1024x618')]);
            //dd($item->getFirstMediaUrl('cover_desktop','thumb1024x618'));
        }





        if (isset($data['tags'])) {

            //dd($data['tags']);
            $data_tags = array();
            foreach ($data['tags'] as $key => $value) {

                //dd($value);
                $tag_check = Tags::select('id')->where('title', '=', $value)->first();
                //dd($tag_check);
                if (isset($tag_check->id)) {
                    array_push($data_tags, $value);
                } else {
                    $data_tag_master = array();
                    $data_tag_master['title'] = $value;
                    $data_tag_master['status'] = 'publish';
                    Tags::create($data_tag_master);
                    array_push($data_tags, $value);
                }
            }
            //dd($data_tags);
            $item->update(['tags' => json_encode($data_tags)]);
        } else {
            $item->update(['tags' => '']);
        }


        if (isset($data['sex'])) {

            //dd($data['tags']);
            $data_sex = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach ($data['sex'] as $key => $value) {

                //dd($value);
                if (is_numeric($value)) {
                    array_push($data_sex, (int)$value);
                } else {
                    $data_sex_master = array();
                    $data_sex_master['name'] = $value;
                    $data_sex_master['status'] = 'publish';
                    $sex_id = Sex::create($data_sex_master);
                    //dd($sex_id->id);
                    array_push($data_sex, $sex_id->id);
                }
            }
            //dd($data_sex);
            $item->update(['sex' => json_encode($data_sex)]);
        } else {
            $item->update(['sex' => '']);
        }


        if (isset($data['age'])) {

            //dd($data['tags']);
            $data_age = array();
            //DataTags::where('data_id','=',$id)->delete();
            foreach ($data['age'] as $key => $value) {

                //dd($value);
                if (is_numeric($value)) {
                    array_push($data_age, (int)$value);
                } else {
                    $data_age_master = array();
                    $data_age_master['name'] = $value;
                    $data_age_master['status'] = 'publish';
                    $age_id = Age::create($data_age_master);
                    //dd($age_id->id);
                    array_push($data_age, $age_id->id);
                }
            }
            //dd($data_age);
            $item->update(['age' => json_encode($data_age)]);
        } else {
            $item->update(['age' => '']);
        }

        //dd("Age Success");

        if ($data['interesting_issues'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'interesting_issues')
                ->first();

            $data_article['page_layout'] = 'interesting_issues';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];

            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];

            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'interesting_issues')
                    ->update($data_article);
            }

            //dd($data['UploadFileID'],$check_data);

        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'interesting_issues')
                ->delete();
        }

        if ($data['articles_research'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'articles_research')
                ->first();

            $data_article['page_layout'] = 'articles_research';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];

            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'articles_research')
                    ->update($data_article);
            }

            //dd($data['UploadFileID'],$check_data);
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'articles_research')
                ->delete();
        }

        if ($data['include_statistics'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'include_statistics')
                ->first();

            $data_article['page_layout'] = 'include_statistics';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+50 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'include_statistics')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'include_statistics')
                ->delete();
        }


        if ($data['health_literacy'] == 2) {

            $check_data = Article::select('id')
                ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health-literacy')
                ->first();

            $data_article['page_layout'] = 'health-literacy';
            $data_article['title'] = $data['title'];
            $data_article['description'] = $data['description'];
            $data_article['short_description'] = strip_tags($data['description']);
            $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
            $data_article['dol_UploadFileID'] = $data['UploadFileID'];
            $data_article['dol_url'] = $data['FileAddress'];
            $data_article['dol_template'] = $data['Template'];
            $data_article['dol_json_data'] = $item->json_data;
            $data_article['category_id'] = 0;

            $json_decode  = json_decode($item->json_data);


            $array_category = [];
            foreach ($json_decode->Issues as $value_issues) {
                //dd($value_issues);
                if ($value_issues->ID == 5) {
                    #แอลกอฮอล์
                    //$data_article['category_id'] = 5;
                    array_push($array_category, 5);
                }

                if ($value_issues->ID == 28) {
                    #บุหรี่
                    //$data_article['category_id'] = 6;
                    array_push($array_category, 6);
                }

                if ($value_issues->ID == 39) {
                    #อาหาร
                    //$data_article['category_id'] = 7;
                    array_push($array_category, 7);
                }

                if ($value_issues->ID == 18) {
                    #กิจกรรมทางกาย
                    //$data_article['category_id'] = 8;
                    array_push($array_category, 8);
                }

                if ($value_issues->ID == 41) {
                    #อุบัติเหตุ
                    //$data_article['category_id'] = 9;
                    array_push($array_category, 9);
                }

                if ($value_issues->ID == 37) {
                    #เพศ เช่น ท้องไม่พร้อม
                    //$data_article['category_id'] = 10;
                    array_push($array_category, 10);
                }

                if ($value_issues->ID == 34) {
                    #สุขภาพจิต
                    //$data_article['category_id'] = 11;
                    array_push($array_category, 11);
                }
                if ($value_issues->ID == 35) {
                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                    //$data_article['category_id'] = 12;
                    array_push($array_category, 12);
                }

                if ($value_issues->ID == 36) {
                    #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                    //$data_article['category_id'] = [12];
                    array_push($array_category, 12);
                }

                if ($value_issues->ID == 27) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 33) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 49) {
                    #สิ่งแวดล้อม
                    //$data_article['category_id'] = 13;
                    array_push($array_category, 13);
                }

                if ($value_issues->ID == 16) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 21) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 32) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }

                if ($value_issues->ID == 42) {
                    #อื่นๆ
                    //$data_article['category_id'] = 14;
                    array_push($array_category, 14);
                }
            }
            $data_article['category_id'] = json_encode($array_category);
            //$data_article['category_id'] 
            //dd($data_article,"Case 1");
            $date_year = date('Y-m-d');
            $date_year = strtotime($date_year);
            $date_year = strtotime("+10 year", $date_year);
            $data_article['start_date'] = date("Y-m-d H:i:s");
            $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
            $data_article['updated_by'] = $data['updated_by'];
            //dd($data_article);


            if (!isset($check_data->id)) {
                Article::create($data_article);
            } else {
                Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                    ->where('page_layout', '=', 'health-literacy')
                    ->update($data_article);
            }
        } else {
            Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                ->where('page_layout', '=', 'health-literacy')
                ->delete();
        }


        if ($item->json_data != '' && $data['status'] == 'publish') {
            $json_decode  = json_decode($item->json_data);
            //dd($json_decode);

            if (gettype($json_decode) == 'object') {
                $check_data = 0;
                $data_array_check = array("healthliteracy");

                foreach ($json_decode->Keywords as $value) {
                    //echo $value;
                    //echo "<br>";
                    if (array_keys($data_array_check, strtolower($value))) {
                        $check_data = 1;
                        //echo $value;-
                        //echo "<br>";
                        //exit();
                        //dd("True");
                    }
                }

                if ($check_data == 1) {

                    $check_article = Article::select('id')
                        ->where('dol_UploadFileID', '=', $data['UploadFileID'])
                        ->where('page_layout', '=', 'health-literacy')
                        ->first();

                    $data_article['page_layout'] = 'health-literacy';
                    $data_article['title'] = $data['title'];
                    $data_article['description'] = $data['description'];
                    $data_article['short_description'] = strip_tags($data['description']);
                    $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
                    $data_article['dol_UploadFileID'] = $data['UploadFileID'];
                    $data_article['dol_url'] = $data['FileAddress'];
                    $data_article['dol_template'] = $data['Template'];
                    $data_article['dol_json_data'] = $item->json_data;
                    $data_article['category_id'] = 0;

                    $array_category = [];
                    foreach ($json_decode->Issues as $value_issues) {
                        //dd($value_issues);
                        if ($value_issues->ID == 5) {
                            #แอลกอฮอล์
                            //$data_article['category_id'] = 5;
                            array_push($array_category, 5);
                        }

                        if ($value_issues->ID == 28) {
                            #บุหรี่
                            //$data_article['category_id'] = 6;
                            array_push($array_category, 6);
                        }

                        if ($value_issues->ID == 39) {
                            #อาหาร
                            //$data_article['category_id'] = 7;
                            array_push($array_category, 7);
                        }

                        if ($value_issues->ID == 18) {
                            #กิจกรรมทางกาย
                            //$data_article['category_id'] = 8;
                            array_push($array_category, 8);
                        }

                        if ($value_issues->ID == 41) {
                            #อุบัติเหตุ
                            //$data_article['category_id'] = 9;
                            array_push($array_category, 9);
                        }

                        if ($value_issues->ID == 37) {
                            #เพศ เช่น ท้องไม่พร้อม
                            //$data_article['category_id'] = 10;
                            array_push($array_category, 10);
                        }

                        if ($value_issues->ID == 34) {
                            #สุขภาพจิต
                            //$data_article['category_id'] = 11;
                            array_push($array_category, 11);
                        }
                        if ($value_issues->ID == 35) {
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            //$data_article['category_id'] = 12;
                            array_push($array_category, 12);
                        }

                        if ($value_issues->ID == 36) {
                            #ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)
                            //$data_article['category_id'] = [12];
                            array_push($array_category, 12);
                        }

                        if ($value_issues->ID == 27) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 33) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 49) {
                            #สิ่งแวดล้อม
                            //$data_article['category_id'] = 13;
                            array_push($array_category, 13);
                        }

                        if ($value_issues->ID == 16) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 21) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 32) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }

                        if ($value_issues->ID == 42) {
                            #อื่นๆ
                            //$data_article['category_id'] = 14;
                            array_push($array_category, 14);
                        }
                    }
                    $data_article['category_id'] = json_encode($array_category);
                    //$data_article['category_id'] 
                    //dd($data_article,"Case 2");

                    $date_year = date('Y-m-d');
                    $date_year = strtotime($date_year);
                    $date_year = strtotime("+10 year", $date_year);
                    $data_article['start_date'] = date("Y-m-d H:i:s");
                    $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                    $data_article['updated_by'] = $data['updated_by'];
                    //dd($data_article);

                    if (!isset($check_article->id)) {
                        Article::create($data_article);
                    } else {
                        Article::where('dol_UploadFileID', '=', $data['UploadFileID'])
                            ->where('page_layout', '=', 'health-literacy')
                            ->update($data_article);
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




        self::postLogs(['event' => 'แก้ไขข้อมูลหัวข้อ "' . $data_update['title'] . '"', 'module_id' => '13']);
        return redirect()->route('admin.api.list-media.index')
            ->with('status', 'success')
            ->with('message', 'Successfully');
    }


    public function getImport()
    {
        dd("Import Success");
        $list_media = ListMedia::select('json_data')
            ->where('json_data', '!=', '')
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
            if (gettype($json_decode) == 'object') {
                $check_data = 0;
                $data_array_check = array("healthliteracy");

                foreach ($json_decode->Keywords as $value_search) {
                    //echo strtolower($value_search);
                    //exit();
                    //echo "<br>";
                    if (array_keys($data_array_check, strtolower($value_search))) {
                        $check_data = 1;
                        //echo $value;-
                        //echo "<br>";
                        //exit();
                        $i++;
                    }
                    //dd("Success");
                }

                if ($check_data == 1) {
                    //dd($json_decode);
                    $check_article = Article::select('id')
                        ->where('dol_UploadFileID', '=', $json_decode->UploadFileID)
                        ->where('page_layout', '=', 'health-literacy')
                        ->first();

                    if (!isset($check_article->id)) {

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
                        $data_article['end_date'] = date('Y-m-d H:i:s', $date_year);
                        //dd($data_article);
                        //Article::create($data_article);

                    }
                }
            }
            //dd("Case Success");
        }

        dd("Test Import", $i);
    }



    public function getExcelReport(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $input = $request->all();
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'listmedia-api&webview' . "-" . $date_now;
        $type = 'xlsx';
        $items = ListMedia::Data3(['request' => $request->all()]);

        //dd($items);

        return Excel::create($file_name, function ($excel) use ($items) {
            $excel->sheet('mySheet', function ($sheet) use ($items) {
                //$sheet->fromArray($items,null, 'A1', true);
                $sheet->row(1, array(
                    'id',
                    'title',
                    'template',
                    'webview_status',
                    'api_status',
                    'status',
                    'not_publish_reason',
                    'format',
                    'file_address',
                    'json_data',
                    'sex',
                    'age',
                    'keyword',
                    'issue',
                    'target',
                    'setting',
                    'department',
                    'show_rc',
                    'show_dol',
                    'show_learning',
                    'articles_research',
                    'research_not_publish_reason',
                    'include_statistics',
                    'stat_not_publish_reason',
                    'knowledges',
                    'knowledge_not_publish_reason',
                    'media_campaign',
                    'campaign_not_publish_reason',
                    'interesting_issues',
                    'interesting_issues_not_publish_reason',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'url'
                ));


                $index = 2;
                foreach ($items as $key => $value) {
                    // dd($value->knowledges);
                    //dd($key,$value);
                    $json_data = json_decode($value->json_data);

                    $Issues_array = [];
                    if (isset($json_data->Issues)) {
                        foreach ($json_data->Issues as $value_issues) {
                            //dd($value_issues);
                            if (isset($value_issues->Name)) {
                                array_push($Issues_array, $value_issues->Name);
                            }
                        }
                    }

                    $Targets_array = [];
                    if (isset($json_data->Targets)) {
                        foreach ($json_data->Targets as $value_targets) {
                            //dd($value_issues);
                            if (isset($value_targets->Name)) {
                                array_push($Targets_array, $value_targets->Name);
                            }
                        }
                    }
                    $Settings_array = [];
                    if (isset($json_data->Settings)) {
                        foreach ($json_data->Settings as $value_settings) {
                            //dd($value_issues);
                            if (isset($value_settings->Name)) {
                                array_push($Settings_array, $value_settings->Name);
                            }
                        }
                    }

                    $sex_text = [];
                    //dd(gettype($value->sex));
                    if ($value->sex != '' && gettype(json_decode($value->sex)) == 'array') {
                        //dd(gettype(json_decode($value->sex)));
                        foreach (json_decode($value->sex) as $value_sex) {
                            //dd($value_issues);
                            $text = '';
                            if ($value_sex == 1) {
                                $text = 'ชาย';
                            }
                            if ($value_sex == 2) {
                                $text = 'หญิง';
                            }
                            if ($value_sex == 3) {
                                $text = 'หลากหลายทางเพศ';
                            }
                            array_push($sex_text, $text);
                        }
                    }
                    $age_text = [];
                    if ($value->age != '' && gettype(json_decode($value->age)) == 'array') {
                        foreach (json_decode($value->age) as $value_age) {
                            //dd($value_issues);
                            $text = '';
                            if ($value_age == 4) {
                                $text = 'เยาวชน(15–24ปี)';
                            }
                            if ($value_age == 13) {
                                $text = 'ปฐมวัย(0–5ปี)';
                            }
                            if ($value_age == 19) {
                                $text = 'ผู้สูงอายุ(60ปีขึ้นไป)';
                            }
                            if ($value_age == 24) {
                                $text = 'วัยเรียน(6–12ปี)';
                            }
                            if ($value_age == 25) {
                                $text = 'วัยทำงาน(15-59ปี)';
                            }
                            if ($value_age == 26) {
                                $text = 'วัยรุ่น(13–15ปี)';
                            }
                            array_push($age_text, $text);
                        }
                    }

                    $sheet->row($index++, array(
                        $value->id,
                        $value->title,
                        $value->template,
                        ($value->web_view == 1 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->api == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->status == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->status == 'publish' ? '-' : $value->not_publish_reason),
                        (isset($json_data->Format) ? $json_data->Format : ''),
                        (isset($json_data->FileAddress) ? $json_data->FileAddress : ''),
                        $value->json_data,
                        implode(",", $sex_text),
                        implode(",", $age_text),
                        (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''),
                        implode(",", $Issues_array),
                        implode(",", $Targets_array),
                        implode(",", $Settings_array),
                        (isset($json_data->DepartmentName) ? $json_data->DepartmentName : ''),
                        $value->show_rc,
                        $value->show_dol,
                        $value->show_learning,
                        ($value->articles_research == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->articles_research == 2 ? '-' : $value->research_not_publish_reason),
                        ($value->include_statistics == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->include_statistics == 2 ? '-' : $value->stat_not_publish_reason),
                        ($value->knowledges == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->knowledges == 2 ? '-' : $value->knowledge_not_publish_reason),
                        ($value->media_campaign == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->media_campaign == 2 ? '-' : $value->campaign_not_publish_reason),
                        ($value->interesting_issues == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                        ($value->interesting_issues == 2 ? '-' : $value->interesting_issues_not_publish_reason),
                        $value->created_at,
                        $value->updated_at,
                        $value->created_by,
                        $value->updated_by,
                        route('media2-detail', base64_encode($value->id))
                    ));
                }
            });
        })->download($type);
        //dd("Get Excel Report");
    }

    public function movetotrash(Request $request)
    {
        $update = ListMedia::Where('id', '=', $request->id)->first();

        $update->media_trash = 'Y';
        $update->show_rc = '1';
        $update->show_dol = '1';
        $update->show_learning = '1';
        $update->status = 'draft';
        $update->save();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://persona.thaihealth.or.th/api/thrc/update_media_public',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "media_id":' . $update->id . ',
            "media_status": "draft",
            "media_dol": "N",
            "media_learning": "N",
            "media_thrc": "N"
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);


        return response()->json(['status' => true]);
    }

    public function recycletrash(Request $request)
    {

        $update = ListMedia::Where('id', '=', $request->id)->first();
        $update->media_trash = null;
        $update->save();
        return response()->json(['status' => true]);
    }
    public function deletemediafile(Request $request)
    {

        $update = ListMedia::Where('id', '=', $request->id)->first();
        $filePath_1 = 'mediadol/' . $update->UploadFileID . '/' . $update->local_path;
        $filePath_2 = 'mediadol/' . $update->UploadFileID . '/' . $update->thumbnail_address;
        $forder =  'mediadol/' . $update->UploadFileID;

        if (is_dir($forder)) {
            if ($update->local_path != null) {
                if (file_exists($filePath_1)) {
                    unlink($filePath_1);
                }
            }

            if ($update->local_path != null) {
                if (file_exists($filePath_2)) {
                    unlink($filePath_2);
                }
            }
            rmdir($forder);
        }

        $IcbDolLog = IcbDolLog::Where('UploadFileID', '=', $update->UploadFileID)->delete();
        $update->local_path = null;
        $update->thumbnail_address = null;
        $update->save();
        return response()->json(['status' => true]);
    }
    public function delecttrash(Request $request)
    {
        $update = ListMedia::Where('id', '=', $request->id)->first();
        $update->media_trash = 'P';
        $update->save();
        return response()->json(['status' => true]);
    }


    public function job_user_connect(){

      // $curl = curl_init();
      // curl_setopt_array($curl, array(
      //   CURLOPT_URL => 'https://api.connect-x.tech/connectx/api/auth/login',
      //   CURLOPT_RETURNTRANSFER => true,
      //   CURLOPT_ENCODING => '',
      //   CURLOPT_MAXREDIRS => 10,
      //   CURLOPT_TIMEOUT => 0,
      //   CURLOPT_FOLLOWLOCATION => true,
      //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //   CURLOPT_CUSTOMREQUEST => 'POST',
      //   CURLOPT_POSTFIELDS =>'{
      //   "email": "thaihealth-api@connect-x.tech",
      //   "password": "ab2cebadecfd0bb638288a88e60bba27d66199b9"
      // }
      // ',
      //   CURLOPT_HTTPHEADER => array(
      //     'Content-Type: application/json'
      //   ),
      // ));
      // $response = curl_exec($curl);
      // $data_token=json_decode($response);
      // curl_close($curl);
        $user_data=User::whereNotNull('phone')->offset(90)->limit(10)->get();
        $total_data = count($user_data);
        $loop = 1;
        $error = [];
         foreach ($user_data as $row){
            try {
                $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.connect-x.tech/connectx/api/customer?externalId=cx_mobilePhone',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'PATCH',
              CURLOPT_POSTFIELDS =>'{
               "cx_mobilePhone":"'.$row->phone.'",
               "cx_Name": "'.$row->name.'",
               "cx_birthDate": "'.$row->date_of_birth.'",
               "cx_email" : "'.$row->facebook_id.'",
               "organizeId": "sT7oVQc4gutP3hghRo4P"
            }
            ',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6InRoYWloZWFsdGgtYXBpQGNvbm5lY3QteC50ZWNoIiwidXNlcklkIjoiRERqaXVkbzRGR0NNUzJDalB0cGYiLCJzdWIiOiJERGppdWRvNEZHQ01TMkNqUHRwZiIsIm9yZ2FuaXplSWQiOiJzVDdvVlFjNGd1dFAzaGdoUm80UCIsInN0YXlMb2dpbiI6ZmFsc2UsImN1c3RvbVRva2VuIjoiVTJGc2RHVmtYMS82bEIvem9HZHNPeVlwRXBxZ1J0K1FoMlRReXZ5dmNic1lDekVyd2UxTWovQzJOcXdPb3V6RXZwbCtiS0xzTW1ZdDh0dk53SVZOQUxVSlZRd0s3YzU1Y2pzQXdtaExBZmQzZWFLYnJEYW9DQi9abDZlMlNGRU5CYVZkc0lhNHJVTDVKMjhLMzhvSHdJY1U1dldCK3lCYnV6RmVqU09Yb1VWVnViZlU4anZaMUdrQzdBVTZYenNrVjBscExZU2JaSmYyMFAwQ29YeU5STVVzOW5PUWlGNzR6cTZjeVBNd0hYU2psOGY4M2dVQUYrdXpJZnQxZUFXOGFWNjFpZWZzWk9Sd0pocjcvdGIwMU1LWUZqdkJUUlRiQ0hDZTd3N3cxWVdQM25Vd293V2NBMEtQckhOL3JmRWpUbE04OWxqQUowV3Nwd3RzV2lGY29CODJ4ZUdHVmNod2VpMy9PMXhlb0tRUG5GSGJLcnRpTWkwM2lEZHdrSG9GcTNqNGxiQmJ5R2ZaTFY5aUdSZmV5aE5yYi9vemdvSzNleTFZa2xWMnUzV2NMZUdSN3RZbW83U0orNGpKbWlDZ09HU3RkUzV3ZGtFd0VyU3MxYnNCMHZqUVlJeFEvRzYzNkRGSi9Nd3VKWEh1MER6Rk1aUWdxZmRDNlpDbGdsVzJGYmNYTU9LTGxYTTJ4TXlOdlhzRm16T2k5Y0VBQ1pkMzlxYW4zSVhSNFhkLytJUWlVSW1CSXZ6RnprRThlQVFUM3FEZE1tWkVld3JkSHc3dWFSMEJMM0VQbjBxcVRXU1RpZEsvVVJwNUQrUkExNU04cGRteWZVKzRTM2tPYlRyaDBYblpzK0tZWHByeDhPdlJ2dElCT1d5a0R1eVVVdDkvOHJlbWZ2ZjNjaFB5azNVeVdNaEpIeWU5MFZ0WS9XT1lyYmpPN3YrZFd4Rno4TnI3ZUFNYk1KUS9SamhPL2xiTmZ1ZVZhVmVBYVcvUi9jcmhtRGZ6SSs0eCtIM0liS1RUby8vTkVYQnF5Nmc4bGNRMmJEQjRrUitCU1I0ZitobVBKV1NmT3pqVm12MnVDRTVhdWFqRmxRZUlqamdpZWpVOEJ1clU4ek5WOVhnb05OTWlpR2RxZmJjOHBwaS9HenNGZXRPbVdIZGQvdHNIUGRvc0NlZHVUdWd4c2JpcUl0djVyMGZCRXh2YWdHTlg0WTVOSVJzNDNrQS9XMG52S3ZDSW1DOW9OWFA3aFFBeUMwU0RDR2lQb1h3R21QS1JxZkFSU3FNN0pzTHJ5ZHZsdm9ocTgwQ3AwTjhKbGtIQi82NE45cElsUTU5ZzA1cjBsakhndTN1aCtMdm9oUmlFeExkSS9ZWlJlYUVISUdRT1FFeGIxaG96bW52ZWsvTzNGYzBYVjBzcUdtOHVJdFVlSk9peW1qdHNnTEFQeEZjdHpmNlpVNWJVc0VLOFNRQlBhaWUvazVJVFlnTXdWekZRYW5QVjlGSUZleGhQTGQ2Q1RDdVg1NHJKZDZYaFpCSUdUYTlYZFNlWm5vOWhTNXFQTHhKdW8yUXpoSE8rUkVvaGhqR3JlTERjTWlyWUJndz0iLCJpbWFnZSI6Im51bGwiLCJkaXNwbGF5TmFtZSI6IlRIQUlIRUFMVEggQVBJIiwiaWF0IjoxNjc2NDI2OTQ2LCJleHAiOjE2NzY1MTMzNDZ9.aqNI6sn2Wkp1S3g7zrWCiBwq3ZxoInIBrp5N1jKERMs',
                'Content-Type: application/json'
              ),
            ));
            $loop++;
            $response = curl_exec($curl);
            $arr[]=$response;
            curl_close($curl);
            } catch (\Throwable $th) {
                $error[] = $row->phone;
           
            }
         }
         $count_data=count($arr);
         return  "Total Send User Data".$count_data."of".$total_data."Error Total offset 90 = ".count($error);



    }
}
