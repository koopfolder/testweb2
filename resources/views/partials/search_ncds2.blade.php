@inject('request', 'Illuminate\Http\Request')
@php
    //$ncds2 =ThrcHelpers::getNcds2($request->all());
    //dd($ncds2,count($ncds2['categorys']));
    $template =  ThrcHelpers::getTempalte($request->all());
    $time_cache  = ThrcHelpers::time_cache(15);
    //dd(phpinfo());
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\AreaController', 'getFrontArea'))   
    @php
        //$area = App\Modules\Api\Http\Controllers\AreaController::getFrontArea($request->all());  
        //dd($area);
    @endphp
@endif
<section class="row row_search_inside">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 col-lg-3 head_ndssearch">
               <h2>อัพเดท<span>สถาณการณ์ NCDs</span></h2>
            </div>
            <div class="col-12 col-md-6 col-lg-7">
            {{ Form::open(['url' => route('article-update-the-status-here-ncds'),'method' =>'get','id'=>'form_search']) }}
                <div class="searchbox_inside">
                    <div class="input_seach">{{ Form::text('keyword',isset($input['keyword']) ? $input['keyword']:'',['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50']) }}</div>
                            <div class="selectbox">
                                <select name="issue">
                                    <option value="0">ประเด็น</option>
                                    <option value="5" {{ (isset($input['issue']) ? ($input['issue'] == '5' ? 'selected':''):'') }} >แอลกอฮอล์</option>
                                    <option value="28" {{ (isset($input['issue']) ? ($input['issue'] == '28' ? 'selected':''):'') }} >บุหรี่</option>
                                    <option value="39" {{ (isset($input['issue']) ? ($input['issue'] == '39' ? 'selected':''):'') }} >อาหาร</option>
                                    <option value="18" {{ (isset($input['issue']) ? ($input['issue'] == '18' ? 'selected':''):'') }} >กิจกรรมทางกาย</option>
                                    <option value="41" {{ (isset($input['issue']) ? ($input['issue'] == '41' ? 'selected':''):'') }} >อุบัติเหตุ</option>
                                    <option value="37" {{ (isset($input['issue']) ? ($input['issue'] == '37' ? 'selected':''):'') }} >เพศ เช่น ท้องไม่พร้อม</option>
                                    <option value="34" {{ (isset($input['issue']) ? ($input['issue'] == '34' ? 'selected':''):'') }} >สุขภาพจิต</option>
                                    <option value="35,36" {{ (isset($input['issue']) ? ($input['issue'] == '35,36' ? 'selected':''):'') }} >ความสัมพันธ์ (ครอบครัว ชุมชน ปัจจัยแวดล้อม)</option>
                                    <option value="27,33,49" {{ (isset($input['issue']) ? ($input['issue'] == '27,33,49' ? 'selected':''):'') }} >สิ่งแวดล้อม</option>
                                    <option value="16,21,32,42" {{ (isset($input['issue']) ? ($input['issue'] == '16,21,32,42' ? 'selected':''):'') }} >อื่นๆ</option>
                                </select>
                            </div><div class="selectbox">
                                {{ Form::select('template',$template,(isset($input['template']) ? $input['template']:'')) }}
                            </div><div class="selectbox">
                                @php
                                    //dd($input['target']);
                                @endphp
                                <select name="target">
                                    <option value="0">กลุ่มเป้าหมาย</option>
                                    <option value="13" {{ (isset($input['target']) ? ($input['target'] == '13' ? 'selected':''):'') }} >ปฐมวัย</option>
                                    <option value="24" {{ (isset($input['target']) ? ($input['target'] == '24' ? 'selected':''):'') }}>วัยเรียน</option>
                                    <option value="4,26" {{ (isset($input['target']) ? ($input['target'] == '4,26' ? 'selected':''):'') }}>วัยรุ่น</option>
                                    <option value="25" {{ (isset($input['target']) ? ($input['target'] == '25' ? 'selected':''):'') }}>วัยทำงาน</option>
                                    <option value="19" {{ (isset($input['target']) ? ($input['target'] == '19' ? 'selected':''):'') }}>ผู้สูงอายุ</option>
                                    <option value="6" {{ (isset($input['target']) ? ($input['target'] == '6' ? 'selected':''):'') }}>ผู้ด้อยโอกาส</option>               
                                    <option value="1" {{ (isset($input['target']) ? ($input['target'] == '1' ? 'selected':''):'') }}>ChangeAgent</option>
                                    <option value="15,20,28,29" {{ (isset($input['target']) ? ($input['target'] == '15,20,28,29' ? 'selected':''):'') }}>ทุกช่วงวัย</option>
                                </select>
                            </div><div class="selectbox">
                                <select name="setting">
                                    <option value="0">พื้นที่</option>
                                    <option value="9" {{ (isset($input['setting']) ? ($input['setting'] == '9' ? 'selected':''):'') }} >ศูนย์พัฒนาเด็กเล็ก</option>
                                    <option value="3,16" {{ (isset($input['setting']) ? ($input['setting'] == '3,16' ? 'selected':''):'') }} >ครอบครัว</option>
                                    <option value="1" {{ (isset($input['setting']) ? ($input['setting'] == '1' ? 'selected':''):'') }} >โรงเรียน</option>
                                    <option value="4" {{ (isset($input['setting']) ? ($input['setting'] == '4' ? 'selected':''):'') }} >ชุมชน</option>
                                    <option value="5,6,8,11,13,14,15" {{ (isset($input['setting']) ? ($input['setting'] == '5,6,8,11,13,14,15' ? 'selected':''):'') }} >องค์กร ภาคสาธารณะ/เอกชน</option>
                                    <option value="2,7,9,12" {{ (isset($input['setting']) ? ($input['setting'] == '2,7,9,12' ? 'selected':''):'') }} >ระบบบริการสุขภาพ</option>
                                    <option value="3" {{ (isset($input['setting']) ? ($input['setting'] == '3' ? 'selected':''):'') }} >พฤติกรรมส่วนบุคคล</option>
                                    <option value="16" {{ (isset($input['setting']) ? ($input['setting'] == '16' ? 'selected':''):'') }} >ทุก Setting</option>
                                </select>
                            </div>
                </div><button class="btn_search_inside">ค้นหา</button>
            {!! Form::close() !!}
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