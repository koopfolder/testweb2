@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>Categories</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="{{ route('admin.news.categories.index') }}">Categories</a></li>
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
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> Delete selected</button>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>Title</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($categories as $row)
							<tr data-entry-id="{{ $row->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $row->id }}" id="select-all" /></td>
								<td>{{ str_limit($row->title, 60) }}</td>
								<td><span class="label label-info label-green">{{ strtoupper($row->status) }}</span></td>
								<td>
									<a href="{{ route('admin.news.categories.edit', $row->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> Edit</a>
									<a href="{{ route('admin.news.categories.delete', $row->id) }}" 
											data-toggle="confirmation" 
											data-title="Are You Sure?"
											class="btn btn-danger "><i class="fa fa-trash-o"></i> Delete</a>
								</td>
							</tr>
							
							 @if ($row->children->count() > 0)
								@foreach($row->children as $children)
									@include('news::partials.children', ['category' => $children])
								@endforeach
							@endif

							@endforeach
						</tbody>
					</table>
				</div>
        	</div>
    	</div>
		{{ Form::close() }}

		{{ Form::open(['url' => route('admin.categories.edit', $category->id)]) }}
		{{ Form::hidden('module', 'news') }}
        <div class="col-xs-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Category</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
						<label>Parent Category <span style="color:red">*</span></label>
                        <select name="parent_id" class="form-control">
                            <option value="0">None</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}"
								@if ($category->parent_id == $item->id)
									{{ 'selected="selected"' }}
								@endif

								@if($category->id == $item->id)
									{{ 'disabled="disabled"' }}
								@endif

                            >{{ $item->title }}</option>

							 @if ($item->children->count() > 0)
								@foreach($item->children as $children)
									<option value="{{ $children->id }}"
									@if ($category->id == $children->parent_id)
										{{ 'selected="selected"' }}
									@endif
									@if($category->id == $children->id)
										{{ 'disabled="disabled"' }}
									@endif
									> - {{ $children->title }}</option>
								@endforeach
							@endif

                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
						<label>Title <span style="color:red">*</span></label>
						{!! Form::text('title', $category->title ?? old('title'), ['class' => 'form-control', 'placeholder' => 'Enter Title']) !!}
                    </div>
                    <div class="form-group">
						<label>Status <span style="color:red">*</span></label>
						{{ Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], $category->status ?? old('status'), ['class' => 'form-control']) }}
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