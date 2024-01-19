<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>	
  <meta name="robot" content="index, follow" />
  <meta name="generator" content="Brackets">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="expires" content="never" />
  <meta name="google-site-verification" content="YIpznSXAMXbYoXDnQ5SrPlJcvIJKwxvwzQppdppxD5Q" />
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

  <link href="{{ asset('themes/thrc/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/thrc/css/bootstrap-theme.min.css') }}" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="{{ asset('themes/thrc/fontawesome/css/all.min.css') }}"/>
  <link type="text/css" rel="stylesheet" href="{{ asset('themes/thrc/css/layout.css') }}"/>

  <script src="{{ asset('themes/thrc/js/jquery.min.js') }}"></script>
  <script src="{{ asset('themes/thrc/js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('themes/thrc/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesnt work if you view the page via file -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    
  <script type="text/javascript" language="javascript" src="{{ asset('themes/thrc/dotdotdot-master/src/js/jquery.dotdotdot.min.js') }}"></script>

  <!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="{{ asset('themes/thrc/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}"></script>
  <!-- Add fancyBox main JS and CSS files -->
  <script type="text/javascript" src="{{ asset('themes/thrc/fancybox/source/jquery.fancybox.min.js?v=2.1.5') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('themes/thrc/fancybox/source/jquery.fancybox.css?v=2.1.5') }}" media="screen" />
  <!-- Add Button helper (this is optional) -->
  <link rel="stylesheet" type="text/css" href="{{ asset('themes/thrc/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5') }}" />
	<script type="text/javascript" src="{{ asset('themes/thrc/fancybox/source/helpers/jquery.fancybox-buttons.min.js?v=1.0.5') }}"></script>
  <!-- Add Thumbnail helper (this is optional) -->
  <link rel="stylesheet" type="text/css" href="{{ asset('themes/thrc/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7') }}" />
	<script type="text/javascript" src="{{ asset('themes/thrc/fancybox/source/helpers/jquery.fancybox-thumbs.min.js?v=1.0.7') }}"></script>
  <!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="{{ asset('themes/thrc/fancybox/source/helpers/jquery.fancybox-media.min.js?v=1.0.6') }}"></script>

  <link rel="stylesheet" href="{{ asset('themes/thrc/wow-master/css/libs/animate.css') }}">
  <script src="{{ asset('themes/thrc/wow-master/dist/wow.min.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('themes/thrc/owlcarousel/assets/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('themes/thrc/owlcarousel/assets/owl.theme.default.min.css') }}">
  <script src="{{ asset('themes/thrc/owlcarousel/owl.carousel.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('themes/thrc/flexslider/flexslider.css') }}" type="text/css" media="screen" />
  <!-- Modernizr -->
  <script src="{{ asset('themes/thrc/flexslider/demo/js/modernizr.js') }}"></script>
  <script defer src="{{ asset('themes/thrc/flexslider/jquery.flexslider-min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('themes/thrc/flexslider/demo/js/shCore.js') }}"></script>
  <script type="text/javascript" src="{{ asset('themes/thrc/flexslider/demo/js/shBrushJScript.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('themes/thrc/scrollbar-plugin/jquery.mCustomScrollbar.css') }}">
  <script src="{{ asset('themes/thrc/scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/sweetalert2-8.2.6/dist/sweetalert2.min.css') }}">
  <script src="{{ asset('adminlte/bower_components/sweetalert2-8.2.6/dist/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/bower_components/jquery-validation-1.19.0/dist/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/bower_components/bootstrap-3-typeahead/bootstrap3-typeahead.min.js') }}"></script> 
  <script src="{{ asset('adminlte/bower_components/jQuery-Plugin-To-Smartly-Load-Images-While-Scrolling-loadScroll/jQuery.loadScroll.min.js') }}"></script>


  <!-- bootstrap datetimepicker -->
  <link rel="stylesheet" href="{{ asset('adminlte\bower_components\bootstrap-datepicker\dist\css\bootstrap-datepicker.min.css') }}">

  <!-- Datetimepicker -->
  <script src="{{ asset('adminlte\bower_components\bootstrap-datepicker\dist\js\bootstrap-datepicker.min.js') }}"></script>



  @yield('meta')
  
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @section('style')

  @show
    <!-- Scripts -->
    <script>
        var baseUrl = '{{ url('/') }}';
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        //console.log(window.Laravel.csrfToken);
        path_owl_left_direction_arrow = '{{ asset('themes/thrc/images/owl-left-direction-arrow.svg') }}';
        path_owl_right_thin_chevron = '{{ asset('themes/thrc/images/owl-right-thin-chevron.svg') }}';
        path_owl_left_direction_arrow_w = '{{ asset('themes/thrc/images/owl-left-direction-arrow_w.svg') }}';
        path_owl_right_thin_chevron_w = '{{ asset('themes/thrc/images/owl-right-thin-chevron_w.svg') }}';
        check_error = '{{  ($errors->any() ? json_encode($errors->all()):'') }}';
        //console.log(check_error,typeof check_error,check_error.search("email_exists"),check_error.search("email_unique"));
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-148159049-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-148159049-1');
    </script>
  <style type="text/css">
  #float-consent {
      z-index: 999;
      position: fixed;
      height: 50px;
      bottom: 5%;
      right: 10px;
      width: 150px;
      padding: 5px;
      border: 1px solid #ccc;
      background: #85d0c9;
      color: white;
      font-weight: bold;
      text-align: center;
      cursor: pointer;
      border-radius: 7px;
      box-shadow: 0 0.195rem .25rem rgba(0,0,0,.095) !important;
      opacity: 0.9;
  }
  #float-consent a {
      color: black;
  }
  #float-consent span {
      color: black;
  }
  .float-consent-sub {
      padding-top: 6px;
  }
  </style>
