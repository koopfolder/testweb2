@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('roles::backend.group') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('roles::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.roles.index') }}">{{ trans('roles::backend.group') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.roles.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('roles::backend.add_group') }}</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> {{ trans('roles::backend.delete_all') }}</button>
							<!--
							<a href="{{ route('admin.roles.export', 'excel') }}" class="btn btn-success"><i class="fa fa-file-o"></i> Export Excel</a>
							-->
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('roles::backend.name') }}</th>
								<th>{{ trans('roles::backend.permission') }}</th>
								<th>{{ trans('roles::backend.status') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@php
								//dd($roles);
							@endphp
							@foreach ($roles as $role)
							<tr data-entry-id="{{ $role->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $role->id }}" id="select-all" /></td>
								<td>{{ $role->name }}</td>
                                <td>
                                    @foreach ($role->abilities()->pluck('name') as $ability)
                                        <span class="label label-info label-many">{{ $ability }}</span>
                                    @endforeach
                                </td>
								<td>
									@if ($role->status == 'publish')
									<span class="label label-info label-green">
										{{ trans('users::backend.publish') }}
									</span>
									@else
									<span class="label label-danger">
										{{ trans('users::backend.draft') }}
									</span>
									@endif
								</td>
								<td>
									<a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('roles::backend.edit') }}</a>
									<a href="{{ route('admin.roles.delete', $role->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> {{ trans('roles::backend.delete') }}</a>
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

