@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('users::backend.connectionauthority') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('users::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.connectionauthority.index') }}">{{ trans('users::backend.connectionauthority') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.connectionauthority.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.connectionauthority.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('users::backend.add') }}</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> {{ trans('users::backend.delete_all') }}</button>
							<!--
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'Users', 'table' => 'users']) }}" class="btn btn-success"><i class="fa fa-file-o"></i> Excel</a>
							-->
							
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatableConnectionauthority" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('users::backend.client_id') }}</th>
								<th>{{ trans('users::backend.client_company_name') }}</th>
								<th>{{ trans('users::backend.status') }}</th>
								<th>{{ trans('users::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($connectionauthority as $conn)
							<tr data-entry-id="{{ $conn->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $conn->id }}" id="select-all" /></td>
								<td>{{ $conn->client_id }}</td>
								<td>{{ $conn->client_company_name }}</td>
								<td>
									@if ($conn->client_status == '1')		
										{{ trans('users::backend.open') }}
									@else
										{{ trans('users::backend.closed') }}
									@endif
								</td>
								<td>{{ date('d/m/Y', strtotime($conn->created_at))  }}</td>					
								<td>
									<a href="{{ route('admin.connectionauthority.edit', $conn->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('users::backend.edit') }}</a>
									<a href="{{ route('admin.connectionauthority.delete', $conn->id) }}" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> {{ trans('users::backend.delete') }}</a>
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
<script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/jquery-ui/jquery-ui.js') }}"></script>

    <script>
        $(function() {
		 	i = 0;
    		$('#datatableConnectionauthority').DataTable({
      			'paging'      : true,
      			'lengthChange': true,
      			'searching'   : true,
      			'ordering'    : true,
      			'info'        : true,
      			'autoWidth'   : true,
            	initComplete: function () {
                     this.api().columns().every(function () {
                     	 i++;
                         var column = this;
                     	 if(i == 4){
                      	//  console.log(column.header(),i);
	                         $(column.header()).append("<br>")
			                	var select = $('<select><option value="">ทั้งหมด</option></select>')				
			                    .appendTo($(column.header()))
			                             .on('change', function () {
			                                 var val = $.fn.dataTable.util.escapeRegex(
			                                     $(this).val()
			                                 );
			                                 column
			                                     .search(val ? '^' + val + '$' : '', true, false)
			                                     .draw();
			                             });
			                        //  column.data().unique().sort().each(function (d, j) {
			                             select.append('<option value="เปิด">เปิด</option><option value="ปิด">ปิด</option>')
			                		// });	
									
                     	 }/*Endif*/
           			 });
        		}
    		});


        
        });
    </script>
@endsection

