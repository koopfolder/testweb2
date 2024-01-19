@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('history::backend.edit_history') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('history::backend.home') }}</a></li>
        <li><a href="{{ route('admin.banner.index') }}">{{ trans('history::backend.room') }}</a></li>
        <li class="active">{{ trans('history::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.history.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('history::backend.description') }}</a></li>
                            <li><a href="#tab_revisions" data-toggle="tab">{{ trans('history::backend.revisions') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('history::backend.year') }} <span style="color:red">*</span></label>
                                    {{ Form::select('year',$years,$data->year,['class'=>'form-control select2','id'=>'years']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('history::backend.description_th') }} <span style="color:red">*</span></label>
                                    {{ Form::textarea('description_th', $data->description_th ?? old('description_th'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('history::backend.description_en') }}</label>
                                    {{ Form::textarea('description_en', $data->description_en ?? old('description_en'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revisions">
                                @include('history::partials.revision', ['revisions' => $revisions])
                            </div>    
                                                        
                            <a href="{{ route('admin.history.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('history::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('history::backend.submit') }}</button>                                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('history::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        @php
                            $user_id = auth()->user()->id;
                            $permission_status = RoosterHelpers::getPermissionsstatus($user_id);
                            $status = ['publish' =>trans('article::backend.publish'),'draft'=>trans('article::backend.draft')];
                            if($permission_status->count()){
                                $check_publish = json_decode($permission_status->role->permissions_data)->publish;
                                $check_draft = json_decode($permission_status->role->permissions_data)->draft;
                                if($check_publish =='0'){
                                    unset($status['publish']);
                                }
                                if($check_draft =='0'){
                                    unset($status['draft']);
                                }
                            }
                        @endphp

                        <label>{{ trans('history::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status', $data->status), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#years").select2();

    });
</script>
@endsection
@section('css')
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered{
        margin:-8px !important;
    }
</style>
@endsection