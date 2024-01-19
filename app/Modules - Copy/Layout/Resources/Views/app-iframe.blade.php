<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" type="image/ico" href="{{ asset('adminlte/favicon.ico') }}"/>

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>THRC CMS</title>

	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">

	<!-- Ionicons -->
	<link rel="stylesheet" href="{{ asset('adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">

	<!-- jvectormap -->
	<link rel="stylesheet" href="{{ asset('adminlte/bower_components/jvectormap/jquery-jvectormap.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('adminlte/dist/css/AdminLTE.min.css') }}">
	<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="{{ asset('adminlte/dist/css/skins/_all-skins.css') }}">

	<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/build/toastr.css') }}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- bootstrap datetimepicker -->
  <link rel="stylesheet" href="{{ asset('adminlte\plugins\bootstrap-datetimepicker\css\bootstrap-datetimepicker.min.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}">
  	<!-- bootstrap wysihtml5 - text editor -->
 	<link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
  	<!-- Tagsinput -->
 	<link rel="stylesheet" href="{{ asset('adminlte/plugins/tags-input/dist/jquery.tagsinput.min.css') }}">
	<script>var baseUrl = '{{ url('/') }}';</script>
	@yield('css')

</head>
<body class="hold-transition">
    <div class="wrapper">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
	<!-- jQuery 3 -->
	<script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/bower_components/jquery-ui/jquery-ui.js') }}"></script>

	<!-- Bootstrap 3.3.7 -->
	<script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

	<!-- FastClick -->
	<script src="{{ asset('adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
	<!-- AdminLTE App -->
	<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

	<!-- SlimScroll -->
	<script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
	
	<!-- AdminLTE for demo purposes -->
	<script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>

	<!-- Toastr -->
	<script src="{{ asset('adminlte/plugins/toastr/build/toastr.min.js') }}"></script>

	<!-- date-range-picker -->
	<script src="{{ asset('adminlte/bower_components/moment/min/moment.min.js') }}"></script>
	<script src="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<!-- bootstrap datepicker -->
	<script src="{{ asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<!-- bootstrap color picker -->
	<script src="{{ asset('adminlte/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
	<!-- bootstrap time picker -->
	<script src="{{ asset('adminlte/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

	<!-- DataTables -->
	<script src="{{ asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/bower_components/datatables.net/js/filterDropDown.js') }}"></script>
	<script src="{{ asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>



	
	<!-- Select2 -->
	<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

	<!-- Tagsinput -->
	<script src="{{ asset('adminlte/plugins/tags-input/dist/jquery.tagsinput.min.js') }}"></script>

	<!-- Bootstrap Confirmation -->
	<script src="{{ asset('adminlte/plugins/bootstrap-confirmation/bootstrap-confirmation.js') }}"></script>
	{{--  ckeditor  --}}
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	
  	<!-- Datetimepicker -->
	<script src="{{ asset('adminlte\plugins\bootstrap-datetimepicker\js\bootstrap-datetimepicker.min.js') }}"></script>

	@if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>$(function() { Command: toastr["error"]("{{$error}}") });</script>
        @endforeach
	@endif

    @if (session('status'))
    <script>$(function() { Command: toastr["{{session('status')}}"]("{{session('message')}}") });</script>
    @endif

    <script>
        $(function() {
		 	
		 	$("#select-all").click(function () {
		    	$('input:checkbox').not(this).prop('checked', this.checked);
		 	});

        	$('.datepicker').datepicker({
            	format: 'yyyy-mm-dd',
            	autoclose: true
        	});

        	$('.datetimepicker').datetimepicker();

			$('[data-toggle=confirmation]').confirmation({
				rootSelector: '[data-toggle=confirmation]',
				container: 'body'
			});

    		$('#datatable').DataTable({
      			'paging'      : true,
      			'lengthChange': true,
      			'searching'   : true,
      			'ordering'    : true,
      			'info'        : true,
      			'autoWidth'   : true
    		});
    		$('.datatable').DataTable({
      			'paging'      : true,
      			'lengthChange': true,
      			'searching'   : true,
      			'ordering'    : true,
      			'info'        : true,
      			'autoWidth'   : true
    		});
        });
    </script>

<script>
  var editor_config = {
    path_absolute : "{{ url('/') }}/",
    selector: "textarea.ckeditor",
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern",
	  "textcolor colorpicker"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media forecolor backcolor",
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }
  };

  tinymce.init(editor_config);
</script>

	@yield('javascript')

</body>
</html>
