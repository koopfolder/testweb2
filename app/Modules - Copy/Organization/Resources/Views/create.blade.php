@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('organization::backend.organization') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('organization::backend.home') }}</a></li>
        <li><a href="{{ route('admin.organization.index') }}">{{ trans('organization::backend.organization') }}</a></li>
        <li class="active">{{ trans('organization::backend.add') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.organization.create'), 'files' => true]) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('organization::backend.description') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group col-md-6">
                                    <label for="InputName">{{ trans('organization::backend.image_desktop_th') }} (1366px * 768px) <span style="color:red">*</span></label>
                                    @if($data->getMedia('desktop_th')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('desktop_th')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    {{ Form::hidden('check_desktop_th','1') }}
                                    @else
                                    {{ Form::hidden('check_desktop_th','0') }}
                                    @endif
                                    <div class="areaImage">{!! Form::file('desktop_th') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputName">{{ trans('organization::backend.image_mobile_th') }} (768px * auto) <span style="color:red">*</span></label>
                                    @if($data->getMedia('mobile_th')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('mobile_th')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    {{ Form::hidden('check_mobile_th','1') }}
                                    @else
                                    {{ Form::hidden('check_mobile_th','0') }}
                                    @endif
                                    <div class="areaImage">{!! Form::file('mobile_th') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputName">{{ trans('organization::backend.image_desktop_en') }} (1366px * 768px)</label>
                                    @if($data->getMedia('desktop_en')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('desktop_en')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('desktop_en') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputName">{{ trans('organization::backend.image_mobile_en') }} (768px * auto)</label>
                                    @if ($data->getMedia('mobile_en')->isNotEmpty())
                                    <div><img src="{{ asset($data->getMedia('mobile_en')->first()->getUrl()) }}" width="250"></div>
                                    <p class="help-block">Format: jpeg, png (no more than 2M)</p>
                                    @endif
                                    <div class="areaImage">{!! Form::file('mobile_en') !!}</div>
                                    <p class="help-block">Format file: jpeg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <a href="{{ route('preview-organization-chart') }}" class="btn btn-success" target="_blank">
                                <i class="fa fa-eye"></i> Preview
                            </a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('organization::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('organization::backend.setting') }}</h3>
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
                        <label>{{ trans('organization::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
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