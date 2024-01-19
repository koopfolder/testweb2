@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('article::backend.health-literacy') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('article::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.health-literacy.index') }}">{{ trans('article::backend.health-literacy') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
				{{ Form::open(['url' => route('admin.health-literacy.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.health-literacy.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('article::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('article::backend.delete_all') }}
							</button>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatableManager" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('article::backend.title') }}</th>
								<th>{{ trans('article::backend.health-literacy-category2') }}</th>
								<th>{{ trans('article::backend.status') }}</th>
								<th>{{ trans('article::backend.api') }}</th>
								<th>{{ trans('article::backend.created') }}</th>
								<th>{{ trans('article::backend.last_updated_by') }}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($items as $item)
							@php
								//dd($item);
							@endphp
							<tr data-entry-id="{{ $item->id }}">
								<td width="5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" id="select-all" /></td>
								<td>{{ $item->title }}</td>
								<td>{{ isset($item->category->title) ? $item->category->title:'' }}</td>
								<td>
									@if($item->status == 'publish')
									<span class="label label-info">
										{{  trans('article::backend.publish')  }}
									</span>
									@else
									<span class="label label-danger">
										{{  trans('article::backend.draft')  }}
									</span>
									@endif
								</td>
								<td id="api_{{ $item->id }}">
									@if($item->dol_UploadFileID =='')
										<a href="javascript:" onclick="update('api','{{ $item->id }}')" title="อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ ($item->api =='publish' ? trans('api::backend.draft'):trans('api::backend.publish')) }}">
											@if($item->api == 'publish')
											<span class="label label-info">
												{{  trans('api::backend.publish')  }}
											</span>
											@else
											<span class="label label-danger">
												{{  trans('api::backend.draft')  }}
											</span>
											@endif									
										</a> 
									@endif
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								@php
									$user_name = '';
									if(isset($item->updatedBy->name)){
										$user_name = $item->updatedBy->name;
									}else if(isset($item->createdBy->name)){
										$user_name = $item->createdBy->name;
									}
								@endphp
								<td>{{ $user_name }}</td>
								<td>
									<a href="{{ route('article-detail',$item->slug) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> {{ trans('article::backend.preview') }}
									</a>
									<a href="{{ route('admin.health-literacy.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('article::backend.edit') }}
									</a>
									@if($item->status !='publish')
									<a href="{{ route('admin.health-literacy.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('article::backend.delete') }}
									</a>
									@endif
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
<script src="{{ asset('adminlte/bower_components/axios/axios.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/jquery-loading-master/dist/jquery.loading.js') }}"></script>
<script type="text/javascript">
		let url_list_media_update_status = '{{  route('admin.api.list-media.update-status') }}';
		jQuery(document).ready(function(){
			jQuery('#datatableManager').DataTable({
      			'paging'      : true,
      			'lengthChange': true,
      			'searching'   : true,
      			'ordering'    : true,
      			'info'        : true,
      			'autoWidth'   : true,
      			filterDropDown: {
					columns: [
							{
								idx: 2
							}
							],
					bootstrap: true
				}
    		});
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

function update(field,id){
	let val;

	$('.content').loading('toggle');
	//console.log(field,id,val);

	axios.post(url_list_media_update_status,{
		field:field,
		id:id,
		val:val,	
		media_type:'article'
	  })
	  .then(function (response) {
	        // handle success
	    //console.log(response.data);
	    $('.content').loading('toggle');
	    if(response.status === 200){

			switch(response.data.field) {

		  	  	case 'api':
			    	if(response.data.status_data === true){
			    		$("#api_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ trans('api::backend.draft')}}");
			    		$("#api_"+id+' a span').attr('class', 'label label-info');
				    	$("#api_"+id+' a span').text('{{ trans('api::backend.publish') }}');
			    	}else{
			    		$("#api_"+id+' a span').attr("title", "อัพเดทข้อมูล {{ trans('api::backend.api') }} = {{ trans('api::backend.publish')}}");
			    		$("#api_"+id+' a span').attr('class', 'label label-danger');
				    	$("#api_"+id+' a span').text('{{ trans('api::backend.draft') }}');
			    	}
			    break;
			  	
			  default:
			    // code block

			}	    	
	    }

	  })
	  .catch(function (error) {
	        // handle error
	        console.log(error);
	  })
	  .then(function () {
	        // always executed
	});

}



	</script>
@endsection