@inject('request', 'Illuminate\Http\Request')
@extends('layout::app')
@section('content')
<section class="content-header">
    @if($request->segment(3) =='left')
            <h1>{{ trans('footermenus::backend.footer_menu') }} {{ trans('footermenus::backend.footer_menu_left') }}</h1>
    @elseif($request->segment(3)  =='center')
            <h1>Footer Center Menu</h1>
    @elseif($request->segment(3)  =='right')
            <h1>{{ trans('footermenus::backend.footer_menu') }} {{ trans('footermenus::backend.footer_menu_right') }}</h1>
    @else

    @endif
	<ol class="breadcrumb">
    	    <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('footermenus::backend.footer_menu') }}</a></li>
        @if($request->segment(3) =='left')
            <li><a href="{{ route('admin.footermenus.left.index') }}">{{ trans('footermenus::backend.footer_menu') }} {{ trans('footermenus::backend.footer_menu_left') }}</a></li>
        @elseif($request->segment(3)  =='center')
            <li><a href="{{ route('admin.footermenus.center.index') }}">Footer Center Menu</a></li>
        @elseif($request->segment(3)  =='right')
            <li><a href="{{ route('admin.footermenus.right.index') }}">{{ trans('footermenus::backend.footer_menu') }} {{ trans('footermenus::backend.footer_menu_right') }}</a></li>
        @else

        @endif
	</ol>
</section>
<section class="content">
	<div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_frontend" data-toggle="tab">Frontend</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_frontend">
                    	@include('footermenus::partials.menus', ['items' => $frontend, 'site' => 'frontend'])
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection