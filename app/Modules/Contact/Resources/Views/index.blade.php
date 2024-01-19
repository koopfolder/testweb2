@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('contact::backend.contact_us') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('contact::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.contact.index') }}">{{ trans('contact::backend.contact_us') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.contact.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('contact::backend.delete_all') }}
							</button>
							<!--
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'contact', 'table' => 'contacts']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> Excel
							</a>
							-->
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('contact::backend.name') }}</th>
								<th>{{ trans('contact::backend.email') }}</th>
								<th>{{ trans('contact::backend.subject') }}</th>
								<th>{{ trans('contact::backend.message') }}</th>
								<th>{{ trans('contact::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->name }}</td>
								<td>{{ $item->email }}</td>
								<td>{{ ($item->subject ? $item->subject->title:'') }}</td>
								<td>{{ $item->message }}</td>
								<td>{{ date('Y M, d', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.contact.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('contact::backend.delete') }}
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
            	</div>

				{{ Form::close() }}
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