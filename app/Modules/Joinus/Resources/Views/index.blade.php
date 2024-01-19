@extends('layout::app')
@section('content')
@php
		//dd("View");
@endphp
<section class="content-header">
	<h1>{{ trans('joinus::backend.career') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('joinus::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.career.index') }}">{{ trans('joinus::backend.career') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
						    <!--
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'joinus', 'table' => 'joinus']) }}" class="btn btn-success">
								<i class="fa fa-file-o"></i> {{ trans('joinus::backend.excel') }}
							</a> -->
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('joinus::backend.name') }}</th>
								<th>{{ trans('joinus::backend.position') }}</th>
								<th>{{ trans('joinus::backend.phone') }}</th>
								<th>{{ trans('joinus::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@php
								//dd($items);
							@endphp
							@foreach ($items as $item)
							@php
								//dd($item);
							@endphp
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>
									@if($item->prefix =='2')
										{{ 'นาง' }}
        							@elseif($item->prefix =='3')
            							{{ 'นางสาว' }}
            						@elseif($item->prefix =='4')
            							{{ 'นางหรือนางสาว' }}
        							@else
        								{{ 'นาย' }}
        							@endif
									{{ $item->name." ".$item->surname }}
								</td>
								<td>
									{{ (isset($item->position) ? $item->position->position_th:'')  }}
								</td>
								<td>
									{{ $item->phone }}
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="{{ route('admin.viewmore.index',$item->id) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> View More
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
            	</div>
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