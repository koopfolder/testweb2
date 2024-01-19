<?php
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Modules\Banner\Models\{Banner, BannerCategory};
use App\Modules\Article\Models\{Article,ArticleCategory};
use App\Modules\Api\Models\{ListMedia,ListArea,ListCategory,ListIssue,ListProvince,ListSetting,ListTarget,ListMediaIssues,ListMediaKeywords,ListMediaTargets,ListTemplate,Age,Sex};
use App\Modules\Manager\Models\Manager;
use Silber\Bouncer\Database\Role;
//use Silber\Bouncer\Database\Assigned_roles;
use App\Assigned_roles;
use App\Modules\Documentsdownload\Models\{Documents};
use App\Modules\Setting\Models\Setting;
use App\Modules\Menus\Models\Menu;
use App\Modules\Exhibition\Models\Exhibition;
use App\Modules\Exhibition\Models\Article AS Exhibition_Article;
use Junity\Hashids\Facades\Hashids;




class ThrcHelpers
{

    public static function CF_encode_json($arr) {
      $str = json_encode( $arr );
      $enc = base64_encode($str );
      $enc = strtr( $enc, 'poligamI123456', '123456poligamI');
      return $enc;
    }

    public static function CF_decode_json($str) {
      $dec = strtr( $str , '123456poligamI', 'poligamI123456');
      $dec = base64_decode( $dec );
      $obj = json_decode( $dec ,true);
      return $obj;
    }

    public static function encryptID($id,$decript=false,$pass='',$separator='-', & $data=array()) {
        $pass = $pass?$pass:Config::get('app.key');
        $pass2 = Config::get('app.url');;
        $bignum = 200000000;
        $multi1 = 500;
        $multi2 = 50;
        $saltnum = 10000000;
        if($decript==false){
            $strA = self::alphaid(($bignum+($id*$multi1)),0,0,$pass);
            $strB = self::alphaid(($saltnum+($id*$multi2)),0,0,$pass2);
            $out = $strA.$separator.$strB;
        } else {
            $pid = explode($separator,$id);


        //    trace($pid);
            $idA = (self::alphaid($pid[0],1,0,$pass)-$bignum)/$multi1;
            $idB = (self::alphaid($pid[1],1,0,$pass2)-$saltnum)/$multi2;
            $data['id A'] = $idA;
            $data['id B'] = $idB;
            $out = ($idA==$idB)?$idA:false;
        }
        return $out;
    }

    public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
    {
        $index = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n<strlen($index); $n++) {
                $i[] = substr( $index,$n ,1);
            }

            $passhash = hash('sha256',$passKey);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512',$passKey)
                : $passhash;

            for ($n=0; $n < strlen($index); $n++) {
                $p[] =    substr($passhash, $n ,1);
            }

