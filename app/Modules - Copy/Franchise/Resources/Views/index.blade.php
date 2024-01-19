@extends('layout::app')
@section('content')
<section class="content-header">
	<h1>{{ trans('franchise::backend.franchise') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('franchise::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.franchise.index') }}">{{ trans('franchise::backend.franchise') }}</a></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        	<div class="box">
        		<div class="col-xs-3" style="margin-bottom: 4%;padding: 10px;display: none;" id="import">
					{{ Form::open(['url' => route('admin.franchise.import'),'files' => true,'id'=>'form_main']) }}
	                    {!! Form::file("file_franchise",["id"=>"file_franchise",'class'=>'form-contro']) !!}
	                    <p class="help-block">Format file: xlsx (no more than 5M)</p>
						Example:<a href="{{ asset('files/franchise/franchise.xlsx') }}">Franchise.xlsx</a>
						<br>
						<button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('franchise::backend.import') }}</button>
					{!! Form::close() !!}
				</div>

				{{ Form::open(['url' => route('admin.franchise.deleteAll')]) }}
				<div class="box-body">
					<div class="row">
						<div class="col-xs-12">
							<a href="{{ route('admin.franchise.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> {{ trans('franchise::backend.add') }}
							</a>
							<button type="submit" class="btn btn-danger" data-toggle="confirmation" data-title="Are You Sure?">
								<i class="fa fa-trash-o"></i> {{ trans('franchise::backend.delete_all') }}
							</button>
							<a class="btn btn-success import">
								{{ trans('franchise::backend.import') }}
							</a>
							<a href="{{ route('admin.export.index', ['moduleSlug' => 'franchise', 'table' => 'franchise']) }}" class="btn btn-success">
									<i class="fa fa-file-o"></i> Excel
							</a>
						</div>
					</div>
				</div>
            	<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all" /></th>
								<th>{{ trans('franchise::backend.brand_name') }}</th>
								<th>{{ trans('franchise::backend.description') }}</th>
								<th>{{ trans('franchise::backend.status') }}</th>
								<th>{{ trans('franchise::backend.created') }}</th>
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
										{{ $item->status == 'publish' ? trans('franchise::backend.publish')  : trans('franchise::backend.draft')  }}
									</span>
								</td>
								<td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
								<td>
									
									<a href="{{ route('preview-franchise',$item->slug) }}" class="btn btn-success" target="_blank">
										<i class="fa fa-eye"></i> Preview
									</a> 
									
									<a href="{{ route('admin.franchise.edit', $item->id) }}" class="btn btn-primary">
										<i class="fa fa-gear"></i> {{ trans('franchise::backend.edit') }}
									</a>
									<a href="{{ route('admin.franchise.delete', $item->id) }}" data-toggle="confirmation" data-title="Are you sure ?" class="btn btn-danger">
										<i class="fa fa-trash-o"></i> {{ trans('franchise::backend.delete') }}
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

			jQuery('.import').click(function(event) {
				jQuery('#import').toggle('slow');
			});


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
