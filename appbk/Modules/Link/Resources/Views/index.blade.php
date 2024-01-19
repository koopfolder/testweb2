@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Link</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.link.index') }}">Link</a></li>
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
                    	@include('link::partials.menus', ['items' => $frontend, 'site' => 'frontend'])
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
	$(function() {
		 $("#select-all").click(function () {
		     $('input:checkbox').not(this).prop('checked', this.checked);
		 });
	});
</script>
@stop