@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd("asdassds");
@endphp
@if(method_exists('App\Modules\Article\Http\Controllers\ArticleCategoryController', 'getDataHealthLiteracy'))   
    @php
        $data = App\Modules\Article\Http\Controllers\ArticleCategoryController::getDataHealthLiteracy($request->all());  
        //dd($data);
    @endphp
@endif
@extends('layouts.app')
@section('content')

<section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>{{ $data['title_h1'] }}</h1>
                </div>
            </div>
            <div class="row row_learning">
                
                @if($data['items']->count())
                    @foreach($data['items'] AS $key=>$value)
                        @php
                            
                            if($value->getMedia('cover_desktop')->isNotEmpty()){
                                $value->cover_desktop = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                            }else{
                                $value->cover_desktop = asset('themes/thrc/images/no-image-icon-3.jpg');
                            }
                         
                        @endphp
                        <div class="col-xs-12 col-sm-4 item_learning">
                            <a href="{{ route('list-health-literacy',\Hashids::encode($value->id)) }}">
                                <figure>
                                    <img src="{{ $value->cover_desktop }}" class="img-responsive">
                                </figure>
                                <figcaption>
                                        <h1 class="dotmaster">{{ $value->title }}</h1>
                                        <div class="readmore" href="#">อ่านต่อ</div>
                                </figcaption>
                            </a>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="row">
                <div class="col-xs-12 wrap_btn_viewmore">
                   
                </div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ประเด็นความรอบรู้สุขภาพ</title>
    <meta charset="UTF-8">
    <meta name="description" content="ประเด็นความรอบรู้สุขภาพ">
    <meta name="keywords" content="ประเด็นความรอบรู้สุขภาพ">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
<script>
    $(document).ready(function(){
        $('.owl-lmhl').on('initialized.owl.carousel changed.owl.carousel', function(event){ 
             $(".dotmaster").trigger("update.dot");
        });
        $(".owl-lmhl").owlCarousel({
            loop:true,
            margin:0,
            nav:false,
            dots:true,
             autoplay:true,
            autoplayTimeout:6000,
            //navText: ["<img src='images/owl-left-direction-arrow.svg'>","<img src='images/owl-right-thin-chevron.svg'>"],
            slideBy: 1,
            responsive:{
                0:{
                    items:1,
                    margin:0
                    //slideBy: 3
                },
                500:{
                    items:1
                },
                768:{
                    margin:0,
                    items:1
                }
            }
        }); 


		{{--  $.ajax({
			url: '{{ route("admin.manager.AjaxUpdateOrder") }}',
			type: "POST",
			dataType: 'json',
			data: {_token:jQuery('meta[name="csrf-token"]').attr('content')},
			success:function(response){
				console.log(response);
			}
		});  --}}


    });
</script>
@endsection
@section('js')
	@parent

@endsection