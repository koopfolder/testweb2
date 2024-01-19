@extends('layout::app')

@section('content')
<section class="content-header">
	<h1>{{ trans('menus::backend.menu') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('menus::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.menus.index') }}">{{ trans('menus::backend.menu') }}</a></li>
	</ol>
</section
>
<section class="content">
	<div class="row">
        
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_frontend" data-toggle="tab">Frontend</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_frontend">
                    	@include('menus::partials.menus', ['items' => $frontend, 'site' => 'frontend'])
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection