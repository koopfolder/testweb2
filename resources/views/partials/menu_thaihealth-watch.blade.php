@php
    $lang = \App::getLocale();
    $main_menu = ThrcHelpers::getMenu(['position'=>'header']);
    //dd($main_menu);
    //$logo_value = ThrcHelpers::getSetting(['slug'=>'logo_desktop','retrieving_results'=>'first']);
    //$logo = ($logo_value ? asset($logo_value->value) : '');
    $logo = asset('themes/thrc/thaihealth-watch/images/ThaiHealthWatch-logo.svg');
    $time_cache  =  ThrcHelpers::time_cache(15);
    //dd($time_cache);
    //exit();

    //dd(auth()->user());
@endphp

<nav class="row mainnavbar">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-sm-2 logo"><a href="{{ route('thaihealth-watch') }}"><img src="{{ $logo }}"></a></div>
            <div class="col-xs-6 col-sm-10 mainmenu">
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