@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
    if(Auth::check()){
        $user_explode = explode(" ", auth()->user()->name);
        //dd($user_explode);
        $name = $user_explode[0];
        $surname = isset($user_explode[1]) ? $user_explode[1]:'';
        $phone = auth()->user()->phone;
        $email = auth()->user()->email;
    }else{
        $name = '';
        $surname = '';
        $phone = '';
        $email =  '';
    }


@endphp
@if(method_exists('App\Modules\Api\Http\Controllers\RequestMediaDetailController', 'getData'))   
    @php
        $data = App\Modules\Api\Http\Controllers\RequestMediaDetailController::getData($request->all());  
        //dd($data);
    @endphp
@endif
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_content">
                <div class="col-xs-12">
                    <div class="head_inside">
                        <h1>ขอรับสื่อ</h1>
                    </div>
                    <div class="desc_register">
                        @if(isset($data->title))
                            <h2>{{ $data->title }}</h2>
                            {!! $data->description !!}
                        @endif
                    </div>
                    <div class="box_formregister">
                        {!! Form::open(['url' => route('request-media-front.create'),'method'=>'post','id'=>'requestmediaForm']) !!}
                          <div class="row">
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group">
                                    <label>ชื่อ<span>*</span></label>
                                    <input type="text" placeholder="ชื่อ" name="firstname" value="{{ $name ?? old('firstname') }}" maxlength="50" class="form-control">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group">
                                    <label>นามสกุล<span>*</span></label>
                                    <input class="form-control" type="text" placeholder="นามสกุล" name="lastname" value="{{ $surname ?? old('lastname') }}" maxlength="50">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group">
                                    <label>อีเมล <span>*</span></label>
                                    <input class="form-control" type="text" placeholder="อีเมล" name="email" value="{{ $email ?? old('email') }}" maxlength="50">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-6">
                                  <div class="form-group">
                                    <label>เบอร์โทร <span>*</span></label>
                                    <input class="form-control" type="text" placeholder="เบอร์โทร" name="phone" value="{{ $phone ?? old('phone') }}" maxlength="20">
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-12">
                                  <div class="form-group">
                                    <label>ข้อความ <span>*</span></label>
                                    <textarea name="description" class="form-control" style="margin: 0px 3px 0px 0px; height: 198px;"></textarea>
                                  </div>
                              </div>
                              <div class="col-xs-12">
                                  <div class="wrap_btn_bd">
                                    <button class="btn_submit" type="submit">ส่งข้อมูล</button>
                                    <button class="btn_cancel" type="cancel">ยกเลิก</button>
                                </div>
                              </div>
                          </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ขอรับสื่อ</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
@endsection
@section('style')
	@parent
<style>
        .desc_register{
            background-color: #009881;
            color: #fff;
            padding: 5px 30px 15px;
            margin: 30px 0 10px;
        }
        .desc_register h2{
            font-size: 1.4rem;
            font-weight: normal;
            margin-bottom: 0;
        }
        .desc_register figcaption{
            border-bottom: dotted 1px #fff;
            font-size: 1.1rem;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .box_formregister{
            border: 1px solid #ccc;
            padding: 40px 30px 60px;
        }
        .box_formregister .form-control{
            font-size: 1rem;
            height: 40px;
        }
        .box_formregister label{
            font-weight: normal;
            margin-bottom: 0;
        }
        .box_formregister label span{
            color: #f00;
        }
        .icon_calendar img{
            width: 20px;
            height: auto;
        }
        .wrap_btn_bd{
            margin-top: 20px;
            text-align: right;
        }
        .wrap_btn_bd button{
            height: 40px;
            padding: 0 30px;
            line-height: 40px;
            color: #fff;
            border: 0;
            font-size: 1.1rem;
            margin-left: 5px;
        }
        .btn_submit{
            background-color: #009881;
        }
        @media (max-width: 767px) {
            .desc_register h2{
                font-size: 1.2rem;
            }
            .desc_register figcaption{
                font-size: 1rem;
            }
            .desc_register{
                padding: 5px 15px 15px;
                margin-top: 15px;
            }
            .box_formregister {
                padding: 40px 15px 60px;
            }
            .wrap_btn_bd{
                text-align: left;
            }
            .wrap_btn_bd button{
                font-size: 1rem;
                margin-left: 0;
                margin-right: 5px;
            }
        }
    </style>
@endsection
@section('js')
	@parent

@endsection