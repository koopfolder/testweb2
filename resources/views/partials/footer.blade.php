<?php
	$lang = \App::getLocale();
    $left_menu = ThrcHelpers::getMenu(['position'=>'footer-left']);
    $right_menu = ThrcHelpers::getMenu(['position'=>'footer-right']);
    //dd($left_menu);

    $site = ThrcHelpers::getSetting(['slug'=>'site','retrieving_results'=>'first']);
    $address = ThrcHelpers::getSetting(['slug'=>'address','retrieving_results'=>'first']);
    $telephone = ThrcHelpers::getSetting(['slug'=>'telephone','retrieving_results'=>'first']);
    $fax = ThrcHelpers::getSetting(['slug'=>'fax','retrieving_results'=>'first']);
    $email = ThrcHelpers::getSetting(['slug'=>'email','retrieving_results'=>'first']);

?>
<footer class="row">
        <div class="container">
            <div class="row">
                <!-- Left Menu -->
                <div class="col-xs-6 col-sm-4 col-md-2 link_footer">
                    @if($left_menu->count())
                        @foreach ($left_menu AS $key_level1 =>$value_level1)
                        <div><a href="{{ $value_level1['url'] }}" target="{{ ($value_level1['target'] ==1 ? '_blank':'') }}">{{ $value_level1['name'] }}</a>
                        </div>
                        @endforeach
                    @endif
                   
                </div>
                <!-- Right Menu -->
                <div class="col-xs-6 col-sm-4 col-md-2 link_footer">
                    @if($right_menu->count())
                        @foreach ($right_menu AS $key_level1 =>$value_level1)
                        <div><a href="{{ $value_level1['url'] }}" target="{{ ($value_level1['target'] ==1 ? '_blank':'') }}">{{ $value_level1['name'] }}</a></div>
                        @endforeach
                    @endif
                </div>
                <!--<div class="col-xs-12 col-sm-4 col-md-3 newsletter">
                    <h1>ติดต่อขอรับสื่อ</h1>
                    <input type="text" placeholder="Email">
                    <button>ขอรับสื่อ</button>
                </div>-->
                
                <div class="col-xs-12 col-md-5 footer_cinfo">
                    <h1>{{ (collect($site)->isNotEmpty() ? $site->value:'') }}</h1>
                    <p>{{ (collect($address)->isNotEmpty() ? $address->value:'') }}</p>
                    <p>โทรศัพท์ : {{ (collect($telephone)->isNotEmpty() ? $telephone->value:'') }} | โทรสาร : {{ (collect($fax)->isNotEmpty() ? $fax->value:'') }}</p>
                    <p>อีเมลล์ : {{ (collect($email)->isNotEmpty() ? $email->value:'') }}</p>
                </div>
            </div>
        </div>
</footer>

<style>
        footer.row{
            background-color: #434242;
            padding-top: 30px;
            padding-bottom: 40px;
        }
        .link_footer{
            padding-right: 0;
        }
        .link_footer div{
            line-height: 1;
        }
        .link_footer a{
            display: inline-block;
            color: #dbdbdb;
            font-size: 0.9rem;
            line-height: 1.1;
            position: relative;
            padding-left: 15px;
        }
        .link_footer a::before{
            content: "";
            position: absolute;
            width: 3px;
            height: 3px;
            background-color: #dbdbdb;
            border-radius: 5px;
            left: 5px;
            top: 8px;
        }
        .newsletter{
            
        }
        .newsletter h1{
            color: #cecece;
            font-size: 0.9rem;
            margin-top: 0;
            margin-bottom: 5px;
            line-height: 1;
            font-weight: normal;
        }
        .newsletter input{
            border-radius: 0;
            border: 1px solid #FFF;
            width: 100%;
            background-color: transparent;
            color: #cecece;
            padding: 0 10px;
        }
        .newsletter button{
            font-size: 0.8rem;
            color: #FFF;
            background-color: #009881;
            display: inline-block;
            border: 0;
            line-height: 1;
            padding: 5px 18px 3px 18px;
            margin-top: 15px;
        }
        .newsletter input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
          color: #cecece;
        }
        .newsletter input::-moz-placeholder { /* Firefox 19+ */
          color: #cecece;
        }
        .newsletter input:-ms-input-placeholder { /* IE 10+ */
          color: #cecece;
        }
        .newsletter input:-moz-placeholder { /* Firefox 18- */
          color: #cecece;
        }
        .footer_cinfo{
            padding-left: 30px;
        }
        .footer_cinfo h1{
            margin-top: 0;
            margin-bottom: 0;
            font-size: 1rem;
            color: #cecece;
            line-height: 1.2;
        }
        .footer_cinfo p{
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #cecece;
            line-height: 1.2;
        }
    @media (max-width: 767px){
        .footer_cinfo{
            padding-left: 15px;
        }
    }
    @media (max-width: 767px){
        .newsletter{
            margin-top: 20px;
            margin-bottom: 15px;
        }
        footer.row {
            padding-top: 25px;
            padding-bottom: 20px;
        }
    }
</style>