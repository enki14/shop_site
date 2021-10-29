<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Http\Lib\Common;
use Response;
use Symfony\Component\HttpClient\HttpClient;


class StoreSetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // store_urlを取得することができず (~_~)
    public function daiei_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));


        // サイトのページによってscrapeのidを変える
        // ページ1は18・19、ページ2は21・22、ページ3は24・25、ページ4は27・28
        $sql_27 = 'select url, element_path from scrape where id = 27';
        $s_27 = DB::select($sql_27);
        foreach($s_27 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });
            
        }


        $sql_28 = 'select url, element_path from scrape where id = 28';
        $s_28 = DB::select($sql_28);
        foreach($s_28 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $adr_tel = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->text();
            });

        }

        // 配列をフィルタリングして奇数を取る。int型を明示しないと怒られる
        $tel = array_filter($adr_tel, function($num) {
            return (int)$num / 2 !== 0;
        });
        // 偶数を取る
        $address = array_filter($adr_tel, function($num) {
            return (int)$num / 2 === 0;
        });
        
        
        for($i = 0; $i < count($store); $i++){
            $store_name = str_replace('ダイエー', '', $store[$i]);

            // 連想配列にはarray_valuesで抜き取らないと、値が２重に取られてしまう
            $store_tel = array_values($tel);
            $store_address = array_values($address);

            // 町・地区を取得
            $sepa_addr = Common::separate_address_one($store_address[$i]);
            $city = $sepa_addr['city'];
            $district = $sepa_addr['district'];


            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $insert = "insert into store(shop_id, store_id, store_name, store_address, store_tel, prefectures, town, ss_town) 
                values(3, $max_id, '$store_name', '$store_address[$i]', '$store_tel[$i]', '東京都', '$city', '$district')";
                DB::insert($insert);
                DB::commit();
                
            }
           
        }

    }



    


}
