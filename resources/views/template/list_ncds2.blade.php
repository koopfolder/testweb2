@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($data);
@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\FrontendController', 'getDataNcds4'))   
    @php
        //$data = App\Modules\Api\Http\Controllers\FrontendController::getDataNcds4($request->all());  
        //dd($data['items']);
    @endphp
@endif
@extends('layouts.app_ncds')
@section('content')
@include('partials.search_ncds2')
<section class="row" id="app">
    <ncds2-list-component
        api_url="{{ route('api.ncds.ncds2-list') }}"
        api_url_read_more="{{ route('api.ncds.ncds2-list-readmore') }}"
        access_token="{{ env('NDCS_AUTH','$2y$10$GSWmnuo/iZFvFETuV/cD9O1D0T/wgxev558g/gPfcVrj6ih8lBFA.') }}"
        text_title="อัพเดทสถานการณ์ NCDs"
        text_situation_ncds_1="อัพเดทสถานการณ์ทั่วไป"
        text_situation_ncds_2="อัพเดทสถานการณ์ตามโรค"        
        text_read_more="อ่านต่อ"
        icon_ncds_eye="{{ asset('themes/thrc/images/ncds_eye.svg') }}"
        keyword="{{ (isset($input['keyword']) ? $input['keyword']:'') }}"
        issue="{{ (isset($input['issue']) ? $input['issue']:'')  }}"  
        template="{{ (isset($input['template']) ? $input['template']:'') }}"
        target="{{ (isset($input['target']) ? $input['target']:'')  }}"
        setting="{{ (isset($input['setting']) ? $input['setting']:'')  }}"
    ></ncds2-list-component>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>อัพเดทสถานการณ์ NCDs</title>
    <meta charset="UTF-8">
    <meta name="description" content="อัพเดทสถานการณ์ NCDs">
    <meta name="keywords" content="อัพเดทสถานการณ์ NCDs">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
<style>
    .tab_ncdsupdate{
        position: static;
    }
    .tab_ncdsupdate.sticky{
        position: fixed;
        z-index: 99
    }
    .tab_ncdsupdate.sticky > div{
        font-size: 1.05rem;
    }
</style>
@endsection
@section('js')
	@parent
<script>
 
</script>
@endsection