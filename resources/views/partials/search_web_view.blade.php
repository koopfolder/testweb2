@inject('request', 'Illuminate\Http\Request')
@php
    $target =ThrcHelpers::getTargetWebView($request->all());
    //dd($target);
    $issue =  ThrcHelpers::getIssueWebView($request->all());
    //dd($issue);
    $setting =  ThrcHelpers::getDolSettingWebView($request->all());
    $sex =  ThrcHelpers::getSexWebView($request->all());
    //dd($sex,$data);
    $time_cache  = ThrcHelpers::time_cache(15);
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\AreaController', 'getFrontArea'))   
    @php
        //$area = App\Modules\Api\Http\Controllers\AreaController::getFrontArea($request->all());  
        //dd($area);
    @endphp
@endif
<section class="row wow fadeInDown row_search_inside" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-2 head_avsearch">
                    <h1><span>ADVANCE</span>SEARCH</h1>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10 wrap_searchinside">
                    {{ Form::open(['url' => route('media-list-webview'),'method' =>'get','id'=>'form_search']) }}
                    <div class="searchbox_inside">
                            <div class="input_seach">{{ Form::text('keyword',isset($data['old']['keyword']) ? $data['old']['keyword']:'',['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50']) }}</div>
                            <div class="input_seach">{{ Form::number('age',isset($data['old']['age']) ? $data['old']['age']:'',['class'=>'','placeholder'=>'อายุ','maxlength'=>'2']) }}</div>
                            <div class="selectbox">
                                {{ Form::select('sex',$sex,(isset($data['old']['sex']) ? $data['old']['sex']:'')) }}
                            </div><div class="selectbox">
                                <select name="issue">
                                    <option value="0">ประเด็น</option>
                                    @if($issue->count())
                                        @foreach($issue AS $key=>$value)
                                            <option value="{{ $value->issues_id }}"  {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == $value->issues_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div><div class="selectbox">
                                @php
                                    //dd($data['old']['target']);
                                @endphp
                                <select name="target">
                                    <option value="0">กลุ่มเป้าหมาย</option>
                                @if($target->count())
                                    @foreach($target AS $key=>$value)
                                        <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            </div><div class="selectbox">
                                {{ Form::select('setting',$setting,(isset($data['old']['setting']) ? $data['old']['setting']:'')) }}
                            </div>
                    </div><button class="btn_search_inside">ค้นหา</button>
                    {{ Form::hidden('device_token',isset($data['old']['device_token']) ? $data['old']['device_token']:'',['class'=>'']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
</section>
<style>
        optgroup{
            background-color: #002c3e;
        }
        option{
            background-color: #002c3e;
        }
</style>