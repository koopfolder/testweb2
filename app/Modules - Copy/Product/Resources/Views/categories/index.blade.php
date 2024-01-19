@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>หมวดหมู่</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
    	<li><a href="{{ route('admin.news.categories.index') }}">หมวดหมู่</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['url' => route('admin.categories.deleteAll')]) }}
		<div class="col-xs-12">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.product.categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่มใหม่</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> ลบที่เลือก</button>
							<a href="{{ route('admin.product.categories.export', 'excel') }}" class="btn btn-success"><i class="fa fa-file-o"></i> ส่งออก Excel</a>
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>รูปภาพ</th>
								<th>หมวดหมู่</th>
								<th>จำนวนสินค้า</th>
								<th>เรียงจาก</th>
								<th>สถานะ</th>
								<th>สร้างเมื่อ</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($categories as $category)
							@can('page-' . $category->slug)
							<tr data-entry-id="{{ $category->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $category->id }}" id="select-all" /></td>
								<td>
									@if ($category->getMedia('bg')->isNotEmpty())
										<img src="{{ asset($category->getMedia('bg')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ str_limit($category->title, 60) }}</td>
								<td>{{ $category->posts()->get()->count() }}</td>
								<td>{{ $category->order }}</td>
								<td><span class="label label-info label-green">{{ strtoupper($category->status) }}</span></td>
								<td>{{ date('d M Y', strtotime($category->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.product.index', ['category' => $category->id]) }}" class="btn btn-success"><i class="fa fa-shopping-cart"></i> สินค้า</a>
									<a href="{{ route('admin.product.categories.edit', $category->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> แก้ไข</a>
									<a href="{{ route('admin.product.categories.delete', $category->id) }}" data-toggle="confirmation" data-title="ต้องการลบ?" class="btn btn-danger" ><i class="fa fa-trash-o"></i> ลบ</a>
								</td>
							</tr>
							@endcan
							@endforeach
						</tbody>
					</table>
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