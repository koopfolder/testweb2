@extends('layout::app')

@section('content')
<section class="content-header">
	<h1>{{ trans('exhibition::backend.exhibition_master') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.exhibition.master.index') }}">{{ trans('exhibition::backend.exhibition_master') }}</a></li>
	</ol>
</section
>
@php
    //dd($items);
@endphp
<section class="content">
	<div class="row">
        
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_frontend" data-toggle="tab">Frontend</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_frontend">
                    	@include('exhibition::partials.exhibition_master.items', ['items' => $items])
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection