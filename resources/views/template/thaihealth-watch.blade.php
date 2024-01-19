@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
@endphp
@extends('layouts.app_thaihealth_watch')
@section('content')
<div id="app">
    <th-watch1-component
        api_url="{{ route('api.thaihealth-watch.mainbanner') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
    ></th-watch1-component>
    <th-watch2-component
        api_url="{{ route('api.thaihealth-watch.single_page') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="อ่านต่อ"
    ></th-watch2-component>
    <th-watch3-component
        api_url="{{ route('api.thaihealth-watch.form-generator') }}"
        background="{{ asset('themes/thrc/thaihealth-watch/images/Rectangle 3923.png') }}"
        url_register="{{ route('thaihealth-watch.register') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_register="ลงทะเบียนเพื่อรับเอกสาร"
        text_form_generator="ลงทะเบียน"
        {{-- text_h1="ลงทะเบียน / แบบฟอร์มออนไลน์"
        text_p="Thaihealth WATCH 2023 'สังคมปรับ ชีวิตเปลี่ยน' จับตาทิศทางสุขภาพคนไทย เตรียมพร้อมชีวิตใหม่ที่กำลังจะเปลี่ยนแปลง" --}}
        text_h1="ลงทะเบียน"
        text_p="Thaihealth WATCH จับตาทิศทางสุขภาพคนไทย"
    ></th-watch3-component>
    <th-watch4-component
        title="เทรนด์สุขภาพประจำปี"
        api_url="{{ route('api.thaihealth-watch.health_trends') }}"
        url_list="{{ route('thaihealth-watch.health-trends-list') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="ดูทั้งหมด"
    ></th-watch4-component>
    <th-watch5-component
        api_url="{{ route('api.thaihealth-watch.main_video') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="อ่านต่อ"
    ></th-watch5-component>
    <th-watch6-component
        api_url="{{ route('api.thaihealth-watch.panel_discussion') }}"
        title="Panel Discussion"
        url_list="{{ route('thaihealth-watch.panel-discussion-list') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="ดูทั้งหมด"
    ></th-watch6-component>
    <th-watch7-component
        api_url="{{ route('api.thaihealth-watch.points_to_watch') }}"
        title="ประเด็นที่น่าจับตา"
        title2="Video"
        url_list="{{ route('thaihealth-watch.points-to-watch-list') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="ดูทั้งหมด"
    ></th-watch7-component>
    <th-watch8-component
        title="หน่วยงานที่เกี่ยวข้อง"
        api_url="{{ route('api.thaihealth-watch.footerbanner') }}"
        access_token="{{ env('THWATCH_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
    ></th-watch8-component>
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

</style>
@endsection
@section('js')
	@parent
<script>

</script>
@endsection