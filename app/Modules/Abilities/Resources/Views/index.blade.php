@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Abilities</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.abilities.index') }}">Abilities</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
            	<div class="box-header">
              		<h3 class="box-title">Abilities</h3>
				</div>
				{{ Form::open(['url' => route('admin.abilities.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.abilities.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Ability</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> Delete selected</button>
							<a href="{{ route('admin.abilities.export', 'excel') }}" class="btn btn-primary"><i class="fa fa-file-o"></i> Export CSV</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>ID</th>
								<th>Name</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($abilities as $ability)
							<tr data-entry-id="{{ $ability->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $ability->id }}" id="select-all" /></td>
								<td>{{ $ability->id }}</td>
								<td>{{ $ability->name }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($ability->status) }}
									</span>
								</td>
								<td>
									<a href="{{ route('admin.abilities.edit', $ability->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
									<a href="{{ route('admin.abilities.delete', $ability->id) }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
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

