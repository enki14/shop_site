<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Vender\CallTwitterApi;
use Goutte\Client;
use Response;
use Symfony\Component\HttpClient\HttpClient;




class AdminController extends Controller
{
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

            //dd($apires);
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

    public function dataplus(){
        $sql = array(
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
        );
        
        // dd($sql);
        foreach($sql as $key => $val){
            // sql文を囲むにはクォーテーションではなくて、ダブルクォーテーション
            // カラムの値を追加したいとき、主キーが既にある場合はupdate文で実施する
            $up = "update shop set shop_url = '$val' where shop_id = $key";
            DB::update($up);
            DB::commit();
            
        }
    }
    
    

}
