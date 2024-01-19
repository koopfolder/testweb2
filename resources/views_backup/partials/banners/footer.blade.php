
@php
$category_footer_banner = ThrcHelpers::getSetting(['slug'=>'footer_banner','retrieving_results'=>'first']);
if(collect($category_footer_banner)->isNotEmpty()){
  $footer_banner = ThrcHelpers::getBanners(['category'=>$category_footer_banner->value,'retrieving_results'=>'get','case_query'=>'none']);
}
//dd($footer_banner);

@endphp
@if(collect($footer_banner)->count())
<section class="row wow fadeInDown row_lb">
        <span></span>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 wrap_lb">
                    <div class="owl-lb owl-carousel owl-theme">
                        @foreach($footer_banner AS $key =>$value)
                        <div><a href="{{ $value['link'] }}"><img src="{{ $value['image_desktop'] }}" alt="{{ $value['name'] }}"></a></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
</section>
@endif