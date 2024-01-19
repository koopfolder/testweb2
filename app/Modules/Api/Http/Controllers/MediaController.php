<?php

namespace App\Modules\Api\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Api\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Api\Models\{ListMedia, ListArea, ListCategory, ListIssue, ListProvince, ListSetting, ListTarget, ListMediaIssues, ListMediaKeywords, ListMediaTargets, ViewMedia, Tags, DataTags, Sex, Age, IcbDolLog};
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
use File;
use App\Jobs\GenerateExcelReportJob;
use App\Jobs\DownloadFileFromDol;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

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
        foreach ($target2 as $key_target => $value_target) {
            if ($value_target['TargetGuoupID'] == 3) {
                $target2[$key_target]['title'] = $target2[$key_target]['title'] . "(กลุ่มเพศ)";
            } else if ($value_target['TargetGuoupID'] == 2) {
                $target2[$key_target]['title'] = $target2[$key_target]['title'] . "(กลุ่มอายุ)";
            } else if ($value_target['TargetGuoupID'] == 1) {
                $target2[$key_target]['title'] = $target2[$key_target]['title'] . "(กลุ่มบุคคล/อาชีพ)";
            }
        }

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
                        // $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
                        // //dd($body);
                        // $client = new \GuzzleHttp\Client();
                        // $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
                        //     'headers' => [
                        //         'Content-Type' => 'application/json; charset=utf-8'
                        //     ],
                        //     'body' => $body
                        // ]);
                        // $response_api = $request->getBody()->getContents();
                        // $data_json = json_decode($response_api);

                        //dd($data_json);

                        // if ($data_json->status_code === 200) {
                        //
                        //     $access_token = $data_json->data->access_token;
                        //     //dd($access_token);
                        //     $body = '{"device_token":"thrc_backend","media_type":"' . $input['media_type'] . '","status_media":"' . $status . '","media":' . json_encode($data_media) . '}';
                        //     //dd($body);
                        //     $client = new \GuzzleHttp\Client();
                        //     $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_UPDATE_MEDIA'), [
                        //         'headers' => [
                        //             'Content-Type' => 'application/json; charset=utf-8',
                        //             'authorization' => $access_token
                        //         ],
                        //         'body' => $body
                        //     ]);
                        //     $response_api = $request->getBody()->getContents();
                        //     $data_json = json_decode($response_api);
                        //     //dd($data_json);
                        // }
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
                        // //$data_media->json_data = json_encode($media_json_data);
                        // $data_media->json_data = $media_json_data;

                        // /*Login*/
                        // $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
                        // //dd($body);
                        // $client = new \GuzzleHttp\Client();
                        // $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
                        //     'headers' => [
                        //         'Content-Type' => 'application/json; charset=utf-8'
                        //     ],
                        //     'body' => $body
                        // ]);
                        // $response_api = $request->getBody()->getContents();
                        // $data_json = json_decode($response_api);


                        // if ($data_json->status_code === 200) {

                        //     $access_token = $data_json->data->access_token;
                        //     //dd($access_token);
                        //     $body = '{"device_token":"thrc_backend","media_type":"' . $input['media_type'] . '","status_media":"' . $status . '","media":' . json_encode($data_media) . '}';
                        //     //dd($body);
                        //     $client = new \GuzzleHttp\Client();
                        //     $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_UPDATE_MEDIA'), [
                        //         'headers' => [
                        //             'Content-Type' => 'application/json; charset=utf-8',
                        //             'authorization' => $access_token
                        //         ],
                        //         'body' => $body
                        //     ]);
                        //     $response_api = $request->getBody()->getContents();
                        //     $data_json = json_decode($response_api);
                        //     //dd($data_json);
                        // }
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
                $list_media->whereIn('show_dol', [2]);
            })
            ->where(function ($list_media) {
                $list_media->where('start_date', '<=', date("Y-m-d"))
                    ->orWhereNull('start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('end_date', '>=', date("Y-m-d"))
                    ->orWhereNull('end_date');
            })
            ->orderby('id', "DESC")
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

                    array_push($arr_tar, $val1->ID);
                }
            }

            if (is_array($res->Issues)) {
                foreach ($res->Issues as $key1 => $val_issue) {

                    array_push($arr_issue, $val_issue->ID);
                }
            }

            if (is_array($res->Settings)) {
                foreach ($res->Settings as $key1 => $val_setting) {

                    array_push($arr_setting, $val_setting->ID);
                }
            }

            if (!empty($list_media[$key]['json_data']->Keywords)) {
                $Keywords = implode(',', $list_media[$key]['json_data']->Keywords);
            }
            if (!empty($res->cover_desktop)) {
                $link_tumnail = $res->cover_desktop;
            } else
            if (!empty($item['image_path'])) {
                $link_tumnail = ENV('APP_URL') . $item['image_path'];
            } else
            if (!empty($item['thumbnail_address'])) {
                $link_tumnail = ENV('APP_URL') . "/mediadol/" . $item['UploadFileID'] . '/' . $item['thumbnail_address'];
            } else {

                $link_tumnail = $res->ThumbnailAddress;
            }
            // $media_thumbnail_address = (is_null($item['thumbnail_address'])) ?  $res->ThumbnailAddress : "https://resourcecenter.thaihealth.or.th/mediadol/" . $item['UploadFileID'] . "/" . $item['thumbnail_address'];

            // if ($list_media->getMedia('cover_desktop')->isNotEmpty()) {
            //     $link_tumnail = url($list_media->getMedia('cover_desktop')->first()->getUrl());
            // }else

            // if(!empty($list_media['image_path'])){
            //     $link_tumnail = ENV('APP_URL')."/".$list_media['image_path'];
            // }

            // if(!empty($list_media['thumbnail_address'])){
            //     $link_tumnail = ENV('APP_URL')."/mediadol/".$list_media['UploadFileID'].'/'.$list_media['thumbnail_address'];
            // } else {

            //     $link_tumnail = $json_decode_update->ThumbnailAddress;
            // }

            // $media_file_path = (is_null($item['local_path'])) ?  $res->ThumbnailAddress : "https://resourcecenter.thaihealth.or.th/mediadol/" . $item['UploadFileID'] . "/" . $item['local_path'];
            if (is_null($item['local_path'])) {
                $media_file_path = $res->FileAddress;
            } else {
                $media_file_path = "https://resourcecenter.thaihealth.or.th/mediadol/" . $item['UploadFileID'] . "/" . $item['local_path'];
            }
            $sub_array['media_id'] = $item['id'];
            $sub_array['media_title'] = $item['title'];
            $sub_array['media_dol'] = ($item['show_dol'] == 1 ? "N" : "Y");
            $sub_array['media_learning'] = ($item['show_learning'] == 1 ? "N" : "Y");
            $sub_array['media_short_description'] = $item['description'];
            $sub_array['media_thumbnail_address'] =  $link_tumnail;
            $sub_array['media_thumbnail_address_change'] = "";
            $sub_array['media_type'] = $item['template'];
            $sub_array['media_file_path'] = $media_file_path;
            $sub_array['Issues'] = json_encode($arr_issue);
            $sub_array['Targets'] = json_encode($arr_tar);
            $sub_array['Settings'] = json_encode($arr_setting);
            $sub_array['Sex'] = $item['sex'];
            $sub_array['Ages'] = $item['age'];
            $sub_array['Keywords'] = $Keywords;
            $list_media_api[$key] = $sub_array;
        }
        dd($list_media_api);
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


    public function GetMultimediaMp4Thumbnail()
    {
        $UploadFileID = [
            "009dfa1e-e796-ed11-80fb-00155d1aab67", "69513fa0-6786-ed11-80fb-00155d1aab67", "424ad5ed-b185-ed11-80fb-00155d1aab67", "f3f1d1de-58a0-ed11-80fc-00155d1aab68", "d991087e-7b86-ed11-80fb-00155d1aab67", "36a746d2-525e-ed11-80fa-00155db45626", "1b9f5232-5ca0-ed11-80fc-00155d1aab68", "e5bfa048-5ca0-ed11-80fc-00155d1aab68", "c7393d5b-5ca0-ed11-80fc-00155d1aab68", "042c2343-2e48-ed11-80fa-00155db45626", "33570663-5ca0-ed11-80fc-00155d1aab68", "21961a86-5ca0-ed11-80fc-00155d1aab68", "6027caae-2950-ed11-80fa-00155db45626", "45f86ce6-0e5f-ed11-80fa-00155db45626", "04d352ef-0e5f-ed11-80fa-00155db45626", "15fc5e4f-df9a-ed11-80fb-00155d1aab67", "58c72c68-9c6f-ed11-80fa-00155db45626", "5e34a9cb-525e-ed11-80fa-00155db45626", "3a0f4006-e796-ed11-80fb-00155d1aab67", "49931c7e-0f87-ed11-80fb-00155d1aab67", "739e9b18-f48f-ed11-80fb-00155d1aab67", "15ad8c98-ec7c-ed11-80fb-00155d1aab67", "363ab9d8-457c-ed11-80fb-00155d1aab67", "0b3a38ba-2c7c-ed11-80fb-00155d1aab67", "a1450505-de80-ed11-80fb-00155d1aab67", "5795755b-477c-ed11-80fb-00155d1aab67", "24e24753-ed7c-ed11-80fb-00155d1aab67", "f09b4227-e796-ed11-80fb-00155d1aab67", "75239be8-5187-ed11-80fb-00155d1aab67", "2a227f5e-3e84-ed11-80fb-00155d1aab67", "dc203389-467c-ed11-80fb-00155d1aab67", "ebe309e1-b090-ed11-80fb-00155d1aab67", "bb7891f0-4c4d-ed11-80fa-00155db45626", "fe1cb543-9885-ed11-80fb-00155d1aab67", "51ff8b52-013f-ed11-80fa-00155db45626", "ea4989a4-8c86-ed11-80fb-00155d1aab67", "69875033-57a0-ed11-80fc-00155d1aab68", "71af0283-58a0-ed11-80fc-00155d1aab68", "806f5deb-f09d-ed11-80fb-00155d1aab67", "3162a281-52a0-ed11-80fc-00155d1aab68", "f340c84a-55a0-ed11-80fc-00155d1aab68", "21b649b8-51a0-ed11-80fc-00155d1aab68", "a8304ec0-e696-ed11-80fb-00155d1aab67", "50ff8b52-013f-ed11-80fa-00155db45626", "b90e45c9-57a0-ed11-80fc-00155d1aab68", "f89b41fc-0e5f-ed11-80fa-00155db45626", "08b24f14-2192-ed11-80fb-00155d1aab67", "083d9083-e89d-ed11-80fb-00155d1aab67", "c4ad52dd-5487-ed11-80fb-00155d1aab67", "132a4cb3-5087-ed11-80fb-00155d1aab67", "652819ac-9285-ed11-80fb-00155d1aab67", "16a014ca-9390-ed11-80fb-00155d1aab67", "33e0ff2b-9490-ed11-80fb-00155d1aab67", "22724387-af90-ed11-80fb-00155d1aab67", "bf7e24b6-208b-ed11-80fb-00155d1aab67", "19d344ec-f19d-ed11-80fb-00155d1aab67", "678bf77b-1480-ed11-80fb-00155d1aab67", "c5511d2d-7295-ed11-80fb-00155d1aab67", "82b91aa0-1c4a-ed11-80fa-00155db45626", "264e72fe-88a7-ed11-80fc-00155d1aab68", "e8c00d6a-1c4a-ed11-80fa-00155db45626", "f353dfbd-fb9f-ed11-80fc-00155d1aab68", "1e1f3a22-8b8d-ed11-80fb-00155d1aab67", "7402c530-ff9f-ed11-80fc-00155d1aab68", "698e5761-b185-ed11-80fb-00155d1aab67", "7c3985cd-b285-ed11-80fb-00155d1aab67", "6fcb99ac-5744-ed11-80fa-00155db45626", "f7331ae2-bf58-ed11-80fa-00155db45626", "085c0845-217d-ed11-80fb-00155d1aab67", "d509fa4f-5487-ed11-80fb-00155d1aab67", "c2bb1099-6ba0-ed11-80fc-00155d1aab68", "ab836ae3-1e72-ed11-80fb-00155d1aab67", "9c262a7d-5871-ed11-80fa-00155db45626", "ada97f54-5871-ed11-80fa-00155db45626", "726904c3-4871-ed11-80fa-00155db45626", "07b2ee6e-74a3-ed11-80fc-00155d1aab68", "52988d54-f99d-ed11-80fb-00155d1aab67", "082e1e67-8086-ed11-80fb-00155d1aab67", "6fb1b6dc-5744-ed11-80fa-00155db45626", "564785dc-525e-ed11-80fa-00155db45626", "f31243be-2d7c-ed11-80fb-00155d1aab67", "8d4845a4-cd8f-ed11-80fb-00155d1aab67", "c0e294a0-7686-ed11-80fb-00155d1aab67", "b3a1e1d6-547b-ed11-80fb-00155d1aab67", "0064b1b7-5744-ed11-80fa-00155db45626", "70b8224b-b490-ed11-80fb-00155d1aab67", "7e561171-3880-ed11-80fb-00155d1aab67", "55165940-a556-ed11-80fa-00155db45626", "075e26c4-58a0-ed11-80fc-00155d1aab68", "f2c12646-368b-ed11-80fb-00155d1aab67", "4380e967-ed8f-ed11-80fb-00155d1aab67", "6805f9d0-73a0-ed11-80fc-00155d1aab68", "d208ddda-049e-ed11-80fb-00155d1aab67", "d572bd89-2345-ed11-80fa-00155db45626", "b081b87f-2345-ed11-80fa-00155db45626", "5d2d8a9d-936f-ed11-80fa-00155db45626", "01297f15-198b-ed11-80fb-00155d1aab67", "342b08f0-ea9d-ed11-80fb-00155d1aab67", "a3a1d953-9590-ed11-80fb-00155d1aab67", "0e066f69-ed9d-ed11-80fb-00155d1aab67", "564658d2-eb9d-ed11-80fb-00155d1aab67", "2d457aed-ec9d-ed11-80fb-00155d1aab67", "1f508ff5-fc9f-ed11-80fc-00155d1aab68", "f33549fa-de80-ed11-80fb-00155d1aab67", "23cc4074-9977-ed11-80fb-00155d1aab67", "1e1df8e6-8255-ed11-80fa-00155db45626", "36ddd925-7199-ed11-80fb-00155d1aab67", "0f03ba26-9d90-ed11-80fb-00155d1aab67", "bfeeffd5-54a0-ed11-80fc-00155d1aab68", "16542070-fc9f-ed11-80fc-00155d1aab68", "ee01d3dd-52a0-ed11-80fc-00155d1aab68", "f73cf1fc-70a0-ed11-80fc-00155d1aab68", "37e637b0-3c4f-ed11-80fa-00155db45626", "af950d93-936f-ed11-80fa-00155db45626", "651aee39-fe9f-ed11-80fc-00155d1aab68", "6e6215ba-55a0-ed11-80fc-00155d1aab68", "04ec6124-52a0-ed11-80fc-00155d1aab68", "953a1c4b-5187-ed11-80fb-00155d1aab67", "df73a198-7886-ed11-80fb-00155d1aab67", "08b6bfc3-7d86-ed11-80fb-00155d1aab67", "271ef176-328b-ed11-80fb-00155d1aab67", "47357f7a-787f-ed11-80fb-00155d1aab67", "fcbde938-478b-ed11-80fb-00155d1aab67", "26988268-bb85-ed11-80fb-00155d1aab67", "5af1ded7-dc80-ed11-80fb-00155d1aab67", "3343597f-3b80-ed11-80fb-00155d1aab67", "f47623bc-8d98-ed11-80fb-00155d1aab67", "ffe83e48-5587-ed11-80fb-00155d1aab67", "e2d82a01-9877-ed11-80fb-00155d1aab67", "b660b053-3980-ed11-80fb-00155d1aab67", "72980e12-3796-ed11-80fb-00155d1aab67", "3c97c211-448b-ed11-80fb-00155d1aab67", "40c60682-6da0-ed11-80fc-00155d1aab68", "c6ccbff6-ae90-ed11-80fb-00155d1aab67", "51dcd957-b885-ed11-80fb-00155d1aab67", "37aa6ee9-ef9d-ed11-80fb-00155d1aab67", "52ff8b52-013f-ed11-80fa-00155db45626", "2b7e6040-db80-ed11-80fb-00155d1aab67", "705a3465-7786-ed11-80fb-00155d1aab67", "bd82cc83-9464-ed11-80fa-00155db45626", "5fcb6858-9785-ed11-80fb-00155d1aab67", "27603771-b290-ed11-80fb-00155d1aab67", "b9164492-049e-ed11-80fb-00155d1aab67", "ae210451-f39d-ed11-80fb-00155d1aab67", "5c44ba8f-72a0-ed11-80fc-00155d1aab68", "2b0f89eb-71a0-ed11-80fc-00155d1aab68", "d3c1b1c4-5744-ed11-80fa-00155db45626", "da2e4239-7c86-ed11-80fb-00155d1aab67", "b45548f6-9d90-ed11-80fb-00155d1aab67", "de53ed01-ef7c-ed11-80fb-00155d1aab67", "de7bafd4-5744-ed11-80fa-00155db45626", "2ddc041a-3f86-ed11-80fb-00155d1aab67", "b79fc5df-8a98-ed11-80fb-00155d1aab67", "baaaa7c0-3c4f-ed11-80fa-00155db45626", "dc1c1ad8-eb5f-ed11-80fa-00155db45626", "3dbb0c06-6ba0-ed11-80fc-00155d1aab68", "806f5209-7898-ed11-80fb-00155d1aab67", "4740efd8-f9ac-ed11-80fd-00155d1aab66", "6ce9058d-82a3-ed11-80fc-00155d1aab68", "24d10183-80a3-ed11-80fc-00155d1aab68", "84d059fe-177d-ed11-80fb-00155d1aab67", "6691823f-3771-ed11-80fa-00155db45626", "7b27751e-9c85-ed11-80fb-00155d1aab67", "5aa14d53-027d-ed11-80fb-00155d1aab67", "348b4509-50a0-ed11-80fc-00155d1aab68", "ae30b4b3-5aa0-ed11-80fc-00155d1aab68", "99374b08-5ba0-ed11-80fc-00155d1aab68", "75c11c3b-5ba0-ed11-80fc-00155d1aab68", "5cb46025-5aa0-ed11-80fc-00155d1aab68", "e9cefb61-f09d-ed11-80fb-00155d1aab67", "7cdca35e-f7a8-ed11-80fc-00155d1aab68", "06855098-797f-ed11-80fb-00155d1aab67", "7fc525d1-6f95-ed11-80fb-00155d1aab67", "4e670b4d-059e-ed11-80fb-00155d1aab67", "f4d6148e-059e-ed11-80fb-00155d1aab67", "532dcb09-e89d-ed11-80fb-00155d1aab67", "e45a1d1c-e296-ed11-80fb-00155d1aab67", "2fb1ac83-71a0-ed11-80fc-00155d1aab68", "a812f068-e096-ed11-80fb-00155d1aab67", "04c924b9-7298-ed11-80fb-00155d1aab67", "d2c562c1-7298-ed11-80fb-00155d1aab67"
        ];
        $MediaDataID = [];
        $data =   ListMedia::wherein('UploadFileID', $UploadFileID)->get()->toArray();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $jsonData = json_decode($value['json_data']);
                if (!empty($jsonData)) {
                    if (!empty($jsonData->ThumbnailAddress)) {
                        if (strpos($jsonData->ThumbnailAddress, "mp4.png") !== false) {
                            $MediaDataID[] = $value['id'];
                        }
                    }
                }
            }
        }
        dd(implode(",", $MediaDataID));
    }
    public function get_resourcecenter_test(Request $request)
    {

        $page = (isset($request->page)) ? $request->page : 1;
        $limit = (isset($request->limit)) ? $request->limit : 1000;
        $offset = ($page - 1) * $limit;

        $list_media_count = ListMedia::whereNotNull('title')->where('id', 54702)->count();
        $list_media = ListMedia::whereNotNull('title')->where('id', 54702)->limit($limit)->offset($offset)->get();


        $total_page = ceil($list_media_count / $limit);




        try {

            foreach ($list_media as $key => $item) {
                //     if(isnull($item['tags'])){
                //         $tags = 'Y';JSON_EXTRACT(($item['tags']),'$.Keywords');
                //    }else{
                //         $tags='N';
                //    }

                $tags_decode = json_decode($item['json_data']);
                if ($item['tags'] == null or $item['tags'] == '') {
                    $tags = (!empty($tags_decode->Keywords)) ? $tags_decode->Keywords : null;
                } else {
                    $tags = $item['tags'];
                }

                $Issues = (!empty($tags_decode->Issues)) ? $tags_decode->Issues : null;
                $Settings = (!empty($tags_decode->Settings)) ? $tags_decode->Settings : null;
                $Targets = (!empty($tags_decode->Targets)) ? $tags_decode->Targets : null;


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
                $sub_array['tags'] = $tags;
                //$sub_array['web_view'] = $item['web_view'];
                $sub_array['image_path'] =  (isset($item['image_path']) ? URL($item['image_path']) : null);
                $sub_array['status'] = $item['status'];
                $sub_array['Issues'] = $Issues;
                $sub_array['Settings'] = $Settings;
                $sub_array['Targets'] = $Targets;
                $sub_array['category_id'] = $item['category_id'];

                dd(json_decode($tags));
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

            //code...
        } catch (\Throwable $th) {
        }
    }

    public function get_resourcecenter_all(Request $request)
    {

        $page = (isset($request->page)) ? $request->page : 1;
        $limit = (isset($request->limit)) ? $request->limit : 1000;
        $offset = ($page - 1) * $limit;

        $list_media_count = ListMedia::whereNotNull('title')->count();
        $list_media = ListMedia::whereNotNull('title')->limit($limit)->offset($offset)->get();


        $total_page = ceil($list_media_count / $limit);




        try {

            foreach ($list_media as $key => $item) {
                //     if(isnull($item['tags'])){
                //         $tags = 'Y';JSON_EXTRACT(($item['tags']),'$.Keywords');
                //    }else{
                //         $tags='N';
                //    }

                $tags_decode = json_decode($item['json_data']);
                if ($item['tags'] == null or $item['tags'] == '') {
                    $tags = (!empty($tags_decode->Keywords)) ? $tags_decode->Keywords : null;
                } else {
                    $tags = $item['tags'];
                }

                $Issues = (!empty($tags_decode->Issues)) ? $tags_decode->Issues : null;
                $Settings = (!empty($tags_decode->Settings)) ? $tags_decode->Settings : null;
                $Targets = (!empty($tags_decode->Targets)) ? $tags_decode->Targets : null;


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
                $sub_array['tags'] = $tags;
                //$sub_array['web_view'] = $item['web_view'];
                $sub_array['image_path'] =  (isset($item['image_path']) ? URL($item['image_path']) : null);
                $sub_array['status'] = $item['status'];
                $sub_array['Issues'] = $Issues;
                $sub_array['Settings'] = $Settings;
                $sub_array['Targets'] = $Targets;
                $sub_array['category_id'] = $item['category_id'];

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

            //code...
        } catch (\Throwable $th) {
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



        $edit_by = auth()->user()->email;
        // dd($list_data['image_base64']);
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
            while (count($array_keyword) < 5) {
                $array_keyword[] = null;
            }
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
        if (!array_key_exists('CoverPath', $list_data)) {
            $list_data['CoverPath'] = null;
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
            $res->Province = 1;
        }


        if ($list_data['term'] == '50') {
            $SendMediaTermStatus = 50;
        } elseif ($list_data['term'] == '49') {
            $SendMediaTermStatus = 49;
        } else {
            $SendMediaTermStatus = 52;
        }
        if(!empty($list_data['chack_persona'])){
            if ($list_data['chack_persona'] == 'Y') {
                $SendMediaTermStatus = 51;
            }
            
        }
       
        
        if ($list_data['not_term'] == 'undefined') {
            $list_data['not_term'] = null;
        } else {
            if (array_key_exists('detail_not_trem', $list_data)) {

                $list_data['not_term'] = rtrim($list_data['not_term'] . ',' . $list_data['detail_not_trem'], ',');
            }
        }

        if(!empty($res->DirectLink)){
            $DirectLink = $res->DirectLink;
        }else{
            $DirectLink = null;
        }

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'http://dol.thaihealth.or.th/WCF/DOLService.svc/json/UpdateMediaDol',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => array('UserName' => 'thaihealthweb','Password' => '8e57kLBwP$#AQSTh#G4WF6@9^hhEUGw+FRW',
        // 'UploadFileID' => $list_data['UploadFileID'],
        // 'CalledBy' => 'nuttamon@thaihealth.or.th',
        // 'CalledByType' => 'ThaiHealth',
        // 'FileAddress' => $list_data['FileAddress'],
        // 'ThumbnailAddress' => $list_data['ThumbnailAddress'],
        // 'Keywords' => $res_keyword,
        // 'Title' =>  $list_data['title'],
        // 'Description' =>$list_data['description'],
        // 'Template' =>  $list_data['Template'],
        // 'TemplateDetail' =>  $list_data['template_detail'],
        // 'PublishLevel' => $res->PublishLevel,
        // 'CreativeCommons' => $res->CreativeCommons,
        // 'Category' => $res->CategoryID,
        // 'Issues' => $res_issue,
        // 'OtherIssueText' => 'Other Target',
        // 'Targets' => $res_target,
        // 'OtherTargetText' => 'Other Target',
        // 'Settings' => $res_setting,
        // 'OtherSettingText' => 'Other Target',
        // 'Area' => 1,
        // 'Province' => $res->Province,
        // 'Source' => $res->Source,
        // 'ReleasedDate' => $res->ReleasedDate,
        // 'Creator' =>  $list_data['creator'],
        // 'Production' => $list_data['production'],
        // 'Publisher' => $list_data['publisher'],
        // 'Contributor' => $res->Contributor,
        // 'Identifier' => $res->Identifier,
        // 'Language' => $res_Language,
        // 'Relation' =>  $res->Relation,
        // 'Format' => $list_data['format'],
        // 'IntellectualProperty' => $res->IntellectualProperty,
        // 'OS' => $list_data['os'] ,
        // 'Owner' => $list_data['owner'],
        // 'PeriodStart' => $res->PeriodStart ,
        // 'PeriodEnd' => $res->PeriodEnd,
        // 'Duration' => $res->Duration,
        // 'IsSubProject' => $res->ProjectCode,
        // 'SubProjectCode' => $res->SubProjectCode ,
        // 'CoverByte' => $list_data['image_base64'] ,
        // 'CoverType' => $list_data['img_ext_th'],
        // 'CoverPath' => $list_data['CoverPath'] ,
        // 'SendMediaTermStatus' => $SendMediaTermStatus,
        // 'SendMediaTermComment' => $list_data['not_term']),
        // ));

        // $response = curl_exec($curl);
        // dd($response);
        // curl_close($curl);


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
                "Password":"8e57kLBwP$#AQSTh#G4WF6@9^hhEUGw+FRW",
                "UploadFileID":"' . $list_data['UploadFileID'] . '",
                "CalledBy": "nuttamon@thaihealth.or.th",
                "CalledByType":"ThaiHealth",
                "FileAddress":"' . $list_data['FileAddress'] . '",
                "ThumbnailAddress":"' . $list_data['ThumbnailAddress'] . '",
                "Keywords":' . $res_keyword . ',
                "Title":"' . str_replace('"', '\\"', $list_data['title'])  . '",
                "Description":"' . str_replace('"', '\\"', $list_data['description'])  . '",
                "Template":"' . $list_data['Template'] . '",
                "TemplateDetail":"' . $list_data['template_detail'] . '",
                "PublishLevel":"' .    $res->PublishLevel . '",
                "CreativeCommons":"' . $res->CreativeCommons . '",
                "Category":' . $res->CategoryID . ',
                "Issues":' . $res_issue . ',
                "OtherIssueText":"Other Issue",
                "Targets":' . $res_target . ',
                "OtherTargetText":"Other Target", 
                "Settings":' . $res_setting . ',
                "OtherSettingText":"Other Setting", 
                "Area":1,
                "Province":"' . $res->Province . '",
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
                "DirectLink":"' . $DirectLink . '",
                "CoverByte":"' . $list_data['image_base64'] . '",
                "CoverType":"' . $list_data['img_ext_th'] . '",
                "CoverPath":"' . $list_data['CoverPath'] . '",
                "SendMediaTermStatus":"' . $SendMediaTermStatus . '",
                "SendMediaTermComment":"'  . $list_data['not_term'] . '",
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);



        return  $response;
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

    function UpdateMediaFileToPersonaHealth($MediaID = null)
    {
        if (!empty($MediaID)) {

            $MediaData = ListMedia::where('id', $MediaID)->first();
            if (!empty($MediaData)) {
                $Thumbnail = null;
                $SourceFile = null;
                if (!empty($MediaData['thumbnail_address'])) {
                    $Thumbnail = ENV('APP_URL') . "/mediadol/" . $MediaData['UploadFileID'] . '/' . $MediaData['thumbnail_address'];
                }

                if (!empty($MediaData->local_path)) {
                    $SourceFile = url('mediadol' . '/' . $MediaData->UploadFileID . '/' . $MediaData->local_path);
                }
                if (!empty($Thumbnail) || !empty($SourceFile)) {
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://persona.thaihealth.or.th/api/UpdateMediaFileFromThrc',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('MediaID' => $MediaID, 'ThumbnailFile' => $Thumbnail, 'SourceFile' => $SourceFile),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo "ResponseFrom PerosonaHealth MediaID : " . $MediaID . "\n" . "Response :" . $response . "\n";
                }
            }
        }
    }

    function UpdatePersonaFileMedia()
    {
        dd("Comment This");
        $MediaID = "433,435,436,438,440,581,584,611,612,655,781,786,788,825,836,893,898,916,920,922,930,931,986,996,1158,1159,1168,1172,1204,1206,1212,1378,1379,1447,1449,1549,1592,1603,1614,1619,1672,1677,1733,1740,1744,1878,1881,1938,2007,2093,2094,2097,2323,2325,2326,2327,2328,2329,2330,2331,2332,2333,2355,2356,2357,2358,2359,2503,2504,2523,2525,2527,2532,2545,2549,2551,2552,2555,2556,2558,2569,2570,2571,2617,2619,2621,2622,2954,2959,2964,2965,2974,2975,2978,2987,2988,2990,2996,2998,3003,3020,3022,3023,3026,3027,3028,3043,3052,3053,3273,3275,3276,3277,3278,3279,3280,3281,3282,3283,3284,3285,3286,3287,4375,4592,5389,5758,6183,6233,6235,6239,6240,6243,6244,6246,6248,6250,6252,6325,6390,6416,6474,6476,6508,6676,6716,6725,6737,6739,6740,6767,6768,6787,6790,6792,6798,6934,6937,6944,6948,6949,6950,6951,6952,6953,6954,6956,6960,6966,6969,6971,6978,6983,7003,7004,7005,7007,7024,7026,7042,7051,7064,7066,7076,7081,7091,7094,7096,7100,7107,7118,7126,7131,7133,7142,7145,7157,7193,7203,7205,7209,7215,7220,7222,7224,7225,7233,7237,7244,7245,7247,7248,7249,7251,7262,7366,7369,7373,7377,7378,7379,7408,7415,7418,7467,7474,7479,7480,7487,7489,7491,7508,7517,7522,7531,7553,7581,7595,7629,7630,7632,7633,7634,7635,7644,7645,7646,7647,7649,7652,7653,7654,7657,7658,7661,7663,7666,7667,7669,7674,7675,7677,7680,7688,7692,7694,7696,8156,8175,8181,8185,8197,8202,8203,8206,8210,8214,8228,8232,8233,8234,8237,8239,8240,8242,8248,8250,8252,8263,8266,8270,8272,8279,8282,8283,8284,8286,8289,8293,8322,8339,8346,8348,8349,8351,8352,8678,8682,8683,8809,8852,8881,8882,8889,8890,9008,9013,9014,9023,9025,9030,9037,9040,9044,9052,9056,9060,9062,9068,9071,9077,9080,9082,9099,9101,9104,9105,9110,9113,9117,9128,9133,9137,9141,9153,9166,9196,9197,9200,9210,9219,9231,9242,9248,9518,10254,12364,12370,12377,12381,45162,45165,45166,45178,45430,50556,50793,50889,51034,51096,51205,51365,51366,51367,51369,51480,51536,51537,51538,51543,51706,51745,51764,51816,51854,51899,52191,52193,52259,52321,52322,52353,52392,52393,52394,52395,52550,53504,53505,53506,53508,53509,53511,53512,53516,53674,53893,53895,53896,53930,53932,53939,53941,53946,53949,54354,54431,54502,54544,54545,54549,54561,54562,54563,54566,54567,54569,54572,54574,54578,54579,54582,54586,54587,54591,54592,54596,54598,54599,54600,54673,54675,54676,54906,54930,54931,54935,54981,54987,55056,55107,55123,55126,55160,55332,55337,55338,55365,55717,55719,56065,56109,56372,56938,57043,57093,57140,57293,57295,57334,57348,57413,57426,57428,57477,57560,57588,57628,57629,57715,57776,57778,57929,57979,58221,58266,58278,58293,58294,58623,58794,58795,58797,58808,58890,58891,58892,58893,58894,58895,58896,58897,58898,58899,58935,58936,58937,58938,58939,58940,58941,58947,58948,58949,58950,58951,58952,58953,58954,58955,58956,58957,58958,58959,58960,58961,58962,59118,59119,59121,59146,59201,59202,59203,59263,59264,59265,59268,59269,59278,59280,59291,59346,59350,59356,59368,59397,59399,59401,59581,59614,59710,59919,59922,59923,59982,60009,60013,60068,60070,60071,60072,60074,60076,60077,60078,60079,60094,60095,60096,60097,60136,60137,60139,60140,60141,60243,60247,60248,60249,60250,60333,60334,60337,60397,60442,60527,60528,60532,60534,60592,60594,60595,60598,60846,60847,60848,60904,60943,60944,60961,60962,61003,61005,61007,61010,61011,61013,61014,61015,61019,61020,61021,61022,61023,61024,61025,61026,61027,61028,61029,61031,61070,61105,61135,61148,61149,61150,61152,61213,61255,61256,61267,61268,61270,61271,61272,61356,61376,61377,61541,61544,61545,61547,61576,61586,61609,61614,61615,61711,61742,61743,61747,61774,61813,61847,61853,61856,61880,61889,61891,62035,62075,62078,62079,62082,62086,62162,62201,62217,62218,62241,62285,62357,62358,62415,62416,62417,62423,62424,62449,62464,62466,62467,62468,62470,62471,62503,62504,62507,62508,62537,62538,62539,62540,62541,62542,62543,62544,62658,62659,62660,62661,62662,62666,62667,62668,62669,62670,62671,62672,62674,62716,62829,62833,62838,62839,62840,62841,62842,62843,62844,62845,62846,62847,62874,62875,62876,62877,62878,62902,62905,62906,62907,63022,63023,63052,63092,63124,63130,63131,63132,63133,63134,63148,63149,63150,63151,63152,63153,63155,63158,63159,63160,63166,63171,63173,63174,63176,63177,63183,63184,63185,63186,63187,63188,63194,63272,63273,63274,63286,63295,63296,63297,63298,63303,63304,63315,63326,63327,63328,63329,63373,63463,63464,63465,63466,63467,63468,63471,63472,63476,63480,63504,63507,63510,63511,63515,63517,63518,63519,63536,63537,63538,63539,63540,63569,63587,63696,63785,63813,63828,63829,63933,63934,63935,63936,63937,63941,63943,63971,63972,63973,63975,63976,63977,63980,63999,64004,64008,64011,64020,64021,64024,64037,64038,64039,64040,64041,64042,64043,64044";
        $MediaID = explode(",", $MediaID);
        $MediaData = ListMedia::wherein('id', $MediaID)
            // ->where('id',243)
            ->wherenull('local_path')
            ->wherenull('thumbnail_address')
            // ->limit(1)
            ->get()
            ->toArray();
        // dd($MediaData);
        if (!empty($MediaData)) {
            foreach ($MediaData as $KeyMediaData => $valueMediaData) {
                // dd($valueMediaData['id']);
                echo "GetFileFromDol MediaID : " . $valueMediaData['id'] . "\n";
                $this->GetMeidaFileFromDolDev($valueMediaData['id'], $valueMediaData['UploadFileID']);
                echo "GetFileFromDolSuccess MediaID : " . $valueMediaData['id'] . "\n";
                echo "UpdateLinkToPersonaHealth MediaID : " . $valueMediaData['id'] . "\n";
                $this->UpdateMediaFileToPersonaHealth($valueMediaData['id']);

                // dd($valueMediaData['id']);

            }
        }

        return "success";
    }

    public function GetPersonaMedia()
    {
        $data_id = [
            "009dfa1e-e796-ed11-80fb-00155d1aab67", "69513fa0-6786-ed11-80fb-00155d1aab67", "424ad5ed-b185-ed11-80fb-00155d1aab67", "f3f1d1de-58a0-ed11-80fc-00155d1aab68", "d991087e-7b86-ed11-80fb-00155d1aab67", "36a746d2-525e-ed11-80fa-00155db45626", "1b9f5232-5ca0-ed11-80fc-00155d1aab68", "e5bfa048-5ca0-ed11-80fc-00155d1aab68", "c7393d5b-5ca0-ed11-80fc-00155d1aab68", "042c2343-2e48-ed11-80fa-00155db45626", "33570663-5ca0-ed11-80fc-00155d1aab68", "21961a86-5ca0-ed11-80fc-00155d1aab68", "6027caae-2950-ed11-80fa-00155db45626", "45f86ce6-0e5f-ed11-80fa-00155db45626", "04d352ef-0e5f-ed11-80fa-00155db45626", "15fc5e4f-df9a-ed11-80fb-00155d1aab67", "58c72c68-9c6f-ed11-80fa-00155db45626", "5e34a9cb-525e-ed11-80fa-00155db45626", "3a0f4006-e796-ed11-80fb-00155d1aab67", "49931c7e-0f87-ed11-80fb-00155d1aab67", "739e9b18-f48f-ed11-80fb-00155d1aab67", "15ad8c98-ec7c-ed11-80fb-00155d1aab67", "363ab9d8-457c-ed11-80fb-00155d1aab67", "0b3a38ba-2c7c-ed11-80fb-00155d1aab67", "a1450505-de80-ed11-80fb-00155d1aab67", "5795755b-477c-ed11-80fb-00155d1aab67", "24e24753-ed7c-ed11-80fb-00155d1aab67", "f09b4227-e796-ed11-80fb-00155d1aab67", "75239be8-5187-ed11-80fb-00155d1aab67", "2a227f5e-3e84-ed11-80fb-00155d1aab67", "dc203389-467c-ed11-80fb-00155d1aab67", "ebe309e1-b090-ed11-80fb-00155d1aab67", "bb7891f0-4c4d-ed11-80fa-00155db45626", "fe1cb543-9885-ed11-80fb-00155d1aab67", "51ff8b52-013f-ed11-80fa-00155db45626", "ea4989a4-8c86-ed11-80fb-00155d1aab67", "69875033-57a0-ed11-80fc-00155d1aab68", "71af0283-58a0-ed11-80fc-00155d1aab68", "806f5deb-f09d-ed11-80fb-00155d1aab67", "3162a281-52a0-ed11-80fc-00155d1aab68", "f340c84a-55a0-ed11-80fc-00155d1aab68", "21b649b8-51a0-ed11-80fc-00155d1aab68", "a8304ec0-e696-ed11-80fb-00155d1aab67", "50ff8b52-013f-ed11-80fa-00155db45626", "b90e45c9-57a0-ed11-80fc-00155d1aab68", "f89b41fc-0e5f-ed11-80fa-00155db45626", "08b24f14-2192-ed11-80fb-00155d1aab67", "083d9083-e89d-ed11-80fb-00155d1aab67", "c4ad52dd-5487-ed11-80fb-00155d1aab67", "132a4cb3-5087-ed11-80fb-00155d1aab67", "652819ac-9285-ed11-80fb-00155d1aab67", "16a014ca-9390-ed11-80fb-00155d1aab67", "33e0ff2b-9490-ed11-80fb-00155d1aab67", "22724387-af90-ed11-80fb-00155d1aab67", "bf7e24b6-208b-ed11-80fb-00155d1aab67", "19d344ec-f19d-ed11-80fb-00155d1aab67", "678bf77b-1480-ed11-80fb-00155d1aab67", "c5511d2d-7295-ed11-80fb-00155d1aab67", "82b91aa0-1c4a-ed11-80fa-00155db45626", "264e72fe-88a7-ed11-80fc-00155d1aab68", "e8c00d6a-1c4a-ed11-80fa-00155db45626", "f353dfbd-fb9f-ed11-80fc-00155d1aab68", "1e1f3a22-8b8d-ed11-80fb-00155d1aab67", "7402c530-ff9f-ed11-80fc-00155d1aab68", "698e5761-b185-ed11-80fb-00155d1aab67", "7c3985cd-b285-ed11-80fb-00155d1aab67", "6fcb99ac-5744-ed11-80fa-00155db45626", "f7331ae2-bf58-ed11-80fa-00155db45626", "085c0845-217d-ed11-80fb-00155d1aab67", "d509fa4f-5487-ed11-80fb-00155d1aab67", "c2bb1099-6ba0-ed11-80fc-00155d1aab68", "ab836ae3-1e72-ed11-80fb-00155d1aab67", "9c262a7d-5871-ed11-80fa-00155db45626", "ada97f54-5871-ed11-80fa-00155db45626", "726904c3-4871-ed11-80fa-00155db45626", "07b2ee6e-74a3-ed11-80fc-00155d1aab68", "52988d54-f99d-ed11-80fb-00155d1aab67", "082e1e67-8086-ed11-80fb-00155d1aab67", "6fb1b6dc-5744-ed11-80fa-00155db45626", "564785dc-525e-ed11-80fa-00155db45626", "f31243be-2d7c-ed11-80fb-00155d1aab67", "8d4845a4-cd8f-ed11-80fb-00155d1aab67", "c0e294a0-7686-ed11-80fb-00155d1aab67", "b3a1e1d6-547b-ed11-80fb-00155d1aab67", "0064b1b7-5744-ed11-80fa-00155db45626", "70b8224b-b490-ed11-80fb-00155d1aab67", "7e561171-3880-ed11-80fb-00155d1aab67", "55165940-a556-ed11-80fa-00155db45626", "075e26c4-58a0-ed11-80fc-00155d1aab68", "f2c12646-368b-ed11-80fb-00155d1aab67", "4380e967-ed8f-ed11-80fb-00155d1aab67", "6805f9d0-73a0-ed11-80fc-00155d1aab68", "d208ddda-049e-ed11-80fb-00155d1aab67", "d572bd89-2345-ed11-80fa-00155db45626", "b081b87f-2345-ed11-80fa-00155db45626", "5d2d8a9d-936f-ed11-80fa-00155db45626", "01297f15-198b-ed11-80fb-00155d1aab67", "342b08f0-ea9d-ed11-80fb-00155d1aab67", "a3a1d953-9590-ed11-80fb-00155d1aab67", "0e066f69-ed9d-ed11-80fb-00155d1aab67", "564658d2-eb9d-ed11-80fb-00155d1aab67", "2d457aed-ec9d-ed11-80fb-00155d1aab67", "1f508ff5-fc9f-ed11-80fc-00155d1aab68", "f33549fa-de80-ed11-80fb-00155d1aab67", "23cc4074-9977-ed11-80fb-00155d1aab67", "1e1df8e6-8255-ed11-80fa-00155db45626", "36ddd925-7199-ed11-80fb-00155d1aab67", "0f03ba26-9d90-ed11-80fb-00155d1aab67", "bfeeffd5-54a0-ed11-80fc-00155d1aab68", "16542070-fc9f-ed11-80fc-00155d1aab68", "ee01d3dd-52a0-ed11-80fc-00155d1aab68", "f73cf1fc-70a0-ed11-80fc-00155d1aab68", "37e637b0-3c4f-ed11-80fa-00155db45626", "af950d93-936f-ed11-80fa-00155db45626", "651aee39-fe9f-ed11-80fc-00155d1aab68", "6e6215ba-55a0-ed11-80fc-00155d1aab68", "04ec6124-52a0-ed11-80fc-00155d1aab68", "953a1c4b-5187-ed11-80fb-00155d1aab67", "df73a198-7886-ed11-80fb-00155d1aab67", "08b6bfc3-7d86-ed11-80fb-00155d1aab67", "271ef176-328b-ed11-80fb-00155d1aab67", "47357f7a-787f-ed11-80fb-00155d1aab67", "fcbde938-478b-ed11-80fb-00155d1aab67", "26988268-bb85-ed11-80fb-00155d1aab67", "5af1ded7-dc80-ed11-80fb-00155d1aab67", "3343597f-3b80-ed11-80fb-00155d1aab67", "f47623bc-8d98-ed11-80fb-00155d1aab67", "ffe83e48-5587-ed11-80fb-00155d1aab67", "e2d82a01-9877-ed11-80fb-00155d1aab67", "b660b053-3980-ed11-80fb-00155d1aab67", "72980e12-3796-ed11-80fb-00155d1aab67", "3c97c211-448b-ed11-80fb-00155d1aab67", "40c60682-6da0-ed11-80fc-00155d1aab68", "c6ccbff6-ae90-ed11-80fb-00155d1aab67", "51dcd957-b885-ed11-80fb-00155d1aab67", "37aa6ee9-ef9d-ed11-80fb-00155d1aab67", "52ff8b52-013f-ed11-80fa-00155db45626", "2b7e6040-db80-ed11-80fb-00155d1aab67", "705a3465-7786-ed11-80fb-00155d1aab67", "bd82cc83-9464-ed11-80fa-00155db45626", "5fcb6858-9785-ed11-80fb-00155d1aab67", "27603771-b290-ed11-80fb-00155d1aab67", "b9164492-049e-ed11-80fb-00155d1aab67", "ae210451-f39d-ed11-80fb-00155d1aab67", "5c44ba8f-72a0-ed11-80fc-00155d1aab68", "2b0f89eb-71a0-ed11-80fc-00155d1aab68", "d3c1b1c4-5744-ed11-80fa-00155db45626", "da2e4239-7c86-ed11-80fb-00155d1aab67", "b45548f6-9d90-ed11-80fb-00155d1aab67", "de53ed01-ef7c-ed11-80fb-00155d1aab67", "de7bafd4-5744-ed11-80fa-00155db45626", "2ddc041a-3f86-ed11-80fb-00155d1aab67", "b79fc5df-8a98-ed11-80fb-00155d1aab67", "baaaa7c0-3c4f-ed11-80fa-00155db45626", "dc1c1ad8-eb5f-ed11-80fa-00155db45626", "3dbb0c06-6ba0-ed11-80fc-00155d1aab68", "806f5209-7898-ed11-80fb-00155d1aab67", "4740efd8-f9ac-ed11-80fd-00155d1aab66", "6ce9058d-82a3-ed11-80fc-00155d1aab68", "24d10183-80a3-ed11-80fc-00155d1aab68", "84d059fe-177d-ed11-80fb-00155d1aab67", "6691823f-3771-ed11-80fa-00155db45626", "7b27751e-9c85-ed11-80fb-00155d1aab67", "5aa14d53-027d-ed11-80fb-00155d1aab67", "348b4509-50a0-ed11-80fc-00155d1aab68", "ae30b4b3-5aa0-ed11-80fc-00155d1aab68", "99374b08-5ba0-ed11-80fc-00155d1aab68", "75c11c3b-5ba0-ed11-80fc-00155d1aab68", "5cb46025-5aa0-ed11-80fc-00155d1aab68", "e9cefb61-f09d-ed11-80fb-00155d1aab67", "7cdca35e-f7a8-ed11-80fc-00155d1aab68", "06855098-797f-ed11-80fb-00155d1aab67", "7fc525d1-6f95-ed11-80fb-00155d1aab67", "4e670b4d-059e-ed11-80fb-00155d1aab67", "f4d6148e-059e-ed11-80fb-00155d1aab67", "532dcb09-e89d-ed11-80fb-00155d1aab67", "e45a1d1c-e296-ed11-80fb-00155d1aab67", "2fb1ac83-71a0-ed11-80fc-00155d1aab68", "a812f068-e096-ed11-80fb-00155d1aab67", "04c924b9-7298-ed11-80fb-00155d1aab67", "d2c562c1-7298-ed11-80fb-00155d1aab67"
        ];
        $data =  DB::table('list_media')
            ->where('show_dol', 2)
            ->wherein('UploadFileID', $data_id)
            ->wherenull('local_path')
            ->wherenull('thumbnail_address')
            ->get()
            ->toArray();

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->GetMeidaFileFromDol($value->id, $value->UploadFileID);
            }
        }
    }


    public function MediaJobFileCheck()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $MediaDataAll = ListMedia::where('status', 'publish')->get()->toArray();
        $DataExports = [];
        if (!empty($MediaDataAll)) {
            foreach ($MediaDataAll as $keyMedia => $valueMedia) {
                try {
                    $ThumbnailStatus = false;
                    $MediaData =   ListMedia::where('id', $valueMedia['id'])->first();
                    $MediaJsonData = json_decode($MediaData['json_data']);
                    // Thumbnail
                    if ($MediaData->getMedia('cover_desktop')->isNotEmpty()) {
                        $link_tumnail = url($MediaData->getMedia('cover_desktop')->first()->getUrl());
                    } else
            if (!empty($MediaData['image_path'])) {
                        $link_tumnail = ENV('APP_URL') . "/" . $MediaData['image_path'];
                    } else
            if (!empty($MediaData['thumbnail_address'])) {
                        $link_tumnail = ENV('APP_URL') . "/mediadol/" . $MediaData['UploadFileID'] . '/' . $MediaData['thumbnail_address'];
                    } else {

                        $link_tumnail = $MediaJsonData->ThumbnailAddress;
                    }
                    // SourceFile
                    if (is_null($MediaData->local_path)) {
                        $FileAddress = $this->GenUrlDownLoadfileDol($MediaData['UploadFileID']);
                        if (empty($FileAddress)) {
                            $FileAddress = $FileAddress = $MediaJsonData->FileAddress;
                        }
                    } else {
                        $FileAddress = url('mediadol' . '/' . $MediaData->UploadFileID . '/' . $MediaData->local_path);
                    }

                    $ThumbnailStatus = $this->isUrlWorking($link_tumnail);
                    $SourceFileStatus = $this->isUrlWorking($FileAddress);
                    $DataExports[] = [
                        'MediaID' => $MediaData['id'],
                        'MediaTitle' => $MediaData['title'],
                        'ThubmnailFileStatus' => (!empty($ThumbnailStatus)) ? "ไฟล์ปกติ" : "ไฟล์เสียหาย",
                        'SourceFileStatus' => (!empty($SourceFileStatus)) ? "ไฟล์ปกติ" : "ไฟล์เสียหาย",
                    ];
                } catch (\Throwable $th) {
                }
            }
            if (!empty($DataExports)) {
                $file_name = 'MediaStatus';
                Excel::create($file_name, function ($excel) use ($DataExports) {
                    $excel->sheet('mySheet', function ($sheet) use ($DataExports) {
                        $sheet->row(1, array(
                            "ชื่อสื่อ",
                            "สถานะไฟล์ปก",
                            "สถานะไฟล์สื่อ",
                            "ลิงก์สื่อ",
                        ));
                        $index = 2;
                        foreach ($DataExports as $keyExports => $valueExports) {
                            // dd($valueExports);
                            $sheet->row($index++, array(
                                $valueExports['MediaTitle'],
                                $valueExports['ThubmnailFileStatus'],
                                $valueExports['SourceFileStatus'],
                                route('media2-detail', base64_encode($valueExports['MediaID']))
                            ));
                        }
                    });
                })->store('xlsx', storage_path('excel/exports'));
            }
        }

        return "success";
    }

    public function QueueDonwloadFileFromDol($Media_ID)
    {
        $MeidaData = ListMedia::where('id', $Media_ID)->first();
        DownloadFileFromDol::dispatch($MeidaData['id'], $MeidaData['UploadFileID']);
    }

    public function GetMeidaFileFromDolDev($Media_ID = null, $UploadFileID = null)
    {

        $check_file = null;
        if (empty($check_file)) {
            ini_set('memory_limit', '-1');
            date_default_timezone_set("Asia/Bangkok");
            $file_folder = "/var/www/html/public/mediadol";

            $i = 0;
            $error = 0;

            // cretate log file
            $logfilePath = "/var/www/html/manual_log/prd-" . date('Y-m-d') . ".txt";

            if (!file_exists($logfilePath)) {
                // Open the file for writing, creating it if it does not exist
                $fh = fopen($logfilePath, 'x');
                // Close the file
                fclose($fh);
            }

            //echo strtoupper($data->UploadFileID) . "\n";
            try {

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://dol.thaihealth.or.th/WCF/DOLService.svc/json/GetMediaDol',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                    "UserName":"thrc-pro",
                    "Password":"sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3",
                    "UploadFileID":"' . strtoupper($UploadFileID) . '"
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: lastRequestTime='
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);

                $resArr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',  $response), true);

                // check UploadFileID ว่ามีค่าหรือไม่ ถ้ามีค่า ให้ทำงานโดนการ copy หรือไม่
                if ($resArr['UploadFileID'] != "") {
                    $patrh = $file_folder . '/' . $resArr['UploadFileID'];
                    $file_ext = $resArr['UploadFile']['Format'];
                    if (!is_dir($patrh)) {
                        mkdir($file_folder . '/' . $resArr['UploadFileID'], 0777, true);
                    }
                    $tmp = explode('/', $resArr['UploadFile']['FileAddress']);
                    $filename_FileAddress = $tmp[count($tmp) - 1];
                    $fFileAddress = explode('.', $filename_FileAddress);

                    $tmp = explode('/', $resArr['UploadFile']['ThumbnailAddress']);
                    $filename_ThumbnailAddress = $tmp[count($tmp) - 1];
                    $fThumbnailAddress = explode('.', $filename_ThumbnailAddress);

                    $fileFileAddress = (is_array($fFileAddress)) ? md5(time()) . "." . $file_ext  : md5(time());
                    $fThumbnailAddress = (is_array($fThumbnailAddress)) ? "ThumbnailAddress_" . md5(time()) . "." . $fThumbnailAddress[1] : "ThumbnailAddress_" . md5(time());
                    // ฟังก์ชักชั่นโหลดไฟล์ตัวใหม่
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '{
                    "UserName":"thrc-pro",
                    "Password":"sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3",
                    "UploadFileID": "' . strtoupper($UploadFileID) . '",
                    "Email" :"khomsan@thaihealth.or.th",
                    "IsPublic" :true
                }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);
                    $TokenDol = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);
                    curl_close($curl);
                    if (empty($TokenDol)) {
                        $fileFileAddress = null;
                    } else {

                        if (empty($TokenDol['Token'])) {
                            $fileFileAddress = null;
                        } else {
                            try {
                                $file = file_get_contents('https://dol.thaihealth.or.th/DownloadFile/' . $TokenDol['Token']);

                                if (file_put_contents($patrh . '/' . $fileFileAddress, $file)) {

                                    DB::table('list_media')
                                        ->where('id', $Media_ID)
                                        ->update([
                                            'local_path' => $fileFileAddress
                                        ]);
                                }
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }
                    }
                    //ตัวเก่า
                    //if($resArr['UploadFile']['FileAddress'] != "") {
                    //$tmp = explode('/', $resArr['UploadFile']['FileAddress']);
                    //$filename = $tmp[count($tmp) - 1];
                    //$file = file_get_contents($resArr['UploadFile']['FileAddress']);
                    //file_put_contents($patrh . '/' . $fileFileAddress, $file);
                    //}

                    if ($resArr['UploadFile']['CoverPath'] != "") {
                        $tmp = explode('/', $resArr['UploadFile']['CoverPath']);
                        $filename = $tmp[count($tmp) - 1];
                        try {
                            $file = file_get_contents($resArr['UploadFile']['CoverPath']);
                            if (file_put_contents($patrh . '/' . $fThumbnailAddress, $file)) {
                                DB::table('list_media')
                                    ->where('id', $Media_ID)
                                    ->update([
                                        'thumbnail_address' => $fThumbnailAddress
                                    ]);
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    } else	
                            if ($resArr['UploadFile']['ThumbnailAddress'] != "") {
                        $tmp = explode('/', $resArr['UploadFile']['ThumbnailAddress']);
                        $filename = $tmp[count($tmp) - 1];
                        try {
                            $file = file_get_contents($resArr['UploadFile']['ThumbnailAddress']);
                            // file_put_contents($patrh . '/' . $fThumbnailAddress, $file);
                            if (file_put_contents($patrh . '/' . $fThumbnailAddress, $file)) {
                                DB::table('list_media')
                                    ->where('id', $Media_ID)
                                    ->update([
                                        'thumbnail_address' => $fThumbnailAddress
                                    ]);
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }

                    // // update file_path
                    // $sql = "UPDATE list_media SET
                    // 		local_path = '" . $fileFileAddress . "',
                    // 		thumbnail_address = '" . $fThumbnailAddress . "'
                    // 	WHERE id='" . $data->id . "'";
                    // $conn->query($sql) or die("UPDATE Error: " . $conn->error);

                    // DB::table('list_media')
                    // ->where('id',$Media_ID)
                    // ->update([
                    //     'local_path' => $fileFileAddress,
                    //     'thumbnail_address' => $fThumbnailAddress
                    // ]);
                    $CheckData =  DB::table('ibc_dol_import_log')->where('UploadFileID', $resArr['UploadFileID'])->get()->toArray();
                    if (empty($CheckData)) {
                        DB::table('ibc_dol_import_log')->insert([
                            'UploadFileID' => $resArr['UploadFileID'],
                            'create_at' => DB::raw("NOW()")
                        ]);
                    }

                    // $sql = "INSERT INTO ibc_dol_import_log VALUES ('" . $resArr['UploadFileID'] . "', NOW())";
                    // $conn->query($sql) or die("INSERT Error: " . $conn->error);
                }
            } catch (Exception $e) {
                // dd($e);
                $error++;
                $message = date('Y-m-d H:i:s') . ': error to copy file list_media id ' . $Media_ID . PHP_EOL;
                // Write the message to the log file
                error_log($message, 3, $logfilePath);
            }
        }
    }
    public function GetMeidaFileFromDol($Media_ID = null, $UploadFileID = null)
    {

        $check_file = DB::table('ibc_dol_import_log')->where('UploadFileID', $UploadFileID)->get()->toArray();
        if (empty($check_file)) {
            ini_set('memory_limit', '-1');
            date_default_timezone_set("Asia/Bangkok");
            $file_folder = "/var/www/html/public/mediadol";

            $i = 0;
            $error = 0;

            // cretate log file
            $logfilePath = "/var/www/html/manual_log/prd-" . date('Y-m-d') . ".txt";

            if (!file_exists($logfilePath)) {
                // Open the file for writing, creating it if it does not exist
                $fh = fopen($logfilePath, 'x');
                // Close the file
                fclose($fh);
            }

            //echo strtoupper($data->UploadFileID) . "\n";
            try {

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://dol.thaihealth.or.th/WCF/DOLService.svc/json/GetMediaDol',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                    "UserName":"thrc-pro",
                    "Password":"sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3",
                    "UploadFileID":"' . strtoupper($UploadFileID) . '"
                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Cookie: lastRequestTime='
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                $resArr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',  $response), true);

                // check UploadFileID ว่ามีค่าหรือไม่ ถ้ามีค่า ให้ทำงานโดนการ copy หรือไม่
                if ($resArr['UploadFileID'] != "") {
                    $patrh = $file_folder . '/' . $resArr['UploadFileID'];
                    $file_ext = $resArr['UploadFile']['Format'];
                    if (!is_dir($patrh)) {
                        mkdir($file_folder . '/' . $resArr['UploadFileID'], 0777, true);
                    }
                    $tmp = explode('/', $resArr['UploadFile']['FileAddress']);
                    $filename_FileAddress = $tmp[count($tmp) - 1];
                    $fFileAddress = explode('.', $filename_FileAddress);

                    $tmp = explode('/', $resArr['UploadFile']['ThumbnailAddress']);
                    $filename_ThumbnailAddress = $tmp[count($tmp) - 1];
                    $fThumbnailAddress = explode('.', $filename_ThumbnailAddress);

                    $fileFileAddress = (is_array($fFileAddress)) ? md5(time()) . "." . $file_ext  : md5(time());
                    $fThumbnailAddress = (is_array($fThumbnailAddress)) ? "ThumbnailAddress_" . md5(time()) . "." . $fThumbnailAddress[1] : "ThumbnailAddress_" . md5(time());
                    // ฟังก์ชักชั่นโหลดไฟล์ตัวใหม่
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://dol.thaihealth.or.th/WCF/DOLOtherService.svc/json/GenTokenDownload',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '{
                    "UserName":"thrc-pro",
                    "Password":"sHdd-eMW_wa_cZht748K$2^$Y2_Hyk6jc3",
                    "UploadFileID": "' . strtoupper($UploadFileID) . '",
                    "Email" :"khomsan@thaihealth.or.th",
                    "IsPublic" :true
                }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);
                    $TokenDol = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true);
                    curl_close($curl);
                    if (empty($TokenDol)) {
                        $fileFileAddress = null;
                    } else {

                        if (empty($TokenDol['Token'])) {
                            $fileFileAddress = null;
                        } else {
                            try {
                                $file = file_get_contents('https://dol.thaihealth.or.th/DownloadFile/' . $TokenDol['Token']);

                                if (file_put_contents($patrh . '/' . $fileFileAddress, $file)) {

                                    DB::table('list_media')
                                        ->where('id', $Media_ID)
                                        ->update([
                                            'local_path' => $fileFileAddress
                                        ]);
                                }
                            } catch (\Throwable $th) {
                                //throw $th;
                            }
                        }
                    }
                    //ตัวเก่า
                    //if($resArr['UploadFile']['FileAddress'] != "") {
                    //$tmp = explode('/', $resArr['UploadFile']['FileAddress']);
                    //$filename = $tmp[count($tmp) - 1];
                    //$file = file_get_contents($resArr['UploadFile']['FileAddress']);
                    //file_put_contents($patrh . '/' . $fileFileAddress, $file);
                    //}

                    if ($resArr['UploadFile']['CoverPath'] != "") {
                        $tmp = explode('/', $resArr['UploadFile']['CoverPath']);
                        $filename = $tmp[count($tmp) - 1];
                        try {
                            $file = file_get_contents($resArr['UploadFile']['CoverPath']);
                            if (file_put_contents($patrh . '/' . $fThumbnailAddress, $file)) {
                                DB::table('list_media')
                                    ->where('id', $Media_ID)
                                    ->update([
                                        'thumbnail_address' => $fThumbnailAddress
                                    ]);
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    } else	
                            if ($resArr['UploadFile']['ThumbnailAddress'] != "") {
                        $tmp = explode('/', $resArr['UploadFile']['ThumbnailAddress']);
                        $filename = $tmp[count($tmp) - 1];
                        try {
                            $file = file_get_contents($resArr['UploadFile']['ThumbnailAddress']);
                            // file_put_contents($patrh . '/' . $fThumbnailAddress, $file);
                            if (file_put_contents($patrh . '/' . $fThumbnailAddress, $file)) {
                                DB::table('list_media')
                                    ->where('id', $Media_ID)
                                    ->update([
                                        'thumbnail_address' => $fThumbnailAddress
                                    ]);
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }

                    // // update file_path
                    // $sql = "UPDATE list_media SET
                    // 		local_path = '" . $fileFileAddress . "',
                    // 		thumbnail_address = '" . $fThumbnailAddress . "'
                    // 	WHERE id='" . $data->id . "'";
                    // $conn->query($sql) or die("UPDATE Error: " . $conn->error);

                    // DB::table('list_media')
                    // ->where('id',$Media_ID)
                    // ->update([
                    //     'local_path' => $fileFileAddress,
                    //     'thumbnail_address' => $fThumbnailAddress
                    // ]);
                    DB::table('ibc_dol_import_log')->insert([
                        'UploadFileID' => $resArr['UploadFileID'],
                        'create_at' => DB::raw("NOW()")
                    ]);
                    // $sql = "INSERT INTO ibc_dol_import_log VALUES ('" . $resArr['UploadFileID'] . "', NOW())";
                    // $conn->query($sql) or die("INSERT Error: " . $conn->error);
                }
            } catch (Exception $e) {
                // dd($e);
                $error++;
                $message = date('Y-m-d H:i:s') . ': error to copy file list_media id ' . $Media_ID . PHP_EOL;
                // Write the message to the log file
                error_log($message, 3, $logfilePath);
            }
        }
    }

    public function postTransfer(Request $request)
    {
        
        $string = $request->UploadFileID;

        if (strlen($string) >= 4) {

            $characters = substr($string, 0, 4);
        }


        if ($characters == 'THRC') {


            //dd($request->all());
            $item = ListMedia::findOrFail($request['id']);
            $json_decode_update  = json_decode($item->json_data);
            $data = $request->all();


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

            if ($data['term'] == '50') {
                $SendMediaTermStatus = 50;
            } elseif ($data['term'] == '49') {
                $SendMediaTermStatus = 49;
            } else {
                $SendMediaTermStatus = 52;
            }
            if(!empty($data['show_dol'])){
                if ($data['show_dol'] == 2) {
                    $SendMediaTermStatus = 51;
                }
                
            }


            $array = array();

            $array['UploadFileID'] = $item->UploadFileID;
            $array['FileAddress'] = isset($json_decode_update->FileAddress) ? $json_decode_update->FileAddress : "";
            $array['Media_ref'] = isset($json_decode_update->Media_ref) ? $json_decode_update->Media_ref : "";
            $array['ThumbnailAddress'] = isset($json_decode_update->ThumbnailAddress) ? $json_decode_update->ThumbnailAddress : "";
            $array['CoverPath'] = isset($json_decode_update->CoverPath) ? $json_decode_update->CoverPath : "";
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
            $array['SendMediaTermStatus'] = $SendMediaTermStatus;


            if (isset($data['tags_dol'])) {
                $data_tags_dol = [];
                foreach (explode(",", $data['tags_dol']) as $key => $value) {
                    array_push($data_tags_dol, $value);
                }
            }
            // update tag dol to data
            $array['Keywords'] = isset($data_tags_dol) ? $data_tags_dol : $array['Keywords'];

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

            if ($data['term'] == "undefined") {
                $data['term'] = null;
            }



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

            // $string = $data['tags'];
            // $data_update['tags'] = json_encode(explode(",", $string));
            if (isset($data['tags'])) {
            
                $data_tags = array();
                foreach (explode(",", $data['tags']) as $key => $value) {
    
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
                $data_update['tags'] = json_encode($data_tags);
            } else {
                $data_update['tags'] = '';
            }

            if ($request->hasFile('cover_desktop')) {
                $item->clearMediaCollection('cover_desktop');
                $time = carbon::now()->format('His');
                $rename = $request->id."-".$time.".".$request->file('cover_desktop')->getClientOriginalExtension();
                
                $item->addMedia($request->file('cover_desktop'))->usingFileName($rename)->toMediaCollection('cover_desktop');
                $item->update(['image_path' => $item->getFirstMediaUrl('cover_desktop', 'thumb1024x618')]);
                //dd($item->getFirstMediaUrl('cover_desktop','thumb1024x618'));
            }

            $data_update['end_date'] = $data['end_date'];

            if ($data['term'] != 'undefined') {
                $data_update['SendMediaTermStatus'] = $SendMediaTermStatus;
            }
            if ($data['not_term'] != 'undefined') {
                $data_update['SendMediaTermComment'] = $data['not_term'];
            }
            if ($data['detail_not_trem'] != 'undefined') {
                $data_update['detail_not_term'] = $data['detail_not_trem'];
            }

            if (!empty($request['chack_show_data'])) {
                ListMedia::where('show_data', 1)->update([
                    'show_data' => null
                ]);
            }
            $data_update['show_data'] = $request['chack_show_data'];

            $media_dol = (isset($data['show_dol']) && $data['show_dol'] == '2') ? "Y" : "N";
            $media_learning = (isset($data['show_learning']) && $data['show_learning'] == '2') ? "Y" : "N";
            $media_thrc = (isset($data['show_rc']) && $data['show_rc'] == '2') ? "Y" : "N";
            $issue_dol = ($data['issue'] != '' ? '[' . $data['issue'] . ']' : '""');
            $status_chk = $request->status;
            $setting_dol = ($data['setting'] != '' ? '[' . $data['setting'] . ']' : '""');
            $web_view_status = (int)$data['web_view'];
            if ($data['api'] == 'draft') {
                $api_status = 0;
            } else {
                $api_status = 1;
            }

            $getdata = ListMedia::where('id', $request['id'])->first();
        
            if ($getdata->getMedia('cover_desktop')->isNotEmpty()) {
                $link_tumnail = url($getdata->getMedia('cover_desktop')->first()->getUrl());
            } elseif (!empty($getdata['image_path'])) {
                $link_tumnail = ENV('APP_URL') . "/" . $getdata['image_path'];
               
            } elseif (!empty($getdata['thumbnail_address'])) {
                $link_tumnail = ENV('APP_URL') . "/mediadol/" . $getdata['UploadFileID'] . '/' . $getdata['thumbnail_address'];
            } else {
                $link_tumnail = $json_decode_update->ThumbnailAddress;
            }



            // $item->update($data_update);

            $status = DB::table('list_media')->where('id', $request['id'])
                ->update($data_update);

            // if ($request->hasFile('cover_desktop')) {
            //     $item->clearMediaCollection('cover_desktop');
            //     $item->addMedia($request->file('cover_desktop'))->toMediaCollection('cover_desktop');
            //     $item->update(['image_path' => $item->getFirstMediaUrl('cover_desktop', 'thumb1024x618')]);
            //     //dd($item->getFirstMediaUrl('cover_desktop','thumb1024x618'));
            // }


            if ($data['interesting_issues'] == 2) {

                $check_data = Article::select('id')
                    ->where('dol_UploadFileID', '=',$item->UploadFileID)
                    ->where('page_layout', '=', 'interesting_issues')
                    ->first();


                    return $response = ([
                        'test' => $check_data
                    ]);

                $data_article['page_layout'] = 'interesting_issues';
                $data_article['title'] = $data['title'];
                $data_article['description'] = $data['description'];
                $data_article['short_description'] = strip_tags($data['description']);
                $data_article['dol_cover_image'] = ($item->image_path != '' ? $item->image_path : $data['ThumbnailAddress']);
                $data_article['dol_UploadFileID'] =$item->UploadFileID;
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
                    Article::where('dol_UploadFileID', '=',$item->UploadFileID)
                        ->where('page_layout', '=', 'interesting_issues')
                        ->update($data_article);
                }

                //dd($data['UploadFileID'],$check_data);

            } else {
                Article::where('dol_UploadFileID', '=', $item->UploadFileID)
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

            $this->update_persona_data(
                $request['id'],
                $data['title'],
                $media_dol,
                $media_learning,
                $media_thrc,
                $data['description'],
                !empty($link_tumnail) ? $link_tumnail : "",
                $item->template,
                isset($json_decode_update->FileAddress) ? "https://resourcecenter.thaihealth.or.th/mediadol/".$item->UploadFileID."/".$json_decode_update->FileAddress : "",
                $issue_dol,
                $data['target'],
                $setting_dol,
                $item->sex,
                $item->age,
                $status_chk,
                $web_view_status,
                $api_status
            );

            // $response = array();
            $response['status'] = 200;
            $response['data_Description'] = [
                'Code' => '00',
            ];
            
            return  Response::json($response ,200);
        } else {

           
        $idchk = $request->id;
        $status_chk = $request->status;
        $chk_img = ListMedia::where('id', $request->id)->first();



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

        if (!empty($chk_img['file_thumbnail_link'])) {
            $tmp['ThumbnailAddress'] = $chk_img['file_thumbnail_link'];
        }
        $CoverPath_Array = json_decode($chk_img['json_data']);
        if (property_exists($CoverPath_Array, 'CoverPath')) {
            $tmp['CoverPath'] = $CoverPath_Array->CoverPath;
        } else {
            $tmp['CoverPath'] = null;
        }
        
        $tmp_new = $tmp;
        //dd($tmp_new);
        // update data to dol
        $list_data = $this->updol_media($tmp);
        //dd($list_data);

        // $item = ListMedia::findOrFail($request['id']);
        // dd(json_decode($item->json_data));

        //dd($data);
        //$settings = Setting::select('value','slug')->whereIn('slug',['knowledges','media_campaign'])->get()->pluck('value','slug');
        $item = ListMedia::findOrFail($request['id']);
        $json_decode_update  = json_decode($item->json_data);
        $data = $request->all();

        // โหลดไฟล์สื่อและปกจาก DOL
        DownloadFileFromDol::dispatch($item->id, $item->UploadFileID);
        
        // $this->GetMeidaFileFromDol($item->id, $item->UploadFileID);
       
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
        $array['CoverPath'] = isset($json_decode_update->CoverPath) ? $json_decode_update->CoverPath : "";
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
                CURLOPT_URL => 'https://dol.thaihealth.or.th/WCF/DOLService.svc/json/GetMediaDol',
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
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
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

        //$curl = curl_init();

        //$media_dol = (isset($data['show_dol']) && $data['show_dol'] == '2') ? "Y" : "N";
        //$media_learning = (isset($data['show_learning']) && $data['show_learning'] == '2') ? "Y" : "N";
        //$media_thrc = (isset($data['show_rc']) && $data['show_rc'] == '2') ? "Y" : "N";

        //$today = date('Y-m-d');

        //if ($data['start_date'] != "" && $data['start_date'] > $today) {
        //    $media_dol = 'N';
        //    $media_learning  = 'N';
        //    $media_thrc = 'N';
        //}

        //if ($data['end_date'] != "" && $today > $data['end_date']) {
        //    $media_dol = 'N';
        //    $media_learning  = 'N';
        //    $media_thrc = 'N';
        //}


        // dd([$media_dol, $media_learning]);

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://persona.thaihealth.or.th/api/thrc/update_media_from_thrc',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //             "media_id": ' . $request['id'] . ',
        //             "media_title": "' . $data['title'] . '",
        //             "media_dol": "' . $media_dol . '",
        //             "media_learning": "' . $media_learning . '",
        //             "media_thrc": "' . $media_thrc . '",
        //             "media_short_description": "' . str_replace("'", "", $data['description']) . '",
        //             "media_thumbnail_address": "' . $json_decode_update->ThumbnailAddress . '",
        //             "media_thumbnail_address_change": "",
        //             "media_type": "' . $item->template . '",
        //             "media_file_path": "' . $data['FileAddress'] . '",
        //             "Issues": "' . $issue_dol . '",
        //             "Targets": "[' . $data['target'] . ']",
        //             "Settings": "' . $setting_dol . '",
        //             "Sex": "[' . $item->sex . ']",
        //             "Ages": "[' . $item->age . ']"
        //         }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);

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
        $data_json_update_media = 02;
        $check_status_update = 02;
        // $data_json_update_media = $list_data['Code'];
        // $check_status_update = $list_data['Code'];
        $data_Description = $list_data;


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

        if ($data['term'] == "undefined") {
            $data['term'] = null;
        }

        if ($data['term'] == '50') {
            $SendMediaTermStatus = 50;
        } elseif ($data['term'] == '49') {
            $SendMediaTermStatus = 49;
        } else {
            $SendMediaTermStatus = 52;
        }
        if(!empty($data['show_dol'])){
            if ($data['show_dol'] == 2) {
                $SendMediaTermStatus = 51;
            }
            
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
        if ($data['term'] != 'undefined') {
            $data_update['SendMediaTermStatus'] = $SendMediaTermStatus;
        }
        if ($data['not_term'] != 'undefined') {
            $data_update['SendMediaTermComment'] = $data['not_term'];
        }
        if ($data['detail_not_trem'] != 'undefined') {
            $data_update['detail_not_term'] = $data['detail_not_trem'];
        }
        // not reason 
        // $data_update['research_not_publish_reason'] = $data['research_not_publish_reason'];
        // $data_update['stat_not_publish_reason'] = $data['stat_not_publish_reason'];
        // $data_update['knowledge_not_publish_reason'] = $data['knowledge_not_publish_reason'];   
        // $data_update['campaign_not_publish_reason'] = $data['campaign_not_publish_reason'];
        // $data_update['interesting_issues_not_publish_reason'] = $data['interesting_issues_not_publish_reason'];

        if (!empty($request['chack_show_data'])) {
            ListMedia::where('show_data', 1)->update([
                'show_data' => null
            ]);
        }
        // dd($request['chack_show_data']);
        $data_update['show_data'] = $request['chack_show_data'];

        $item->update($data_update);
        if ($request->hasFile('cover_desktop')) {
            $item->clearMediaCollection('cover_desktop');
            $time = carbon::now()->format('His');
            $rename = $request->id."-".$time.".".$request->file('cover_desktop')->getClientOriginalExtension();
            
            $item->addMedia($request->file('cover_desktop'))->usingFileName($rename)->toMediaCollection('cover_desktop');
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
        //try {
        //     /*Login*/
        //     $body = '{"username":"' . env('THRC_API_USERNAME') . '","password":"' . env('THRC_API_PASSWORD') . '","device_token":"thrc_backend"}';
        //     //dd($body);
        //     $client = new \GuzzleHttp\Client();
        //     $request = $client->request('POST', env('THRC_URL_API') . env('THRC_URL_API_LOGIN'), [
        //         'headers' => [
        //             'Content-Type' => 'application/json; charset=utf-8'
        //         ],
        //         'body' => $body
        //     ]);
        //     $response_api = $request->getBody()->getContents();
        //     $data_json = json_decode($response_api);
        //
        //     //dd($data_json);
        //
        //     if ($data_json->status_code === 200) {
        //
        //         $access_token = $data_json->data->access_token;
        //         //dd($access_token);
        //         $body = '{"device_token":"thrc_backend","media_type":"media","status_media":"' . $data['api'] . '","media":' . json_encode($data_media) . '}';
        //         //dd($body,$media_json_data_new,env('THRC_URL_API','https://api.thaihealth.or.th'));
        //         $client = new \GuzzleHttp\Client();
        //         $request = $client->request('POST', env('THRC_URL_API', 'https://api.thaihealth.or.th') . env('THRC_URL_API_UPDATE_MEDIA', '/api/update-media'), [
        //             'headers' => [
        //                 'Content-Type' => 'application/json; charset=utf-8',
        //                 'authorization' => $access_token
        //             ],
        //             'body' => $body
        //         ]);
        //         $response_api = $request->getBody()->getContents();
        //         $data_json = json_decode($response_api);
        //         //dd($data_json);
        //     }
        //     /* End Api */
        //} catch (\Throwable $th) {
        //    //throw $th;
        //}
        //       



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
        if (strpos($data_Description, "ProjectCode") === false) {
            $DolErroeDetail  = $data_Description;
        } else {
            $DolErroeDetail = "ค่าของ ProjectCode ถูกแนบกับเล่มรายงานแล้วไม่สามารถเปลี่ยนแปลงได้";
        }
        $response['data_Description'] = str_replace([" ", "﻿"], "", $DolErroeDetail);


        //$response['data_dol_new'] =$data_dol_new;
        //$response['body'] =$body_update;
        //$response['array'] =$array;

 


        //$response['file'] =$item->image_path;
        //$response['new_json'] =$new_json;


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



        if ($media_dol == 'N') {
            $status_chk = 'draft';
        }
        $getdata = ListMedia::where('id', $idchk)->first();
        
        if ($getdata->getMedia('cover_desktop')->isNotEmpty()) {
            $link_tumnail = url($getdata->getMedia('cover_desktop')->first()->getUrl());
        } else
        if (!empty($getdata['image_path'])) {
            $link_tumnail = ENV('APP_URL') . "/" . $getdata['image_path'];
           
        } else
        if (!empty($getdata['thumbnail_address'])) {
            $link_tumnail = ENV('APP_URL') . "/mediadol/" . $getdata['UploadFileID'] . '/' . $getdata['thumbnail_address'];
        } else {
            $link_tumnail = $json_decode_update->ThumbnailAddress;
        }

        if ($data['api'] == 'draft') {
            $api_status = 0;
        } else {
            $api_status = 1;
        }
        $web_view_status = (int)$data['web_view'];

        if (is_null($getdata->local_path)) {
            $FileAddress = $data['FileAddress'];
        } else {
            $FileAddress = url('mediadol' . '/' . $getdata->UploadFileID . '/' . $getdata->local_path);
        }


        if (is_null($getdata->image_path)) {
            $file_dol = null;
        } else {
            $file_dol = url($getdata->image_path);
        }
       
        $web_view_status = (int)$data['web_view'];
        $this->update_persona_data(
            $idchk,
            $data['title'],
            $media_dol,
            $media_learning,
            $media_thrc,
            $data['description'],
            $link_tumnail,
            $item->template,
            $FileAddress,
            $issue_dol,
            $data['target'],
            $setting_dol,
            $item->sex,
            $item->age,
            $status_chk,
            $web_view_status,
            $api_status
        );

        if (!empty($file_dol)) {
            $tmp_new['CoverPath'] =  $file_dol;
        }
        $tmp_new['not_term'] = $getdata->SendMediaTermComment;
        $tmp_new['detail_not_trem'] = $getdata->detail_not_term;
        $tmp_new['chack_persona'] = $media_dol;

        $this->updol_media($tmp_new);

        return  Response::json($response, 200);
     }
    }

    public function update_persona_data($idchk, $title, $media_dol, $media_learning, $media_thrc, $description, $link_tumnail, $template, $FileAddress, $issue_dol, $target, $setting_dol, $sex, $age, $status_chk, $web_view_status, $api_status)
    {


        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://persona.thaihealth.or.th/api/thrc/update_media_from_thrc',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{
        //             "media_id": ' . $idchk . ',
        //             "media_title": "' . $title. '",
        //             "media_dol": "' . $media_dol . '",
        //             "media_learning": "' . $media_learning . '",
        //             "media_thrc": "' . $media_thrc . '",
        //             "media_short_description": "' .$description. '",
        //             "media_thumbnail_address":"'.$link_tumnail.'",
        //             "media_thumbnail_address_change": "",
        //             "media_type": "' . $template . '",
        //             "media_file_path": "' . $FileAddress . '",
        //             "Issues": "' . $issue_dol . '",
        //             "Targets": "[' . $target . ']",
        //             "Settings": "' . $setting_dol . '",
        //             "Sex": "[' . $sex . ']",
        //             "Ages": "[' . $age . ']",
        //             "media_status": "' . $status_chk. '"
        //         }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json'
        //     ),
        // ));


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://persona.thaihealth.or.th/api/thrc/update_media_from_thrc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'media_id' => $idchk,
                'media_title' => $title,
                'media_dol' =>  $media_dol,
                'media_learning' => $media_learning,
                'media_thrc' =>  $media_thrc,
                'media_short_description' => $description,
                'media_thumbnail_address' => $link_tumnail,
                'media_thumbnail_address_change' => 'null',
                'media_type' =>  $template,
                'media_file_path' => $FileAddress,
                'Issues' =>  $issue_dol,
                'Targets' => "[$target]",
                'Settings' => $setting_dol,
                'Sex' => "[$sex]",
                'Ages' => "[$age]",
                'media_status' => $status_chk,
                'web_view_status' => $web_view_status,
                'api_status' => $api_status
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);



        $resArr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',  $response), true);
        // dd($resArr);
        return $resArr;
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



    // public function getExcelReport(Request $request)
    // {
    //     ini_set('memory_limit', -1);
    //     ini_set('max_execution_time', 0);
    //     set_time_limit(0);
    //     $input = $request->all();
    //     $date_now = date('Y-m-d-H-i-s');
    //     $file_name = 'listmedia-api&webview' . "-" . $date_now;
    //     $type = 'xlsx';
    //     $items = ListMedia::Data3(['request' => $request->all()]);

    //     //dd($items);

    //     return Excel::create($file_name, function ($excel) use ($items) {
    //         $excel->sheet('mySheet', function ($sheet) use ($items) {
    //             //$sheet->fromArray($items,null, 'A1', true);
    //             $sheet->row(1, array(
    //                 'id',
    //                 'title',
    //                 'template',
    //                 'webview_status',
    //                 'api_status',
    //                 'status',
    //                 'not_publish_reason',
    //                 'format',
    //                 'file_address',
    //                 'json_data',
    //                 'sex',
    //                 'age',
    //                 'keyword',
    //                 'issue',
    //                 'target',
    //                 'setting',
    //                 'department',
    //                 'show_rc',
    //                 'show_dol',
    //                 'show_learning',
    //                 'articles_research',
    //                 'research_not_publish_reason',
    //                 'include_statistics',
    //                 'stat_not_publish_reason',
    //                 'knowledges',
    //                 'knowledge_not_publish_reason',
    //                 'media_campaign',
    //                 'campaign_not_publish_reason',
    //                 'interesting_issues',
    //                 'interesting_issues_not_publish_reason',
    //                 'created_at',
    //                 'updated_at',
    //                 'created_by',
    //                 'updated_by',
    //                 'SendMediaTermStatus',
    //                 'url'
    //             ));


    //             $chunkSize = 1000;
    //             $chunks = $items->chunk($chunkSize);
    //             $index = 2;
    //             foreach ($chunks as $chunk) {
    //                 foreach ($chunk as $value) {
    //                 // dd($value->knowledges);
    //                 //dd($key,$value);
    //                 $json_data = json_decode($value->json_data);

    //                 $Issues_array = [];
    //                 if (isset($json_data->Issues)) {
    //                     foreach ($json_data->Issues as $value_issues) {
    //                         //dd($value_issues);
    //                         if (isset($value_issues->Name)) {
    //                             array_push($Issues_array, $value_issues->Name);
    //                         }
    //                     }
    //                 }

    //                 $Targets_array = [];
    //                 if (isset($json_data->Targets)) {
    //                     foreach ($json_data->Targets as $value_targets) {
    //                         //dd($value_issues);
    //                         if (isset($value_targets->Name)) {
    //                             array_push($Targets_array, $value_targets->Name);
    //                         }
    //                     }
    //                 }
    //                 $Settings_array = [];
    //                 if (isset($json_data->Settings)) {
    //                     foreach ($json_data->Settings as $value_settings) {
    //                         //dd($value_issues);
    //                         if (isset($value_settings->Name)) {
    //                             array_push($Settings_array, $value_settings->Name);
    //                         }
    //                     }
    //                 }

    //                 $sex_text = [];
    //                 //dd(gettype($value->sex));
    //                 if ($value->sex != '' && gettype(json_decode($value->sex)) == 'array') {
    //                     //dd(gettype(json_decode($value->sex)));
    //                     foreach (json_decode($value->sex) as $value_sex) {
    //                         //dd($value_issues);
    //                         $text = '';
    //                         if ($value_sex == 1) {
    //                             $text = 'ชาย';
    //                         }
    //                         if ($value_sex == 2) {
    //                             $text = 'หญิง';
    //                         }
    //                         if ($value_sex == 3) {
    //                             $text = 'หลากหลายทางเพศ';
    //                         }
    //                         array_push($sex_text, $text);
    //                     }
    //                 }
    //                 $age_text = [];
    //                 if ($value->age != '' && gettype(json_decode($value->age)) == 'array') {
    //                     foreach (json_decode($value->age) as $value_age) {
    //                         //dd($value_issues);
    //                         $text = '';
    //                         if ($value_age == 4) {
    //                             $text = 'เยาวชน(15–24ปี)';
    //                         }
    //                         if ($value_age == 13) {
    //                             $text = 'ปฐมวัย(0–5ปี)';
    //                         }
    //                         if ($value_age == 19) {
    //                             $text = 'ผู้สูงอายุ(60ปีขึ้นไป)';
    //                         }
    //                         if ($value_age == 24) {
    //                             $text = 'วัยเรียน(6–12ปี)';
    //                         }
    //                         if ($value_age == 25) {
    //                             $text = 'วัยทำงาน(15-59ปี)';
    //                         }
    //                         if ($value_age == 26) {
    //                             $text = 'วัยรุ่น(13–15ปี)';
    //                         }
    //                         array_push($age_text, $text);
    //                     }
    //                 }
    //                 if ($value->SendMediaTermStatus == '49') {
    //                     $term = "สื่อวาระกลาง(อยู่ระหว่างพิจารณา)";
    //                 } elseif ($value->SendMediaTermStatus == '50') {
    //                     $term = "สื่อวาระกลาง";
    //                 } else {
    //                     $term = "ไม่เป็นสื่อวาระกลาง";
    //                 }

    //                 $sheet->row($index++, array(
    //                     $value->id,
    //                     $value->title,
    //                     $value->template,
    //                     ($value->web_view == 1 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->api == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->status == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->status == 'publish' ? '-' : $value->not_publish_reason),
    //                     (isset($json_data->Format) ? $json_data->Format : ''),
    //                     (isset($json_data->FileAddress) ? $json_data->FileAddress : ''),
    //                     $value->json_data,
    //                     implode(",", $sex_text),
    //                     implode(",", $age_text),
    //                     (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''),
    //                     implode(",", $Issues_array),
    //                     implode(",", $Targets_array),
    //                     implode(",", $Settings_array),
    //                     (isset($json_data->DepartmentName) ? $json_data->DepartmentName : ''),
    //                     $value->show_rc,
    //                     $value->show_dol,
    //                     $value->show_learning,
    //                     ($value->articles_research == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->articles_research == 2 ? '-' : $value->research_not_publish_reason),
    //                     ($value->include_statistics == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->include_statistics == 2 ? '-' : $value->stat_not_publish_reason),
    //                     ($value->knowledges == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->knowledges == 2 ? '-' : $value->knowledge_not_publish_reason),
    //                     ($value->media_campaign == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->media_campaign == 2 ? '-' : $value->campaign_not_publish_reason),
    //                     ($value->interesting_issues == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
    //                     ($value->interesting_issues == 2 ? '-' : $value->interesting_issues_not_publish_reason),
    //                     $value->created_at,
    //                     $value->updated_at,
    //                     $value->created_by,
    //                     $value->updated_by,
    //                     $term,
    //                     route('media2-detail', base64_encode($value->id))
    //                 ));
    //                 }
    //             }
    //         });
    //     })->download($type);
    //     //dd("Get Excel Report");
    // }

    public function getExcelReport()
    {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        // $input = $request->all();
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'listmedia-api&webview';
        $type = 'xlsx';
        $items = ListMedia::select(
            'id',
            'title',
            'json_data',
            'template',
            'UploadFileID',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
            'api',
            'web_view',
            'tags',
            'sex',
            'age',
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_rc'),
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_dol'),
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
            'SendMediaTermStatus',
            'media_trash'
        );
        $items->where('show_rc', '=', '2');
        $items->wherenull('media_trash');
        $items->orwhere('show_dol', '!=', '1');
        // $items->wherenull('media_trash');
        // $items->orwhere('status','publish');
        // $items->wherenull('media_trash');
        // $items->orWhere('web_view','=',1);
        // $items->wherenull('media_trash');
        $items->with('createdBy', 'updatedBy');
        $items =  $items->get();
        return Excel::create($file_name, function ($excel) use ($items) {
            $excel->sheet('mySheet', function ($sheet) use ($items) {
                //$sheet->fromArray($items,null, 'A1', true);
                $TotalGetMedia = ListMedia::whereDate('created_at', date("Y-m-d"))->count();
                $TotalPersonaMedia = ListMedia::where('show_dol', 2)->count();
                $TotalThrcMedia = ListMedia::where('show_rc', 2)->orwhere('status', "publish")->count();
                $TotalPassMediaTerm = ListMedia::where('SendMediaTermStatus', 50)->count();
                $TotalNotPassMediaTerm = ListMedia::wherenull('SendMediaTermStatus')->orwhere('SendMediaTermStatus', "=", 52)->count();
                $sheet->row(1, array(
                    'สื่อที่เข้ามาจากDOL' . date('Y-m-d'),
                    $TotalGetMedia . "สื่อ"

                ));
                $sheet->row(2, array(
                    'สื่อที่เผยแพร่บนPersonaHealthทั้งหมด',
                    $TotalPersonaMedia . "สื่อ"

                ));
                $sheet->row(3, array(
                    'สื่อที่เผยแพร่บนResourceCenterทั้งหมด',
                    $TotalThrcMedia . "สื่อ"
                ));
                $sheet->row(4, array(
                    'สื่อวาระกลางทั้งหมด',
                    $TotalPassMediaTerm . "สื่อ"
                ));
                $sheet->row(5, array(
                    'ไม่เป็นสื่อวาระกลางทั้งหมด',
                    $TotalNotPassMediaTerm . "สื่อ"
                ));
                $sheet->row(7, array(
                    // 'id',
                    'ชื่อสื่อ',
                    'ประเภทสื่อ',
                    // 'webview_status',
                    // 'api_status',
                    'สถานะสื่อ',
                    // 'not_publish_reason',
                    // 'format',
                    // 'file_address',
                    // 'json_data',
                    'เพศ',
                    'ช่วงอายุ',
                    'keyword',
                    'ประเด็น',
                    'กลุ่มเป้าหมาย',
                    'กลุ่มพื้นที่',
                    'สำนัก',
                    'สถานะเผยแพร่สื่อบนResourceCenter',
                    'สถานะเผยแพร่สื่อบนPersonaHealth',
                    // 'show_learning',
                    // 'articles_research',
                    // 'research_not_publish_reason',
                    // 'include_statistics',
                    // 'stat_not_publish_reason',
                    // 'knowledges',
                    // 'knowledge_not_publish_reason',
                    // 'media_campaign',
                    // 'campaign_not_publish_reason',
                    // 'interesting_issues',
                    // 'interesting_issues_not_publish_reason',
                    // 'วันที่สร้าง',
                    // 'วันที่แก้ไข',
                    // 'สร้างโดย',
                    // 'updated_by',
                    'SendMediaTermStatus',
                    'ลิงก์สื่อ'
                ));

                $chunkSize = 5000;
                $chunks = $items->chunk($chunkSize);
                $index = 8;

                foreach ($chunks as $chunk) {
                    foreach ($chunk as $value) {

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
                                    // if(!empty($value_targets->Name)){
                                    array_push($Targets_array, $value_targets->Name);
                                    // }

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
                                if (!empty($text)) {
                                    array_push($sex_text, $text);
                                }
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
                                if (!empty($text)) {
                                    array_push($age_text, $text);
                                }
                            }
                        }

                        if ($value->SendMediaTermStatus == '49') {
                            $term = "สื่อวาระกลาง(อยู่ระหว่างพิจารณา)";
                        } elseif ($value->SendMediaTermStatus == '50') {
                            $term = "สื่อวาระกลาง";
                        } else {
                            $term = "ไม่เป็นสื่อวาระกลาง";
                        }
                        $sheet->row($index++, array(
                            // $value->id,
                            $value->title,
                            $value->template,
                            // ($value->web_view == 1 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->api == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            ($value->status == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->status == 'publish' ? '-' : $value->not_publish_reason),
                            // (isset($json_data->Format) ? $json_data->Format : ''),
                            // (isset($json_data->FileAddress) ? $json_data->FileAddress : ''),
                            // $value->json_data,
                            implode(",", $sex_text),
                            implode(",", $age_text),
                            (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''),
                            implode(",", $Issues_array),
                            implode(",", $Targets_array),
                            implode(",", $Settings_array),
                            (isset($json_data->DepartmentName) ? $json_data->DepartmentName : ''),
                            $value->show_rc,
                            $value->show_dol,
                            // $value->show_learning,
                            // ($value->articles_research == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->articles_research == 2 ? '-' : $value->research_not_publish_reason),
                            // ($value->include_statistics == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->include_statistics == 2 ? '-' : $value->stat_not_publish_reason),
                            // ($value->knowledges == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->knowledges == 2 ? '-' : $value->knowledge_not_publish_reason),
                            // ($value->media_campaign == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->media_campaign == 2 ? '-' : $value->campaign_not_publish_reason),
                            // ($value->interesting_issues == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->interesting_issues == 2 ? '-' : $value->interesting_issues_not_publish_reason),
                            // $value->created_at,
                            // $value->updated_at,
                            // $value->created_by,
                            // $value->updated_by,
                            $term,
                            route('media2-detail', base64_encode($value->id))
                        ));
                    }
                }
            });
        })->store('xlsx', storage_path('excel/exports'));
        //dd("Get Excel Report");
    }

    public function getExcelReport_test()
    {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        // $input = $request->all();
        $date_now = date('Y-m-d-H-i-s');
        $file_name = 'listmedia-api&webview';
        $type = 'xlsx';
        $items = ListMedia::select(
            'id',
            'title',
            'json_data',
            'template',
            'UploadFileID',
            'SendMediaTermComment',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
            'api',
            'web_view',
            'tags',
            'sex',
            'age',
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_rc'),
            DB::raw('IF(show_rc=2,"เผยแพร่","ไม่เผยแพร่") as show_dol'),
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
            'SendMediaTermStatus',
            'media_trash'
        );
        $items->where('show_rc', '=', '2');
        $items->wherenull('media_trash');
        $items->orwhere('show_dol', '!=', '1');
      //  $items->where('SendMediaTermStatus', '!=', 'null');
       // $items->where('created_at', '>=', '2022-10-01 00:00:00');
       // $items->where('created_at', '<=', '2023-06-12 23:59:00');
        // $items->wherenull('media_trash');
        // $items->orwhere('status','publish');
        // $items->wherenull('media_trash');
        // $items->orWhere('web_view','=',1);
        // $items->wherenull('media_trash');
        $items->with('createdBy', 'updatedBy');
        $items =  $items->get();
        //dd($items);
        return Excel::create($file_name, function ($excel) use ($items) {
            $excel->sheet('mySheet', function ($sheet) use ($items) {
                //$sheet->fromArray($items,null, 'A1', true);
                $TotalGetMedia = ListMedia::whereDate('created_at', date("Y-m-d"))->count();
                $TotalPersonaMedia = ListMedia::where('show_dol', 2)->count();
                $TotalThrcMedia = ListMedia::where('show_rc', 2)->orwhere('status', "publish")->count();
                $TotalPassMediaTerm = ListMedia::where('SendMediaTermStatus', 50)->count();
                $TotalNotPassMediaTerm = ListMedia::wherenull('SendMediaTermStatus')->orwhere('SendMediaTermStatus', "=", 52)->count();
                $sheet->row(1, array(
                    'สื่อที่เข้ามาจากDOL' . date('Y-m-d'),
                    $TotalGetMedia . "สื่อ"

                ));
                $sheet->row(2, array(
                    'สื่อที่เผยแพร่บนPersonaHealthทั้งหมด',
                    $TotalPersonaMedia . "สื่อ"

                ));
                $sheet->row(3, array(
                    'สื่อที่เผยแพร่บนResourceCenterทั้งหมด',
                    $TotalThrcMedia . "สื่อ"
                ));
                $sheet->row(4, array(
                    'สื่อวาระกลางทั้งหมด',
                    $TotalPassMediaTerm . "สื่อ"
                ));
                $sheet->row(5, array(
                    'ไม่เป็นสื่อวาระกลางทั้งหมด',
                    $TotalNotPassMediaTerm . "สื่อ"
                ));
                $sheet->row(7, array(
                    // 'id',
                    'ชื่อสื่อ',
                    'ประเภทสื่อ',
                    'webview_status',
                    'api_status',
                    'สถานะเผยแพร่สื่อบนPersonaHealth',
                    'เผยแพร่/ไม่เผยแพร่',
                    '(เหตุผลไม่เผยแพร่)',
                    'ที่อยู่สื่อ',
                    'เจ้าของสื่อ',
                    'เพศ',
                    'ช่วงอายุ',
                    'keyword',
                    'ประเด็น',
                    'กลุ่มเป้าหมาย',
                    'กลุ่มพื้นที่',
                    'วันที่และเวลาส่งสื่อ',
                    'ผู้ส่งสื่อ',
                    'วันที่และเวลาแก้ไข',
                    'ผู้แก้ไขสื่อล่าสุด',
                    // 'format',
                    // 'file_address',
                    // 'json_data',
                    // 'สำนัก',
                    // 'สถานะเผยแพร่สื่อบนResourceCenter',
                    // 'show_learning',
                    // 'articles_research',
                    // 'research_not_publish_reason',
                    // 'include_statistics',
                    // 'stat_not_publish_reason',
                    // 'knowledges',
                    // 'knowledge_not_publish_reason',
                    // 'media_campaign',
                    // 'campaign_not_publish_reason',
                    // 'interesting_issues',
                    // 'interesting_issues_not_publish_reason',
                    // 'วันที่สร้าง',
                    // 'วันที่แก้ไข',
                    // 'สร้างโดย',
                    // 'updated_by',
                    // 'SendMediaTermStatus',
                    // 'ลิงก์สื่อ'
                ));

                $chunkSize = 5000;
                $chunks = $items->chunk($chunkSize);
                $index = 8;

                foreach ($chunks as $chunk) {
                    foreach ($chunk as $value) {
                      //  dd($value);

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
                                    // if(!empty($value_targets->Name)){
                                    array_push($Targets_array, $value_targets->Name);
                                    // }

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
                                if (!empty($text)) {
                                    array_push($sex_text, $text);
                                }
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
                                if (!empty($text)) {
                                    array_push($age_text, $text);
                                }
                            }
                        }

                        if ($value->SendMediaTermStatus == '49') {
                            $term = "สื่อวาระกลาง(อยู่ระหว่างพิจารณา)";
                        } elseif ($value->SendMediaTermStatus == '50') {
                            $term = "สื่อวาระกลาง";
                        } else {
                            $term = "ไม่เป็นสื่อวาระกลาง";
                        }
                        $sheet->row($index++, array(
                            // $value->id,
                            $value->title,
                            $value->template,
                            ($value->web_view == 1 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            ($value->api == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            $value->show_rc,
                            $term,
                            $value->SendMediaTermComment,
                            (isset($json_data->FileAddress) ? $json_data->FileAddress : ''),
                            (isset($json_data->DepartmentName) ? $json_data->DepartmentName : ''),
                            implode(",", $sex_text),
                            implode(",", $age_text),
                            (isset($json_data->Keywords) ? implode(",", $json_data->Keywords) : ''),
                            implode(",", $Issues_array),
                            implode(",", $Targets_array),
                            implode(",", $Settings_array),
                            $value->created_at,
                            $value->created_by,
                            $value->updated_at,
                            $value->updated_by
                            // ($value->status == 'publish' ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->status == 'publish' ? '-' : $value->not_publish_reason),
                            // (isset($json_data->Format) ? $json_data->Format : ''),
                            // $value->json_data,
                            // implode(",", $Issues_array),
                            // implode(",", $Targets_array),
                            // implode(",", $Settings_array),
                            // $value->show_rc,
                            // $value->show_dol,
                            // $value->show_learning,
                            // ($value->articles_research == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->articles_research == 2 ? '-' : $value->research_not_publish_reason),
                            // ($value->include_statistics == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->include_statistics == 2 ? '-' : $value->stat_not_publish_reason),
                            // ($value->knowledges == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->knowledges == 2 ? '-' : $value->knowledge_not_publish_reason),
                            // ($value->media_campaign == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->media_campaign == 2 ? '-' : $value->campaign_not_publish_reason),
                            // ($value->interesting_issues == 2 ? 'เผยแพร่' : 'ไม่เผยแพร่'),
                            // ($value->interesting_issues == 2 ? '-' : $value->interesting_issues_not_publish_reason),
                            // $value->created_at,
                            // $value->updated_at,
                            // $value->created_by,
                            // $value->updated_by,
                            // $term,
                            // route('media2-detail', base64_encode($value->id))
                        ));
                    }
                }
            });
        })->store('xlsx', storage_path('excel\exports\listmedia-api&webviewtest'));
        //dd("Get Excel Report");
    }

    public function DonwLoadExcelReport_test()
    {
        $filePath = storage_path('excel\exports\listmedia-api&webviewtest\listmedia-api&webview.xlsx');
        if (!File::exists($filePath)) {
            // You can optionally log this error or return a different response
            abort(404, 'File not found');
        }
        return response()->download($filePath);
    }

    public function DonwLoadExcelReport()
    {
        $filePath = storage_path('excel\exports\listmedia-api&webview.xlsx');
        if (!File::exists($filePath)) {
            // You can optionally log this error or return a different response
            abort(404, 'File not found');
        }
        return response()->download($filePath);
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
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
    public function recycletrashall(Request $request)
    {

        $data = ListMedia::Where('media_trash', '=', 'Y')->get();

        foreach ($data as $row) {
            $update = ListMedia::Where('id', '=', $row->id)->first();
            $update->media_trash = null;
            $update->save();
        }
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
    public function delecttrashall(Request $request)
    {
        $data = ListMedia::Where('media_trash', '=', 'Y')->get();
        foreach ($data as $row) {
            $update = ListMedia::Where('id', '=', $row->id)->first();
            $update->media_trash = 'P';
            $update->save();
        }
        return response()->json(['status' => true]);
    }

    public function job_user_connect()
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.connect-x.tech/connectx/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
         "email": "thaihealth-api@connect-x.tech",
         "password": "ab2cebadecfd0bb638288a88e60bba27d66199b9"
       }
       ',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        $data_token = json_decode($response);

        curl_close($curl);
        $user_data = User::whereNotNull('phone')->get();
        $total_data = count($user_data);
        $loop = 1;
        $error = [];
        foreach ($user_data as $row) {

            try {

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.connect-x.tech/connectx/api/customer?externalId=cx_mobilePhone',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PATCH',
                    CURLOPT_POSTFIELDS => '{
                    "cx_mobilePhone":"' . $row->phone . '",
                    "cx_Name": "' . $row->name . '",
                    "cx_birthDate": "' . $row->date_of_birth . '",
                    "cx_email" : "' . $row->facebook_id . '",
                    "organizeId": "sT7oVQc4gutP3hghRo4P"
                    }
                    ',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $data_token->access_token,
                        'Content-Type: application/json'
                    ),
                ));

                $loop++;
                $response = curl_exec($curl);
                $arr[] = $response;
                curl_close($curl);
                echo $response . "\n";
                echo "success" . "\n";
            } catch (\Throwable $th) {
                echo "failed";
                $error[] = $row->phone;
                continue;
            }
        }
        $count_data = count($arr);
        return  "Total Send User Data" . $count_data . "of" . $total_data . "Error Total offset 90 = " . count($error);
    }

    public function MediaLinkGenerateByID(Request $req)
    {
        $id = $req['id'];
        if (!empty($id)) {
            $id = explode(',', $id);
            $URL_ARR = [];
            foreach ($id as $key => $value) {
                $HashID = Hashids::encode($value);
                $URL = env('APP_URL') . '/media/' . $HashID;
                $URL_ARR[$value] = $URL;
            }

            return $URL_ARR;
        } else {
            return null;
        }
    }
}
