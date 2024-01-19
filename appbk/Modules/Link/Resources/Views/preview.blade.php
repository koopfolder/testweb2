@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>แก้ไขเมนู</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
        <li><a href="{{ route('admin.menus.index') }}">เมนู</a></li>
        <li class="active">แก้ไขเมนู</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <iframe src="http://amenity.app/preview-url/{{$url}}" style="width: 100%;height: 800px;"></iframe>
        </div>
    </div>
</section>
@endsection

@section('javascript')

<script type="text/javascript">
	$(function() {

        function link() {
            var link_type = $("select[name=link_type]").val(); 
            if (link_type == 'external') {
                $("#url_external").show();
                $("#page").hide();
                $("#layout").hide();
            } else {
                $("#url_external").hide();
                $("#page").show();
                $("#layout").show();
                $('#category_product').hide(); 
                $("select[name=layout]").trigger('change');
            }
        }
        link();

        $("select[name=link_type]").on('change', function(event) {
            event.preventDefault();
            var link_type = $("select[name=link_type]").val();
            if (link_type == 'external') {
                $("#url_external").show();
                $("#page").hide();
                $("#layout").hide();
            } else {
                $("#url_external").hide();
                $("#page").show();
                $("#layout").show();
                $("select[name=layout]").trigger('change');
            }
        });

        function layout() {
            var layout = $("select[name=layout]").val();
            if (layout == 'single-page') {
                $("#page").show();
            } else if (layout == 'product') {
                $("#page").hide();
                $('#category_product').show();
            } else {
                $("#page").hide();
                $('#category_product').hide();            
            }
        }

        layout();

        $("select[name=layout]").on('change', function(event) {
            event.preventDefault();
            var layout = $("select[name=layout]").val();
            if (layout == 'single-page') {
                $("#page").show();
                $('#category_product').hide();
            } else if(layout == 'product') {
                $("#page").hide();
                $('#category_product').show();
            } else if(layout == 'contact') {
                $("#page").hide();
                $('#category_product').hide();
            } else {
                $("#page").hide();
                $('#category_product').hide();
            }
        });

        $('.select2').select2({
            tags: false
        });

        $(".select2").on("select2:select", function (evt) {
              var element = evt.params.data.element;
              var $element = $(element);
              $element.detach();
              $(this).append($element);
              $(this).trigger("change");
        });



	});
</script>
@stop

