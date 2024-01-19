@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Categories</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['url' => route('admin.categories.deleteAll')]) }}
		<div class="col-xs-8">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete selected</button>
							<a href="{{ route('admin.categories.export', 'excel') }}" data-toggle="confirmation" data-title="Are You Sure?" class="btn btn-primary"><i class="fa fa-file-o"></i> Export CSV</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Title</th>
								<th>Module</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($categories as $category)
							<tr data-entry-id="{{ $category->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $category->id }}" id="select-all" /></td>
								<td>{{ str_limit($category->title, 60) }}</td>
								<td>{{ $category->module }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($category->status) }}
									</span>
								</td>
								<td>
									<a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
									<a href="{{ route('admin.categories.delete', $category->id) }}" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
								</td>
							</tr>
							
							 @if ($category->children->count() > 0)
								@foreach($category->children as $children)
									@include('categories::partials.children', ['category' => $children])
								@endforeach
							@endif

							@endforeach
						</tbody>
					</table>
				</div>
        	</div>
    	</div>
		{{ Form::close() }}

		{{ Form::open(['url' => route('admin.categories.create'), 'files' => true]) }}
        {{ Form::hidden('user_id', auth()->user()->id) }}
		{{ Form::hidden('models', 'news') }}
        <div class="col-xs-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Category</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
						{!! Form::label('parent_category', 'Parent Category *', ['class' => 'control-label']) !!}
                        <select name="parent_id" class="form-control">
                            <option value="0">None</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                            @if (old('parent_id') == $category->id)
                                {{ 'selected="selected"' }}
                            @endif
                            >{{ str_limit($category->title, 50) }}</option>

							 @if ($category->children->count() > 0)
							@foreach($category->children as $children)
							<option value="{{ $children->id }}"> - {{ $children->title }}</option>
							@endforeach
							@endif

                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
						{!! Form::label('title', 'Title *', ['class' => 'control-label']) !!}
						{!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter Title']) !!}
                    </div>
                    <div class="form-group">
						{!! Form::label('module', 'Module', ['class' => 'control-label']) !!}
						<select name="module" class="form-control">
							@foreach($modules->toArray() as $key => $value)
							<option value="{{ $value }}">{{ $key }}</option>
							@endforeach 
						</select>
                    </div>
                    <div class="form-group">
						<label>Status</label>
						{{ Form::select('status', ['open' => 'Open', 'close' => 'Closed'], old('status'), ['class' => 'form-control']) }}
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