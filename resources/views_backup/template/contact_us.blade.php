@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd("Contact Us");

    $site = ThrcHelpers::getSetting(['slug'=>'site','retrieving_results'=>'first']);
    $address = ThrcHelpers::getSetting(['slug'=>'address','retrieving_results'=>'first']);
    $telephone = ThrcHelpers::getSetting(['slug'=>'telephone','retrieving_results'=>'first']);
    $fax = ThrcHelpers::getSetting(['slug'=>'fax','retrieving_results'=>'first']);
    $email = ThrcHelpers::getSetting(['slug'=>'email','retrieving_results'=>'first']);
    $facebook = ThrcHelpers::getSetting(['slug'=>'facebook','retrieving_results'=>'first']);
    $line = ThrcHelpers::getSetting(['slug'=>'line','retrieving_results'=>'first']);
    $youtube = ThrcHelpers::getSetting(['slug'=>'youtube','retrieving_results'=>'first']);
    $twitter = ThrcHelpers::getSetting(['slug'=>'twitter','retrieving_results'=>'first']);

    //dd(!empty($twitter->value));

@endphp
@if(method_exists('App\Modules\Contact\Http\Controllers\FrontController', 'getSubject'))   
    @php
        $subject = App\Modules\Contact\Http\Controllers\FrontController::getSubject($request->all());  
        //dd($subject);
    @endphp
@endif
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_contact">
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="wrap_contact_info">
                        <h1>{{ (collect($site)->isNotEmpty() ? $site->value:'') }}</h1>
                        <address>
                          {{ (collect($address)->isNotEmpty() ? $address->value:'') }}
                        </address>
                        <div class="link_contact">
                            <div>
                                @if(!empty($email->value))
                                <a class="contact_mail" href="mailto:activity.thc@thaihealth.or.th">อีเมล์ : {{ (collect($email)->isNotEmpty() ? $email->value:'') }}</a>
                                @endif
                            </div>
                            <div>
                                @if(!empty($line->value))
                                <a class="contact_line" target="_blank" href="http://line.me/ti/p/{{ (collect($line)->isNotEmpty() ? $line->value:'') }}">Line ID : {{ (collect($line)->isNotEmpty() ? $line->value:'') }}</a>
                                @endif
                            </div>
                            <div>@if(!empty($facebook->value))<a class="contact_fb" target="_blank" href="{{ (collect($facebook)->isNotEmpty() ? $facebook->value:'') }}">{{ (collect($facebook)->isNotEmpty() ? $facebook->value:'') }}</a>@endif</div>
                            <div>@if(!empty($twitter->value))<a class="contact_tt" target="_blank" href="{{ (collect($twitter)->isNotEmpty() ? $twitter->value:'') }}">{{ (collect($twitter)->isNotEmpty() ? $twitter->value:'') }}<a>@endif
                            </div><div>@if(!empty($youtube->value))<a class="contact_yt" target="_blank" href="{{ (collect($youtube)->isNotEmpty() ? $youtube->value:'') }}">{{ (collect($youtube)->isNotEmpty() ? $youtube->value:'') }}@endif</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-7 wrap_googlemap">
                    <div class="wrap_iframe">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.983181711188!2d100.54109731519989!3d13.719467990368372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29f3a56ad13c1%3A0x86f54093fe170f72!2sThai+Health+Promotion+Foundation!5e0!3m2!1sen!2sth!4v1564149655730!5m2!1sen!2sth" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="row bg_form_contact">
                <div class="col-xs-12 col-sm-4 head_formcontact">
                    <h1>CONTACT</h1>
                    <h2>FORM</h2>
                </div>
                {!! Form::open(['url' => route('contact.form'),'method'=>'post','id'=>'contactForm','class'=>'col-xs-12 col-sm-8 bg_form_right']) !!}
                    <div class="form-group">
                        {{ Form::select('subject_id',$subject,'',['class'=>'form-control']) }}    
                    </div>
                    <div class="form-group">
                         <input type="text" placeholder="ชื่อ - นามสกุล" name="name" value="{{ old('firstname') }}" maxlength="50" class="form-control">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="อีเมล" name="email" value="{{ old('email') }}" maxlength="50">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="เบอร์โทร" name="phone" value="{{ old('phone') }}" maxlength="20">
                    </div>
                    <div class="form-group">
                        <textarea name="message" class="form-control" style="margin: 0px; height: 202px; width: 619px;"></textarea>
                    </div>
                    <button>ส่งข้อความ</button>
                {!! Form::close() !!}
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>{{ (!empty($menu['meta_title']) ? $menu['meta_title']:$menu['name']) }}</title>
    <meta charset="UTF-8">
    <meta name="description" content="{{ $menu['meta_description'] }}">
    <meta name="keywords" content="{{ $menu['meta_keywords'] }}">
    <meta name="author" content="">
