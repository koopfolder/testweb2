@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Add Link</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.link.index') }}">Link</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.link.create'), 'files' => true]) }}
        {{ Form::hidden('user_id', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">{{ trans('layout::admin.form.description') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('layout::admin.form.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">Name <span style="color:red;">*</span></label>
                                    {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Category of Banner</label>
                                                {!! Form::select('banner_category_id', $bannerCategories, old('banner_category_id'), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="checkbox" style="margin-top: 32px;">
                                                    <label>
                                                        <input type="checkbox" name="make_reservation" value="1"
                                                            @if (old('make_reservation'))
                                                            checked="checked"
                                                            @endif> Make a Reservation
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                    
                                <div class="form-group">
                                    <label for="InputName">Excerpt</label>
                                        {{ Form::textarea('excerpt', old('excerpt'), ['class' => 'form-control', 'rows' => 4]) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">Description</label>
                                        {{ Form::textarea('description', old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label>Link Type <span style="color:red;">*</span></label>
                                    {!! Form::select('link_type', ['external' => 'External', 'internal' => 'Internal'], old('link_type'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group" id="url_external">
                                    <label for="InputName">Link <span style="color:red;">*</span></label>
                                    {{ Form::text('url_external', old('url_external'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group" id="layout">
                                    <label>Layout <span style="color:red;">*</span></label>
                                    {!! Form::select('layout', $layouts, old('layout'), ['class' => 'form-control']) !!}
                                </div>

                                @include('menus::partials.pages', ['pages' => $pages])
                                @include('menus::partials.rooms', ['rooms' => $rooms])
                                @include('menus::partials.restaurants', ['restaurants' => $restaurants])
                                @include('menus::partials.promotions', ['promotions' => $promotions])
                                @include('menus::partials.recreations', ['recreations' => $recreations, 'module_slug' => null, 'module_ids' => null])

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="target" value="1"
                                            @if (old('target'))
                                            checked="checked"
                                            @endif> Open new page.
                                    </label>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control']) !!}
                                </div>                     
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>                                
                            </div>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Setting</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Position <span style="color:red;">*</span></label>
                        <select name="position" class="form-control">
                            <option value="footer-left" @if(old('position') == 'footer-left') selected="selected" @endif>Footer Left</option>
                            <option value="footer-center" @if(old('position') == 'footer-center') selected="selected" @endif>Footer Center</option>
                            <option value="footer-right" @if(old('position') == 'footer-right') selected="selected" @endif>Footer Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputName">Order</label>
                        {{ Form::text('order', 0, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>Status <span style="color:red;">*</span></label>
                        {!! Form::select('status', ['publish' => 'Publish',  'draft' => 'Draft'], old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')

<script type="text/javascript">
	$(function() {
        $("select[name=link_type]").on('change', function(event) {
            var link_type = $(this).val();
            if (link_type == 'internal') {
                $("#url_external").hide();
                $("#layout").show();                
            } else {
                $("#url_external").show();
                $("#layout").hide();
            }
        });
        $("select[name=link_type]").trigger('change');

        $("select[name=layout]").on('change', function(event) {
            var layout = $( this ).val();
            switch(layout) {
                case 'home':
                    $("#pages, #recreations, #single-recreation, #promotions, #restaurants, #rooms").hide();
                    break;
                case 'single-page':
                    $("#recreations, #single-recreation, #promotions, #restaurants, #rooms").hide();
                    $("#pages").show();
                    break;
                case 'offers-promotions':
                    $("#pages, #recreations, #single-recreation, #restaurants, #rooms").hide();
                    $("#promotions").show();
                    break;
                case 'restaurant':
                    $("#pages, #recreations, #single-recreation, #promotions, #rooms").hide();
                    $("#restaurants").show();
                    break;
                case 'room':
                    $("#pages, #recreations, #single-recreation, #promotions, #restaurants").hide();
                    $("#rooms").show();
                    break;
                case 'leisure-recreation':
                    $("#pages, #single-recreation, #promotions, #restaurants, #rooms").hide();
                    $("#recreations").show();
                    break;
                default:
                    $("#pages, #recreations, #single-recreation, #promotions, #restaurants, #rooms").hide();
                    break;
            }
        });
        $("select[name=layout]").trigger('change');

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

