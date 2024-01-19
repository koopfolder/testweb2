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
                {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'อีเมล']) }}
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
            <div class="text-center bt-login">
                <a href="{{ env('URL_SSO_LOGIN') }}" class="btn_login_sso">Login With SSO</a>
            </div>
    </div>

    <footer class="admin-footer">
      <div class="pull-right hidden-xs">
      </div>
      Copyright 2019  THRC. All rights reserved.
    </footer>

</div>
<style>
.login-box{
    margin: 3% auto !important;
}
.btn_login_sso {
    display: block;
    font-size: 1.1rem;
    color: #FFF;
    background-color: #009881;
    border-radius: 5px;
    padding: 14px 5px 7px 10px;
    text-align: center;
    line-height: 1;
    margin-top: 15px;
    /* background-image: url(https://resourcecenter.thaihealth.or.th/themes/thrc/images/facebookbtn.svg); */
    background-size: 16px auto;
    background-repeat: no-repeat;
    background-position: 38px center;
}
</style>
@endsection
