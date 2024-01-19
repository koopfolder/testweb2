@inject('request', 'Illuminate\Http\Request')
@php
	//dd("Test");
@endphp

@extends('layouts.app_thaihealth_watch')
@section('content')
<div id="app">
    <health-trends-list-component
        text_title="เทรนด์สุขภาพประจำปี"
        text_read_more="โหลดเพิ่มเติม"
        api_url="{{ route('api.thaihealth-watch.health-trends-list') }}"
        api_url_read_more="{{ route('api.thaihealth-watch.health-trends-list-readmore') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
    ></health-trends-list-component>  
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ThaihealthWatch</title>
    <meta charset="UTF-8">
    <meta name="description" content="ThaihealthWatch">
    <meta name="keywords" content="ThaihealthWatch">
    <meta name="author" content="">
@endsection
@section('style')
	@parent

@endsection
@section('js')
	@parent
    <script>
   
</script>
@endsection