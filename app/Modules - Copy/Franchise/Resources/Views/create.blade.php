@extends('layout::app')
@section('content')
<section class="content-header">
    <h1>{{ trans('franchise::backend.add_franchise') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i>{{ trans('franchise::backend.home') }}</a></li>
        <li><a href="{{ route('admin.franchise.index') }}">{{ trans('franchise::backend.franchise') }}</a></li>
        <li class="active">{{ trans('franchise::backend.add') }}</li>
    </ol>
</section>
@php
        //dd($category);
@endphp
<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.franchise.create'),'files' => true,'id'=>'form_main']) }}
        {{ Form::hidden('created_by', auth()->user()->id) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_description" data-toggle="tab">{{ trans('franchise::backend.description') }}</a></li>
                            <li ><a href="#tab_contact" data-toggle="tab">{{ trans('franchise::backend.contact') }}</a></li>
                            <li ><a href="#tab_branch" data-toggle="tab">{{ trans('franchise::backend.branch') }}</a></li>
                            <li ><a href="#tab_cover_image" data-toggle="tab">{{ trans('franchise::backend.cover_image') }}</a></li>
                            <li><a href="#tab_gallery" data-toggle="tab">{{ trans('article::backend.gallery') }}</a></li>
                            <li ><a href="#tab_attachments" data-toggle="tab">{{ trans('franchise::backend.attachments') }}</a>
                            </li>
                            <li ><a href="#tab_seo" data-toggle="tab">{{ trans('franchise::backend.seo') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_description">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.juristic_person_registration_number') }} <span style="color:red">*</span></label>
                                    {{ Form::number('juristic_person_registration_number',old('juristic_person_registration_number'),['class'=>'form-control','maxlength'=>'10']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.brand_name') }} <span style="color:red">*</span></label>
                                    {{ Form::text('brand_name',old('brand_name'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.company_name') }}<span style="color:red">*</span></label>
                                    {{ Form::text('company_name',old('company_name'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_type') }}</label>
                                    {{ Form::text('franchise_type',old('franchise_type'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.description') }}</label>
                                    {{ Form::textarea('description',old('description'),['class'=>'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.number_of_branches') }}</label>
                                    {{ Form::number('number_of_branches',old('number_of_branches'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.lowest_investment_budget') }}</label>
                                    {{ Form::number('lowest_investment_budget',old('lowest_investment_budget'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.highest_investment_budget') }}</label>
                                    {{ Form::number('highest_investment_budget',old('highest_investment_budget'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.franchise_fee') }}</label>
                                    {{ Form::text('franchise_fee',old('franchise_fee'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.royalty_fee') }}</label>
                                    {{ Form::text('royalty_fee',old('royalty_fee'),['class'=>'form-control']) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_contact">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.contacts') }}</label>
                                    {{ Form::text('contact_name',old('contact_name'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.phone') }}</label>
                                    {{ Form::text('phone',old('phone'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.mobile') }}</label>
                                    {{ Form::text('mobile',old('mobile'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.fax') }}</label>
                                    {{ Form::text('fax',old('fax'),['class'=>'form-control','maxlength'=>'20']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.email') }}</label>
                                    {{ Form::email('email',old('email'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.website') }}</label>
                                    {{ Form::text('website',old('website'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.line') }}</label>
                                    {{ Form::text('line',old('line'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.facebook') }}</label>
                                    {{ Form::text('facebook',old('facebook'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.youtube') }}</label>
                                    {{ Form::text('youtube',old('youtube'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.instagram') }}</label>
                                    {{ Form::text('instagram',old('instagram'),['class'=>'form-control','maxlength'=>'50']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.contacts_address') }}</label>
                                    {{ Form::text('contact_address',old('contact_address'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.subdistrict') }}<span style="color:red">*</span></label>
                                    {{ Form::text('contact_subdistrict',old('contact_subdistrict'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.district') }}<span style="color:red">*</span></label>
                                    {{ Form::text('contact_district',old('contact_district'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.provice') }}<span style="color:red">*</span></label>
                                    {{ Form::text('contact_province',old('contact_province'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.zipcode') }}<span style="color:red">*</span></label>
                                    {{ Form::text('contact_zipcode',old('contact_zipcode'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                  <div class="map_canvas"></div>
                                  {{ Form::text('geocomplete','388 อาคาร Amigo Tower ถนน สี่พระยา แขวง มหาพฤฒาราม เขต บางรัก กรุงเทพมหานคร 10500 ไทย',['class'=>'form-control','id'=>'geocomplete','placeholder'=>trans('franchise::backend.type_in_an_address')]) }}
                                  <input id="find" type="button" value="{{ trans('franchise::backend.find') }}" class="btn btn-primary" />
                                  <input name="formatted_address" style="display:none;" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.latitude') }}</label>
                                    {{ Form::text('lat',old('lat'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.longitude') }}</label>
                                    {{ Form::text('lng',old('lng'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <a id="reset" href="#" style="display:none;">{{ trans('franchise::backend.reset_marker') }}</a>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_branch">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.import_branch') }}</label>
                                    {!! Form::file("file_branch",["id"=>"file_branch",'class'=>'form-contro']) !!}
                                    <p class="help-block">Format file: xlsx (no more than 5M)</p>
                                    Example:<a href="{{ asset('files/branch/branch.xlsx') }}">Branch.xlsx</a>
                                </div>
                                <div class="form-group">
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.insert_manual') }}</label>
                                </div>   
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.address') }}</label>
                                    {{ Form::text('address',old('address'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.subdistrict') }}</label>
                                    {{ Form::text('subdistrict',old('subdistrict'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.district') }}</label>
                                    {{ Form::text('district',old('district'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.provice') }}</label>
                                    {{ Form::text('province',old('province'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.zipcode') }}</label>
                                    {{ Form::text('zipcode',old('zipcode'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                  <div class="map_canvas_branch"></div>
                                  {{ Form::text('geocomplete_branch','388 อาคาร Amigo Tower ถนน สี่พระยา แขวง มหาพฤฒาราม เขต บางรัก กรุงเทพมหานคร 10500 ไทย',['class'=>'form-control','id'=>'geocomplete_branch','placeholder'=>trans('franchise::backend.type_in_an_address')]) }}
                                  <input id="find_branch" type="button" value="{{ trans('franchise::backend.find') }}" class="btn btn-primary" />
                                  <input name="formatted_address_branch" style="display:none;" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.latitude') }}</label>
                                    {{ Form::text('lat_branch',old('lat_branch'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.longitude') }}</label>
                                    {{ Form::text('lng_branch',old('lng_branch'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <a id="reset_branch" href="#" style="display:none;">{{ trans('franchise::backend.reset_marker') }}</a>
                                </div>
                                <div class="form-group" style="margin-top:10%;margin-bottom: 5%">
                                   <label for="InputName">{{ trans('franchise::backend.all_branch') }}</label>
                                   <button type="button" class="btn btn-primary" id="add_location" style="float: right;">เพิ่มสาขา</button>
                                </div>
                                <div id="zone_location">

                                </div>
                            </div>
                            <div class="tab-pane" id="tab_cover_image">
                                <div class="form-group col-sm-6">
                                    <label for="InputName">{{ trans('franchise::backend.logo_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('logo_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="InputName">{{ trans('franchise::backend.cover_image_desktop') }} (1366px * 768px)</label>
                                    <div class="areaImage">{!! Form::file('cover_desktop') !!}</div>
                                    <p class="help-block">Format: jpg, png (no more than 2M)</p>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_gallery">
                                <div class="form-group plus">
                                    <img src="{{ asset('dbdfranchise/images/if_plus_add_blue.png') }}" title="Add Gallery" id="add_gallery" alt="add_gallery">
                                </div>
                                <div id="zone_gallery">
                                    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_attachments">
                                <div class="form-group plus">
                                    <img src="{{ asset('dbdfranchise/images/if_plus_add_blue.png') }}" title="Add Document" id="add_document" alt="add_document">
                                </div>
                                <div id="zone_document">
                                    
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.meta_title') }}</label>
                                    {{ Form::text('meta_title',old('meta_title'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.meta_keywords') }}</label>
                                    {{ Form::text('meta_keywords',old('meta_keywords'),['class'=>'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('franchise::backend.meta_description') }}</label>
                                    {{ Form::textarea('meta_description',old('meta_description'),['class'=>'form-control']) }}
                                </div>
                            </div>

                            <a href="{{ route('admin.franchise.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('franchise::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('franchise::backend.submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('franchise::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('franchise::backend.category') }} <span style="color:red">*</span></label>
                        {!! Form::select('category_id',$category, old('category_id'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        @php
                            $status = ['publish' =>trans('franchise::backend.publish'),'draft'=>trans('franchise::backend.draft')];
                        @endphp
                        <label>{{ trans('franchise::backend.status') }} <span style="color:red">*</span></label>
                        {!! Form::select('status',$status, old('status'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</section>
@endsection
@section('javascript')
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCL7ouNb0_8mh0qDvDy0Lrs1INk9IJ7Dxo"></script>
<script src="{{ asset('adminlte/bower_components/geocomplete/jquery.geocomplete.js') }}"></script>

<!-- dependencies for zip mode -->
<script type="text/javascript" src="{{ asset('adminlte/bower_components/jquery.Thailand.js/dependencies/zip.js/zip.js') }}"></script>
<!-- / dependencies for zip mode -->
<script type="text/javascript" src="{{ asset('adminlte/bower_components/jquery.Thailand.js/dependencies/JQL.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminlte/bower_components/jquery.Thailand.js/dependencies/typeahead.bundle.js') }}"></script> 
<script type="text/javascript" src="{{ asset('adminlte/bower_components/jquery.Thailand.js/dist/jquery.Thailand.min.js') }}"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#add_gallery').click(function(){
            //console.log("Add gallery click");
            gallery_length = jQuery('.gallery').length;
            html_gallery = '<div class="form-group gallery" id="gl_'+(gallery_length+1)+'">';
            html_gallery += '<label for="InputName">{{ trans("article::backend.gallery_desktop") }} (1366px * 768px)</label>';
            html_gallery += '<div class="areaImage">{!! Form::file("gallery_desktop[]",["id"=>"file_gallery"]) !!} <img class="del_gallery" onclick="JavaScript:del_gallery('+(gallery_length+1)+');" src="{{ asset("dbdfranchise/images/if_plus_add_minus.png") }}" title="Delete Gallery" id="delete_gallery" alt="delete_gallery"></div>';
            html_gallery += '<p class="help-block">Format file: jpeg, png (no more than 2M)</p>';
            html_gallery += '</div>';
            jQuery('#zone_gallery').append(html_gallery);
        });

        jQuery('#add_document').click(function(){
            //console.log("Add document click");
            document_length = jQuery('.document').length;
            html_document = '<div class="form-group document" id="dm_'+(document_length+1)+'">';
            html_document += '<label for="InputName">{{ trans("franchise::backend.attachments") }}</label>';
            html_document += '<div>{{ Form::text("document_name[]","",["id"=>"name_document"]) }}</div>';
            html_document += '<div class="areaImage">{!! Form::file("document[]",["id"=>"file_document"]) !!}<img class="del_document" onclick="JavaScript:del_document('+(document_length+1)+');" src="{{ asset("dbdfranchise/images/if_plus_add_minus.png") }}" title="Delete attachments" id="delete_document" alt="delete_document"></div>';
            html_document += '<p class="help-block">Format file: xlsx,xls,doc,docx,pdf,zip (no more than 5M)</p>';
            html_document += '</div>';
            jQuery('#zone_document').append(html_document);
        });


        jQuery('#add_location').click(function(){
            //console.log("Add gallery click");
            location_length = jQuery('.location').length;
            html_location = '<div class="form-group location" id="lo_'+(location_length+1)+'">';
            html_location += '<span>ที่อยู่</span>';
            html_location += '{{ Form::hidden('location_address[]','') }}';
            html_location += 
            '{{ Form::hidden('location_subdistrict[]','') }}';
            html_location += '{{ Form::hidden('location_district[]','') }}';
            html_location += '{{ Form::hidden('location_province[]','') }}';
            html_location += '{{ Form::hidden('location_zipcode[]','') }}';
            html_location += '{{ Form::hidden('location_lat[]','') }}';
            html_location += '{{ Form::hidden('location_lng[]','') }}';
            html_location += '<button type="button" class="btn btn-danger" onclick="JavaScript:del_location('+(location_length+1)+');" style="float: right;">ลบ</button>';
            html_location += '<button type="button" class="btn btn-primary" onclick="JavaScript:edit_location('+(location_length+1)+');" style="float: right;margin-right: 10px;">แก้ไขที่อยู่ตามแผนที่</button>';
            html_location += '</div>';
            jQuery('#zone_location').append(html_location);
        });


       jQuery("#geocomplete").geocomplete({
          map: ".map_canvas",
          details: "form ",
          markerOptions: {
            draggable: true
          }
        });
        
       jQuery("#geocomplete").bind("geocode:dragged", function(event, latLng){
         console.log(latLng+" Case Contact");
         jQuery("input[name=lat]").val(latLng.lat());
         jQuery("input[name=lng]").val(latLng.lng());
         jQuery("#reset").show();
        });
        
        
       jQuery("#reset").click(function(){
         jQuery("#geocomplete").geocomplete("resetMarker");
         jQuery("#reset").hide();
          return false;
        });
        
       jQuery("#find").click(function(){
         jQuery("#geocomplete").trigger("geocode");
        }).click();


       /* Case Branch */

       jQuery("#geocomplete_branch").geocomplete({
          map: ".map_canvas_branch",
          details: "form ",
          markerOptions: {
            draggable: true
          }
        });
        
       jQuery("#geocomplete_branch").bind("geocode:dragged", function(event, latLng){
         console.log(latLng+" Case branch");
         jQuery("input[name=lat_branch]").val(latLng.lat());
         jQuery("input[name=lng_branch]").val(latLng.lng());
         jQuery("#reset_branch").show();
        });
        
        
       jQuery("#reset_branch").click(function(){
         jQuery("#geocomplete_branch").geocomplete("resetMarker");
         jQuery("#reset_branch").hide();
          return false;
        });
        
       jQuery("#find_branch").click(function(){
         jQuery("#geocomplete_branch").trigger("geocode");
        }).click();



    });


                                    
    function del_gallery(id){
        jQuery('#gl_'+id).hide('slow');
        jQuery('#gl_'+id+' #file_gallery').remove();
    }

    function del_document(id){
        jQuery('#dm_'+id).hide('slow');
        jQuery('#dm_'+id+' #file_document').remove();
    }

    function del_location(id){
        console.log(id);
        jQuery('#lo_'+id).hide('slow');
        jQuery('#lo_'+id+' input[name="location_address\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_subdistrict\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_district\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_province\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_zipcode\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_lat\\[\\]"]').remove();
        jQuery('#lo_'+id+' input[name="location_lng\\[\\]"]').remove();
    }

    function edit_location(id){

        console.log('Location'+id);
        val_address = $('input[name="address"]').val();
        val_subdistrict= $('input[name="subdistrict"]').val();
        val_district = $('input[name="district"]').val();
        val_province = $('input[name="province"]').val();
        val_zipcode = $('input[name="zipcode"]').val();
        val_lat = $('input[name="lat_branch"]').val();
        val_lng = $('input[name="lng_branch"]').val();

        text_geocomplete_branch = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
        //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);

        jQuery('#lo_'+id+' span').text('ที่อยู่ '+text_geocomplete_branch);
        jQuery('#lo_'+id+' input[name="location_address\\[\\]"]').val(val_address);
        jQuery('#lo_'+id+' input[name="location_subdistrict\\[\\]"]').val(val_subdistrict);
        jQuery('#lo_'+id+' input[name="location_district\\[\\]"]').val(val_district);
        jQuery('#lo_'+id+' input[name="location_province\\[\\]"]').val(val_province);
        jQuery('#lo_'+id+' input[name="location_zipcode\\[\\]"]').val(val_zipcode);
        jQuery('#lo_'+id+' input[name="location_lat\\[\\]"]').val(val_lat);
        jQuery('#lo_'+id+' input[name="location_lng\\[\\]"]').val(val_lng);

    }

</script>
<script type="text/javascript">

        /*Case Contact*/
        $.Thailand({
            database: '{{ asset('adminlte/bower_components/jquery.Thailand.js/database/db.json') }}', 
            $district: $('#form_main [name="contact_subdistrict"]'),
            $amphoe: $('#form_main [name="contact_district"]'),
            $province: $('#form_main [name="contact_province"]'),
            $zipcode: $('#form_main [name="contact_zipcode"]'),

            onDataFill: function(data){
                console.info('Data Filled', data);
            },

            onLoad: function(){
                console.info('Autocomplete is ready!');
            }
        });

        // watch on change
        $('#form_main [name="contact_subdistrict"]').change(function(){
            //console.log('ตำบล', this.value);
            val_address = $('input[name="contact_address"]').val();
            val_subdistrict= $('input[name="contact_subdistrict"]').val();
            val_district = $('input[name="contact_district"]').val();
            val_province = $('input[name="contact_province"]').val();
            val_zipcode = $('input[name="contact_zipcode"]').val();

            text_geocomplete = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete"]').val(text_geocomplete);
        });
        $('#form_main [name="contact_district"]').change(function(){
            //console.log('อำเภอ', this.value);
            val_address = $('input[name="contact_address"]').val();
            val_subdistrict= $('input[name="contact_subdistrict"]').val();
            val_district = $('input[name="contact_district"]').val();
            val_province = $('input[name="contact_province"]').val();
            val_zipcode = $('input[name="contact_zipcode"]').val();

            text_geocomplete = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete"]').val(text_geocomplete);
        });
        $('#form_main [name="contact_province"]').change(function(){
            //console.log('จังหวัด', this.value);
            val_address = $('input[name="contact_address"]').val();
            val_subdistrict= $('input[name="contact_subdistrict"]').val();
            val_district = $('input[name="contact_district"]').val();
            val_province = $('input[name="contact_province"]').val();
            val_zipcode = $('input[name="contact_zipcode"]').val();

            text_geocomplete = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete"]').val(text_geocomplete);
        });
        $('#form_main [name="contact_zipcode"]').change(function(){
            //console.log('รหัสไปรษณีย์', this.value);
            val_address = $('input[name="contact_address"]').val();
            val_subdistrict= $('input[name="contact_subdistrict"]').val();
            val_district = $('input[name="contact_district"]').val();
            val_province = $('input[name="contact_province"]').val();
            val_zipcode = $('input[name="contact_zipcode"]').val();

            text_geocomplete = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete"]').val(text_geocomplete);
        });

        /*Case Location*/
        $.Thailand({
            database: '{{ asset('adminlte/bower_components/jquery.Thailand.js/database/db.json') }}', 
            $district: $('#form_main [name="subdistrict"]'),
            $amphoe: $('#form_main [name="district"]'),
            $province: $('#form_main [name="province"]'),
            $zipcode: $('#form_main [name="zipcode"]'),

            onDataFill: function(data){
                console.info('Data Filled', data);
            },
            onLoad: function(){
                console.info('Autocomplete is ready!');
            }
        });

        // watch on change
        $('#form_main [name="subdistrict"]').change(function(){
            //console.log('ตำบล Location', this.value);
            val_address = $('input[name="address"]').val();
            val_subdistrict= $('input[name="subdistrict"]').val();
            val_district = $('input[name="district"]').val();
            val_province = $('input[name="province"]').val();
            val_zipcode = $('input[name="zipcode"]').val();

            text_geocomplete_branch = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete_branch"]').val(text_geocomplete_branch);
        });
        $('#form_main [name="district"]').change(function(){
            //console.log('อำเภอ Location', this.value);
            val_address = $('input[name="address"]').val();
            val_subdistrict= $('input[name="subdistrict"]').val();
            val_district = $('input[name="district"]').val();
            val_province = $('input[name="province"]').val();
            val_zipcode = $('input[name="zipcode"]').val();

            text_geocomplete_branch = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete_branch"]').val(text_geocomplete_branch);
        });
        $('#form_main [name="province"]').change(function(){
            //console.log('จังหวัด Location', this.value);
            val_address = $('input[name="address"]').val();
            val_subdistrict= $('input[name="subdistrict"]').val();
            val_district = $('input[name="district"]').val();
            val_province = $('input[name="province"]').val();
            val_zipcode = $('input[name="zipcode"]').val();

            text_geocomplete_branch = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete_branch"]').val(text_geocomplete_branch);
        });
        $('#form_main [name="zipcode"]').change(function(){
            //console.log('รหัสไปรษณีย์ Location', this.value);
            val_address = $('input[name="address"]').val();
            val_subdistrict= $('input[name="subdistrict"]').val();
            val_district = $('input[name="district"]').val();
            val_province = $('input[name="province"]').val();
            val_zipcode = $('input[name="zipcode"]').val();

            text_geocomplete_branch = val_address+' '+val_subdistrict+' '+val_district+' '+val_province+' '+val_zipcode;
            //console.log(val_address,val_subdistrict,val_district,val_province,val_zipcode);
            $('input[name="geocomplete_branch"]').val(text_geocomplete_branch);
        });

</script>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('adminlte/bower_components/jquery.Thailand.js/dist/jquery.Thailand.min.css') }}">
<style type="text/css">
    .plus{
        margin-left:93%;
        cursor: pointer;
    }
    .del_gallery{
        margin-left: 201px;
        margin-top: -71px;
        cursor: pointer;
    }
    .map_canvas {
    width: 600px;
    height: 400px;
    margin: 10px 20px 10px 0;
    }

    .map_canvas_branch {
    width: 600px;
    height: 400px;
    margin: 10px 20px 10px 0;
    }

    fieldset { width: 320px; margin-top: 20px}
    fieldset strong { display: block; margin: 0.5em 0 0em; }
    fieldset input { width: 95%; }
    ul span { color: #999; }

</style>
@endsection
