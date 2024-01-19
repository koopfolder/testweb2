@inject('request', 'Illuminate\Http\Request')
@php
    $target =ThrcHelpers::getTarget($request->all());
    $issue =  ThrcHelpers::getIssue($request->all());
    $template =  ThrcHelpers::getTempalte($request->all());
    $setting =  ThrcHelpers::getDolSetting($request->all());
    //dd(Request::url());
    $time_cache  = ThrcHelpers::time_cache(15);
    //echo $data['old']['issue'];
    //dd($data);
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\AreaController', 'getFrontArea'))   
    @php
        //$area = App\Modules\Api\Http\Controllers\AreaController::getFrontArea($request->all());  
        //dd($area);
    @endphp
@endif
<section class="row wow fadeInDown row_search_inside">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-2 head_avsearch">
                    <h1><span>ค้นหา</span><span style="margin-top: 5px;">ความรอบรู้</span><span style="margin-top: 5px;">ด้านสุขภาพ</span></h1>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10 wrap_searchinside">
                    {{ Form::open(['url' =>Request::url(),'method' =>'get','id'=>'form_search']) }}
                    <div class="searchbox_inside">
                            <div class="input_seach">{{ Form::text('keyword',isset($data['old']['keyword']) ? $data['old']['keyword']:'',['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50']) }}</div>
                            <div class="selectbox">
                                <select name="issue">
                                    <option value="0">ประเด็น</option>
                                    <option value="5" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '5' ? 'selected':''):'') }} >แอลกอฮอล์</option>
                                    <option value="28" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '28' ? 'selected':''):'') }} >บุหรี่</option>
                                    <option value="39" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '39' ? 'selected':''):'') }} >อาหาร</option>
                                    <option value="18" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '18' ? 'selected':''):'') }} >กิจกรรมทางกาย</option>
                                    <option value="41" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '41' ? 'selected':''):'') }} >อุบัติเหตุ</option>
                                    <option value="37" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '37' ? 'selected':''):'') }} >เพศ เช่น ท้องไม่พร้อม</option>
                                    <option value="34" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '34' ? 'selected':''):'') }} >สุขภาพจิต</option>
                                    <option value="35,36" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '35,36' ? 'selected':''):'') }} >ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)</option>
                                    <option value="27,33,49" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '27,33,49' ? 'selected':''):'') }} >สิ่งแวดล้อม</option>
                                    <option value="16,21,32,42" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == '16,21,32,42' ? 'selected':''):'') }} >อื่นๆ</option>
                                </select>
                            </div><div class="selectbox">
                                {{ Form::select('template',$template,(isset($data['old']['template']) ? $data['old']['template']:'')) }}
                            </div><div class="selectbox">
                                @php
                                    //dd($data['old']['target']);
                                @endphp
                                <select name="target">
                                    <option value="0">กลุ่มเป้าหมาย</option>
                                    <option value="13" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '13' ? 'selected':''):'') }} >ปฐมวัย</option>
                                    <option value="24" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '24' ? 'selected':''):'') }}>วัยเรียน</option>
                                    <option value="4,26" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '4,26' ? 'selected':''):'') }}>วัยรุ่น</option>
                                    <option value="25" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '25' ? 'selected':''):'') }}>วัยทำงาน</option>
                                    <option value="19" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '19' ? 'selected':''):'') }}>ผู้สูงอายุ</option>
                                    <option value="6" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '6' ? 'selected':''):'') }}>ผู้ด้อยโอกาส</option>               
                                    <option value="1" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '1' ? 'selected':''):'') }}>ChangeAgent</option>
                                    <option value="15,20,28,29" {{ (isset($data['old']['target']) ? ($data['old']['target'] == '15,20,28,29' ? 'selected':''):'') }}>ทุกช่วงวัย</option>
                                </select>
                            </div><div class="selectbox">
                                <select name="setting">
                                    <option value="0">พื้นที่</option>
                                    <option value="9" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '9' ? 'selected':''):'') }} >ศูนย์พัฒนาเด็กเล็ก</option>
                                    <option value="3,16" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '3,16' ? 'selected':''):'') }} >ครอบครัว</option>
                                    <option value="1" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '1' ? 'selected':''):'') }} >โรงเรียน</option>
                                    <option value="4" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '4' ? 'selected':''):'') }} >ชุมชน</option>
                                    <option value="5,6,8,11,13,14,15" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '5,6,8,11,13,14,15' ? 'selected':''):'') }} >องค์กร ภาคสาธารณะ/เอกชน</option>
                                    <option value="2,7,9,12" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '2,7,9,12' ? 'selected':''):'') }} >ระบบบริการสุขภาพ</option>
                                    <option value="3" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '3' ? 'selected':''):'') }} >พฤติกรรมส่วนบุคคล</option>
                                    <option value="16" {{ (isset($data['old']['setting']) ? ($data['old']['setting'] == '16' ? 'selected':''):'') }} >ทุก Setting</option>
                                </select>
                            </div>
                    </div><button class="btn_search_inside">ค้นหา</button>
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