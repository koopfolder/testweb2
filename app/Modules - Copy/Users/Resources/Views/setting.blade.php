@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Setting</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.users.setting') }}">Setting</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['url' => route('admin.users.setting.deleteAll')]) }}
		<div class="col-xs-8">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete selected</button>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Name</th>
								<th>Value</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($settings as $setting)
							<tr data-entry-id="{{ $setting->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $setting->id }}" id="select-all" /></td>
								<td>{{ $setting->name }}</td>
								<td>{{ $setting->value }}</td>
								<td><span class="label label-info label-green">{{ strtoupper($setting->status) }}</span></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
        	</div>
    	</div>
		{{ Form::close() }}

		{{ Form::open(['url' => route('admin.users.setting')]) }}
		{{ Form::hidden('module', 'users') }}
        <div class="col-xs-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Setting</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
						{!! Form::label('name', 'Name *', ['class' => 'control-label']) !!}
						{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Name']) !!}
                    </div>
                    <div class="form-group">
						{!! Form::label('value', 'Value *', ['class' => 'control-label']) !!}
						{!! Form::text('value', old('value'), ['class' => 'form-control', 'placeholder' => 'Enter Value']) !!}
                    </div>
                    <div class="form-group">
						<label>Status</label>
						{{ Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status'), ['class' => 'form-control']) }}
					</div>		
                </div>
                <div class="box-footer">
                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
                </div>
            </div>
        </div>
		{{ Form::close() }}
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