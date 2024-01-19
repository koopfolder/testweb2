@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
@endphp
@extends('layouts.app_ncds')
@section('content')
<div id="app">
    <ncds1-component
        api_url="{{ route('api.ncds.ncds1') }}"
        url_ncds1_list="{{ route('article-know-ncds') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_read_more="อ่านต่อ"
    ></ncds1-component>
    <ncds2-component
        api_url="{{ route('api.ncds.ncds2') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        ncds_eye="{{ asset('themes/thrc/images/ncds_eye.svg') }}"
        url_view_all="{{ route('article-update-the-status-here-ncds') }}"
        text_title="อัพเดทสถานการณ์ NCDs"
        text_view_all="ดูทั้งหมด"
        text_situation_ncds_1="อัพเดทสถานการณ์ทั่วไป"
        text_situation_ncds_2="อัพเดทสถานการณ์ตามโรค"
    ></ncds2-component>
    <!--<ncds3-component
        api_url="{{ route('api.ncds.ncds3') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_title="ทักษะความรอบรู้ด้านสุขภาพ"
        text_read_more="อ่านต่อ"
    ></ncds3-component>-->
    <ncds4-component
        api_url="{{ route('api.ncds.ncds4') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_title="แบบทดสอบทักษะความรอบรู้ด้านสุขภาพ"
        text_view_all="ดูทั้งหมด"
        url_view_all="{{ route('article-health-knowledge-skills') }}"
        icon_owl_left_direction_arrow_ncds_w="{{ asset('themes/thrc/images/owl-left-direction-arrow-ncds-w.svg') }}"
        icon_owl_right_thin_chevron_ncds_w="{{ asset('themes/thrc/images/owl-right-thin-chevron-ncds-w.svg') }}"
    ></ncds4-component>
    <ncds5-component
        api_url="{{ route('api.ncds.ncds5') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_title="สื่อและเครื่องมือ"
        text_view_all="ดูทั้งหมด"
        url_view_all="{{ route('list-health-literacy-category')  }}"
        icon_owl_left_direction_arrow_ncds="{{ asset('themes/thrc/images/owl-left-direction-arrow-ncds.svg') }}"
        icon_owl_right_thin_chevron_ncds="{{ asset('themes/thrc/images/owl-right-thin-chevron-ncds.svg') }}"
    ></ncds5-component>
    <ncds6-component
    api_url="{{ route('api.ncds.ncds6') }}"
    access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
    text_title="เครื่องมืออื่่นๆ ที่่น่าสนใจ"
    text_view_all="ดูทั้งหมด"
    url_view_all="{{ route('article-other-interesting-tools')  }}"
    icon_ncds_eye="{{ asset('themes/thrc/images/ncds_eye.svg') }}"
    ></ncds6-component>
    <ncds7-component
    api_url="{{ route('api.ncds.ncds7') }}"
    access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
    text_title="ภาคีที่เกี่ยวข้อง"
    icon_owl_left_direction_arrow_ncds_w="{{ asset('themes/thrc/images/owl-left-direction-arrow-ncds-w.svg') }}"
    icon_owl_right_thin_chevron_ncds_w="{{ asset('themes/thrc/images/owl-right-thin-chevron-ncds-w.svg') }}"
    ></ncds7-component>

</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>NCDs</title>
    <meta charset="UTF-8">
    <meta name="description" content="NCDs">
    <meta name="keywords" content="NCDs">
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