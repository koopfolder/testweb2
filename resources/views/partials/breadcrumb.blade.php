@inject('request', 'Illuminate\Http\Request')
@php
	$segment_2 = $request->segment(2);
	$segment_3 = $request->segment(3);
	$segment_4 = $request->segment(4);
	//dd($segment_2,$segment_3,$segment_4);
@endphp
<div class="zone_breadcrumb">
		@if($segment_2 !='' && $segment_3 !='')
			@if($segment_2 !='preview')
			{!! Breadcrumbs::render($segment_2."-".$segment_3) !!}
			@endif
		@else
			@if($segment_2 !='preview')
			{!! Breadcrumbs::render($segment_2) !!}
			@endif
		@endif
</div>



