@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('joinus::backend.viewmore') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('article::backend.home') }}</a></li>
        <li><a href="{{ route('admin.career.index') }}">{{ trans('joinus::backend.career') }}</a></li>
        <li class="active">{{ trans('joinus::backend.viewmore') }}</li>
    </ol>
</section>
@php
    //dd($data);
@endphp
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.name') }}</label>:
                                @if($data->prefix =='2')
                                        {{ 'นาง' }}
                                @elseif($data->prefix =='3')
                                        {{ 'นางสาว' }}
                                @elseif($data->prefix =='4')
                                        {{ 'นางหรือนางสาว' }}
                                @else
                                        {{ 'นาย' }}
                                @endif
                                {{ $data->name." ".$data->surname }}
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.sex') }}</label>:
                                @if($data->sex =='M')
                                    {{ 'ชาย' }}
                                @else
                                    {{ 'หญิง' }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.birthdate') }}</label>:
                                {{ date('d M Y', strtotime($data->birthdate)) }}
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.nationality') }}</label>:
                                {{ $data->nationality }}
                            </div>
                        <!--    <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.province') }}</label>:
                                {{ $data->province }}
                            </div> -->
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.email_address') }}</label>:
                                {{ $data->email }}
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.phone_number') }}</label>:
                                {{ $data->phone }}
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.id_type') }}</label>:
                                @if($data->id_type =="identification_number")
                                    {{ 'Identification Number :'.$data->id_no }}
                                @else
                                    {{ 'Passport Number :'.$data->id_no }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.education') }}</label>:
                                {{ $data->education }}
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.educational_institutions') }}</label>:
                                {{ $data->educational_institutions }}
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.other') }}</label>:
                                {{ $data->educational_institutions_other }}
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.field_of_study_major') }}</label>:
                                {{ $data->field_of_study_major }}
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.work_experience') }} (1)</label>
                                <div>{{ trans('joinus::backend.position') }} : {{ $data->position_1 }}</div>
                                <div>{{ trans('joinus::backend.company') }} : {{ $data->company_1 }}</div>
                                <div>
                                    {{ trans('joinus::backend.duration_of_service') }} : {{ ($data->duration_of_service_year_1 !='' ?  $data->duration_of_service_year_1.' '.trans('joinus::backend.year'):'') }}  {{ ($data->duration_of_service_month_1 !='' ?  $data->duration_of_service_month_1.' '.trans('joinus::backend.month'):'') }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.work_experience') }} (2)</label>
                                <div>{{ trans('joinus::backend.position') }} : {{ $data->position_2 }}</div>
                                <div>{{ trans('joinus::backend.company') }} : {{ $data->company_2 }}</div>
                                <div>
                                    {{ trans('joinus::backend.duration_of_service') }} : {{ ($data->duration_of_service_year_2 !='' ?  $data->duration_of_service_year_2.' '.trans('joinus::backend.year'):'') }}  {{ ($data->duration_of_service_month_2 !='' ?  $data->duration_of_service_month_2.' '.trans('joinus::backend.month'):'') }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.work_experience') }} (3)</label>
                                <div>{{ trans('joinus::backend.position') }} : {{ $data->position_3 }}</div>
                                <div>{{ trans('joinus::backend.company') }} : {{ $data->company_3 }}</div>
                                <div>
                                    {{ trans('joinus::backend.duration_of_service') }} : {{ ($data->duration_of_service_year_3 !='' ?  $data->duration_of_service_year_3.' '.trans('joinus::backend.year'):'') }}  {{ ($data->duration_of_service_month_3 !='' ?  $data->duration_of_service_month_3.' '.trans('joinus::backend.month'):'') }}
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.attached_images') }}</label>:
                                @if($data->attached_images !='')
                                    <a href="{{ asset($data->attached_images) }}" download="{{ trans('joinus::backend.attached_images') }}_file">{{ trans('joinus::backend.attached_images') }} File</a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.attachment_history') }}</label>:
                                @if($data->attachment_history !='')
                                    <a href="{{ asset($data->attachment_history) }}" download="{{ trans('joinus::backend.attachment_history') }}_file">{{ trans('joinus::backend.attachment_history') }} File</a>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="InputName">{{ trans('joinus::backend.other_documents') }}</label>:
                                @if($data->other_documents !='')
                                    <a href="{{ asset($data->other_documents) }}" download="{{ trans('joinus::backend.other_documents') }}_file">{{ trans('joinus::backend.other_documents') }} File</a>
                                @endif
                                <br>
                                @if($data->other_documents_2 !='')
                                    <a href="{{ asset($data->other_documents_2) }}" download="{{ trans('joinus::backend.other_documents') }}_file">{{ trans('joinus::backend.other_documents') }} File</a>
                                @endif
                                <br>
                                @if($data->other_documents_3 !='')
                                    <a href="{{ asset($data->other_documents_3) }}" download="{{ trans('joinus::backend.other_documents') }}_file">{{ trans('joinus::backend.other_documents') }} File</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('javascript')

@endsection
@section('css')

@endsection