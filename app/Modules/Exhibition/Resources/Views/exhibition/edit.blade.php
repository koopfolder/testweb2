@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('exhibition::backend.edit_exhibition') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
        <li><a href="{{ route('admin.exhibition.index') }}">{{ trans('exhibition::backend.exhibition') }}</a></li>
        <li class="active">{{ trans('exhibition::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.exhibition.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('exhibition::backend.description') }}</a></li>
                            <li><a href="#tab_cover_image" data-toggle="tab">{{ trans('exhibition::backend.cover_image') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.title') }} <span style="color:red">*</span></label>
                                    {{ Form::text('title',$data->title ?? old('title'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.short_description') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('short_description',$data->short_description ?? old('short_description'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.description') }}</label>
                                    {{ Form::textarea('description',$data->description ?? old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.url_external') }} <span style="color:red">*</span></label>
                                    {{ Form::text('url_external',$data->url_external ?? old('url_external'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.attachment') }}</label>
                                    @if($data->file_path !='' && file_exists(public_path().$data->file_path))
                                    <a href="{{ asset($data->file_path) }}" download>
                                        {{ $data->file_name }}
                                    </a>
                                    @endif
                                    <div class="areaImage">{!! Form::file('attached_file') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: xlsx,xls,doc,pdf (ไม่เกิน 10M)</p>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.start_date') }}<span style="color:red">*</span></label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        {{ Form::text('start_date',Carbon\Carbon::parse($data->start_date)->format('Y-m-d H:i:s') ?? old('start_date'),['class'=>'form-control start_date']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.end_date') }}</label>
                                    <div class='input-group date' id='datetimepicker2'>
                                        {{ Form::text('end_date',Carbon\Carbon::parse($data->end_date)->format('Y-m-d H:i:s') ?? old('end_date'),['class'=>'form-control end_date']) }}
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.cover_image') }} (1366px * 768px)</label>
                                    @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">นามสกุลไฟล์: jpeg, png (ไม่เกิน 5M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">นามสกุลไฟล์: jpg, png (ไม่เกิน 5M)</p>
                                </div>
                            </div>              
                            <a href="{{ route('admin.exhibition.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('exhibition::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('exhibition::backend.submit') }}</button>                                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('exhibition::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                        @endphp
                        <label>{{ trans('exhibition::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.exhibition_category') }} <span style="color:red;">*</span></label>
                        <select name="category_id" class="form-control">
                            <option value="0" @if($data->category_id == "0") selected="selected" @endif>เลือกหมวดหมู่</option>
                            @foreach($exhibition_masters as $exhibition_master)                                
                                    <option value="{{ $exhibition_master->id }}" @if($data->category_id == $exhibition_master->id) selected="selected" @endif>
                                        {{ $exhibition_master->title }}
                                    </option>
                                    @if ($exhibition_master->children->isNotEmpty())
                                        @foreach($exhibition_master->children->sortBy('order') as $children)
                                        <option value="{{ $children->id }}" @if($data->category_id == $children->id) selected="selected" @endif>
                                            - {{ $children->title }}
                                        </option>
                                        @endforeach
                                    @endif
                            @endforeach
                        </select>
                    </div>
                    <!--<div class="form-group">
                        <label for="InputName">{{ trans('exhibition::backend.order') }}</label>
                        {{ Form::text('order', $data->order, ['class' => 'form-control']) }}
                    </div> -->
                    <div class="form-group">
                        <label for="InputName">{{ trans('exhibition::backend.recommend') }} </label>
                        {{ Form::radio('recommend', '2', ($data->recommend == '2' ? true:false)) }}{{ trans('exhibition::backend.yes') }}
                        {{ Form::radio('recommend', '1', ($data->recommend == '1' ? true:false)) }}{{ trans('exhibition::backend.no') }}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.rounds') }} </label>
                        @php
                            $data_rounds = ($data->rounds !='' ? json_decode($data->rounds):[]);
                            $data_rounds =array_combine($data_rounds,$data_rounds);
                            //dd($data_rounds,$data->rounds,array_flip($data_rounds),$collect);
                        @endphp
                        {!! Form::select('rounds[]',$data_rounds,$data_rounds ?? old('rounds'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.rooms') }} </label>
                        @php
                            $data_rooms = ($data->rooms !='' ? json_decode($data->rooms):[]);
                            $data_rooms =array_combine($data_rooms,$data_rooms);
                            //dd($data_rooms,$data->rooms,array_flip($data_rooms),$collect);
                        @endphp
                        {!! Form::select('rooms[]',$data_rooms,$data_rooms ?? old('rooms'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.open_date') }} </label>
                        @php
                        $day_arr=array(
                                    "Sun"=>"วันอาทิตย์",
                                    "Mon"=>"วันจันทร์",
                                    "Tue"=>"วันอังคาร",
                                    "Wed"=>"วันพุธ",
                                    "Thu"=>"วันพฤหัสบดี",
                                    "Fri"=>"วันศุกร์",
                                    "Sat"=>"วันเสาร์"
                                    );
                        $data_open_date = ($data->open_date !='' ? json_decode($data->open_date):[]);
                        $data_open_date =array_combine($data_open_date,$data_open_date);
                        @endphp
                        {!! Form::select('open_date[]',$day_arr,$data_open_date, ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                    </div>                      
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
        $(function (){

        $(".js-tags-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })

        $('#datetimepicker1').datetimepicker({ 
            //format:'DD/MM/YYYY',
            //format:'YYYY/MM/DD HH:mm:ss',
        });
        //console.log(date);
        $('#datetimepicker2').datetimepicker({ 
            //format:'DD/MM/YYYY',
            //format:'YYYY/MM/DD HH:mm:ss',
        });


    });
</script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('adminlte/bower_components/bootstrap-datetimepicke/bootstrap-datetimepicker.min.css') }}">
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection