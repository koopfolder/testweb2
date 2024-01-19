@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('users::backend.connectionauthority_edit') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('users::backend.home') }}</a></li>
        <li><a href="{{ route('admin.connectionauthority.index') }}">{{ trans('users::backend.connectionauthority') }}</a></li>
        <li class="active">{{ trans('users::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
    {{ Form::open(['url' => route('admin.connectionauthority.edit', $connectionauthority->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="ClientId">{{ trans('users::backend.client_id') }} <span style="color:red;">*</span></label>
                        {{ Form::text('client_id', $connectionauthority->client_id, ['class' => 'form-control', 'disabled']) }}
                    </div>
                    <div class="form-group">
                        <label for="ClientCompanyName">{{ trans('users::backend.client_company_name') }} <span style="color:red;">*</span></label>
                        {{ Form::text('client_company_name', $connectionauthority->client_company_name, ['class' => 'form-control']) }}
                    </div>
                    <!-- <div class="form-group">
                        <label for="Phone">{{ trans('users::backend.phone') }} <span style="color:red;">*</span></label>
                        {{ Form::text('phone', old('phone'), ['class' => 'form-control']) }}
                    </div> -->
                    <div class="form-group">
                        <label for="InputPassword">{{ trans('users::backend.password') }} (แนะนำให้ใช้ 4 ตัวอักษร แต่ไม่เกิน 60 ตัว)</label>
                        {{ Form::password('password', ['class' => 'form-control',]) }}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.connectionauthority.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('users::backend.back') }}</a>
                    <button class="btn btn-success pull-right" type="submit" value="save"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('users::backend.submit') }}</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('users::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                <div class="form-group">
                    <label>{{ trans('users::backend.status') }} <span style="color:red;">*</span></label>
                        {!! Form::select('client_status', ['1' =>trans('users::backend.open'), '0' =>trans('users::backend.closed')], $connectionauthority->client_status ?? old('client_status'), ['class' => 'form-control']) !!}
                    </div>

                   
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

