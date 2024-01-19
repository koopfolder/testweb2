@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
	//dd($data);
@endphp
    <section class="row row_overflow frm_register">
        <h1>รีเซ็ตรหัสผ่าน</h1>
        <div class="col-xs-12 wrap_content">
                <div class="row ctd4">
                    <div class="col-xs-12 col-sm-6 box_text">
                        {!! Form::open(['url' => route('user.reset'),'method'=>'post','id'=>'resetForm']) !!}
                        <div class="form_register" style="margin-left:10%;">
                            <div class="form-group">
                                <label for="password"><span>*</span> รหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="reset_password" maxlength="50" id="reset_password" passwordCheck="passwordCheck">
                            </div>
                            <div class="form-group">
                                <label for="cfpassword"><span>*</span> ยืนยันรหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="confirm_reset_password" id="confirm_reset_password"  passwordCheck="passwordCheck">
                            </div>
                            <span style="color:red;">
                                หมายเหตุ 
                                <br>
                                * รหัสผ่านจะต้องมีตัวอักษรอย่างน้อย 8 ตัวอักษร
                                <br>
                                * รหัสผ่านจะต้องมีตัวอักษรพิมพ์เล็ก, พิมพ์ใหญ่ และตัวเลข
                            </span>
                        </div>
                        <div class="wrap_btn_subregister">
                            <button class="btn_cfregister" type="submit">รีเซ็ตรหัสผ่าน</button>
                            <button class="btn_cancel" type="reset">ยกเลิก</button>
                        </div>
                        {{ FORM::hidden('forgotpassword_token',$token) }}
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('meta')
    @parent
    <title></title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
@endsection
@section('js')
	@parent
@endsection
@section('style')
	@parent

@endsection