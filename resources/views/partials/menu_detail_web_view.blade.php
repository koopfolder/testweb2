@php
	$lang = \App::getLocale();
    //$main_menu = ThrcHelpers::getMenu(['position'=>'header']);
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
                <div class="col-xs-4 col-sm-2 logo">
                    <svg class="webback" onclick="window.history.back();" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 256 256" style="enable-background:new 0 0 256 256;" xml:space="preserve" fill="#333">
                    <g>
                        <g>
                            <polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128 		"/>
                        </g>
                    </g>
                    </svg>
<!--                    <img src="{{ asset('themes/thrc/images/back.png') }}" onclick="window.history.back();" class="web_view_back">-->
                    <a></a>
                </div>
                <div class="col-xs-8 col-sm-10">
                    <div class="wrap_seachbtn">
                        <span class="text_hl">Persona Health สื่อเฉพาะคุณ</span>
                        <div id="search_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="136pt" version="1.1" viewBox="-1 0 136 136.21852" width="136pt">
                            <g id="surface1" fill="#FFF">
                            <path d="M 93.148438 80.832031 C 109.5 57.742188 104.03125 25.769531 80.941406 9.421875 C 57.851562 -6.925781 25.878906 -1.460938 9.53125 21.632812 C -6.816406 44.722656 -1.351562 76.691406 21.742188 93.039062 C 38.222656 104.707031 60.011719 105.605469 77.394531 95.339844 L 115.164062 132.882812 C 119.242188 137.175781 126.027344 137.347656 130.320312 133.269531 C 134.613281 129.195312 134.785156 122.410156 130.710938 118.117188 C 130.582031 117.980469 130.457031 117.855469 130.320312 117.726562 Z M 51.308594 84.332031 C 33.0625 84.335938 18.269531 69.554688 18.257812 51.308594 C 18.253906 33.0625 33.035156 18.269531 51.285156 18.261719 C 69.507812 18.253906 84.292969 33.011719 84.328125 51.234375 C 84.359375 69.484375 69.585938 84.300781 51.332031 84.332031 C 51.324219 84.332031 51.320312 84.332031 51.308594 84.332031 Z M 51.308594 84.332031 " />
                            </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <style>
        .mainnavbar{
            background-color: #eee;
            border-bottom: 4px solid #009881;
        }
        .mainnavbar .row{
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }
        #search_icon{
            height: 94px;
            background-color: #ef7e25;
            width: 94px;
            text-align: center;
        }
        #search_icon svg{
            display: inline-block;
            width: 30px;
            height: auto;
            margin-top: 30px;
        }
        .webback{
            display: inline-block;
            width: 24px;
            height: auto;
            margin-top: 30px;
            cursor: pointer;
        }
        .wrap_seachbtn{
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            height: 100%;
            margin-right: -15px;
            margin-left: -15px;
            justify-content: space-between;
        }
        .wrap_seachbtn span{
            width: calc(100% - 94px);
            text-align: center;
            padding-left: 0;
            font-size: 1.25rem;
        }
        @media (max-width: 767px){
            #search_icon{
                height: 84px;
                width: 84px;
            }
            #search_icon svg{
                width: 24px;
                margin-top: 24px;
            }
            .logo{
                height: 84px;
            }
        }

            .text_hl{
                display: inline-block;
                font-size: 20px;
                font-weight: 500;
                vertical-align: middle;
                position: unset;
                min-height: 9px;
                padding-right: 23px;
                padding-left: 24px;
                margin-top: 23px;
            }

        
            .btn_menu{
                right: 45% !important;
            }

            .btn_menu_text{
                color: #009881;
                font-size: 1.4rem !important;
            }
            .web_view_back{
                display: inline-block;
                position: relative;
                /* padding: 5px; */
                /* margin-bottom: 31px; */
                padding-top: 0px;
                margin-top: -57px;
                cursor: pointer;
            }

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

        $('#search_icon').click(function(event) {
            $( ".row_search_inside" ).toggle();
        });
        

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