@php
	$lang = \App::getLocale();
    $main_menu = ThrcHelpers::getMenu(['position'=>'header']);
    //dd($main_menu);
    $logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
    $logo = ($logo_value ? asset($logo_value->value) : '');

    $time_cache  =  ThrcHelpers::time_cache(15);
    //dd($time_cache);
    //exit();

    //dd(auth()->user());
@endphp
    <nav class="row mainnavbar">
        <div class="container">
            <div class="row">
            	<div id="textuat" class="col-lg-4 col-md-7 col-sm-3" style="height: 40px;padding: 7px 8px 0;text-align:  center;border-radius: 0 0 10px 10px;float:  right;">
                    <b style="color: red; font-size: 23px; padding: 7px 32px; margin-top: 5px; margin-left: 10px; position: relative; display: block;">
                        เว็บไซต์สำหรับทดสอบเท่านั้น
                    </b>
                </div>               
                <div class="col-xs-6 col-sm-1 logo"><a href="{{ route('home') }}"><img src="{{ $logo }}"></a></div>
                <div class="col-xs-6 col-sm-11 mainmenu">
                    <div class="btn_menu"><div class="btn_menu_line"><span></span><span></span><span></span></div><div class="btn_menu_text">เมนู</div></div>
                    <ul>
                        @if($main_menu->count())
                            <!-- Level 1 -->
                            @foreach ($main_menu AS $key_level1 =>$value_level1)
                                @php
                                    $check_childrens = collect($value_level1['childrens']);   
                                    //dd($key_level1,$value_level1);                         
                                @endphp
                                @if($check_childrens->count())
                                    <!-- Level 2 -->
                                    <li class="hassub"><a>{{ $value_level1['name'] }}</a>
                                        <div class="submenu">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-3 submenu_left">
                                                        <hgroup>
                                                            <h1>{{ $value_level1['name'] }}</h1>
                                                        </hgroup>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 submenu_mid">
                                                        <ul class="submenu_mid_list">
                                                            @foreach($value_level1['childrens'] AS $key_level2=>$value_level2)
                                                                @php
                                                                    if ($value_level2->url_external) {
                                                                        $value_level2['url'] = $value_level2->url_external;
                                                                    } else {
                                                                        $value_level2['url'] = ThrcHelpers::customUrl($value_level2);
                                                                    }
                                                                    
                                                                    //dd($value_level2->id);
                                                                    if (Cache::has('menu_children'.$value_level2->id)){
                                                                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);
                                                                    }else{

                                                                        $menu_level3 = $value_level2->FrontChildren()->get();
                                                                        Cache::put('menu_children'.$value_level2->id,$menu_level3,$time_cache);
                                                                        $menu_level3 = Cache::get('menu_children'.$value_level2->id);

                                                                    }
                                                                @endphp
                                                                @if($menu_level3->count())
                                                                <li>
                                                                    <a href="#">{{ $value_level2['name'] }}</a>
                                                                    <ul>
                                                                        @foreach($menu_level3 AS $key_cmenu_level3 =>$value_menu_level3)
                                                                            @php
                                                                                if ($value_menu_level3->url_external) {
                                                                                    $value_menu_level3['url'] = $value_menu_level3->url_external;
                                                                                } else {
                                                                                    $value_menu_level3['url'] = ThrcHelpers::customUrl($value_menu_level3);
                                                                                }
                                                                                
                                                                            @endphp
                                                                            <li><a href="{{ $value_menu_level3['url'] }}" target="{{ ($value_menu_level3['target'] ==1 ? '_blank':'') }}">{{ $value_menu_level3['name'] }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                                @else
                                                                <li><a href="{{ $value_level2['url'] }}" target="{{ ($value_level2['target'] ==1 ? '_blank':'') }}">{{ $value_level2['name'] }}</a></li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-3 submenu_right">
 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>                                        
                                @else
                                <li><a href="{{ $value_level1['url'] }}" target="{{ ($value_level1['target'] ==1 ? '_blank':'') }}">{{ $value_level1['name'] }}</a></li>
                                @endif
                            @endforeach
                        @endif
                        <li>
                            <div class="wrap_btn_fz">
                                <div class="btn_fz fontsize_s">A</div><div class="btn_fz fontsize_m">A</div><div class="btn_fz fontsize_l">A</div>
                            </div>
                        </li>
                    </ul>
                    @php
                        $title_btn_login="เข้าสู่ระบบ";
                        if(Auth::check()){
                            $title_btn_login="ข้อมูลผู้ใช้";
                        }
                    @endphp
                    <div class="btn_login"><span>{{ $title_btn_login }}</span>

                        <div class="wrap_login">

                            <div style="{{ (!Auth::check() ? '':'display:none;')  }}">
                                {!! Form::open(['url' => route('login'),'method'=>'post','id'=>'loginForm']) !!}
                                <input class="form-control" name="username_sign_in"  placeholder="ชื่อผู้ใช้งาน" type="text">
                                <input class="form-control" name="password_sign_in" placeholder="รหัสผ่าน" type="password">
                                <div class="wrap_fotgotandlogin">
                                    <a class="fancybox" id="forgotpassword" href="#forgot_password" >ลืมรหัสผ่าน?</a>
                                    <button class="btn_login_popup" type="submit">Login</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div style="{{ (Auth::check() ? '':'display:none;')  }}">
                                @php
                                $user_roles = (Auth::check() ? auth()->user()->load('roles'):'');
                                //dd($user_roles->roles['0']->name);
                                @endphp
                                <div>
                                    บัญชี:{{ (Auth::check() ? auth()->user()->name:'') }}
                                </div>
                                <div class="btn_foradmin_trader">
                                    <a class="fancybox" id="editprofile" href="#edit_profile" style="color:black !important;">แก้ไขโปรไฟล์?</a>
                                </div>
                                <div class="wrap_fotgotandlogout">
                                {!! Form::open(['url' => route('logout'),'method'=>'post','id'=>'logoutForm']) !!}
                                        <button class="btn_login_popup">ออกจากระบบ</button>
                                {!! Form::close() !!}
                                </div>
                            </div>

                            <div style="display:none;">
                                <div id="forgot_password" class="frm_register">
                                    {!! Form::open(['url' => route('user.forgotpassword'),'method'=>'post','id'=>'forgotpasswordForm']) !!}
                                    <h1>ลืมรหัสผ่าน</h1>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="email"><span>*</span> อีเมล</label>
                                                <input type="text" class="form-control" name="email_forgot" value="{{ old('email_forgot') }}" maxlength="50" >
                                                  @if ($errors->any())
                                                        @foreach ($errors->all() as $error)
                                                            @if($error =='email_exists')
                                                            <label id="lastname-error" class="error" for="lastname">ไม่พบอีเมลในระบบ
                                                            </label>
                                                            @endif
                                                        @endforeach
                                                  @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wrap_btn_subregister">
                                        <button class="btn_cfregister" type="submit">ส่งอีเมล</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div><!-- End div style="display:none;" -->
                            <div style="display:none;">
                                <div id="edit_profile" class="frm_register">
                                        {!! Form::open(['url' => route('profile.edit.index'),'method'=>'post','id'=>'editprofileForm']) !!}
                                        <h1>แก้ไขโปรไฟล์</h1>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                
                                                <div class="form-group">
                                                    <label for="email"><span>*</span> รูปโปรไฟล์</label>
                                                    @if(Auth::check())
                                                        @if (auth()->user()->getMedia('avatar')->isNotEmpty())
                                                            <img src="{{ asset(auth()->user()->getMedia('avatar')->first()->getUrl()) }}" width="150">
                                                            <div>
                                                                <a href="{{ route('admin.profile.delete.avatar', auth()->user()->id) }}" 
                                                                    data-toggle="confirmation" 
                                                                    data-title="คุณต้องการลบรูปโปรไฟล์หรือไม่ ?">ลบ</a>
                                                            </div>
                                                        @else
                                                            <img src="{{ asset('images/default-avatar.png') }}" width="150">
                                                        @endif
                                                    @endif
                                                    <br><br>
                                                    {{ Form::file('avatar') }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="email"><span>*</span> ชื่อ</label>
                                                    {{ Form::text('name', (Auth::check() ? auth()->user()->name:''), ['class' => 'form-control']) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="email"><span>*</span> อีเมล</label>
                                                    {{ Form::email('email',(Auth::check() ? auth()->user()->email:''), ['class' => 'form-control', 'disabled' => true]) }}
                                                </div>
                                                <div class="form-group">    
                                                    <label for="email"><span>*</span> วันเดือนปีเกิด</label>
                                                    <div class="input-group date">
                                                        <input type="text" name="date_of_birth" id="datepicker2" value="{{ (Auth::check() ? auth()->user()->date_of_birth:'') }}" class="form-control" placeholder="วันเดือนปีเกิด"> 
                                                        <div class="input-group-addon">
                                                            <span class="glyphicon glyphicon-th"></span>
                                                        </div>
                                                    </div>
                                                </div>   
                                                <div class="form-group">
                                                    <label for="email"><span>*</span> รหัสผ่าน</label>
                                                    {{ Form::password('password', ['class' => 'form-control']) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="email"><span>*</span> ยืนยันรหัสผ่าน</label>
                                                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                                                </div>
    
                                            </div>
                                        </div>
                                        <div class="wrap_btn_subregister">
                                            <button class="btn_send" type="submit">แก้ไข</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                            </div><!-- End div style="display:none;" -->

                            <!-- Register -->
                            <a id="inline" class="btn_register fancybox" href="#data">สมัครสมาชิกเพื่อรับสื่อ</a>
                            <div style="display:none;">
                                <div id="data">
                                    <div class="frm_register">
                                        {!! Form::open(['url' => route('register.create'),'method'=>'post','id'=>'signupForm']) !!}
                                        <h1>ลงทะเบียน</h1>
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6">
                                                  <div class="form-group">
                                                    <input class="form-control"  type="text" placeholder="ชื่อ" name="firstname" value="{{ old('firstname') }}" maxlength="50">
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                                  <div class="form-group">
                                                    <input class="form-control" type="text" placeholder="นามสกุล" name="lastname" value="{{ old('lastname') }}" maxlength="50">
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                                  <div class="form-group">
                                                    <input class="form-control" type="text" placeholder="เบอร์โทร" name="phone" value="{{ old('phone') }}" maxlength="20">
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                                  <div class="form-group">
                                                    <input class="form-control" type="text" placeholder="อีเมล" name="email" value="{{ old('email') }}" maxlength="50">
                                                    @if ($errors->any())
                                                        @foreach ($errors->all() as $error)
                                                            @if($error =='email_unique')
                                                            <label id="lastname-error" class="error" for="lastname">อีเมลถูกใช้ไปแล้ว
                                                            </label>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                  </div>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                                  <div class="form-group">
                                                    <input class="form-control" type="password" placeholder="รหัสผ่าน" name="password" maxlength="50" id="password"  passwordCheck="passwordCheck">
                                                  </div>
                                                  <span style="color:red;">
                                                        หมายเหตุ 
                                                        <br>
                                                        * รหัสผ่านจะต้องตั้งเป็นภาษาอังกฤษเท่านั้น
                                                        <br>
                                                        * มีจำนวนไม่ต่ำกว่า 8 character ต้องประกอบด้วยตัวพิมพ์เล็ก,พิมพ์ใหญ่, ตัวเลข
                                                  </span>
                                              </div>
                                              <div class="col-xs-12 col-sm-6">
                                                    <div class="form-group">    
                                                        <div class="input-group date">
                                                            <input type="text" name="date_of_birth" id="datepicker" class="form-control" placeholder="วันเดือนปีเกิด">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                        </div>
                                                    </div>   
                                              </div>                                             
                                              <div class="col-xs-12">
                                                  <div class="btnregister">
                                                      <button class="btn_send">ลงทะเบียน</button>
                                                      <button class="btn_cancel">ยกเลิก</button>
                                                  </div>
                                              </div>
                                          </div>
                                        {{ Form::hidden('roles[]','Member',['class' => 'form-control']) }}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            <!-- End Register -->
                            <!-- route('login-facebook -->
                            @if(!Auth::check())
                            <a href="{{ route('facebook-login') }}" class="btn_login_facebook">Login With Facebook</a>
                            @endif
                            @if(!Auth::check())
                            <a href="{{ env('URL_SSO_LOGIN') }}" class="btn_login_sso">Login With SSO</a>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <style>
            .mainnavbar{
                z-index: 6899;
                position: relative;
                background-color: #FFF;
            }
            .logo{
                text-align: left;
            }
            .logo a{
                display: inline-block;
                position: relative;
                width: 100px;
            }
            .logo a img{
                max-width: 100%;
                max-height: 80px;
                height: auto;
                width: 100%;
                display: block;
                margin-top: 5px; 
            }
            .mainmenu{
                text-align: right;
                padding-right: 0;
                position: static;
            }
            .mainmenu ul{

                display: block;
                padding: 0;
                margin-bottom: 0;
                margin-top: 35px;
            }
            .mainmenu > ul{
                display: inline-block;
                vertical-align: middle;
            }
            .mainmenu ul li{
                display: inline-block;
                margin-left: 35px;
                padding-bottom: 32px;
                vertical-align: bottom;
            }
            .mainmenu ul li a{
                font-size: 1.1rem;
                display: block;
                color: #2c2b2b;
                text-decoration: none;
                -webkit-transition: all  0.5s ease-in-out;
                -moz-transition:all  0.5s ease-in-out;
                -o-transition: all  0.5s ease-in-out;
                transition: all  0.5s ease-in-out;
            }
            .mainmenu ul li a:hover{
                color: #01a48e;
            }
            .btn_menu{
                display: none;
            }
            .submenu{
                position: absolute;
                left: 0;
                width: 100%;
                top: 100%;
                border-top: 4px solid #01a48e;
                border-bottom: 4px solid #01a48e;
                background-color: #FFF;
                text-align: left;
                color: #000;
            }
            .submenu_left{
                border-right: 1px solid #9d9b9b;
                margin-top: 40px;
                margin-bottom: 15px;
            }
            .submenu_left h1{
                font-size: 2.5rem;
                line-height: 0.7;
                margin-bottom: 0;
            }
            .submenu_left h2{
                font-weight: 300;
                font-size: 2rem;
                line-height: 0.7;
                margin-top: 0;
            }
            .submenu_left h3{
                color: #aeacac;
                font-size: 1rem;
                line-height: 0.9;
                margin-bottom: 3px;
            }
            .submenu_left p{
                color: #aeacac;
                font-size: 0.8rem;
                line-height: 0.9;
            }
            .mainmenu ul li .submenu_left a{
                display: inline-block;
                font-size: 0.8rem;
                border: 1px solid #9b9b9b;
                color: #858484;
                padding: 5px 20px 3px 20px;
                margin-top: 15px;
            }
            .mainmenu ul li .submenu_left a:hover{
                color: #01a48e;
                border: 1px solid #01a48e;
            }
            .submenu_right img{
                width: 100%;
                height: auto;
                display: block;
            }
            .submenu_mid ul.submenu_mid_list{
                columns: 2;
                -webkit-columns: 2;
                -moz-columns: 2;
            }
            .submenu_mid ul.submenu_mid_list > li{
                padding: 0;
                /*background-image: url(images/icon_submenu.jpg);
                background-repeat: no-repeat;
                background-position: left 9px;*/
                padding-left: 17px;
                display: block;
                -webkit-column-break-inside: avoid;
                page-break-inside: avoid;  
                break-inside: avoid-column; 
                position: relative;
            }
            .submenu_mid ul.submenu_mid_list > li::before{
                position: absolute;
                top: 9px;
                left: 0;
                width: 12px;
                height: 12px;
                content: "";
                background-image: url({{ asset('themes/thrc/images/icon_menu.svg') }});
                background-repeat: no-repeat;
                background-size: 12px auto;
            }
            .submenu_mid ul.submenu_mid_list > li > a{
                color: #000;
                font-size: 1rem;
            }
            .submenu_mid{
                margin-bottom: 15px;
            }
            .submenu_mid ul li a{
                font-weight: 500;
                color: #000;
                text-decoration: none;
            }
            .submenu_mid ul li ul{
                margin: 0;
                list-style: disc;
                padding-left: 0px;
            }
            .submenu_mid ul li ul li{
                color: #818181;
                display: list-item;
                padding: 0;
            }
            .submenu_mid ul li ul li a{
                font-size: 0.9rem;
                font-weight: normal;
                color: #000;
            }
            .submenu_mid ul li a:hover{
                color: #01a48e;
            }
            .hassub{
                cursor: pointer;
            }
            .btn_login{
                display: inline-block;
                margin-left: 35px;
                font-size: 1.1rem;
                color: #2c2b2b;
                border-bottom: 2px solid #009881;
                line-height: 1;
                padding: 2px;
                background-image: url({{ asset('themes/thrc/images/user_icon.svg') }});
                background-position: left center;
                background-size: 15px auto;
                background-repeat: no-repeat;
                padding-left: 20px;
                cursor: pointer;
                position: relative;
                vertical-align: middle;
            }
            .mainnavbar.sticky{
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                box-shadow: 2px 2px 2px #eee;
            }
            
            .wrap_login{
                display: none;
                position: absolute;
                top: 100%;
                background-color: #FFF;
                right: -4px;
                padding: 15px;
                border-radius: 5px;
                border: 2px solid #009881;
                width: 280px;
                text-align: left;
                text-decoration: none;
            }
            .wrap_login .form-control{
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-radius: 0;
                border-bottom: 1px solid #ccc;
                font-size: 1rem;
                box-shadow: none;
                margin-top: 10px;
            }
            .wrap_login::before{
                content: "";
                    top: -7px;
            position: absolute;
                right: 15px;
              width: 0;
              height: 0;
              border-left: 7px solid transparent;
              border-right: 7px solid transparent;
              border-bottom: 7px solid #009881;
            }
            .btn_login_popup{
                background-color: #009881;
                color: #FFF;
                line-height: 1;
                padding: 5px 0;
                width: 100px;
                text-align: center;
                display: block;
                border: 0;
                position: absolute;
                right: 0;
                top: 0;
            }
            .wrap_fotgotandlogin{
                display: block;
                position: relative;
                margin-top: 15px;
            }
            .wrap_fotgotandlogin a{
                display: inline-block;
                color: #666;
                text-decoration: none;
                padding-left: 10px;
            }
            .wrap_fotgotandlogin a:hover{
                color: #333;
            }
            .btn_register{
                color: #666;
                text-decoration: none;
                padding-left: 10px;
                text-align: center;
                display: block;
                margin-top: 25px;
            }
            .btn_register:hover{
                color: #333;
            }
            .frm_register{
                background-color: #fff;
                border-top: 7px solid #009881;
                width: 800px;
                overflow: hidden;
                padding: 15px 35px 50px;
            }
            .frm_register h1{
                color: #000;
            }
            .frm_register .form-control{
                border-radius: 0;
                font-size: 1.1rem;
            }
            .frm_register .form-group{
                margin-bottom: 30px;
            }
            .frm_register button{
            width: 130px;
            height: 35px;
            color: #fff;
            cursor: pointer;
            line-height: 35px;
            text-align: center;
            font-size: 1.1rem;
            border: 0;
            margin:10px 5px;
        }
            .btnregister{
                text-align: center;
            }
            .btn_cancel{
                background-color: #aaa;
            }
            .btn_send{
                background-color: #009881;
            }
        .btn_login_facebook{
            display: block;
            font-size: 1.1rem;
            color: #FFF;
            background-color: #3b5998;
            border-radius: 5px;
            padding: 7px 5px 7px 35px;
            text-align: center;
            line-height: 1;
            margin-top: 15px;
            background-image: url({{ asset('themes/thrc/images/facebookbtn.svg') }});
            background-size: 16px auto;
            background-repeat: no-repeat;
            background-position: 38px center;
        }
        .btn_login_facebook:hover{
            color: #FFF;
            text-decoration: none;
        }
        .btn_login_sso{
            display: block;
            font-size: 1.1rem;
            color: #FFF;
            background-color: #009881;
            border-radius: 5px;
            padding: 7px 5px 7px 10px;
            text-align: center;
            line-height: 1;
            margin-top: 15px;
            /*background-image: url({{ asset('themes/thrc/images/facebookbtn.svg') }});*/
            background-size: 16px auto;
            background-repeat: no-repeat;
            background-position: 38px center;
        }
        .btn_login_sso:hover{
            color: #FFF;
            text-decoration: none;
        }
        .wrap_btn_fz{
            display: inline-block;
            vertical-align: top;
            line-height: 20px;
            margin-right: 10px;
        }
        .btn_fz{
            display: inline-block;
            color: #333;
            margin-left: 2px;
            margin-right: 2px;
            vertical-align: baseline;
            font-weight: normal;
            cursor: pointer;
        }
        .fontsize_s{
            font-size: 18px;
        }
        .fontsize_m{
            font-size: 24px;
        }
        .fontsize_l{
            font-size: 30px;
        }

        
        @media (min-width: 992px){
            .submenu{
                z-index: -9;
                opacity: 0;
                visibility: hidden;
                -webkit-transition: all  0.5s ease-in-out;
                -moz-transition:all  0.5s ease-in-out;
                -o-transition: all  0.5s ease-in-out;
                transition: all  0.5s ease-in-out;
            }
            .hassub:hover .submenu{
                opacity: 1.0;
                z-index: 99998;
                visibility: visible;
            }
        }
        @media (max-width: 1199px){
            .mainmenu{
                padding-right: 15px;
            }
            .mainmenu ul li{
                margin-left: 15px;
            }
        }
        @media (max-width: 991px){
            .frm_register{
                width: 90%;
                margin: 0 auto;
            }
            .btn_menu{
                display: inline-block;
                position: absolute;
                top: 18px;
                right: 15px;
            }    
            .btn_menu .btn_menu_line{
                width: 25px;
                display: inline-block;
                padding-right: 5px;
                padding-top: 1px;
                vertical-align: middle;
            }
            .btn_menu .btn_menu_line span{
                display: block;
                height: 3px;
                background-color: #3a3a3a;
                margin-bottom: 3px;
            }
            .btn_menu .btn_menu_text{
                display: inline-block;
                font-size: 1.2rem;
                font-weight: 500;
                letter-spacing: 1px;
                vertical-align: middle;
            }
            .logo a img{
                max-height: 70px;
            }
            .mainmenu > ul{
                position: absolute;
                left: 0;
                background-color: #FFF;
                margin-top: 0;
                top: 100%;
                width: 100%;
                padding-top: 15px;
                padding-bottom: 15px;
                border-top: 2px solid #01a48e;
                border-bottom: 2px solid #01a48e;
                display: none;
            }
            .mainmenu > ul > li{
                padding-bottom: 0;
                display: block;
                margin: 0;
                text-align: left;
            }
            .mainmenu > ul > li > a{
                font-size: 1.2rem;
                padding-left: 15px;
                padding-right: 15px;
            }
            .submenu{
                position: static;
                display: none;
            }
            .submenu_left h1{
                font-size: 1.3rem;
            }
            .submenu_left h2{
                font-size: 1.1rem;
            }
            .submenu_mid ul.submenu_mid_list > li{
                margin-left: 0;
            }
            .submenu_left{
                margin-top: 0;
            }
            .btn_login{
                margin-left: 0;
                margin-right: 70px;
                margin-top: 27px;
            }
            .logo a{
                width: 90px;
            }
            .wrap_btn_fz{
                padding-left: 15px;
                margin-top: 10px;
            }
            .btn_fz{
                padding: 2px 5px;
            }
        }
        @media (max-width: 767px){
            .submenu_mid ul.submenu_mid_list{
                margin-top: 0;
            }
            .submenu_right img{
                max-width: 400px;
            }
            .submenu_mid ul.submenu_mid_list{
                columns: 1;
              -webkit-columns: 1;
              -moz-columns: 1;
            }
            .submenu_right{
                display: none;
            }
            .submenu_left{
                display: none;
            }
            .submenu_mid{
                padding-top: 10px;
            }
            .btn_menu{
                margin-top: 8px;
            }
            .btn_login{
                font-size: 1rem;
            }
            .frm_register{
                width: 100%;
                padding: 0 15px 20px;
            }
            .frm_register h1{
                font-size: 1.4rem;
            }
            .frm_register .form-group{
                margin-bottom: 20px;
            }
            .frm_register button{
                width: 125px;
            }
        }
        @media (max-width: 361px){
            .menuhome a{
                padding: 0 2px;
            }
            .frm_register button{
                width: 118px;
            }
        }
    </style>
        
    <script>
    $(document).ready(function(){

        $( '.btn_menu' ).click(function (event) {
          if (  $( ".mainmenu > ul" ).is( ":hidden" ) ) {
                $(this).addClass("active");
                $('.mainmenu > ul').slideDown();
          } else {
              $('.mainmenu > ul').slideUp();
              $(this).removeClass("active");
              $('.submenu').slideUp();
              $('.hassub').removeClass("active");
          }
          event.stopPropagation();
        });
        
        $( '.hassub' ).click(function (event) {
          if (  $(this).children( ".submenu" ).is( ":hidden" ) ) {
                $('.hassub').removeClass("active");
                $(this).addClass("active");
                $('.submenu').slideUp();
                $(this).children( ".submenu" ).slideDown();
          } else {
              if (Modernizr.mq('(max-width: 991px)')) {
                  $('.submenu').slideUp();
                  $(this).removeClass("active");
              }
          }
          event.stopPropagation();
        });
        
        
         $( 'html' ).click(function (event) {
        });
        
        $( '.btn_login span' ).click(function (event) {
          $('.wrap_login').fadeToggle();
          event.stopPropagation();
        });  
        
        $( '.fontsize_s' ).click(function (event) {
            $('html').removeClass('fz_l');
            $('html').addClass('fz_s');
        });
        $( '.fontsize_m' ).click(function (event) {
            $('html').removeClass('fz_l fz_s');
        });
        $( '.fontsize_l' ).click(function (event) {
            $('html').removeClass('fz_s');
            $('html').addClass('fz_l');
        });

        $('#datepicker').datepicker({ 
            format:'yyyy-mm-dd',
            //format:'YYYY/MM/DD',
            //minDate: start_date
        });

        $('#datepicker2').datepicker({ 
            format:'yyyy-mm-dd',
            //format:'YYYY/MM/DD',
            //minDate: start_date
        });        

    });
        
    $(window).scroll(function() {
        if ($(this).scrollTop() > 0){  
            $('.mainnavbar').addClass("sticky");
            $('body').css('padding-top', $('.mainnavbar').height());
        }
        else{
            $('.mainnavbar').removeClass("sticky");
            $('body').css('padding-top','0px');
        }
    });
    </script>