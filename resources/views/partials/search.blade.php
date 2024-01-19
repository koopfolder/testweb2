@inject('request', 'Illuminate\Http\Request')
@php
    //$target =ThrcHelpers::getTarget($request->all());
    $target =ThrcHelpers::getTarget2($request->all());
    $issue =  ThrcHelpers::getIssue($request->all());
    $template =  ThrcHelpers::getTempalte($request->all());
    $time_cache  = ThrcHelpers::time_cache(15);
    //dd($target);
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
                    <h1><span>ADVANCE</span>SEARCH</h1>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10 wrap_searchinside">
                    {{ Form::open(['url' => route('media-list'),'method' =>'get','id'=>'form_search']) }}
                    <div class="searchbox_inside">
                            <div class="input_seach">{{ Form::text('keyword',isset($data['old']['keyword']) ? $data['old']['keyword']:'',['class'=>'','placeholder'=>'คำค้น','maxlength'=>'50']) }}</div>
                            <div id="issues_opt" class="selectbox">
                                <select name="issue">
                                    <option value="0">ประเด็น</option>
                                    @if($issue->count())
                                        @foreach($issue AS $key=>$value)
                                            @php
                                                //dd($value);
                                                if (Cache::has('issue_count_'.$value->id)){
                                                    $issue_count = Cache::get('issue_count_'.$value->id);
                                                }else{
                                                    $issue_count = $value->children->count();
                                                    Cache::put('issue_count_'.$value->id,$issue_count,$time_cache);
                                                    $issue_count = Cache::get('issue_count_'.$value->id);
                                                }
                                            @endphp
                                            @if ($issue_count > 0)
                                                <optgroup label="{{ $value->name }}">
                                                    @if(!empty($value->issues_id))
                                                        <option value="{{ $value->issues_id }}" {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == $value->issues_id ? 'selected':''):'') }}  >{{ $value->name }}</option>
                                                    @endif
                                                @php
                                                        if (Cache::has('issue_children_'.$value->id)){
                                                            $children = Cache::get('issue_children_'.$value->id);
                                                        }else{

                                                            $children = $value->children->sortBy('name');
                                                            Cache::put('issue_children_'.$value->id,$children,$time_cache);
                                                            $children = Cache::get('issue_children_'.$value->id);
                                                        }
                                                @endphp    
                                                @foreach($children as $children)
                                                    @php
                                                        //dd($children);
                                                    @endphp
                                                    <option value="{{ $children->issues_id }}"  {{ (isset($data['old']['issue'])  ? ($data['old']['issue'] == $children->issues_id ? 'selected':''):'') }}>{{ $children->name }}</option>
                                                @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $value->issues_id }}"  {{ (isset($data['old']['issue']) ? ($data['old']['issue'] == $value->issues_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div><div id="type_opt" class="selectbox">
                                {{ Form::select('template',$template,(isset($data['old']['template']) ? $data['old']['template']:'')) }}
                            </div><!--<div class="selectbox">
                                @php
                                    //dd($data['old']['target']);

                                @endphp
                                <select name="target">
                                    <option value="0">กลุ่มเป้าหมาย</option>
                                @if($target->count())
                                    <optgroup label="บุคคล/อาชีพ">
                                        @foreach($target AS $key=>$value)
                                        @php 
                                            //dd($value);
                                        @endphp
                                        @if($value->TargetGuoupID ==1)
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($target->count())
                                    <optgroup label="อายุ">
                                        @foreach($target AS $key=>$value)
                                        @if($value->TargetGuoupID ==2)
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($target->count())
                                    <optgroup label="เพศ">
                                        @foreach($target AS $key=>$value)
                                        @if($value->TargetGuoupID ==3)
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                    </optgroup>
                                @endif

                                <!-- @if($target->count())
                                    @foreach($target AS $key=>$value)
                                        @php
                                            //dd($value);
                                            if (Cache::has('target_count_'.$value->target_id)){
                                                    $target_count = Cache::get('target_count_'.$value->target_id);
                                                }else{
                                                    $target_count = $value->children->count();
                                                    Cache::put('target_count_'.$value->target_id,$target_count,$time_cache);
                                                    $target_count = Cache::get('target_count_'.$value->target_id);
                                                }
                                        @endphp
                                        @if ($target_count > 0)
                                            <optgroup label="{{ $value->name }}">
                                                @if(!empty($value->target_id))
                                                    <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                                @endif
                                            @php
                                                if (Cache::has('target_children_'.$value->target_id)){
                                                    $children = Cache::get('target_children_'.$value->target_id);
                                                }else{

                                                    $children = $value->children->sortBy('name');
                                                    Cache::put('target_children_'.$value->target_id,$children,$time_cache);
                                                    $children = Cache::get('target_children_'.$value->target_id);
                                                }
                                            @endphp
                                            @foreach($children as $children)
                                                @php
                                                    //dd($data['old']['target']);
                                                @endphp
                                                <option value="{{ $children->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $children->target_id ? 'selected':''):'') }}>{{ $children->name }}</option>
                                            @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target'])  ? ($data['old']['target'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        
                                    @endforeach
                                @endif                                
                            </select>
                            </div>--><div id="age_opt"  class="selectbox">
                                @php
                                    //dd($data['old']['target']);

                                @endphp
                                <select name="target_age">
                                    <option value="0">อายุ</option>
                                @if($target->count())
                                        @foreach($target AS $key=>$value)
                                        @if($value->TargetGuoupID ==2)
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target_age'])  ? ($data['old']['target_age'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                @endif
                            </select>
                            </div><div id="gender" class="selectbox">
                                @php
                                    //dd($data['old']['target']);
                                @endphp
                                <select name="target_sex">
                                    <option value="0">เพศ</option>
                                @if($target->count())
                                        @foreach($target AS $key=>$value)
                                        @if($value->TargetGuoupID ==3)
                                            <option value="{{ $value->target_id }}" {{ (isset($data['old']['target_sex'])  ? ($data['old']['target_sex'] == $value->target_id ? 'selected':''):'') }}>{{ $value->name }}</option>
                                        @endif
                                        @endforeach
                                @endif
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

<script>

$(document).on('click',".btn_search_inside",function(){
    let search_data = $('.input_seach').find('input').val()
    let issues_data = $('#issues_opt').find('select').find(":selected").text();
    let type_data = $('#type_opt').find('select').find(":selected").text();
    let age_data = $('#age_opt').find('select').find(":selected").text();
    let gender_data = $('#gender').find('select').find(":selected").text();
    let search_value = `${search_data},${issues_data},${type_data},${age_data},${gender_data}`
    clicksearch(search_value)
})

</script>