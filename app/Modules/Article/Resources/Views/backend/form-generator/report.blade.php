@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('article::backend.form-generator-report') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('article::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.thaihealth-watch.index') }}">{{ trans('article::backend.form-generator') }}</a></li>
		<li><a >{{ trans('article::backend.form-generator-report') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.thaihealth-watch.form-generator.report-excel',$id) }}" class="btn btn-success">
								<i class="fa fa-file-excel-o"></i> {{ trans('article::backend.export_excel') }}
							</a>	
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('article::backend.title') }}</th>
								<th>{{ trans('article::backend.token') }}</th>
								<th>{{ trans('article::backend.created') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php 
								$json  = json_decode($item->json_data);
								$json_article = json_decode($item->name->dol_json_data);
								//dd($json_article);
								
							@endphp
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->name->title }}</td>
								<td>{{ $json->_token }}</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									<a href="#" class="btn btn-success" data-toggle="modal" data-target="#reportModal{{ $item->id }}">
										<i class="fa fa-table"></i> {{ trans('article::backend.description_report') }}
									</a>
								</td>
							</tr>

							<!-- Modal -->
							<div class="modal fade" id="reportModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">{{ trans('article::backend.description_report') }} "{{ $item->name->title }}"</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="container">
										<div class="row">
											@foreach($json AS $key_json=>$value_json)
											@php 
												//dd($key_json,$value_json);
											@endphp 
											<div class="col-sm">
											<b>{{ $key_json }}</b> :  {{ $value_json }} <br>
											</div>
											@endforeach
										</div>
									</div>										

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
								</div>
								</div>
							</div>
							</div>							
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