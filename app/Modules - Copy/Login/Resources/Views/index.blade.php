@extends('layout::login')

@section('content')
<div class="login-box">

-------------------------------------------------
    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="login-logo">
        <img src="{{ asset('images/admin-login-logo.svg') }}">
        </div>

        {{ Form::open(['url' => route('admin.login')]) }}
            <div class="form-group has-feedback">
            @if (session('status'))
                <div class="alert alert-danger">
					{{ session('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>
            <div class="form-group has-feedback">
                {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'อีเมล์']) }}
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'รหัสผ่าน']) }}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
              <div class="text-center bt-login">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                    เข้าสู่ระบบ
                    </button>
           </div>
        {{ Form::close() }}
    </div>

    <footer class="admin-footer">
      <div class="pull-right hidden-xs">
      </div>
      Copyright 2019  THRC. All rights reserved.
    </footer>

</div>
@endsection
