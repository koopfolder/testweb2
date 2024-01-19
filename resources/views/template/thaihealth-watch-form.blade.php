@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($data);
@endphp
@extends('layouts.app_thaihealth_watch')
@section('content')
<div id="app">
<section class="row wow fadeInDown">
    <section class="row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <hgroup class="head-register">
                        <h1>ลงทะเบียน</h1>
                        <h5>{{ $data->title }}</h5>
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
                        {!! Form::open(['url' => route('thaihealth-watch.form'),'method'=>'post','id'=>'ThWatchRegisterFormG','class'=>'row']) !!}
                            <div class="col-xs-12">
                                <code id="markup"></code>
                            </div>
                            <div class="col-xs-12 ">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_KEY','6LeKfXgdAAAAACd1STCPeuhYklFmFbi029WJwZ1S') }}"></div>
                                <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                            </div>
                            <div class="col-xs-12 wrap_register_submit">
                                <button class="btn-submit" type="submit">ลงทะเบียน</button>
                                <img src="{{ asset('themes/thrc/thaihealth-watch/images/shutterstock_1598189440-removebg.png') }}" alt="">
                            </div>
                            {{ Form::hidden('id',$data->id) }}
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
    .form-control {
        font-size: 20px !important;
        color:#757575 !important;
        border-radius: 2px !important;
    }
    .form-control input{
        height: 35px !important;
    }
</style>
@endsection
@section('js')
	@parent
<script src="{{ asset('adminlte/bower_components/form-builder/form-builder.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/form-builder/form-render.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.4.0/highlight.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
jQuery($ => {
    const escapeEl = document.createElement("textarea");
    const code = document.getElementById("markup");
    const formData ='<?php echo $data->dol_json_data; ?>';
    const addLineBreaks = html => html.replace(new RegExp("><", "g"), ">\n<");
   
        // Grab markup and escape it
    const $markup = $("<div/>");
    $markup.formRender({ formData });

        // set < code > innerText with escaped markup
    code.innerHTML = addLineBreaks($markup.formRender("html"));
    hljs.highlightBlock(code);
});
</script>
@endsection