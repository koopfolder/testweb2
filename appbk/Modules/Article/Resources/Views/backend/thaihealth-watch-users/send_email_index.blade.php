@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('article::backend.register') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('article::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.thaihealth-watch.users.index') }}">{{ trans('article::backend.register') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.thaihealth-watch.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.thaihealth-watch.users.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('article::backend.send_email') }}
							</a>
							<a href="{{ route('admin.thaihealth-watch.users.logs-send-email.index') }}" class="btn btn-primary">
								{{ trans('article::backend.logs_send_email') }}
							</a>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('article::backend.subject') }}</th>
								<th>{{ trans('article::backend.description') }}</th>
								<th>{{ trans('article::backend.to') }}</th>
								<th>{{ trans('article::backend.created') }}</th>
								<th>{{ trans('article::backend.created_by') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->subject }}</td>
								<td>{!! $item->description !!}</td>
								<td>
									@php  
										$to_array = json_decode($item->to);
										//dd($to_array);
									@endphp
									@foreach($to_array as $name)
										{{ $name }}, <br>
									@endforeach
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<th>{{ isset($item->createdBy) ? $item->createdBy->name:'' }}</th>
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