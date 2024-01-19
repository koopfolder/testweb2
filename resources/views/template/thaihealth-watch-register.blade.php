@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
    //dd(env('RECAPTCHA_KEY'));
    //dd(session());
@endphp
@extends('layouts.app_thaihealth_watch')
@section('content')
<div id="app">
<section class="row wow fadeInDown">
        <div class="col-xs-12 banner-inside">
            <img src="{{ asset('themes/thrc/thaihealth-watch/images/banner_register.jpg') }}">
        </div>
    </section>
    <section class="row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <hgroup class="head-register">
                        <h1>ลงทะเบียน</h1>
                        <h5>ลงทะเบียนเพื่อรับเอกสาร ThaihealthWatch</h5>
                    </hgroup>
                </div>             
                <div class="col-xs-12">
                    @if(session()->has('status') && session()->get('status') =='success')
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif 
                    
                    @if(session()->has('status') && session()->get('status') =='error')
                    <div class="alert alert-danger">
                    {{ session()->get('message') }}
                    </div>
                    @endif 
                    <div class="register-box">
                        <h5>ข้อมูลส่วนตัว</h5>
                        
                        {!! Form::open(['url' => route('thaihealth-watch.register'),'method'=>'post','id'=>'ThWatchRegisterForm','class'=>'row']) !!}
                            <div class="col-xs-12 col-md-6">
                                {{ Form::text('name',old('name'), ['class' => '','placeholder'=>'ชื่่อ *','maxlength'=>'50']) }}
                            </div>
                            <div class="col-xs-12 col-md-6">
                                {{ Form::text('surname',old('surname'), ['class' => '','placeholder'=>'นามสกุล *','maxlength'=>'50']) }}
                            </div>
                            <div class="col-xs-12 col-md-6">
                            @php        
                                $agency = ['นักวิชาการ'=>'นักวิชาการ',
                                           'หน่วยงานของรัฐ'=>'หน่วยงานของรัฐ',
                                           'ภาคีเครือข่าย สสส.'=>'ภาคีเครือข่าย สสส.',
                                           'สื่อมวลชล'=>'สื่อมวลชล',
                                           'สถาบันศึกษา'=>'สถาบันศึกษา',
                                           'อื่น ๆ'=>'อื่น ๆ'];
                            @endphp
                                {{ Form::select('agency',$agency,old('agency'),['placeholder'=>'หน่วยงาน *','class'=>'form-control']) }}
                            </div>
                            <div class="col-xs-12 col-md-6">
                                {{ Form::text('email',old('email'), ['class' => '','placeholder'=>'อีเมล *','maxlength'=>'100']) }}
                            </div>
                            <div class="col-xs-12 col-md-6">
                                {{ Form::text('phone',old('phone'), ['class' => '','placeholder'=>'หมายเลขโทรศัพท์ *','maxlength'=>'20']) }}
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <input name="pdpa" value="1" type="checkbox">ข้าพเจ้ายินยอมในการเปิดเผยข้อมูลส่วนบุคคลกับ ThaiHealthWatch และ สสส. อ่านข้อมูลเพิ่มเติมเกี่ยวกับการยินยอมเปิดเผยข้อมูลได้<a href="https://pdpa.thaihealth.or.th/content/policy" target="_blank">ที่นี่</a>
                                </label>
                            </div>
                            <div class="col-xs-12 ">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_KEY','6LeKfXgdAAAAACd1STCPeuhYklFmFbi029WJwZ1S') }}"></div>
                                <input type="hidden"  class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha" >
                            </div>


                            <div class="col-xs-12 wrap_register_submit">
                                <button class="btn-submit" type="submit">ลงทะเบียน</button>
                                <img src="{{ asset('themes/thrc/thaihealth-watch/images/shutterstock_1598189440-removebg.png') }}" alt="">
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ThaiHealthWatch</title>
    <meta charset="UTF-8">
    <meta name="description" content="thaihealth-watch">
    <meta name="keywords" content="thaihealth-watch">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
<style>
    .form-control{
        font-size: 20px !important;
        color:#757575 !important;
        border-radius: 2px !important;
        height: 42px !important;
    }
</style>
@endsection
@section('js')
	@parent
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>

</script>
@endsection