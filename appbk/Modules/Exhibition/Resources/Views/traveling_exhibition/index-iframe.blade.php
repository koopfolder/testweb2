@extends('layout::app-iframe')
@section('content')
<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.article.deleteAll')]) }}
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('article::backend.title') }}</th>
								<th>{{ trans('article::backend.status') }}</th>
								<th>{{ trans('article::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->title }}</td>
								<td>
									<span class="label label-info label-green">
										{{ $item->status == 'publish' ? trans('article::backend.publish')  : trans('article::backend.draft')  }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a class="btn btn-primary" onclick="window.parent.getItemCaseTravelingExhibition('{{ $item->id }}','{{ $item->title }}');">
										{{ 'Select' }}
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
@section('css')
<style type="text/css">
		.content-wrapper{
			margin-left:0px !important;
			background-color:#FFF !important;
		}
		.box{
			border-top:none !important;
			border-radius:0px !important;
			box-shadow:none !important;
		}
</style>
@endsection