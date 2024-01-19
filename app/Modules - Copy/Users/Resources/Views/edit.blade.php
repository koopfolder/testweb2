@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('users::backend.edit_user') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('users::backend.home') }}</a></li>
        <li><a href="{{ route('admin.users.index') }}">{{ trans('users::backend.users') }}</a></li>
        <li class="active">{{ trans('users::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.users.edit', $user->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="InputName">{{ trans('users::backend.name') }} <span style="color:red;">*</span></label>
                        {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputEmailAddress">{{ trans('users::backend.email') }} <span style="color:red;">*</span></label>
                        {{ Form::email('email', $user->email, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="Phone">{{ trans('users::backend.phone') }} <span style="color:red;">*</span></label>
                        {{ Form::text('phone', $user->phone, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">{{ trans('users::backend.password') }} (แนะนำให้ใช้ 4 ตัวอักษร แต่ไม่เกิน 60 ตัว)</label>
                        {{ Form::password('password', ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('users::backend.back') }}</a>
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
                        <label for="">{{ trans('users::backend.group') }}</label>
                        {!! Form::select('roles[]', $roles, old('roles') ? old('role') : $user->roles()->pluck('name', 'name'), ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('users::backend.status') }} <span style="color:red;">*</span></label>
                        {!! Form::select('status', ['publish' =>trans('users::backend.publish'), 'draft' =>trans('users::backend.draft')], $user->status ?? old('status'), ['class' => 'form-control']) !!}
                    </div>
                     <div class="form-group">
                        <label for="exampleInputFile">{{ trans('users::backend.avatar') }} (150x150)</label>
                        @if ( $user->getMedia('avatar')->count() > 0)
                            <a href="{{ route('admin.users.deleteImage', $user->id) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                <i class="fa fa-trash-o"></i>
                            </a>
                            <div><img src="{{ asset($user->getMedia('avatar')->first()->getUrl()) }}" width="100%"></div>
                            <br>
                        @endif
                        {{ Form::file('avatar') }}
                        <p class="help-block">นามสกุลไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

