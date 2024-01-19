@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('users::backend.users') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('users::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.users.index') }}">{{ trans('users::backend.users') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.users.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ trans('users::backend.add') }}</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> {{ trans('users::backend.delete_all') }}</button>
							<!--
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'Users', 'table' => 'users']) }}" class="btn btn-success"><i class="fa fa-file-o"></i> Excel</a>
							-->
							
						</div>
					</div>
				</div>

            	<div class="box-body">
					<table id="datatableUser" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('users::backend.avatar') }}</th>
								<th>{{ trans('users::backend.name') }}</th>
								<th>{{ trans('users::backend.email') }}</th>
								<th>{{ trans('users::backend.group') }}</th>
								<th>{{ trans('users::backend.status') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($users as $user)
							@if ($user->roles()->pluck('name')->first() != 'Administrator')
							<tr data-entry-id="{{ $user->id }}">
								<td><input type="checkbox" name="ids[]" value="{{ $user->id }}" id="select-all" /></td>
								<td>
									@if ( $user->getMedia('avatar')->count() > 0)
										<img src="{{ asset($user->getMedia('avatar')->first()->getUrl('thumb150x150')) }}" width="50">
									@else
										<img src="{{ asset('images/default-avatar.png') }}" width="50">
									@endif
								</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->email }}</td>
								<td>
                                    @foreach ($user->roles()->pluck('name') as $role)
                                        {{ $role }}
                                    @endforeach
								</td>
								<td>
									@if ($user->status == 'publish')
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
									<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary"><i class="fa fa-gear"></i> {{ trans('users::backend.edit') }}</a>
									<a href="{{ route('admin.users.delete', $user->id) }}" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?"><i class="fa fa-trash-o"></i> {{ trans('users::backend.delete') }}</a>
<!-- 									@if ($user->status == 'publish')
									<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success"><i class="fa fa-cloud-upload"></i> ปิดใช้งาน</a>
									@else
									<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success"><i class="fa fa-cloud-upload"></i> เปิดใช้งาน</a>
									@endif -->
								</td>
							</tr>
							@endif
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
    		$('#datatableUser').DataTable({
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
                     	 if(i == 5){
                      	 //console.log(column.header(),i);
	                         $(column.header()).append("<br>")
			                	var select = $('<select><option value=""></option></select>')
			                    .appendTo($(column.header()))
			                             .on('change', function () {
			                                 var val = $.fn.dataTable.util.escapeRegex(
			                                     $(this).val()
			                                 );

			                                 column
			                                     .search(val ? '^' + val + '$' : '', true, false)
			                                     .draw();
			                             });
			                         column.data().unique().sort().each(function (d, j) {
			                             select.append('<option value="' + d + '">' + d + '</option>')
			                	});	
                     	 }/*Endif*/
           			 });
        		}
    		});


        
        });
    </script>
@endsection

