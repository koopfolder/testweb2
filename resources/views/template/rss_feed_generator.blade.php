@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
@endphp
@if(method_exists('App\Http\Controllers\FeedController', 'getDataList'))   
    @php
        $data = App\Http\Controllers\FeedController::getDataList($request->all());  
        //dd($data->count(),$menu);
    @endphp
@endif
@extends('layouts.app')
@section('content')
@include('partials.search')
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_sp">
                <div class="col-xs-12 head_inside">
                    <h1>{{ $menu['name'] }}</h1>
                </div>
                <div class="col-xs-12">

                </div>
            </div>

            <div class="row row_learning row_sp_list">
                @if($data->count())
                    @foreach($data AS $key=>$value)
                        <div class="col-xs-12 item_learning">
                            <a href="{{ $value['url'] }}" target="_blank">
                                <figcaption>
                                    <h1 class="dotmaster">{{ $value['title'] }}</h1>
                                    <p class="dotmaster">
                                        URL:{{ $value['url'] }}
                                    </p>
                                </figcaption>
                            </a>
                        </div>
                    @endforeach 
                @endif
            </div>

        </div>
    </section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>{{ ($menu['meta_title'] != '' ? $menu['meta_title']:$menu['name'])  }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu['meta_description'] }}">
    <meta name="keywords" content="{{ $menu['meta_keywords'] }}">
    <meta name="author" content="">
@endsection
@section('style')
	@parent
    <style>
        .box_search{
            margin-top: 15px;
            background-color: #eee;
            border-radius: 7px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .btn_search_sp{
            background-color: #ef7e25;
            width: 100%;
            height: 35px;
            text-align: center;
            color: #fff;
            border: 0;
        }
        .btn_search_sp img{
            width: 15px;
            height: auto;
            margin-right: 5px;
        }
        .sp_result_text{
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
        }
        .sp_display{
            text-align: right;
            margin-bottom: 25px;
        }
        .sp_display span{
            display: inline-block;
            font-size: 1.1rem;
            color: #333;
            vertical-align: middle;
        }
        .sp_display div{
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            cursor: pointer;
        }
        .sp_display div img{
            display: block;
            width: 22px;
            height: auto;
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
        }
        .sp_display div.active img{
            -webkit-filter: grayscale(0%);
            filter: grayscale(0%);
        }
        .item_learning p{
            height: 2em;
            line-height: 1;
            color: #666;
        }
        .row_sp_list .item_learning{
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .row_sp_list{
            margin-top: 20px;
            margin-bottom: 70px;
        }
        @media (max-width: 767px) {
            .btn_search_sp, .btn_reset{
                margin-top: 15px;
            }
            .box_search{
                margin-top: 10;
                padding: 15px;
            }
        }
    </style>
@endsection
@section('js')
	@parent
<script>
$(document).ready(function(){



});
</script>
@endsection