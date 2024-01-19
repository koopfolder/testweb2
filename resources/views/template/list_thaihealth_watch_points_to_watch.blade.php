@inject('request', 'Illuminate\Http\Request')
@php
	//dd("Test");
@endphp
@if(method_exists('App\Modules\Article\Http\Controllers\ThaihealthWatchController', 'getDataThaihealthWatch')) 
    @php
        $data = App\Modules\Article\Http\Controllers\ThaihealthWatchController::getDataThaihealthWatch($request->all());  
        //dd($data,$request->all());
    @endphp
@endif
@extends('layouts.app_thaihealth_watch')
@section('content')
<div id="app">
    <interesting-point-list-component
        text_article="บทความ"
        text_video="วิดีโอ"
        text_gallery="ภาพกิจกรรม"
        api_url="{{ route('api.thaihealth-watch.interesting-point-list') }}"
        api_url_read_more="{{ route('api.thaihealth-watch.interesting-point-list-readmore') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="ดูเพิ่มเติม"
        icon_article="{{ asset('themes/thrc/thaihealth-watch/images/Group 27335.png') }}" 
        icon_video="{{ asset('themes/thrc/thaihealth-watch/images/Group 27370.png') }}"
        icon_gallery="{{ asset('themes/thrc/thaihealth-watch/images/Group 27374.png') }}"
        url_article="{{ route('thaihealth-watch.points-to-watch-article-list') }}"
        url_video="{{ route('thaihealth-watch.points-to-watch-video-list') }}"
        url_gallery="{{ route('thaihealth-watch.points-to-watch-gallery-list') }}"
    ></interesting-point-list-component>       
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
    $(document).ready(function(){
       

    });
</script>
@endsection