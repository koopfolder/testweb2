@php
    $category_main_banner = ThrcHelpers::getSetting(['slug'=>'main_banner','retrieving_results'=>'first']);
    if(collect($category_main_banner)->isNotEmpty()){
      $main_banner = ThrcHelpers::getBanners(['category'=>$category_main_banner->value,'retrieving_results'=>'get','case_query'=>'none']);
    }
    //dd($main_banner);
    //$facebook = ThrcHelpers::getSetting(['slug'=>'facebook','retrieving_results'=>'first']);
    //$instagram = ThrcHelpers::getSetting(['slug'=>'instagram','retrieving_results'=>'first']);
    //$youtube = ThrcHelpers::getSetting(['slug'=>'youtube','retrieving_results'=>'first']);

@endphp
  <section class="row wow fadeInDown row_slide">

      <div class="container">
          <div class="row">
              <div class="col-xs-12 banner_slide">
                  <div class="website_name"><span>www.resourcecenter.thaihealth.or.th</span></div>
                  <div class="flexbanner flexslider">
                    <ul class="slides">
                      @if(collect($main_banner)->count())
                        @foreach($main_banner AS $key =>$value)
                        <li>
                            <a href="{{ $value['link'] }}">
                            <img data-src="{{ $value['image_desktop'] }}" src="{{ $value['image_desktop'] }}" alt="{{ $value['name'] }}" />
                            </a>
<!--
                            <div class="banner_caption">
                                <hgroup>
                                    <h1>{{ $value['name'] }}</h1>
                                </hgroup>
                                {!! $value['description'] !!}
                                <a href="{{ $value['link'] }}">อ่านต่อ</a>
                            </div>
-->
                        </li>
                        @endforeach
                      @endif
                    </ul>
                  </div>
<!--
                  <div class="number_slide">
                      <div class="current-slide"></div><div>/</div><div class="total-slides"></div>
                  </div>
-->

              </div>
          </div>
      </div>
  </section>
@section('style')
  @parent

@endsection
@section('js')
    @parent
<script>

</script>
@endsection