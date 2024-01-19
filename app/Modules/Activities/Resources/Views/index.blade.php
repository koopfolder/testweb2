@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('activities::backend.edit_activities') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('sustainability::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.sustainability.corporate-gov-policy.index') }}">{{ trans('activities::backend.edit_activities') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.activities.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('sustainability::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('sustainability::backend.delete_all') }}
							</button>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('sustainability::backend.title') }}</th>
								<th>{{ trans('sustainability::backend.description') }}</th>
								<th>{{ trans('sustainability::backend.status') }}</th>
								<th>{{ trans('sustainability::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->title }}</td>
								<td>{!! $item->description !!}</td>
								<td>
									<span class="label label-info label-green">
										{{ $item->status == 'publish' ? trans('sustainability::backend.publish')  : trans('sustainability::backend.draft')  }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									
									<a href="{{ route('preview-activities') }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> Preview
									</a>
									
									<a href="{{ route('admin.activities.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('sustainability::backend.edit') }}
									</a>
									<a href="{{ route('admin.activities.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('sustainability::backend.delete') }}
									</a>
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
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('javascript')
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// jQuery('#datatableManager').DataTable({
   //    			'paging'      : true,
   //    			'lengthChange': true,
   //    			'searching'   : true,
   //    			'ordering'    : true,
   //    			'info'        : true,
   //    			'autoWidth'   : true,
   //    			filterDropDown: {
			// 		columns: [
			// 				{
			// 					idx: 3
			// 				}
			// 				],
			// 		bootstrap: true
			// 	}
   //  		});
   //  		jQuery("#datatableManager tbody").sortable();
			// jQuery('#update_order').click(function(){
			// data_array = [];
			// jQuery('#datatableManager > tbody  > tr').each(function() {
			//     if(typeof jQuery(this).attr('data-entry-id') !=='undefined'){
			//     	data_array.push(jQuery(this).attr('data-entry-id'));
			//     }
			// });
			// if(data_array.length >0){
			// 	jQuery.ajax({
			// 		    url: '{{ route("admin.manager.AjaxUpdateOrder") }}',
			// 		    type: "POST",
			// 		    dataType: 'json',
			// 		    data: {_token:jQuery('meta[name="csrf-token"]').attr('content'),data:data_array},
			// 		    success:function(response){
			// 		    	//console.log(response);
			// 			    if(response.status === true){
			// 			    	Command: toastr["success"]("Update Successfully");
			// 			    	location.reload();
			// 				}else{
			// 					Command: toastr["error"]("Error !");
			// 					location.reload();
			// 				}
			// 		    }
			// 	});
			// }
			// });
		});
	</script>
@endsection
