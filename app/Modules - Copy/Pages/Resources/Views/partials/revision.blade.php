<table id="datatable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>ชื่อ</th>
			<th>สถานะ</th>
			<th>สร้างเมื่อ</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($pages as $page)
		<tr data-entry-id="{{ $page->id }}">
			<td>{{ $page->title }}</td>
			<td>
				<span class="label label-info label-green">
					{{ strtoupper($page->status) }}
				</span>
			</td>
			<td>{{ date('d M Y, H:i', strtotime($page->created_at)) }}</td>
			<td>
				<a href="{{ route('admin.pages.revision', $page->id) }}" data-toggle="confirmation" data-title="ต้องการย้อนกลับ?" class="btn btn-primary"><i class="fa fa-eye"></i> ย้อนกลับ</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>