            array_multisort($p,    SORT_DESC, $i);
            $index = implode($i);
        }

        $base    = strlen($index);

        if ($to_num) {
            // Digital number    <<--    alphabet letter code
            $in    = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out     = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number    -->>    alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a     = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in    = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }


    public static function days(){
        $days = array(
                "1"=>"1",
                "2"=>"2",
                "3"=>"3",
                "4"=>"4",
                "5"=>"5",
                "6"=>"6",
                "7"=>"7",
                "8"=>"8",
                "9"=>"9",
                "10"=>"10",
                "11"=>"11",
                "12"=>"12",
                "13"=>"13",
                "14"=>"14",
                "15"=>"15",
                "16"=>"16",
                "17"=>"17",
                "18"=>"18",
                "19"=>"19",
                "20"=>"20",
                "21"=>"21",
                "22"=>"22",
                "23"=>"23",
                "24"=>"24",
                "25"=>"25",
                "26"=>"26",
                "27"=>"27",
                "28"=>"28",
                "29"=>"29",
                "30"=>"30",
                "31"=>"31"
                );

        return $days;

    }

    public static function months(){
        $lang = \App::getLocale();

        if($lang =='th'){
            $month_arr=array(
            "01"=>"มกราคม",
            "02"=>"กุมภาพันธ์",
            "03"=>"มีนาคม",
            "04"=>"เมษายน",
            "05"=>"พฤษภาคม",
            "06"=>"มิถุนายน",
            "07"=>"กรกฎาคม",
            "08"=>"สิงหาคม",
            "09"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"
            );
        }else{
            $month_arr=array(
            "01"=>"January",
            "02"=>"February",
            "03"=>"March",
            "04"=>"April",
            "05"=>"May",
            "06"=>"June",
            "07"=>"July",
            "08"=>"August",
            "09"=>"September",
            "10"=>"October",
            "11"=>"November",
            "12"=>"December"
            );
        }

        return $month_arr;
    }


    public static function month($timestamp){
        $lang = \App::getLocale();

        if($lang =='th'){
            $month_arr=array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน",
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"
            );
        }else{
            $month_arr=array(
            "0"=>"",
            "1"=>"January",
            "2"=>"February",
            "3"=>"March",
            "4"=>"April",
            "5"=>"May",
            "6"=>"June",
            "7"=>"July",
            "8"=>"August",
            "9"=>"September",
            "10"=>"October",
            "11"=>"November",
            "12"=>"December"
            );
        }

        return $month_arr[date("n",strtotime($timestamp))];
    }


    public static function day($params){
        $lang = \App::getLocale();

        if($lang =='th'){
            $day_arr=array(
            "none"=>"",
            "Sun"=>"วันอาทิตย์",
            "Mon"=>"วันจันทร์",
            "Tue"=>"วันอังคาร",
            "Wed"=>"วันพุธ",
            "Thu"=>"วันพฤหัสบดี",
            "Fri"=>"วันศุกร์",
            "Sat"=>"วันเสาร์"
            );
        }else{
            $day_arr=array(
            "none"=>"",
            "Sun"=>"Sunday",
            "Mon"=>"Monday",
            "Tue"=>"Tuesday",
            "Wed"=>"Wednesday",
            "Thu"=>"Thursday",
            "Fri"=>"Friday",
            "Sat"=>"Saturday"
            );
        }

        return $day_arr[$params];
    }


    public static function DayArray(){

        $lang = \App::getLocale();
        if($lang =='th'){
            $day_arr=array(
            "Sun"=>"Sunday",
            "Mon"=>"Monday",
            "Tue"=>"Tuesday",
            "Wed"=>"Wednesday",
            "Thu"=>"Thursday",
            "Fri"=>"Friday",
            "Sat"=>"Saturday"
            );
        }else{
            $day_arr=array(
            "Sun"=>"วันอาทิตย์",
            "Mon"=>"วันจันทร์",
            "Tue"=>"วันอังคาร",
            "Wed"=>"วันพุธ",
            "Thu"=>"วันพฤหัสบดี",
            "Fri"=>"วันศุกร์",
            "Sat"=>"วันเสาร์"
            );
        }

        return $day_arr;
    }

    public static function HourArray(){
        $hour_array = array(
                        '0'=>'0',
                        '1'=>'1',
                        '2'=>'2',
                        '3'=>'3',
                        '4'=>'4',
                        '5'=>'5',
                        '6'=>'6',
                        '6'=>'6',
                        '7'=>'7',
                        '8'=>'8',
                        '9'=>'9',
                        '10'=>'10',
                        '11'=>'11',
                        '12'=>'12',
                        '13'=>'13',
                        '14'=>'14',
                        '15'=>'15',
                        '16'=>'16',
                        '17'=>'17',
                        '18'=>'18',
                        '19'=>'19',
                        '20'=>'20',
                        '21'=>'21',
                        '22'=>'22',
                        '23'=>'23'
                        );
        return $hour_array;
    }

    public static function MinuteArray(){
        $minute_array = array(
                        '0'=>'00',
                        '15'=>'15',
                        '30'=>'30',
                        '45'=>'45'
                        );
        return $minute_array;
    }

    public static function getYear(){
        $dt = Carbon::parse();
        $lang = \App::getLocale();
        $array = array();
        //$array[0]= \Lang::get('core.please_select_year');
        for ($i=0; $i < 50;$i++){
            if($lang === 'th'){
                $array[(($dt->year+543))-$i]= (($dt->year+543))-$i;
            }else{
                $array[(($dt->year))-$i]= (($dt->year))-$i;
            }
        }
        return $array;
    }

    public static function getYearForBackend(){
        $dt = Carbon::parse();
        $lang = \App::getLocale();
        $array = array();
        //$array[0]= \Lang::get('core.please_select_year');
        for ($i=0; $i < 50;$i++){
            $array[(($dt->year))-$i]= (($dt->year))-$i;
        }
        return $array;
    }

    public static function getSetting($params){

        //dd($params);

        $time_cache  =  ThrcHelpers::time_cache(60);
        $result = '';

        if (Cache::has('setting_'.$params['slug'])){
            $result = Cache::get('setting_'.$params['slug']);
        }else{

            if($params['retrieving_results'] ==='get'){
                $data = Setting::select('value')
                                ->where('slug','=',$params['slug'])
                                ->get();
            }else{
                $data = Setting::select('value')
                                ->where('slug','=',$params['slug'])
                                ->first();
            }
            Cache::put('setting_'.$params['slug'],$data,$time_cache);
            $result = Cache::get('setting_'.$params['slug']);
        }

        return $result;

    }

    public static function getMenu($params){

        //dd("get Menu",$params);

        $time_cache  =  ThrcHelpers::time_cache(10);
        $result = '';

        if (Cache::has('menu_'.$params['position'])){
            $result = Cache::get('menu_'.$params['position']);
        }else{

            $menus = Menu::select('id',
                                  'name AS name',
                                  'slug AS slug',
                                  'url_external',
                                  'description',
                                  'link_type',
                                  'layout',
                                  'target',  
                                  'status',
                                  'parent_id',
                                  'order'
                                  )
                           ->where('status', 'publish')
                           ->where('position', $params['position'])
                           ->where('site', 'frontend')
                           ->where('parent_id', 0)
                           ->orderBy('order', 'ASC')
                           ->get();


            $list = [];
            if ($menus->isNotEmpty()){
                //dd($menus);
                foreach ($menus as $menu) {
                    $list[$menu->id]['name'] = $menu->name;
                    //$list[$menu->id]['description'] = $menu->description;
                    if ($menu->url_external) {
                        $list[$menu->id]['url'] = $menu->url_external;
                    } else {
                        $list[$menu->id]['url'] = ThrcHelpers::customUrl($menu);
                    }

                    if($menu->link_type =='document'){
                        $list[$menu->id]['url'] =  ($menu->document_path !='' ? URL($menu->document_path):'#');
                    }
                    //$list[$menu->id]['image'] = $menu->getMedia('image_desktop')->isNotEmpty() ? asset($menu->getMedia('image_desktop')->first()->getUrl()) : null;
                    $list[$menu->id]['target'] = $menu->target;
                    $list[$menu->id]['layout'] = $menu->layout;
                    $list[$menu->id]['slug'] = $menu->slug;
                    $list[$menu->id]['link_type'] = $menu->link_type;
                    $list[$menu->id]['childrens'] = $menu->FrontChildren()->get();
                }
            }

            Cache::put('menu_'.$params['position'],collect($list),$time_cache);
            $result = Cache::get('menu_'.$params['position']);
        }

        return $result;
    }


    public static function getMenuCaseRoute($params){
        //dd("get Menu");
        $menus = Menu::select('id',
                                  'slug AS slug',
                                  'link_type',
                                  'layout',
                                  'status',
                                  'position',
                                  'parent_id'
                                  )
                           ->where('status', 'publish')
                           ->where('position', $params['position'])
                           ->where('site', 'frontend')
                           ->where('parent_id', 0)
                           ->orderBy('order', 'ASC')
                           ->get();


        $list = [];
        if ($menus->isNotEmpty()){
            //dd($menus);
            foreach ($menus as $menu) {
                $list[$menu->id]['slug'] = $menu->slug;
                $list[$menu->id]['layout'] = $menu->layout;
                $list[$menu->id]['link_type'] = $menu->link_type;
                $list[$menu->id]['childrens'] = $menu->FrontChildrenCaseRotue()->get();
            }
        }
        return collect($list);
    }





    public static function getBreadcrumbs($position){

        $menus = Menu::select('id',
                                  'name_th',
                                  'name_en',
                                  'slug_th',
                                  'slug_en',
                                  'link_type',
                                  'status',
                                  'position',
                                  'layout',
                                  'parent_id'
                            )
                           ->where('status', 'publish')
                           ->where('position',$position)
                           ->where('site', 'frontend')
                           ->where('parent_id', 0)
                           ->orderBy('order', 'ASC')
                           ->get();
        $list=[];
        if ($menus->isNotEmpty()){
            foreach ($menus as $menu){
                $list[$menu->id]['name_th'] = $menu->name_th;
                $list[$menu->id]['name_en'] = $menu->name_en;
                $list[$menu->id]['link_type'] = $menu->link_type;
                $list[$menu->id]['layout'] = $menu->layout;
                if($menu->link_type =='internal'){
                    $list[$menu->id]['url_th'] = url($menu->slug_th);
                    $list[$menu->id]['url_en'] = url($menu->slug_en);
                    $list[$menu->id]['slug_th'] = $menu->slug_th;
                    $list[$menu->id]['slug_en'] = $menu->slug_en;
                }else{
                    $list[$menu->id]['url_th'] = '';
                    $list[$menu->id]['url_en'] = '';
                    $list[$menu->id]['slug_th'] = '';
                    $list[$menu->id]['slug_en'] = '';
                }
                $list[$menu->id]['childrens'] = $menu->FrontBreadcrumbs()->get();
            }
        }
        return collect($list);
    }

    public static function checkActiveMenuLvel2($slug){
        $menu =Menu::select('id',
                      'name_th',
                      'name_en',
                      'slug_th',
                      'slug_en',
                      'parent_id'
                     )
                    ->where('status', 'publish')
                    ->whereRaw(' slug_th ="'.$slug.'" OR slug_en ="'.$slug.'"')
                    ->first();
        // $test = $menu->parent()->first();
        // dd($test->parent()->first());
        return $menu;
    }



    public static function customUrl($menu)
    {
        // $lang = \App::getLocale();
        // $url = url($lang.'/'.$menu->slug);

        //$lang = \App::getLocale();
        $url = url(''.$menu->slug);
        return $url;
    }

    public static function getBanners($params){

        $time_cache  =  ThrcHelpers::time_cache(5);
        $result = '';
        if (Cache::has('data_banner_'.$params['category'])){
            $result = Cache::get('data_banner_'.$params['category']);
        }else{
            $data = Banner::Data($params);
            $list = [];
            if(collect($data)->isNotEmpty() && $params['retrieving_results'] ==='get'){
                foreach($data as $banner) {
                    $list[$banner->id]['name']        = $banner->name;
                    $list[$banner->id]['description'] = $banner->description;
                    $list[$banner->id]['link'] = ThrcHelpers::generateUrl(['use_content'=>$banner->use_content,'use_content_params'=>$banner->use_content_params,'link'=>$banner->link]);
                    $list[$banner->id]['image_desktop'] = $banner->getMedia('desktop')->isNotEmpty() ? asset($banner->getFirstMediaUrl('desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                }    
                
            }else if($data->count() && $params['retrieving_results'] ==='first'){

                $list['name']        = $data->name;
                $list['description'] = $data->description;
                $list['link'] = ThrcHelpers::generateUrl(['use_content'=>$data->use_content,'use_content_params'=>$data->use_content_params,'link'=>$data->link]);
                $list['image_desktop'] = $data->getMedia('desktop')->isNotEmpty() ? asset($data->getFirstMediaUrl('desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
               
            }
            Cache::put('data_banner_'.$params['category'],collect($list),$time_cache);
            $result = Cache::get('data_banner_'.$params['category']);
        }   

       //dd($params);
        return $result;

    }

    public static function generateUrl($params){
        $url = '';
        $lang = \App::getLocale();
        switch ($params['use_content']) {
            case 'news_events':
                if($lang =='th'){
                    $url = URL($lang.'/ข่าวประชาสัมพันธ์-0/รายละเอียด'."/".Hashids::encode(json_decode($params['use_content_params'])->id));
                }else{
                    $url = URL($lang.'/news-0/detail'."/".Hashids::encode(json_decode($params['use_content_params'])->id));
                }
                break;
            case 'business':
                if($lang =='th'){
                    $url = URL($lang.'/ธุรกิจของบริษัท/รายละเอียด');
                }else{
                    $url = URL($lang.'/business/detail');
                }
                break;
            default:
                $url = $params['link'];
                break;
        }
        return $url;
    }

    public static function getNews($params){

        $time_cache  =  ThrcHelpers::time_cache(5);
        $result = '';
        if (Cache::has('data_news')){
            $result = Cache::get('data_news');
        }else{
            $items = Article::FrontDataNews(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);
            $list = [];
            if(collect($items)->isNotEmpty()){
                foreach($items as $key=> $value) {
                    //dd($items);
                    $list[$key]['title']        = $value->title;
                    $list[$key]['description'] = $value->description;
                    $list[$key]['hit'] = $value->hit;
                    $list[$key]['slug'] = $value->slug;
                    $list[$key]['created_at'] = $value->created_at;
                    $list[$key]['updated_at'] = $value->updated_at;
                    $list[$key]['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')):asset('themes/thrc/images/no-image-icon-3.jpg');
                }
            }
            //dd($list);
            Cache::put('data_news',collect($list),$time_cache);
            $result = Cache::get('data_news');
        }   

        return $result;
    }

    public static function getMediaKnowledges($params){

        $time_cache = ThrcHelpers::time_cache(5);
        $result = '';
        if (Cache::has('data_media_knowledges')){
            $result = Cache::get('data_media_knowledges');
        }else{
            $items = ListMedia::DetailKnowledges([]);
            if(isset($items->id)){
                Cache::put('data_media_knowledges',$items,$time_cache);
            }
            $result = Cache::get('data_media_knowledges');
        }
        return $result;

    }

    public static function getMediaCampaign($params){

        $time_cache = ThrcHelpers::time_cache(5);
        $result = '';
        if (Cache::has('data_media_campaign')){
            $result = Cache::get('data_media_campaign');
        }else{
            $items = ListMedia::DetailMediaCampaign([]);
            //dd($items);
            if(isset($items->id)){
                Cache::put('data_media_campaign',$items,$time_cache);
            }
            $result = Cache::get('data_media_campaign');
            //dd($result);
        }
        return $result;

    }


    public static function getArticle($params){
        
        $lang = \App::getLocale();
        //dd($params);
        $items = Article::FrontData(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);
        return $items;
    }

    public static function getInterestingIssues($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';

        if (Cache::has('data_interesting_issues')){
            $result = Cache::get('data_interesting_issues');
        }else{

            $article = Article::FrontDataInterestingIssuesNews(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);
       
            // if($article->count() < 5){
            //     $limit_media  = 5-$article->count();
            // }
            // if($limit_media >=1){

            //     $media = ListMedia::FrontDataInterestingIssuesNews(['status'=>['publish'],
            //     'featured'=>false,
            //     'interesting_issues'=>true,
            //     'limit'=>$limit_media,
            //     'retrieving_results'=>$params['retrieving_results']
            //    ]);
            //     //dd($media,$limit_media,$article->count());
            // }

            // //dd($article->count(),$limit_media);
            if(collect($article)->isNotEmpty() && $params['retrieving_results'] ==='get'){

                foreach($article as $key => $interesting_issues) {

                    $array = array();
                    $array['id']        = $interesting_issues->id;
                    $array['title']        = $interesting_issues->title;
                    $array['description'] = $interesting_issues->description;
                    $array['type_data'] = 'article';
                    $array['url'] = route('interestingissues-article-detail',$interesting_issues->slug);
                    $array['tags'] = $interesting_issues->tags;
                    $array['category'] = $interesting_issues->category;
                    $array['category_id'] = $interesting_issues->category_id;
                    if($interesting_issues->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($interesting_issues->dol_cover_image !=''){
                        $array['cover_desktop'] = $interesting_issues->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $interesting_issues->getMedia('cover_desktop')->isNotEmpty() ? asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
                    
                }

            }

            // if($limit_media >=1){
            //     if(collect($media)->isNotEmpty() && $params['retrieving_results'] ==='get'){

            //         foreach($media as $key => $interesting_issues) {
                        
            //             $json = ($interesting_issues->json_data !='' ? json_decode($interesting_issues->json_data):'');
            //             //dd($interesting_issues,$json,gettype($json));
            //             $array = array();
            //             $array['id']        = $interesting_issues->id;
            //             $array['title']        = $interesting_issues->title;
            //             $array['description'] = $interesting_issues->description;
            //             $array['type_data'] = 'media';
            //             $array['url'] = route('interestingissues-media-detail',Hashids::encode($interesting_issues->id));
            //             $array['cover_desktop'] = $interesting_issues->getMedia('cover_desktop')->isNotEmpty() ? asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
            //             array_push($list,$array);

            //         }

            //     }
            // }

            Cache::put('data_interesting_issues',collect($list),$time_cache);
            $result = Cache::get('data_interesting_issues');

        }

        //dd($result);
        return $result;
    }


    public static function getHealthLiteracy($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';

        if (Cache::has('data_health_literacy')){
            $result = Cache::get('data_health_literacy');
        }else{

            $article = Article::FrontHealthLiteracy(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);
            //dd($article);

            if(isset($article->id)){

                    $array = array();
                    $array['id']        = $article->id;
                    //$array['title']        = $article->title;
                    //$array['description'] = $article->description;
                    //$array['type_data'] = 'article';
                    //$array['url'] = route('interestingissues-article-detail',$article->slug);

                    if($article->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($article->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($article->dol_cover_image !=''){
                        $array['cover_desktop'] = $article->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $interesting_issues->getMedia('cover_desktop')->isNotEmpty() ? asset($interesting_issues->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
            }

            //dd($list);

            Cache::put('data_health_literacy',collect($list),$time_cache);
            $result = Cache::get('data_health_literacy');

        }

        //dd($result);
        return $result;
    }



    public static function getDataArticlesResearch($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';


        if (Cache::has('data_articles_research')){
            $result = Cache::get('data_articles_research');
        }else{

            $article = Article::FrontDataArticlesResearch(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);

            // if($article->count() < 2){
            //     $limit_media  = 2-$article->count();
            // }
            // if($limit_media >=1){

            //     $media = ListMedia::FrontDataArticlesResearch(['status'=>['publish'],
            //     'featured'=>false,
            //     'articles_research'=>true,
            //     'limit'=>$limit_media,
            //     'retrieving_results'=>$params['retrieving_results']
            //    ]);

            //     //dd($media,$limit_media,$article->count());
            // }

            //dd($article->count(),$limit_media);
            if(collect($article)->isNotEmpty() && $params['retrieving_results'] ==='get'){

                foreach($article as $key => $value) {

                    $array = array();
                    $array['id']        = $value->id;
                    $array['title']        = $value->title;
                    $array['description'] = $value->description;
                    $array['type_data'] = 'article';
                    $array['url'] =  route('article-detail',$value->slug);
                    $array['tags'] = $value->tags;
                    $array['category_id'] = $value->category_id;
                    
                    if($value->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($value->dol_cover_image !=''){
                        $array['cover_desktop'] = $value->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
                    
                }

            }

            // if($limit_media >=1){
            //     if(collect($media)->isNotEmpty() && $params['retrieving_results'] ==='get'){

            //         foreach($media as $key => $value) {
            //             $json = ($value->json_data !='' ? json_decode($value->json_data):'');
            //             //dd($value,$json,gettype($json));
            //             $array = array();
            //             $array['id']        = $value->id;
            //             $array['title']        = $value->title;
            //             $array['description'] = $value->description;
            //             $array['type_data'] = 'media';
            //             $array['url'] = route('media-detail',Hashids::encode($value->id));
            //             $array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
            //             array_push($list,$array);

            //         }

            //     }
            // }

            Cache::put('data_articles_research',collect($list),$time_cache);
            $result = Cache::get('data_articles_research');

        }
        //dd($result);
        return $result;
    }


    public static function getDataThaihealthWatch($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';


        if (Cache::has('data_thaihealth_watch')){
            $result = Cache::get('data_thaihealth_watch');
        }else{

            $article = Article::FrontDataArticlesResearch(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);

            // if($article->count() < 2){
            //     $limit_media  = 2-$article->count();
            // }
            // if($limit_media >=1){

            //     $media = ListMedia::FrontDataArticlesResearch(['status'=>['publish'],
            //     'featured'=>false,
            //     'articles_research'=>true,
            //     'limit'=>$limit_media,
            //     'retrieving_results'=>$params['retrieving_results']
            //    ]);

            //     //dd($media,$limit_media,$article->count());
            // }

            //dd($article->count(),$limit_media);
            if(collect($article)->isNotEmpty() && $params['retrieving_results'] ==='get'){

                foreach($article as $key => $value) {

                    $array = array();
                    $array['id']        = $value->id;
                    $array['title']        = $value->title;
                    $array['description'] = $value->description;
                    $array['type_data'] = 'article';
                    $array['url'] =  route('thaihealthwatch-detail',$value->slug);
                    
                    if($value->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($value->dol_cover_image !=''){
                        $array['cover_desktop'] = $value->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
                    
                }

            }

            // if($limit_media >=1){
            //     if(collect($media)->isNotEmpty() && $params['retrieving_results'] ==='get'){

            //         foreach($media as $key => $value) {
            //             $json = ($value->json_data !='' ? json_decode($value->json_data):'');
            //             //dd($value,$json,gettype($json));
            //             $array = array();
            //             $array['id']        = $value->id;
            //             $array['title']        = $value->title;
            //             $array['description'] = $value->description;
            //             $array['type_data'] = 'media';
            //             $array['url'] = route('media-detail',Hashids::encode($value->id));
            //             $array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
            //             array_push($list,$array);

            //         }

            //     }
            // }

            Cache::put('data_thaihealth_watch',collect($list),$time_cache);
            $result = Cache::get('data_thaihealth_watch');

        }
        //dd($result);
        return $result; 
    }


    public static function getDataLearningAreaCreatesDirectExperience($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';


        if (Cache::has('data_learning_area_creates_direct_experience')){
            $result = Cache::get('data_learning_area_creates_direct_experience');
        }else{

            $article = Article::FrontDataArticlesResearch(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);

            // if($article->count() < 2){
            //     $limit_media  = 2-$article->count();
            // }
            // if($limit_media >=1){

            //     $media = ListMedia::FrontDataArticlesResearch(['status'=>['publish'],
            //     'featured'=>false,
            //     'articles_research'=>true,
            //     'limit'=>$limit_media,
            //     'retrieving_results'=>$params['retrieving_results']
            //    ]);

            //     //dd($media,$limit_media,$article->count());
            // }

            //dd($article->count(),$limit_media);
            if(collect($article)->isNotEmpty() && $params['retrieving_results'] ==='get'){

                foreach($article as $key => $value) {

                    $array = array();
                    $array['id']        = $value->id;
                    $array['title']        = $value->title;
                    $array['description'] = $value->description;
                    $array['type_data'] = 'article';
                    $array['url'] =  route('learning-area-creates-direct-experience-detail',$value->slug);
                    
                    if($value->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($value->dol_cover_image !=''){
                        $array['cover_desktop'] = $value->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
                    
                }

            }

            // if($limit_media >=1){
            //     if(collect($media)->isNotEmpty() && $params['retrieving_results'] ==='get'){

            //         foreach($media as $key => $value) {
            //             $json = ($value->json_data !='' ? json_decode($value->json_data):'');
            //             //dd($value,$json,gettype($json));
            //             $array = array();
            //             $array['id']        = $value->id;
            //             $array['title']        = $value->title;
            //             $array['description'] = $value->description;
            //             $array['type_data'] = 'media';
            //             $array['url'] = route('media-detail',Hashids::encode($value->id));
            //             $array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
            //             array_push($list,$array);

            //         }

            //     }
            // }

            Cache::put('data_learning_area_creates_direct_experience',collect($list),$time_cache);
            $result = Cache::get('data_learning_area_creates_direct_experience');

        }
        //dd($result);
        return $result; 
    }




    public static function getDataIncludeStatistics($params){
        
        $time_cache  =  ThrcHelpers::time_cache(5);
        $count = 0;
        $limit_media = 0;
        $list = [];
        $result = '';


        if (Cache::has('data_include_statistics')){
            $result = Cache::get('data_include_statistics');
        }else{

            $article = Article::FrontDataIncludeStatistics(['status'=>['publish'],
                                     'page_layout'=>$params['page_layout'],
                                     'featured'=>$params['featured'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);

            // if($article->count() < 1){
            //     $limit_media  = 1-$article->count();
            // }
            // if($limit_media >=1){

            //     $media = ListMedia::FrontDataIncludeStatistics(['status'=>['publish'],
            //     'featured'=>false,
            //     'include_statistics'=>true,
            //     'limit'=>$limit_media,
            //     'retrieving_results'=>$params['retrieving_results']
            //    ]);

            //     //dd($media,$limit_media,$article->count());
            // }

            //dd($article->count(),$limit_media);
            if(collect($article)->isNotEmpty() && $params['retrieving_results'] ==='get'){

                foreach($article as $key => $value) {

                    $array = array();
                    $array['id']        = $value->id;
                    $array['title']        = $value->title;
                    $array['description'] = $value->description;
                    $array['type_data'] = 'article';
                    $array['url'] =  route('article-detail-case-include-statistics',$value->slug);
                    $array['tags'] = $value->tags;
                    $array['category'] = $value->category;
                    $array['category_id'] = $value->category_id;
                    if($value->getMedia('cover_desktop')->isNotEmpty()){
                        $array['cover_desktop'] = asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635'));
                    }else if($value->dol_cover_image !=''){
                        $array['cover_desktop'] = $value->dol_cover_image;
                    }else{
                        $array['cover_desktop'] = asset('themes/thrc/images/no-image-icon-3.jpg');
                    }
                    //$array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : asset('themes/thrc/images/no-image-icon-3.jpg');
                    array_push($list,$array);
                    
                }

            }

            // if($limit_media >=1){
            //     if(collect($media)->isNotEmpty() && $params['retrieving_results'] ==='get'){

            //         foreach($media as $key => $value) {
            //             $json = ($value->json_data !='' ? json_decode($value->json_data):'');
            //             //dd($value,$json,gettype($json));
            //             $array = array();
            //             $array['id']        = $value->id;
            //             $array['title']        = $value->title;
            //             $array['description'] = $value->description;
            //             $array['type_data'] = 'media';
            //             $array['url'] = route('media-detail-case-include-statistics',Hashids::encode($value->id));
            //             $array['cover_desktop'] = $value->getMedia('cover_desktop')->isNotEmpty() ? asset($value->getFirstMediaUrl('cover_desktop','thumb1366x635')) : (gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg'));
            //             array_push($list,$array);

            //         }

            //     }
            // }

            Cache::put('data_include_statistics',collect($list),$time_cache);
            $result = Cache::get('data_include_statistics');

        }
        //dd($result);
        return $result;
    }





    public static function getFranchise($params){
        $items = Franchise::FrontData(['status'=>['publish'],
                                     'limit'=>$params['limit'],
                                     'retrieving_results'=>$params['retrieving_results']
                                    ]);
        //dd($items);
        $list = [];
        if(collect($items)->isNotEmpty() && $params['retrieving_results'] ==='get'){
            foreach($items as $franchise) {
                $list[$franchise->id]['brand_name']        = $franchise->brand_name;
                $list[$franchise->id]['company_name'] = $franchise->company_name;
                $list[$franchise->id]['category_id'] = $franchise->category_id;
                $list[$franchise->id]['category_name'] = $franchise->category->category_name;
                $list[$franchise->id]['franchise_type'] = $franchise->franchise_type;
                $list[$franchise->id]['number_of_branches'] = $franchise->number_of_branches;
                $list[$franchise->id]['description'] = $franchise->description;
                $list[$franchise->id]['slug'] = $franchise->slug;
                $list[$franchise->id]['cover_desktop'] = $franchise->getMedia('cover_desktop')->isNotEmpty() ? asset($franchise->getMedia('cover_desktop')->first()->getUrl()) : asset('themes/thrc/images/no-image-icon-3.jpg');
            }
        }
        return collect($list);

    }

    public static function getContent($params){
        //dd($params);
        $id = Hashids::decode($params['id']);
        if(collect($id)->isNotEmpty()){
            switch ($params['tb']) {
                case 'article':
                    $data = Article::Breadcrumb(['id'=>$id]);
                    break;
                case 'manager':
                    $data = Manager::Breadcrumb(['id'=>$id]);
                    break;
                default:
                    $data = Article::Breadcrumb(['id'=>$id]);
                    break;
            }
            if(collect($data)->isNotEmpty()){
                return $data;
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }
    }

    public static function getDocument($params){
        $items = Documents::FrontData(['status'=>['publish'],'document_type'=>$params['document_type']]);
        return $items;
    }

    public static function getSubject(){
        $items = Contactsubject::DataDropdown(['status','publish']);
        return $items;
    }

    public static function getMenuName($slug){
        $lang = \App::getLocale();
        $list = [];
        if($lang =='th'){
            $menu = Menu::select('id',
                                  'name_th AS name',
                                  'slug_th AS slug',
                                  'url_external'
                                  )
                        ->where('status','publish')
                        ->where('slug_th',$slug)
                        ->first();
            if($menu){
                $list['name'] = $menu->name;
                if($menu->url_external) {
                  $list['url'] = $menu->url_external;
                } else {
                  $list['url'] = ThrcHelpers::customUrl($menu);
                }
            }
        }else{
            $menu = Menu::select('id',
                                  'name_en AS name',
                                  'slug_en AS slug',
                                  'url_external'
                                  )
                        ->where('status','publish')
                        ->where('slug_en',$slug)
                        ->first();
            if($menu){
                $list['name'] = $menu->name;
                if($menu->url_external) {
                  $list['url'] = $menu->url_external;
                } else {
                  $list['url'] = ThrcHelpers::customUrl($menu);
                }
            }
        }
        return collect($list);
    }


    public static function getPrefix(){
        $lang = \App::getLocale();
        $list = [];

        if($lang =='th'){
            $list['1'] = 'นาย';
            $list['2'] = 'นาง';
            $list['3'] = 'นางสาว';
        }else{
            $list['1'] = 'Mr.';
            //$list['2'] = 'Mrs.';
            //$list['3'] = 'Miss.';
            $list['4']  ='Ms.';
        }
        return $list;
    }


    public static function getPermissionsstatus($user){
        //Assigned_roles
        $data = Assigned_roles::Permissions(['user_id'=>$user]);
        return $data;
        //dd($user,$data);
    }


    public static function getMimes(){
        $result = 'Dbd';
        $data = Setting::select('value')
                            ->where('slug','=','mime_type')
                            ->first();
 
        if(collect($data)->isNotEmpty()){
            $result =implode(",",json_decode($data->value));
            //dd($result);
        }
        return $result;
    }

    public static function getExhibition($params){

        //Exhibition
        //Exhibition_Article
    
        switch ($params['page_layout']) {
            case 'revolving_exhibition':
                $data = Exhibition_Article::FrontData(['featured'=>$params['featured'],'retrieving_results'=>$params['retrieving_results'],'limit'=>$params['limit'],'page_layout'=>$params['page_layout'],'status'=>['publish']]);
                break;
            case 'permanent_exhibition':
                $data = Exhibition_Article::FrontData(['featured'=>$params['featured'],'retrieving_results'=>$params['retrieving_results'],'limit'=>$params['limit'],'page_layout'=>$params['page_layout'],'status'=>['publish']]);
                break;
            case 'exhibition_borrowed':
                $data = Exhibition_Article::FrontData(['featured'=>$params['featured'],'retrieving_results'=>$params['retrieving_results'],'limit'=>$params['limit'],'page_layout'=>$params['page_layout'],'status'=>['publish']]);
                break;    
            case 'traveling_exhibition':
                $data = Exhibition_Article::FrontData(['featured'=>$params['featured'],'retrieving_results'=>$params['retrieving_results'],'limit'=>$params['limit'],'page_layout'=>$params['page_layout'],'status'=>['publish']]);
                break;
            case 'online_exhibition':
                $data = Exhibition::FrontData(['retrieving_results'=>$params['retrieving_results'],'limit'=>$params['limit'],'status'=>['publish']]);
                //dd($data);
                break;  
            
            default:
                
                break;
        }
        // dd($params,$data);
        return collect($data);

    }



    public static function getNotableBooks($params){

        $time_cache  =  ThrcHelpers::time_cache(5);
        $result = '';

        if (Cache::has('data_notable_books')){
            $result = Cache::get('data_notable_books');
        }else{

            // $items = ListMedia::FrontListNotableBooks($params);
            // $list = [];
            // if(collect($items)->isNotEmpty()){
            //     foreach($items as $value) {
            //         $list[$value->id]['title']        = $value->title;
            //         $list[$value->id]['json_data'] = $value->json_data;
            //         $json = ($value->json_data !='' ? json_decode($value->json_data):'');
            //         $list[$value->id]['cover_desktop'] = App\Modules\Api\Http\Controllers\FrontendController::getImage(['model_type'=>'App\Modules\Api\Models\ListMedia','model_id'=>$value->id]); 
            //         $list[$value->id]['cover_desktop'] = (isset($list[$value->id]['cover_desktop']->id)  ? asset('media/'.$list[$value->id]['cover_desktop']->id.'/conversions/thumb1366x635.jpg'):(gettype($json) == 'object' ? $json->ThumbnailAddress : asset('themes/thrc/images/no-image-icon-3.jpg')));
            //     }
            // }
            $list = [];
            try {

                $form_params = ['username'=>env('API_BOOKDOSE_USER','service_web_resources'),'password'=>env('API_BOOKDOSE_PASSWORD','7xNEQTzPXvYd')];
                $client = new \GuzzleHttp\Client();
                $request = $client->request('POST', env('URL_BOOKDOSE_LOGIN','http://library.thaihealth.or.th/api/service_web_resources/user_controller/check_login'), ['form_params' => $form_params]);    
                $response_api = $request->getBody()->getContents();
                $data_json = json_decode($response_api, true);

                if($data_json['status'] == 'success'){
                    Setting::where('slug','=','bookdose_token')->update(['value'=>$data_json['result']['jwt']]);

                    $form_params = ['jwt'=>$data_json['result']['jwt']];
                    $client = new \GuzzleHttp\Client();
                    $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST','http://library.thaihealth.or.th/api/service_web_resources/web_resources_home_controller/popular'), ['form_params' => $form_params]);    
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api, true);
                    
                    if($data_json['status'] == 'success'){
                        //dd($data_json);
                        foreach($data_json['result'] as $value){
                            //dd($value);
                            $list[$value['aid']]['title'] = $value['name'];
                            $list[$value['aid']]['link'] = $value['link'];
                            $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                        }

                    }

                }




                $jtw = Setting::select('value')
                                ->where('slug','=','bookdose_token')
                                ->first();
                //dd($jtw->value);
                if(collect($jtw)->isNotEmpty() && $jtw->value !=''){
                    
                    $form_params = ['jwt'=>$jtw->value];
                    $client = new \GuzzleHttp\Client();
                    $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST','http://library.thaihealth.or.th/api/service_web_resources/web_resources_home_controller/popular'), ['form_params' => $form_params]);    
                    $response_api = $request->getBody()->getContents();
                    $data_json = json_decode($response_api, true);
                    
                    if($data_json['status'] == 'success'){
                        //dd($data_json);
                        foreach($data_json['result'] as $value){
                            //dd($value);
                            $list[$value['aid']]['title'] = $value['name'];
                            $list[$value['aid']]['link'] = $value['link'];
                            $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                        }

                    }

                }
               
            } catch (Exception $e) {

                //echo 'Caught exception: ',  $e->getMessage(), "\n";

                // $form_params = ['username'=>env('API_BOOKDOSE_USER'),'password'=>env('API_BOOKDOSE_PASSWORD')];
                // $client = new \GuzzleHttp\Client();
                // $request = $client->request('POST', env('URL_BOOKDOSE_LOGIN'), ['form_params' => $form_params]);    
                // $response_api = $request->getBody()->getContents();
                // $data_json = json_decode($response_api, true);

                // if($data_json['status'] == 'success'){
                //     Setting::where('slug','=','bookdose_token')->update(['value'=>$data_json['result']['jwt']]);

                //     $form_params = ['jwt'=>$data_json['result']['jwt']];
                //     $client = new \GuzzleHttp\Client();
                //     $request = $client->request('POST', env('URL_BOOKDOSE_POPPULAR_LIST'), ['form_params' => $form_params]);    
                //     $response_api = $request->getBody()->getContents();
                //     $data_json = json_decode($response_api, true);
                    
                //     if($data_json['status'] == 'success'){
                //         //dd($data_json);
                //         foreach($data_json['result'] as $value){
                //             //dd($value);
                //             $list[$value['aid']]['title'] = $value['name'];
                //             $list[$value['aid']]['link'] = $value['link'];
                //             $list[$value['aid']]['cover_desktop'] =  $value['image_thumb_url'];
                //         }

                //     }

                // }
                //dd($data_json['result']['jwt'],$list);
            }

            //dd($list);
            Cache::put('data_notable_books',collect($list),$time_cache);
            $result = Cache::get('data_notable_books');
        }

        return $result;
    }


    public static function getTarget($params)
    {   
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_target')){
            $result = Cache::get('data_target');
        }else{
            $items = ListTarget::select('id','name','target_id','parent_id','TargetGuoupID')->where('parent_id', 0)->where('status','=','publish')->orderBy('order','asc')->get();
            Cache::put('data_target',$items,$time_cache);
            $result = Cache::get('data_target');
        }
        return $result;
    }
    
    
    public static function getTarget2($params)
    {   
        //dd("getTarget2");
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_target2')){
            //dd("Case1");
            $result = Cache::get('data_target2');
        }else{
            $items = ListTarget::select('id','name','target_id','parent_id','TargetGuoupID')->where('status','=','publish')->orderBy('order','asc')->get();
            //dd("Case2",$items);
            Cache::put('data_target2',$items,$time_cache);
            $result = Cache::get('data_target2');
        }
        return $result;
    }

    public static function getIssue($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_issue')){
            $result = Cache::get('data_issue');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = ListIssue::select('id','name','issues_id','parent_id')->where('parent_id', 0)->where('status','=','publish')->orderBy('order','asc')->get();
            Cache::put('data_issue',$items,$time_cache);
            $result = Cache::get('data_issue');
        }

        return $result;

    }

    public static function getTempalte($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_tempalte')){
            $result = Cache::get('data_tempalte');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = ListTemplate::select('title','value')->where('status','=','publish')->orderBy('order','asc')->get()->pluck('title','value');
            Cache::put('data_tempalte',$items,$time_cache);
            $result = Cache::get('data_tempalte');
        }

        return $result;

    }

    public static function getAge($params)
    {   
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_age')){
            $result = Cache::get('data_age');
        }else{
            $items = Age::Data(['status'=>['publish']])->pluck('name','id')->toArray();
            $items[0] = 'ช่วงวัย';
            //rray_push($items,$array);
            ksort($items);
            //dd($items);
            Cache::put('data_age',$items,$time_cache);
            $result = Cache::get('data_age');
        }
        //dd($result);
        return $result;
    }


    public static function getNcds2($params)
    {   
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_ncds2')){
            $result = Cache::get('data_ncds2');
        }else{
            $items = array();
        
            $categorys = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-2'])->pluck('title','id');
        
            $ncds_2_disease = Setting::select('value')->where('slug','=','ncds_2_disease')->first();
            if(isset($ncds_2_disease->value)){
                $ncds_2_disease = json_decode($ncds_2_disease->value);
                $ncds_2_disease = array_combine($ncds_2_disease,$ncds_2_disease);
            }else{
                $ncds_2_disease = [];
            }
            $ncds_2_area = Setting::select('value')->where('slug','=','ncds_2_area')->first();
            if(isset($ncds_2_area->value)){
                $ncds_2_area = json_decode($ncds_2_area->value);
                $ncds_2_area = array_combine($ncds_2_area,$ncds_2_area);
            }else{
                $ncds_2_area = [];
            }

            $items['categorys'] = $categorys;
            $items['ncds_2_disease'] = $ncds_2_disease;
            $items['ncds_2_area'] = $ncds_2_area;

            //dd($items);
            Cache::put('data_ncds2',$items,$time_cache);
            $result = Cache::get('data_ncds2');
        }
        //dd($result);
        return $result;
    }


    public static function getNcds1($params)
    {   
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_ncds1')){
            $result = Cache::get('data_ncds1');
        }else{
            $items = array();
        
            $categorys = ArticleCategory::DataDropdown(['status'=>['publish'],'type'=>'ncds-1'])->pluck('title','id');
            $items['categorys'] = $categorys;
            //dd($items);
            Cache::put('data_ncds1',$items,$time_cache);
            $result = Cache::get('data_ncds1');
        }
        //dd($result);
        return $result;
    }    


    public static function getTargetWebView($params)
    {   
        $time_cache  =  ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_target_webview')){
            $result = Cache::get('data_target_webview');
        }else{
            $items = ListTarget::select('id','name','target_id','parent_id')
            //->where('parent_id', 0)
            ->where('status','=','publish')
            ->whereIN('target_id',[1,4,5,6,7,11,13,14,15,16,18,19,20,21,23,24,25,26,27,28,2])
            ->orderBy('order','asc')->get();
            Cache::put('data_target_webview',$items,$time_cache);
            $result = Cache::get('data_target_webview');
        }
        return $result;
    }

    public static function getIssueWebView($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_issue_webview')){
            $result = Cache::get('data_issue_webview');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = ListIssue::select('id','name','issues_id','parent_id')
            //->where('parent_id', 0)
            ->where('status','=','publish')
            ->whereIN('issues_id',[5,8,15,16,17,18,21,23,27,28,29,32,33,34,35,36,37,38,39,40,41,42,999])
            ->orderBy('order','asc')->get();
            Cache::put('data_issue_webview',$items,$time_cache);
            $result = Cache::get('data_issue_webview');
        }

        return $result;

    }

    public static function getDolSettingWebView($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_setting_webview')){
            $result = Cache::get('data_setting_webview');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = ListSetting::select('name AS title','setting_id AS value')
            ->where('status','=','publish')
            ->whereIN('setting_id',[1,2,4,5,6,7,8,9,10,11,12,13,14,15,0])
            ->orderBy('setting_id','asc')
            ->get()->pluck('title','value');
            Cache::put('data_setting_webview',$items,$time_cache);
            $result = Cache::get('data_setting_webview');
        }

        return $result;

    }


    public static function getSexWebView($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_sex_webview')){
            $result = Cache::get('data_sex_webview');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = Sex::select('name AS title','id AS value')
            ->where('status','=','publish')
            ->orderBy('id','asc')
            ->get()->pluck('title','value');
            Cache::put('data_sex_webview',$items,$time_cache);
            $result = Cache::get('data_sex_webview');
        }

        return $result;

    }




    public static function getDolSetting($params)
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';

        if (Cache::has('data_setting')){
            $result = Cache::get('data_setting');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = ListSetting::select('name AS title','setting_id AS value')->where('status','=','publish')->orderBy('setting_id','asc')->get()->pluck('title','value');
            Cache::put('data_setting',$items,$time_cache);
            $result = Cache::get('data_setting');
        }

        return $result;

    }


    public static function convertYoutube($string) {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
            $string
        );
    }

    public static function time_cache($time = 1){
        //dd($time);
        return Carbon::now()->addMinutes($time);
    }

    public static function getRss($params){
        
        //dd($params);
        try{
            //dd(get_loaded_extensions());
        $feed = Feeds::make($params['url']);
        $data = array(
            'title'     => $feed->get_title(),
            'permalink' => $feed->get_permalink(),
            'items'     => $feed->get_items(),
        );
    
        //dd($data,$feed,$params);
        return $data;

        }catch (\Exception $e){
            //dd($e->getMessage());
        }

    }

    public static function getRequestMediaCookie(){
        $ip  = ThrcHelpers::getIp();        
        $end_time = ThrcHelpers::time_cache(1);
        $test = Cookie::make('test','aaaaa',1,asset('files/cookie'));
        //$test = ThrcHelpers::setCookie();
        $test2 = ThrcHelpers::getCookie();
        //dd($test,$test2,$_COOKIE);
        return $ip;
    }

    public static function getEventCalendar()
    {
        $time_cache  = ThrcHelpers::time_cache(30);
        $result = '';
        $data_now =  Carbon::now();
        //dd($data_now);
        //$start_date = date('Y-m-d',strtotime($data_now . "-4 days"))." 00:00:00";
        $start_date = date('Y-m-d',strtotime($data_now))." 00:00:00";
        $end_date = date('Y-m-d',strtotime($data_now . "+5 days"))." 23:59:59";

        //$date = Carbon::parse($data_now)->format('j');
        //$month = Carbon::parse($data_now)->format('F');

        //$items = Article::FrontEventCalendarList(['page_layout'=>'event_calendar','start_date'=>$start_date,'end_date'=>$end_date]);

        //dd("Test Event",$date,$month,$start_date,$end_date,$items);
        if (Cache::has('data_event_calendar')){
            $result = Cache::get('data_event_calendar');
            //dd("Case1");
        }else{
            //dd("Case2");
            $items = Article::FrontEventCalendarList(['page_layout'=>'event_calendar','start_date'=>$start_date,'end_date'=>$end_date]);
            Cache::put('data_event_calendar',$items,$time_cache);
            $result = Cache::get('data_event_calendar');
        }

        return $result;

    }



}
