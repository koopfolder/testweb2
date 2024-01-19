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
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-148159049-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-148159049-1');
    </script>-->
  <!-- THRC Core -->
  <script type="text/javascript" src="{{ asset('themes/thrc/js/core.min.js') }}"></script>
</head>
<body>

<div class="container-fluid">
  @include('partials.menu_web_view')
  @yield('content')
</div>

@section('js')
  
@show
<script>
  $(function() {  
      // Custom fadeIn Duration
      $('img').loadScroll(500);
  });
  </script>
</body>
<html>
