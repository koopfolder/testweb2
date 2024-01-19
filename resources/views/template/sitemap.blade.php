@inject('request', 'Illuminate\Http\Request')
@php
	$lang = \App::getLocale();
    //dd($menu);
    $main_menu = ThrcHelpers::getMenu(['position'=>'header']);
@endphp
@extends('layouts.app_ncds')
@section('content')
<div id="app">
    <h1>Sitemap</h1>
    <div class="">
            <div class="">               
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
                                    <li class=""><a>{{ $value_level1['name'] }}</a>
                                        <div class="">
                                            <div class="">
                                                <div class="">
                                                    <div class="">
                                                        <ul class="">
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
                                                </div>
                                            </div>
                                        </div>
                                    </li>                                        
                                @else
                                <li><a href="{{ $value_level1['url'] }}" target="{{ ($value_level1['target'] ==1 ? '_blank':'') }}">{{ $value_level1['name'] }}</a></li>
                                @endif
                            @endforeach
                        @endif
             
                    </ul>
            </div>
        </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>SiteMap</title>
    <meta charset="UTF-8">
    <meta name="description" content="SiteMap">
    <meta name="keywords" content="SiteMap">
    <meta name="author" content="THRC">
@endsection
@section('style')
	@parent
<style>

</style>
@endsection
@section('js')
	@parent
<script>

</script>
@endsection