</head>
<body>

<div class="container-fluid">
  @include('partials.menu')
  @yield('content')
  @include('partials.footer')
</div>


<div id="float-consent" style="display:none;">
  <div class="float-consent-sub">
  <img src="{{ asset('themes/thrc/images/shield.png') }}">
  <a href="{{ env('URL_PDPA_POLICY','https://pdpa.thaihealth.or.th/content/policy') }}" target="_blank">privacy</a>
  <span aria-hidden="true" role="presentation" style="margin-left:5px; margin-right:5px;"> - </span>
  <a href="#" id="url_pdpa_terms" target="_blank">terms</a>
  </div>
</div>



@section('js')
  
@show
<script>
  let getcookie = getCookie("pdpa");
  let pdpd_check  = '{{ (isset($pdpa) ? $pdpa:'') }}';
  //console.log(pdpd_check);
  let url_pdpa = '{{ route('pdpa-uid-key') }}';
  let url_pdpa_terms = "{{ env('URL_PDPA_TERMS','https://pdpa.thaihealth.or.th/consent/terms/') }}";
  //console.log(url_pdpa_terms);
  $(function() {  
      // Custom fadeIn Duration
      $('img').loadScroll(500);

      if(getcookie !=''){
        if(pdpd_check !=''){
          setCookie("pdpa_check",pdpd_check,365);
        }
        let getcookie_check = getCookie("pdpa_check");
        //console.log("Case1",getcookie,getcookie_check);
        if(getcookie_check == 'success'){
          
          let url = url_pdpa_terms+getcookie;
          $('#url_pdpa_terms').attr('href',url);
          $('#float-consent').show();
          //console.log('Success',url);
        }
      }else{
        //console.log("Case2");
        $.post(url_pdpa,{RefUidKey:''}, function (data,status) {
          //console.log(data);
          if(data.status_code ==200){
            setCookie("pdpa",data.RefUidKey,365);
            if(data.CheckStatus =='01' || data.CheckStatus=='02'){
              location.href = data.RedirectURL;
            }
          }
        });
      } 
  });

  

  /*PDPA*/
  function setCookie(cname, cvalue, exdays) {
   var d = new Date();
   d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
   var expires = "expires=" + d.toUTCString();
   document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
     var name = cname + "=";
     var decodedCookie = decodeURIComponent(document.cookie);
     var ca = decodedCookie.split(';');
     for (var i = 0; i < ca.length; i++) {
     var c = ca[i];
     while (c.charAt(0) == ' ') {
     c = c.substring(1);
     }
     if (c.indexOf(name) == 0) {
     return c.substring(name.length, c.length);
     }
     }
     return "";
  }


  //pdpa_key

   //var getcookie = getCookie("pdpa");
   //console.log(getcookie);
   //setCookie("pdpa","P9fFyMJrQsITgrXOhXnK6Jxy-x6A77QNbypPTvFeCNg",365);
  </script>
  <!-- THRC Core -->
<script type="text/javascript" src="{{ asset('themes/thrc/js/core.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
<html>
