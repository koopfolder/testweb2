@extends('layout::app')

@section('content')

<section class="content-header">
	<h1>{{ trans('setting::backend.setting') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('setting::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.setting.index') }}">{{ trans('setting::backend.setting') }}</a></li>
	</ol>
</section>
<section class="content">
	<div class="box box-default">
    	<div class="box-body">
    		{{ Form::open(['url' => route('admin.setting.index'), 'files' => true]) }}
        	<div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_address" data-toggle="tab">{{ trans('setting::backend.address') }}</a></li>
                            <li><a href="#tab_logo" data-toggle="tab">{{ trans('setting::backend.logo') }}</a></li>
                            <li><a href="#tab_social" data-toggle="tab">{{ trans('setting::backend.social') }}</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">{{ trans('setting::backend.seo') }}</a></li>
                            <li><a href="#tab_content" data-toggle="tab">{{ trans('setting::backend.content') }}</a></li>
                            <li><a href="#tab_mime_type" data-toggle="tab">{{ trans('setting::backend.mime_type') }}</a></li>
                            <li><a href="#tab_data_transfer" data-toggle="tab">{{ trans('setting::backend.data_transfer') }}</a></li>
                        </ul>
                        <div class="tab-content col-md-12">
                            <div class="tab-pane active" id="tab_address">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="InputName">{{ trans('setting::backend.site_name') }}</label>
                                        {{ Form::text('site', $site, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="InputEmailAddress">{{ trans('setting::backend.address') }}</label>
                                        {{ Form::textarea('address', $address, ['class' => 'form-control', 'rows' => 2]) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="InputName">{{ trans('setting::backend.telephone') }}</label>
                                        {{ Form::text('telephone', $telephone, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="InputName">{{ trans('setting::backend.fax') }}</label>
                                        {{ Form::text('fax', $fax, ['class' => 'form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="InputName">{{ trans('setting::backend.email') }}</label>
                                        {{ Form::text('email', $email, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Location">{{ trans('setting::backend.location') }}</label>
                                        <div id="location" style="width: 550px; height: 400px;"></div>
                                    </div>
                                    <div class="form-group">
                                            <label for="ipt" class=" control-label col-md-2"> {{ trans('setting::backend.latitude') }} : </label>
                                            <div class="col-md-10">
                                                {{ Form::text('map_location[lat]',(!empty($location) ? explode(',',$location)['0']:''),['class'=>'form-control input-sm','id'=>'location-lat']) }}
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="ipt" class=" control-label col-md-2"> {{ trans('setting::backend.lontitude') }} : </label>
                                            <div class="col-md-10">
                                                {{ Form::text('map_location[lng]',(!empty($location) ? explode(',',$location)['1']:''),['class'=>'form-control input-sm','id'=>'location-lon']) }}
                                            </div>
                                    </div>
                                </div>
                            </div><!-- End tab_description -->

                            <div class="tab-pane" id="tab_logo">
                                <div class="form-group">
                                    <label for="InputEmailAddress">{{ trans('setting::backend.logo_desktop') }}</label>
                                    @if ($logo_desktop)
                                        <div style="margin:10px 0;"><img src="{{ url($logo_desktop) }}" width="150"></div>
                                    @endif
                                    {!! Form::file('logo_desktop') !!}
                                    <p class="help-block">{{ trans('setting::backend.fileupload_note') }}</p>
                                </div> 
                            </div>

                            <div class="tab-pane" id="tab_social">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.facebook') }} </label>
                                    {{ Form::text('facebook', $facebook, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.instagram') }} </label>
                                    {{ Form::text('instagram', $instagram, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.youtube') }} </label>
                                    {{ Form::text('youtube', $youtube, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.twitter') }} </label>
                                    {{ Form::text('twitter', $twitter, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.line') }} </label>
                                    {{ Form::text('line', $line, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputEmailAddress">{{ trans('setting::backend.google_analytics') }} </label>
                                    {{ Form::textarea('google_analytics', $google_analytics, ['class' => 'form-control', 'rows' => 4]) }}
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.meta_title') }} </label>
                                    {{ Form::text('meta_title', $meta_title, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.meta_keywords') }} </label>
                                    {{ Form::textarea('meta_keywords', $meta_keywords, ['class' => 'form-control', 'rows' => 4]) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.meta_description') }} </label>
                                    {{ Form::textarea('meta_description', $meta_description, ['class' => 'form-control', 'rows' => 4]) }}
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_content">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.main_banner') }} </label>
                                    {{ Form::select('main_banner',$banner_category,$main_banner,['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.footer_banner') }} </label>
                                    {{ Form::select('footer_banner',$banner_category,$footer_banner,['class' => 'form-control']) }}
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_mime_type">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.mime_type') }} </label>
                                    {{ Form::select('mime_type[]',['xls' => 'xls', 'xlsx' => 'xlsx','doc'=>'doc','docx'=>'docx','pdf'=>'pdf','zip'=>'zip'],$mime_type,['class' => 'form-control','multiple'=>'multiple','style'=>'height:180px;']) }}
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_data_transfer">                                
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.api_keywords') }} </label>
                                    {!! Form::select('api_keywords[]',$api_keywords,$api_keywords_select ?? old('api_keywords'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_2_keywords') }} </label>
                                    {!! Form::select('ncds_2_keywords[]',$ncds_2_keywords,$ncds_2_keywords_select ?? old('ncds_2_keywords'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_4_keywords') }} </label>
                                    {!! Form::select('ncds_4_keywords[]',$ncds_4_keywords,$ncds_4_keywords_select ?? old('ncds_4_keywords'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_5_keywords') }} </label>
                                    {!! Form::select('ncds_5_keywords[]',$ncds_5_keywords,$ncds_5_keywords_select ?? old('ncds_5_keywords'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('setting::backend.ncds_6_keywords') }} </label>
                                    {!! Form::select('ncds_6_keywords[]',$ncds_6_keywords,$ncds_6_keywords_select ?? old('ncds_6_keywords'), ['class' => 'form-control js-tags-tokenizer','multiple'=>'multiple']) !!}
                                </div>                                                                  
                            </div>                            

                        </div><!-- End tap-content -->
                    </div><!-- End nav-tabs-custom -->
                </div><!-- End col-md-12 -->
            </div><!-- End Row -->
            <div class="box-footer">
                <button class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('setting::backend.submit') }}</button>
            </div>
         	{{ Form::close() }}
       	</div>
    </div>
</section>
@endsection
@section('javascript')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyCPicH8xaKf2yBqSeXIsmIc61NKXO7m_l0'></script>
<script src="{{ asset('adminlte/bower_components/jquery-locationpicker/locationpicker.jquery.min.js') }}"></script>
<script type="text/javascript">


    jQuery('#location').locationpicker({
        location:{
                    latitude: {{ (!empty($location) ? explode(',',$location)['0']:'13.764920911324777') }},
                    longitude:{{ (!empty($location) ? explode(',',$location)['1']:'100.53824544698') }}
                },
        radius: 0,
        zoom: 16,
        inputBinding:{
                    latitudeInput: jQuery('#location-lat'),
                    longitudeInput: jQuery('#location-lon')
                },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
    });

    jQuery(document).ready(function(){

        //console.log("Teasdasdsas");
        $('select[name="knowledges"]').select2({
            width:'100%'
        });

        $('select[name="media_campaign"]').select2({
            width:'100%'
        });

        $(".js-tags-tokenizer").select2({
        tags: true,
        tokenSeparators: [',', ' ']
        });

    })
    //$('.js-example-basic-single').select2();

</script>
@endsection