@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ $categoryName ? "สินค้าในหมวด " . $categoryName : "สินค้าทั้งหมด" }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
    	<li><a href="{{ route('admin.product.index') }}">สินค้า</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.product.deleteAll')]) }}
				{{ Form::hidden('redirect', Request::get('category')) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.product.create', ['category' => Request::get('category')]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่มใม่</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> ลบที่เลือก</button>
							<a href="{{ route('admin.product.export', ['fileType' => 'excel', 'category' => Request::get('category')]) }}" class="btn btn-success"><i class="fa fa-file-o"></i> ส่งออก Excel</a>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>รูปภาพ</th>
								<th>ชื่อสินค้า</th>
								<th>ราคา</th>
								<th>สถานะ</th>
								<th>สร้างเมื่อ</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($products as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>
									@if ( $item->getMedia('desktop')->count() > 0)
										<img src="{{ asset($item->getMedia('desktop')->first()->getUrl()) }}" width="100">
									@endif
								</td>
								<td>{{ str_limit($item->title, 50) }}</td>
								<td>{{ number_format($item->excerpt, 2) }}</td>
								<td>
									<span class="label label-info label-green">
										{{ strtoupper($item->status) }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.product.edit', ['id' => $item->id, 'category' => Request::get('category')]) }}" class="btn btn-primary"><i class="fa fa-gear"></i> แก้ไข</a>
									<a href="{{ route('admin.product.delete', ['id' => $item->id, 'category' => Request::get('category')]) }}" data-toggle="confirmation" data-title="ต้องการลบ?" class="btn btn-danger"><i class="fa fa-trash-o"></i> ลบ</a>
									
									@if ($item->status == 'approve')
										@can('manage-approve')
										<a href="{{ route('admin.product.publish.index', ['id' => $item->id, 'status' => 'publish']) }}" data-toggle="confirmation" data-title="ต้องอนุมัติ?" class="btn btn-success"><i class="fa fa-check"></i> อนุมัติ</a>
										@endcan
									@endif

								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
	                <div class="box-footer">
	                    <a href="{{ route('admin.product.categories.index') }}" class="btn btn-default">
	                    	<i class="fa fa-arrow-left"></i> กลับ
	                   	</a>
	                </div>
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