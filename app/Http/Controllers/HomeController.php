<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Vender\CallTwitterApi;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use Response;
use Symfony\Component\HttpClient\HttpClient;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    

    // 以下がadmin.blade.phpからコピペしたやつ
    public function index(){
        $sql = 'select * from shop';
        $list = DB::select($sql);

        $output = [];
        $output['list'] = $list;
        return view('home', $output);
    }
    

    public function insert(Request $request){
    
        
        //usertimelineのユーザーIDは事前に、DBeaverの方のエクセルに入力している。
        //ユーザー管理画面で行っていたのは、ユーザーIDを入力してから「api登録ボタン」を押してロードし、
        //新規内容を更新させたこと
        $sql = 'select * from shop_info where usertimeline is not null';
        $list = DB::select($sql);

        $t = new CallTwitterApi();

        foreach($list as $data){
            $apilist = $t->userTimeline($data->usertimeline);

            
            foreach($apilist as $apidata){
                $at = $apidata->text;

                $sqlc = 'select max(sp.salepoint_code) + 1 as max_code from sale_point sp';
                $max_c = DB::select($sqlc);
                $salepoint_code = $max_c[0]->max_code;

                $sql_is = "insert into sale_point(salepoint_code, shop_id, ivent_description) 
                values($salepoint_code, $data->shop_id, '$at')";
                DB::insert($sql_is);
                DB::commit();
            }
            
        } 

        $sql = 'select * from shop_info';
        $list = DB::select($sql);

        $output = [];
        $output['list'] = $list;
        return view('home', $output);    
    }

    // shopテーブルの店名投入
    public function syokai(Request $request){
        // sslチェック無効の設定
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        
        // これをまとめてループできないものか。。。
        $sql_1 = 'select url, element_path from scrape where id = 1';
        $sql_2 = 'select url, element_path from scrape where id = 2';
        $sql_3 = 'select url, element_path from scrape where id = 3';
        $sql_4 = 'select url, element_path from scrape where id = 4';
        

        $u_e = DB::select($sql_5);
         
        dd($u_e);

        $url = $u_e[0]->url;
        $crawler = $client->request('GET', $url);

        
        $n = $crawler->filter($u_e[0]->element_path)
        ->each(function($node){
            return $node->text();
        });
        $nm = preg_replace('/\d+/', '', $n);
        $name = str_replace('店舗', '', $nm);
        

        // dd($name);
        for($i = 0; $i < count($name); $i++){
            $sql = "select count(*) as cnt from shop where shop_name = '$name[$i]'";
            // selectメソッドは配列で返す
            $exist = DB::select($sql);

            //dd($exist);
            if($exist[0]->cnt == 0){
                    $sqli = 'select max(s.shop_id) + 1 as max_id from shop s';
                    $max = DB::select($sqli);
                    $max_id = $max[0]->max_id;

                    $sql_is = "insert into shop(shop_id, shop_name) values($max_id, '$name[$i]')";
                    DB::insert($sql_is);
                    DB::commit();
                
            }
        }
    }