@endsection
@section('style')
	@parent
    <style>
        .row_contact{
            margin-top: 50px;
            margin-bottom: 80px;
        }
        .wrap_contact_info{
            background-image: url(images/icon_pin.svg);
            background-position: left 5px;
            background-repeat: no-repeat;
            background-size: 30px auto;
            padding-left: 35px;
        }
        .wrap_contact_info h1{
            font-size: 1.75rem;
            margin-bottom: 20px;
            margin-top: 0;
        }
        .wrap_contact_info address, .wrap_contact_info .opentime{
            font-size: 1.2rem;
            line-height: 1;
            margin-bottom: 25px;
        }
        .link_contact{
            
        }
        .link_contact a{
            display: inline-block;
            color: #000;
            font-size: 1.2rem;
            line-height: 1;
            margin-bottom: 10px;
            text-decoration: none;
            background-position: left top;
            background-repeat: no-repeat;
            background-size: 35px auto;
            padding-left: 45px;
            min-height: 35px;
            line-height: 35px;
        }
        .link_contact a.contact_mail{
            background-image: url({{ asset('themes/thrc/images/contact_mail.svg') }});
        }
        .link_contact a.contact_line{
            background-image: url({{ asset('themes/thrc/images/contact_line.svg') }});
        }
        .link_contact a.contact_fb{
            background-image: url({{ asset('themes/thrc/images/contact_facebbook.svg') }});
        } 
        .link_contact a.contact_tt {
        background-image: url({{ asset('themes/thrc/images/contact_Twitter.svg') }});
        }   
        .link_contact a.contact_yt {
            background-image: url({{ asset('themes/thrc/images/contact_Youtube.svg') }});
        }
        .wrap_googlemap{
            padding-left: 80px;
        }
        .wrap_iframe{
            position: relative;
            padding-bottom: 65%;
        }
        .wrap_iframe iframe{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        .bg_form_contact{
            background-color: #439582;
            margin-bottom: 100px;
        }
        .head_formcontact{
            padding-left: 30px;
            padding-top: 30px;
        }
        .head_formcontact h1{
            font-size: 3.5rem;
            color: #FFF;
            margin: 0;
            line-height: 0.7;
        }
        .head_formcontact h2{
            font-size: 2.5rem;
            color: #FFF;
            margin-top: 0;
            line-height: 0.7;
            font-weight: 300;
            margin-bottom: 25px;
        }
        .bg_form_right{
            background-color: #d4e6e2;
            padding: 50px 80px;
        }
        .bg_form_right .form-group{
            margin-bottom: 30px;
        }
        .bg_form_right .form-control{
            font-size: 1.1rem;
        }
        .bg_form_right input.form-control{
            height: 42px;
        }
        .bg_form_right button{
            background-color: #439582;
            font-size: 1.2rem;
            color: #FFF;
            border: 0;
            padding: 5px 20px;
            display: inline-block;
        }
        @media (max-width: 1199px){
            .wrap_contact_info address, .wrap_contact_info .opentime{
                font-size: 1.1rem;
            }
            .link_contact a{
                font-size: 1.1rem;
            }
            .wrap_iframe {
                padding-bottom: 75%;
            }
            .head_formcontact h1{
                font-size: 3rem;
            }
            .head_formcontact h2 {
                font-size: 2rem;
            }
        }
        @media (max-width: 991px){
            .wrap_googlemap{
                padding-left: 15px;
            }
            .wrap_contact_info address, .wrap_contact_info .opentime{
                margin-bottom: 15px;
            }
            .bg_form_right {
                padding: 50px 50px;
            }
        }
        @media (max-width: 767px){
            .bg_form_right .form-group{
                margin-bottom: 15px;
            }
            .bg_form_right {
                padding: 20px;
            }
            .wrap_googlemap{
                margin-top: 15px;
            }
            .head_formcontact h1 {
                font-size: 2rem;
            }
            .head_formcontact h2 {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection
@section('js')
	@parent

@endsection