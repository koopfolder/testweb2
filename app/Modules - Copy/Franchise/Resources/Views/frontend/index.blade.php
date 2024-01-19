@php
	$lang = \App::getLocale();
@endphp
@extends('layouts.app')
@section('content')
@php
	//dd($data,$branch);
@endphp

@include('partials.franchise_search')
<section class="row">
        <div class="col-xs-12 wrap_content">
            <div class="container">
                <div class="row wrap_latestnews">
                    <div class="col-xs-12 col-sm-6">
                        <div class="flexslider">
                              <ul class="slides">
                                @if ($data->getMedia('cover_desktop')->isNotEmpty())
                                    <li><img src="{{ asset($data->getMedia('cover_desktop')->first()->getUrl()) }}" /></li>
                                @endif
                                @if ($data->getMedia('gallery_desktop')->isNotEmpty())
                                    @foreach($data->getMedia('gallery_desktop') AS $key=>$value)
                                    <li><img src="{{ asset($value->getUrl()) }}" /></li>
                                    @endforeach
                                @endif
                                @if(!$data->getMedia('cover_desktop')->isNotEmpty() && !$data->getMedia('gallery_desktop')->isNotEmpty())   
                                     <img src="{{ asset('themes/dbdfranchise/images/no-image-icon-3.jpg') }}">
                                @endif
                              </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="wrap_franchisename">
                            <div class="row">
                                <div class="col-xs-9">
                                    <h1>{{ $data->brand_name }}</h1>   
                                    <div class="news_boxdate">
                                        <div class="fc_star">
                                            <img src="{{ asset('themes/dbdfranchise/images/star.svg') }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/star.svg') }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/star.svg') }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/star.svg') }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/star.svg') }}">
                                        </div>
                                        <div class="list_view">71K</div>
                                        <div class="list_date">{{ Carbon\Carbon::parse($data->created_at)->format('d/m/').(Carbon\Carbon::parse($data->created_at)->format('Y')+543) }}</div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    @if ($data->getMedia('logo_desktop')->isNotEmpty())
                                    <img src="{{ asset($data->getMedia('logo_desktop')->first()->getUrl()) }}" class="img-responsive" alt="">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="icon_sharesocial">
                                        <a href=""><img src="{{ asset('themes/dbdfranchise/images/icon_facebook.svg') }}" alt=""></a>
                                        <a href=""><img src="{{ asset('themes/dbdfranchise/images/icon_twitter.svg') }}" alt=""></a>
                                        <a href=""><img src="{{ asset('themes/dbdfranchise/images/icon_google.svg') }}" alt=""></a>
                                    </div>
                                    <div>
                                        <!--
                                        <p>
                                            ฟรุตเทอร์เดย์ “FRUITURDAY” ร้านเครื่องดื่มและของหวานแนวใหม่ ที่เน้นผลิตภัณฑ์จากผลไม้สด โดยผ่านการคัดสรรคุณภาพของวัตถุดิบอย่างพิถีพิถันทุกขั้นตอน ที่ให้ทั้งความสดชื่น คุณประโยชน์จากผลไม้ และสามารถรับประทานได้ทุกวัน :)  
                                        </p>
                                        <p>
                                            ภายใต้คอนเซ็ป “ไม่สด ไม่ฉ่ำ เราไม่เสริฟ” การันตีความอร่อยด้วยหลากหลายรางวัลและการยอมรับจากทั้งนิตยสาร สื่อมีเดีย และสื่อโทรทัศน์ ทั้งช่อง 3 ช่อง 5, MCOT, TNN และอื่นๆอีกมากมาย
                                        </p>
                                    -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrap_detailfranchise">
                    <div class="row">
                        <div class="col-xs-12 col-sm-10">
                           <div class="row desc_listfranchise">
                                <div class="col-xs-6 col-sm-2"><span class="type_franchise">เลขทะเบียนนิติบุคคล :</span></div>
                                <div class="col-xs-6 col-sm-10">{{ $data->juristic_person_registration_number }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">ชื่อกิจการ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->brand_name }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">หมวดหมู่ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->category->category_name }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">ประเภท :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->franchise_type }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-12 col-sm-2"><span class="type_franchise">รายละเอียด :</span></div>
                                <div class="col-xs-12 col-sm-10">
                                    {!! $data->description !!}
                                </div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">งบลงทุนต่ำสุด :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ number_format($data->lowest_investment_budget) }} บาท</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">งบลงทุนสูงสุด :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ number_format($data->highest_investment_budget) }} บาท</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">ค่าแรกเข้า :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ number_format($data->franchise_fee) }} บาท</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">ค่ารายปี :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->royalty_fee }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2"><span class="type_franchise">จำนวนสาขา :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ ($data->number_of_branches !='' ? $data->number_of_branches.' สาขา':'') }}</div>
                            </div>
                            <div class="row desc_listfranchise box_branchsearch">
                                <div class="col-xs-12 col-sm-2"><span class="type_franchise">ค้นหาสาขา :</span></div>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="row wrap_branchsearchmain">
                                        <div class="col-xs-4 wrap_branchsearch">
                                            <div class="branch_search">
                                                <select>
                                                    <option>จังหวัด :</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 wrap_branchsearch">
                                            <div class="branch_search">
                                                <select>
                                                    <option>อำเภอ :</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 wrap_branchsearch">
                                            <div class="branch_search">
                                                <select>
                                                    <option>ตำบล :</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <button class="searchbar_btn">ค้นหา</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="shop_location">
                            <div id="map" class="embed-responsive embed-responsive-16by9"></div>
                            <!--   <div class="embed-responsive embed-responsive-16by9">
                                  <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3873.273698627863!2d100.48437951479556!3d13.88257599026293!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e284d7ad5ffe43%3A0xe4ce41eb21e9f3d9!2sDepartment+of+Business+Development+Ministry+of+Commerce!5e0!3m2!1sen!2sth!4v1548339905792" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                                </div>
                            -->
                           </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-9">
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">ชื่อผู้ติดต่อ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->contact_name }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">ที่อยู่ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->contact_address }} {{ $data->subdistrict->subdistrict }} {{ $data->district->district }} {{ $data->province->province }} {{ $data->contact_zipcode }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">โทรศัพท์ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->phone }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">โทรศัพท์มือถือ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->mobile }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">แฟกซ์ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->fax }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">อีเมล์ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->email }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-4 col-sm-2 mb_nopadright"><span class="type_franchise">เว็บไซต์ :</span></div>
                                <div class="col-xs-8 col-sm-10">{{ $data->website }}</div>
                            </div>
                            <div class="row desc_listfranchise">
                                <div class="col-xs-12 col-sm-2"><span class="type_franchise">Follow Us :</span></div>
                                <div class="col-xs-12 col-sm-10">
                                    <div class="shop_followus">
                                        <div><img src="{{ asset('themes/dbdfranchise/images/icon_line.svg') }}" alt=""> Line ID : {{ $data->line }}
                                        </div>     
                                        <div>
                                            <a href="{{ $data->facebook }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/icon_facebook.svg') }} " alt="facebook">{{ $data->facebook }}
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ $data->youtube }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/icon_youtube.svg') }}" alt="youtube">{{ $data->youtube }}</a>
                                        </div>
                                        <div>
                                            <a href="{{ $data->instagram }}">
                                            <img src="{{ asset('themes/dbdfranchise/images/icon_instragram.svg') }}" alt="instagram">
                                            {{ $data->instagram }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <a href="" class="btn_contactcustomer"><img src="{{ asset('themes/dbdfranchise/images/icon_message.svg') }}" alt=""> ติดต่อผู้ขาย</a>
                        </div>
                    </div>
                </div><!--wrap_detailfranchise-->
                <div class="pdf_download">
                    @if($attachments->count() >0)
                    <div class="franchise_titledownload">ดาวน์โหลด PDF</div>
                    @foreach($attachments AS $key=>$value)
                    <div class="pdf_boxdownload">
                        <div class="row">
                            <div class="col-xs-12 col-sm-9 col-md-10 box_filename">
                                <div class="pdf_filename">{{ $value->title }}</div>
                                <div class="pdf_amountdownload">(0 ครั้ง)</div>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-md-2">
                                <button class="btn_downloadfile">ดาวน์โหลด 
                                <div>
                                    <a href="{{ asset($value->file_path) }}" download="{{ $value->title }}.{{ $value->file_type }}"><img src="{{ asset('themes/dbdfranchise/images/download.svg') }}" alt=""></a>
                                </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div><!--pdf download-->
                <div class="box_fbplugin">
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = 'https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v3.2';
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                    
                    <div class="fb-comments" data-href="https://developers.facebook.com/docs/plugins/comments#configurator" data-numposts="10"></div>
                </div><!--box_fbplugin-->
            </div>
        </div>
</section>
@endsection
@section('meta')
    @parent
    <title></title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
@endsection
@section('js')
	@parent
    <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCL7ouNb0_8mh0qDvDy0Lrs1INk9IJ7Dxo" type="text/javascript"></script>

    <script type="text/javascript">
        $(window).load(function() {
            $('.flexslider').flexslider({
                animation: "slide",
                slideshowSpeed: 7000,
                animationSpeed: 2500,
                smoothHeight: true,
                slideshow: true,
                controlNav: true, //Boolean: Create navigation for paging control of each slide? Note: Leave true for manualControls usage
                directionNav: true,
                pauseOnHover: false
            });
        });

        brand_name = '{{ $data->brand_name }}';
        contact_latitude = '{{ $data->contact_latitude }}';
        contact_longitude = '{{ $data->contact_longitude }}';
        branch = <?php echo json_encode($branch); ?>;

        //console.log(branch);

        var locations = [];

        if(contact_latitude !='' && contact_longitude !=''){
            locations.push([brand_name,contact_latitude,contact_longitude]);
        }

        if(branch !== 'false'){
            $.each(branch,function(index, el) {
                //console.log(index,el);
                locations.push([el.address+' '+el.subdistrict+' '+el.district+' '+el.province+' '+el.zipcode,el.latitude,el.longitude]);
            });

        }

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(13.7315119,100.52300430000003),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        for (i = 0; i < locations.length; i++) {  
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                  infowindow.setContent(locations[i][0]);
                  infowindow.open(map, marker);
                }
              })(marker, i));
            }

    </script>
@endsection
@section('style')
	@parent
<style>
.flex-control-nav{
    bottom: -52px;
}
.flexslider{
    overflow: visible;
    }
.flex-control-paging li a.flex-active{
    background-color: #999;        
}
.flex-control-paging li a{
    background-color: #ccc;
    }
</style>
@endsection