/**********************   ライフ・西友スクレイピング関係　↓↓↓   ********************************/  

    // 都道府県 | 市区町村 | 地区 毎に分割する関数
    private function separate_address(string $address)
    {
        if (preg_match('/(.{2,3}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $address, $matches) !== 1) {
            return [
                'state' => null,
                'city' => null,
                'other' => null
            ];
        }
        $pattern = '/([\w\-\.]+)([0-9０-９]+|[一二三四五六七八九十百千万]+)*(([0-9０-９]+|[一二三四五六七八九十百千万]+)|(丁目|丁|番地|番|号|-|‐|ー|−|の|東|西|南|北){1,2})*(([0-9０-９]+|[一二三四五六七八九十百千万]}+)|(丁目|丁|番地|番|号){1,2})(.*)/';
        // dd($matches);
        // 地区の番地以降を削除する処理
        $matches[3] = preg_replace($pattern, '', $matches[3]);
        return [
            'state' => $matches[1],
            'city' => $matches[2],
            'district' => $matches[3],
        ];


    }

    // private function goutte_scrap($sql){
    //     $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

    //     foreach($sql as $data){
    //         $url = $data->url;
    //         $crawler = $client->request('GET', $url);
    //         $crawler->filter($data->element_path)
    //         ->each(function($node){
    //             return$node->text();
    //         });

    //     }
    // }


    public function lifeinfo(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        
        $obj = new \stdClass();
        $obj->store_name = "";
        $obj->store_url = "";
        $obj->hours = "";
        $obj->address = "";
        $obj->shop_tel = "";


        $sql_11 = 'select url, element_path from scrape where id = 11';
        $s_11 = DB::select($sql_11);
        foreach($s_11 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->store_name = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            $obj->store_url = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr("href");
            });

        }

        $sql_12 = 'select url, element_path from scrape where id = 12';
        $s_12 = DB::select($sql_12);
        foreach($s_12 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->shop_tel = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            
        }
        // dd($obj->shop_tel);　電話番号のみを使う
        // ^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})(.*)　　→これのmatches[1]
        
        $sql_13 = 'select url, element_path from scrape where id = 13';
        $s_13 = DB::select($sql_13);
        foreach($s_13 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->address = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            
        }
        // dd($obj->address);

        $sql_14 = 'select url, element_path from scrape where id = 14';
        $s_14 = DB::select($sql_14);
        foreach($s_14 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->hours = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
             
        }

      
        for($i = 0; $i < count($obj->store_name); $i++){
            // Uninitialized string offsetのため$store_nameを配列にしている
            $store_name = [];
            $store_name[$i] = $obj->store_name[$i];
            $s_url = $obj->store_url[$i];
            $urlplus = "http://www.lifecorp.jp/store/syuto/" . $s_url;
            $address = $obj->address[$i];
            $sepa_addr = $this->separate_address($address);
            $s_tel = $obj->shop_tel[$i];
            $hours = $obj->hours[$i];
            


            Log::debug($hours);
            // 住所の分割
            $state = $sepa_addr['state'];
            $city = $sepa_addr['city'];
            $district = $sepa_addr['district'];

            
            // 電話番号
            $tel_pattern = '/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})(.*)/u';
            preg_match($tel_pattern, $s_tel, $tel_match);

            if(!empty($hours)){
                // 営業時間
                $start_end_pattern = '/[0-9|０-９]{1,2}[:：][0-9|０-９]{2}[~～][0-9|０-９]{1,2}[:：][0-9|０-９]{2}/u';
                $in_brackets = '/（[^）]+）（[^）]+）|（[^）]+）/u';
                preg_match($start_end_pattern, $hours, $matches);
                preg_match($in_brackets, $hours, $match);
                
                
                Log::debug($matches);
                Log::debug($match);
                if(count($matches) == 0 && count($match) == 0){
                    $b_hours = '';
                }else{
                    if(!empty($matches[0])){
                        if(count($match) == 0){
                            $b_hours = $matches[0];
                        }else{
                            $b_hours = $matches[0] . $match[0];
                        }
                    }else{
                        if(!empty($match[0])){
                            $b_hours = $match[0];
                        }
                    }
                }

            }
            

            $count = "select count(*) as cnt from store where store_name = '$store_name[$i]'";
            $exist = DB::select($count);

            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;
    
                // 上で$store_name[$i]として格納しているので、insertでも$store_name[$i]とする必要があった
                $insert = "insert into store(shop_id, store_id, store_name, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town)
                values(9, $max_id, '$store_name[$i]', '$address', '$tel_match[1]', '$urlplus', '$b_hours', '$state', '$city', '$district')";

                DB::insert($insert);
                DB::commit();
            }
           
        }
    
    }


    public function seiyuData(){

    
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        
        $obj = new \stdClass();
        $obj->store_name = "";
        $obj->store_url = "";
        $obj->event_day = "";
        $obj->address = "";


        $sql_8 = 'select url, element_path from scrape where id = 8';
        $s_8 = DB::select($sql_8);
        foreach($s_8 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->store_name = $crawler->filter('li .shop_search_individual')
            ->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            $obj->store_url = $crawler->filter('li .shop_search_individual')
            ->filter($data->element_path)
            ->each(function($node){
                return $node->attr("href");
            });

            // オブジェクトをログ出力させるときはprint_rを使う
            // Log::debug(print_r($obj), true);
        }
        

        $sql_10 = 'select url, element_path from scrape where id = 10';
        $s_10 = DB::select($sql_10);
        foreach($s_10 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $obj->address = $crawler->filter('li .shop_search_individual')
            ->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            
        }
        // dd($obj);
        for($i = 0; $i < count($obj->store_name); $i++){
            // Uninitialized string offsetのため$store_nameを配列にしている
            $store_name = [];
            $store_name[$i] = $obj->store_name[$i];
            $s_url = $obj->store_url[$i];
            $urlplus = "https://www.seiyu.co.jp" . $s_url;
            $address = $obj->address[$i];
            $sepa_addr = $this->separate_address($address);


            $city = $sepa_addr['city'];
            $district = $sepa_addr['district'];


            $count = "select count(*) as cnt from store where store_name = '$store_name[$i]'";
            $exist = DB::select($count);

            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;
    
                // 上で$store_name[$i]として格納しているので、insertでも$store_name[$i]とする必要があった
                $insert = "insert into store(shop_id, store_id, store_name, store_address, store_url, prefectures, town, ss_town)
                values(1, $max_id, '$store_name[$i]', '$address', '$urlplus', '東京都', '$city', '$district')";
                // dd($insert);
                DB::insert($insert);
                DB::commit();
            }
           
        }

    }

    /**********************   localdataへの区と地区を投入　↓↓↓   ********************************/

    // localdataテーブルの23区とその地区一覧の投入
    // 郵便番号別なので千代田区や中央区など一部取得に向かない区がある
    public function localData(Request $request){
        // sslチェック無効の設定
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql = 'select target_name, url, element_path from scrape where id in (5,6,7)';
        $select = DB::select($sql);
        

        foreach($select as $data){
            $local_name = $data->target_name;
            $prefecture  = '東京都';

            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $text = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            // dd($text);
            Log::debug($text);


            for($i = 0; $i < count($text); $i++){
                $sql = "select count(*) as cnt from localdata where ss_town_name = '$text[$i]'";
                $exist = DB::select($sql);

                if($exist[0]->cnt == 0){
                        $sql_s = 'select max(ld.local_id) + 1 as max_id from localdata ld';
                        $max = DB::select($sql_s);
                        $max_id = $max[0]->max_id;

                        $sql_i = "insert into localdata(local_id, prefectures_name, town_name, ss_town_name) 
                        values($max_id, '$prefecture', '$local_name', '$text[$i]')";
                        DB::insert($sql_i);
                        DB::commit();
                    
                }
            }
        }
        

    }


    public function dataplus(){
        $url = array(
            1 => 'https://www.seiyu.co.jp/',
            2 => 'https://www.itoyokado.co.jp/',
            3 => 'https://www.daiei.co.jp/',
            4 => 'https://www.aeon.co.jp/',
            5 => 'https://www.donki.com/store/shop_detail.php?shop_id=442',
            6 => 'https://www.tokyu-store.co.jp/',
            7 => 'https://www.aeontown.co.jp/',
            8 => 'https://www.summitstore.co.jp/',
            9 => 'http://www.lifecorp.jp/',
            10 => 'https://www.maruetsu.co.jp/',
            11 => 'https://www.inageya.co.jp/',
            12 => 'https://www.comodi-iida.co.jp/',
            13 => 'http://www.ozeki-net.co.jp/',
            14 => 'https://www.tokyu-store.co.jp/',
            15 => 'https://aeonmarket.co.jp/',
            16 => 'https://www.heartful-sanwa.co.jp/',
            17 => 'https://www.keiostore.co.jp/',
            18 => 'https://santoku.co.jp/',
            19 => 'https://www.olympic-corp.co.jp/',
            20 => 'https://www.tobustore.co.jp/',
            21 => 'http://www.ozam.jp/sale/',
            22 => 'http://superalps.info/',
            23 => 'https://www.york-inc.com/',
            24 => 'https://www.daiei.co.jp/',
            25 => 'https://www.eco-s.co.jp/',
            26 => 'https://sunbelx.com/',
            27 => 'https://www.mv-kanto.co.jp/',
            28 => 'https://www.ysmart.co.jp/',
            29 => 'https://www.bunkado.com/',
            30 => 'https://tksaeki.saeki-selvahd.jp/',
            31 => 'http://www.super-yamaichi.com/',
            32 => 'https://www.yaoko-net.com/',
            33 => 'http://www.yoshiya.co.jp/',
            34 => 'http://www.s-amaike.com/',
            35 => 'http://www.supertops.com/',
            36 => 'https://www.ababakafudado.co.jp/afd/',
            37 => 'https://www.odakyu-ox.net/',
            38 => 'https://www.the-fuji.com/',
            39 => 'http://www.marufuji.net/',
            40 => 'http://www.marumanstore.co.jp/',
            41 => 'https://www.e-ccs.co.jp/',
            42 => 'http://www.super-yamazaki.co.jp/',
            43 => 'http://www.keiseistore.co.jp/',
            44 => 'https://www.meguminosato.co.jp/',
            45 => 'https://www.bicrise.com/',
            46 => 'https://www.daikoku-cc.co.jp/brand/tajima/',
            47 => 'https://www.marusho-chain.jp/',
            48 => 'http://s-oota.jp/',
            49 => 'https://www.belc.jp/',
            50 => 'http://www.sakagami-cl.co.jp/',
            51 => 'http://www.yasuno-cc.com/',
            52 => 'https://www.keikyu-store.co.jp/',
            53 => 'http://www.fuji-mart.jp/',
            54 => 'http://www.super-tsukasa.co.jp/',
            55 => 'https://www.inageya.co.jp/',
            56 => '',
            57 => 'http://www.super-kaneman.co.jp/',
            58 => 'http://mami-mart.com/',
            59 => '',
            60 => 'https://shinanoya.co.jp/',
            61 => 'https://www.foodway.co.jp/',
            62 => 'https://wakuwaku-hiroba.com/',
            63 => 'https://www.nittoh-e.co.jp/general-customer/supermarket/',
            64 => '',
            65 => 'https://www.kasumi.co.jp/',
            66 => 'http://www.m-nakamuraya.co.jp/',
            67 => 'https://www.sanwa-net.com/sanwa/super.html',
            68 => '',
            69 => 'http://www.angel-sangyou.co.jp/',
            70 => 'https://www.hirainakamuraya.co.jp/',
            71 => 'https://hiruma-marketplace.jp/',
            72 => 'http://www.isamiya1338.com/',
            73 => 'https://www.sotetsu.rosen.co.jp/',
            74 => 'http://www.super-csn.co.jp/',
            75 => 'http://www.super-shimadaya.com/',
            76 => 'https://www.super-taiyo.com/',
            77 => 'http://fresco-kanto.com/',
            78 => 'https://www.acoop-east-t.jp/',
            79 => 'https://www.daikoku-cc.co.jp/brand/bonvisage/',
            80 => 'http://www.chitoseya.info/',
            81 => 'https://escamare.co.jp/',
            82 => 'https://www.marudai.biz/',
            83 => 'http://www.food-shimizuya.co.jp/',
            84 => 'https://www.arai-group.co.jp/business/food/detail.php',
            85 => 'https://www.rgenki.com/',
            86 => 'https://www.ave.gr.jp/',
            87 => 'http://azumashokuhin.jp/business/market.html',
            88 => 'https://kimuraya-enet.jimdofree.com/',
            89 => 'http://market.kita-grp.co.jp/stores/1',
            90 => 'https://www.kurishima.jp/',
            91 => 'http://www.manamart.jp/',
            92 => '',
            93 => 'http://www.parksyouji.co.jp/top.html',
            94 => '',
            95 => 'http://www.superaoki.com/',
            96 => 'http://www.biglive.jp/',
            97 => 'http://www.hban-yu.jp/',
            98 => 'https://www.yoshiike-group.co.jp/',
            99 => 'https://www.gyomusuper.jp/',
            100 => 'https://www.biga.co.jp/',
            101 => 'https://ok-corporation.jp/',
            102 => 'http://www.hanamasa.co.jp/',
            103 => 'http://www.attack.co.jp/jp/',
            104 => 'http://www.super-mirabelle.jp/',
            105 => 'http://lopia.jp/',
            106 => 'http://www.absya.com/',
            107 => '',
            108 => 'https://www.akidai.jp/',
            109 => 'https://www.japanmeat.co.jp/',
            110 => 'http://tcc.to-ho.co.jp/',
            111 => 'http://www.yasuno-cc.com/',
            112 => 'https://www.amicashop.com/',
            113 => 'https://www.bigyosun.com/',
            114 => 'https://promart-official.com/',
            115 => 'http://www.cc-izumiya.co.jp/5.html',
            116 => 'https://www.sundi.co.jp/',
            117 => 'https://www.beisia.co.jp/',
            118 => 'https://www.aeon.com/store/%E3%82%B6%E3%83%BB%E3%83%93%E3%83%83%E3%82%B0/%E3%82%B6%E3%83%BB%E3%83%93%E3%83%83%E3%82%B0%E6%98%AD%E5%B3%B6%E5%BA%97/',
            119 => 'http://www.ichiba-fc.jp/tenpo/',
            120 => 'http://www.maruei.info/',
            121 => 'https://www.super-taiyo.com/',
            122 => 'http://www.s34.jp/',
            123 => '',
            124 => 'http://www.seijoishii.co.jp/',
            125 => 'https://www.e-kinokuniya.com/',
            126 => 'https://www.bio-c-bon.jp/',
            127 => 'https://www.ace-group.co.jp/',
            128 => 'https://www.im-food.co.jp/',
            129 => 'http://meidi-ya-store.com/',
            130 => 'https://www.garden.co.jp/',
            131 => 'https://www.miuraya.com/',
            132 => 'https://www.fukushimaya.net/',
            133 => 'https://www.tokyu-store.co.jp/precce/',
            134 => '',
            135 => 'https://www.keikyu-store.co.jp/motomachi_union/',
            136 => 'http://national-azabu.com/',
            137 => 'http://www.ville-marche.jp/',
            138 => 'https://www.nissin-world-delicatessen.jp/',
            139 => 'https://www.pantry-lucky.jp/',
            140 => 'https://shop-mirai.coopnet.or.jp/',
            141 => 'https://seikatsuclub.coop/'
            // complete!

        );
        

        $twitter_id = array(
            1 => '109196746',
            2 => '381386284',
            3 => '857522570723119104',
            4 => '4030203792',
            5 => '135017078',
            6 => '',
            7 => '4030203792',
            8 => '139547123',
            9 => '',
            10 => '',
            11 => '',
            12 => '',
            13 => '',
            14 => '425076162',
            15 => '',
            16 => '',
            17 => '',
            18 => '',
            19 => '1113319925240651776',
            20 => '',
            21 => '1392403933193113603',
            22 => '986177546201870337',
            23 => '',
            24 => '',
            25 => '',
            26 => '',
            27 => '',
            28 => '218451633',
            29 => '',
            30 => '',
            31 => '',
            32 => '',
            33 => '',
            34 => '',
            35 => '',
            36 => '',
            37 => '',
            38 => '',
            39 => '',
            40 => '',
            41 => '',
            42 => '',
            43 => '',
            44 => '',
            45 => '',
            46 => '',
            47 => '',
            48 => '',
            49 => '3038985943',
            50 => '',
            51 => '',
            52 => '',
            53 => '',
            54 => '',
            55 => '',
            56 => '',
            57 => '',
            58 => '',
            59 => '',
            60 => '737453434450763777',
            61 => '224915911',
            62 => '',
            63 => '',
            64 => '',
            65 => '',
            66 => '',
            67 => '',
            68 => '',
            69 => '',
            70 => '850567304',
            71 => '',
            72 => '',
            73 => '',
            74 => '',
            75 => '',
            76 => '',
            77 => '',
            78 => '',
            79 => '',
            80 => '',
            81 => '',
            82 => '',
            83 => '',
            84 => '',
            85 => '',
            86 => '',
            87 => '',
            88 => '',
            89 => '527951168',
            90 => '',
            91 => '',
            92 => '',
            93 => '',
            94 => '295546636',
            95 => '310761379',
            96 => '',
            97 => '1280390668418871298',
            98 => '2904275995',
            99 => '',
            100 => '',
            101 => '',
            102 => '',
            103 => '',
            104 => '',
            105 => '',
            106 => '',
            107 => '',
            108 => '',
            109 => '',
            110 => '',
            111 => '',
            112 => '3434637924',
            113 => '',
            114 => '',
            115 => '',
            116 => '',
            117 => '',
            118 => '',
            119 => '',
            120 => '',
            121 => '209907514',
            122 => '',
            123 => '',
            124 => '',
            125 => '',
            126 => '780956551435530240',
            127 => '736301107',
            128 => '',
            129 => '',
            130 => '',
            131 => '',
            132 => '',
            133 => '',
            134 => '',
            135 => '',
            136 => '252472172',
            137 => '',
            138 => '880650639080128513',
            139 => '',
            140 => '',
            141 => '',
            // 赤札堂・ココスナカムラ・食品館あおば・食品の店おおた・フジマート・エンゼルファミリーは各店舗ごとのアカウント
        );

        // dd($sql);
        foreach($twitter_id as $key => $val){
            // sql文を囲むにはクォーテーションではなくて、ダブルクォーテーション
            // カラムの値を追加したいとき、主キーが既にある場合はupdate文で実施する
            $up = "update shop set twitter_user_id = '$val' where shop_id = $key";
            DB::update($up);
            DB::commit();
            
        }
    }


}
