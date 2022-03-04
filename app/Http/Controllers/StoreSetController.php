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


    public function summit_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_30 = 'select url, element_path from scrape where id = 30';
        $s_30 = DB::select($sql_30);
        foreach($s_30 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store_1 = $crawler->filter($data->element_path)->filter('p.p-store-info__item--title')
            ->each(function($node){
                return $node->text();
            });
            
            $adr_time_1 = $crawler->filter($data->element_path)->filter('dl > dd')
            ->each(function($node){
                return $node->text();
            });

            $link_1 = $crawler->filter($data->element_path)->filter('p.p-store-shop__item--btn > a')
            ->each(function($node){
                return $node->attr('href');
            });

        }


        $sql_31 = 'select url, element_path from scrape where id = 31';
        $s_31 = DB::select($sql_31);
        foreach($s_31 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store_2 = $crawler->filter($data->element_path)->filter('p.p-store-info__item--title')
            ->each(function($node){
                return $node->text();
            });

            $adr_time_2 = $crawler->filter($data->element_path)->filter('dl > dd')
            ->each(function($node){
                return $node->text();
            });

            $link_2 = $crawler->filter($data->element_path)->filter('p.p-store-shop__item--btn > a')
            ->each(function($node){
                return $node->attr('href');
            });

        }
        

        $sql_32 = 'select url, element_path from scrape where id = 32';
        $s_32 = DB::select($sql_32);
        foreach($s_32 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store_3 = $crawler->filter($data->element_path)->filter('p.p-store-info__item--title')
            ->each(function($node){
                return $node->text();
            });

            $adr_time_3 = $crawler->filter($data->element_path)->filter('dl > dd')
            ->each(function($node){
                return $node->text();
            });

            $link_3 = $crawler->filter($data->element_path)->filter('p.p-store-shop__item--btn > a')
            ->each(function($node){
                return $node->attr('href');
            });

        }

        // それぞれの配列同士を結合（配列の中身は引数の並び順になる）
        $store = array_merge($store_1, $store_2, $store_3);
        $adr_time = array_merge($adr_time_1, $adr_time_2, $adr_time_3);
        $link = array_merge($link_1, $link_2, $link_3);


        // $retObj->adr_time の偶数を取得
        $address = array_map('current', array_chunk($adr_time, 2));
        // $retObj->adr_time の奇数を取得
        $time = array_map('current', array_chunk(array_slice($adr_time, 1), 2));

        
        for($i = 0; $i < count($store); $i++){
            $store_name = str_replace('サミットストア', '', $store[$i]);
            
            $sepa_addr = Common::separate_address($address[$i]);
            $state = $sepa_addr['state'];
            $city = $sepa_addr['city'];
            $district = $sepa_addr['district'];

            $store_url = 'https://www.summitstore.co.jp/store/' . $link[$i];

            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_url, business_hours, prefectures, town, ss_town) 
                values(8, $max_id, '$store_name', '$address[$i]', '$store_url', '$time[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }

        }
        
    }


    public function maruetu_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_33 = 'select url, element_path from scrape where id = 33';
        $s_33 = DB::select($sql_33);
        foreach($s_33 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('a')
            ->each(function($node){
                return $node->attr('href');
            });
            
            $store = $crawler->filter($data->element_path)->filter('div > dl > dt')
            ->each(function($node){
                return $node->text();
            });

            $zip_adr = $crawler->filter($data->element_path)->filter('div > ul > li:nth-child(1) > dl > dd')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('div > ul > li:nth-child(2) > dl > dd')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter($data->element_path)->filter('div > ul > li:nth-child(3) > dl > dd')
            ->each(function($node){
                return $node->text();
            });
        }
        
        $store_url = array_map('current', array_chunk($link, 2));


        for($i = 0; $i < count($store); $i++){
            $store_name = str_replace('マルエツ', '', $store[$i]);
            preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
            preg_match('/(.{2,3}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[$i], $address);
            $separate = Common::separate_address($address[0]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];

            $count = "select count(*) as cnt from store where store_name = '$store_name'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, zip, store_tel, store_address, store_url, business_hours, prefectures, town, ss_town) 
                values(10, $max_id, '$store_name', '$zip[0]', '$tel[$i]','$address[0]', '$store_url[$i]', '$time[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }
    
    }


    public function inageya_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_34 = 'select url, element_path from scrape where id = 34';
        $s_34 = DB::select($sql_34);
        foreach($s_34 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('td > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter($data->element_path)->filter('td > a')
            ->each(function($node){
                return $node->text();
            });
            
            $zip_adr = $crawler->filter($data->element_path)->filter('td:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter($data->element_path)->filter('td:nth-child(3)')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('td:nth-child(4)')
            ->each(function($node){
                return $node->text();
            });
            // Log::debug($zip_adr);
        }

        for($i = 0; $i < count($store); $i++){
            $store_link = str_replace('.', '', $link[$i]);
            $store_url = 'https://stores.inageya.co.jp' . $store_link;
            $store_name = str_replace('いなげや', '', $store[$i]);
            preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
            preg_match('/(.{2}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[$i], $address);
            $separate = Common::separate_address($address[0]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];

            $count = "select count(*) as cnt from store where store_name = '$store_name'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                if(strpos($store_name, 'bloomingbloomy') === 0){
                    $sql = "insert into store(shop_id, store_id, store_name, zip, store_tel, store_address, store_url, business_hours, prefectures, town, ss_town) 
                    values(55, $max_id, '$store_name', '$zip[0]', '$tel[$i]','$address[0]', '$store_url', '$time[$i]', '$state', '$city', '$district')";
                    // dd($sql);
                    DB::insert($sql);
                    DB::commit();
                }else{
                    $sql = "insert into store(shop_id, store_id, store_name, zip, store_tel, store_address, store_url, business_hours, prefectures, town, ss_town) 
                    values(11, $max_id, '$store_name', '$zip[0]', '$tel[$i]','$address[0]', '$store_url', '$time[$i]', '$state', '$city', '$district')";
                    // dd($sql);
                    DB::insert($sql);
                    DB::commit();
                }

                
            }
        }
    }


    public function comodiIida_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_35 = 'select url, element_path from scrape where id = 35';
        $s_35 = DB::select($sql_35);
        foreach($s_35 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('th > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter($data->element_path)->filter('th > a')
            ->each(function($node){
                return $node->text();
            });
            
            $address = $crawler->filter($data->element_path)->filter('td:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('td:nth-child(3)')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter($data->element_path)->filter('td:nth-child(4)')
            ->each(function($node){
                return $node->text();
            });
        }

        // index28は住所とは関係ない
        unset($address[28]);
        // 順番がずれないようにarray_valuesで詰める
        $st_addr = array_values($address);

        for($i = 0; $i < count($store); $i++){
            $store_link = str_replace('.', '', $link[$i]);
            $store_url = 'https://www.comodi-iida.co.jp/store/tokyo' . $store_link;

            $separate = Common::separate_address_one($st_addr[$i]);
            $city = $separate['city'];
            $district = $separate['district'];
            preg_match('/0\d{1,4}-\d{1,4}-\d{3,4}/u', $tel[$i], $store_tel);

            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(12, $max_id, '$store[$i]', '$st_addr[$i]', '$store_tel[0]', '$store_url', '$time[$i]', '東京', '$city', '$district')";
                Log::debug($sql);
                DB::insert($sql);
                DB::commit();                
            }
        
        }

    }


    public function ozeki_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_36 = 'select url, element_path from scrape where id = 36';
        $s_36 = DB::select($sql_36);
        foreach($s_36 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('dl > dt > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter($data->element_path)->filter('dl > dt > a > span')
            ->each(function($node){
                return $node->text();
            });
            
            $zip_adr = $crawler->filter($data->element_path)->filter('dl > dd > div > p')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('ul > li.shopDetailBtn01 > a > span')
            ->each(function($node){
                return $node->text();
            });
        // dd($tel);
        }

        for($i = 0; $i < count($store); $i++){
            $url = 'http://www.ozeki-net.co.jp' . $link[$i];
            preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
            preg_match('/(.{2}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[$i], $address);
            $search = array('※混雑状況は「google mapsで見る」でご覧ください (外部サイト)', '※混雑状況は「Google Mapsで見る」でご覧ください (外部サイト)');
            $replace = array('', '');
            $st_adr = str_replace($search, $replace, $address[0]);
            $separate = Common::separate_address($st_adr);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];

            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, zip, store_address, store_tel, store_url, prefectures, town, ss_town) 
                values(13, $max_id, '$store[$i]', '$zip[0]', '$st_adr', '$tel[$i]', '$url', '$state', '$city', '$district')";
                Log::debug($sql);
                DB::insert($sql);
                DB::commit();
            }
        }

    }


    public function tokyu_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_37 = 'select url, element_path from scrape where id = 37';
        $s_37 = DB::select($sql_37);
        foreach($s_37 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('tr:nth-child(1) > th > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter($data->element_path)->filter('tr:nth-child(1) > th > a')
            ->each(function($node){
                return $node->text();
            });
            
            $address = $crawler->filter($data->element_path)->filter(' tr:nth-child(2) > td > div.left > p:nth-child(1)  > span:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter(' tr:nth-child(2) > td > div.left > p:nth-child(2) > span:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter($data->element_path)->filter('tr:nth-child(2) > td > div.right > p > span:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });
            Log::debug($time);
        }

        for($i = 0; $i < count($store); $i++){
            $url = 'https://www.tokyu-store.co.jp' . $link[$i];
            $search = array('：', ' 地図はこちら', 'お客様には多大なるご不便・ご迷惑をおかけいたしますが、何卒ご了承頂きますようお願い致します。');
            $replace = array('', '', '');
            $st_adr = str_replace($search, $replace, $address[$i]);
            $st_tel = str_replace($search, $replace, $tel[$i]);
            $st_time = str_replace($search, $replace, $time[$i]);
            $separate = Common::separate_address($st_adr);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];

            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(14, $max_id, '$store[$i]', '$st_adr', '$st_tel', '$url', '$st_time', '$state', '$city', '$district')";
                // Log::debug($sql);
                DB::insert($sql);
                DB::commit();
            }
        }

    }



    public function peacock_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_39 = 'select url, element_path from scrape where id = 39';
        $s_39 = DB::select($sql_39);
        foreach($s_39 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('a.storeName')
            ->each(function($node){
                return $node->text();
            });

            $address = $crawler->filter($data->element_path)->filter('div.adds > span.address')
            ->each(function($node){
                return $node->text();
            });

            $zip = $crawler->filter($data->element_path)->filter('div.adds > span.zip')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('div.tel')
            ->each(function($node){
                return $node->text();
            });

        }
        
        for($i = 0; $i < count($store); $i++){
            $st_name = str_replace('PEACOCK STORE', '', $store[$i]);
            $separate = Common::separate_address($address[$i]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store
                (shop_id, store_id, store_name, zip, store_address, store_tel, prefectures, town, ss_town) 
                values(15, $max_id, '$st_name', '$zip[$i]', '$address[$i]', '$tel[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }

        foreach($s_39 as $data){

            $url_2 = $data->url . 'p_2/';
            $crawler_2 = $client->request('GET', $url_2);
            $store2 = $crawler_2->filter($data->element_path)->filter('a.storeName')
            ->each(function($node){
                return $node->text();
            });
            
            $address2 = $crawler_2->filter($data->element_path)->filter('div.adds > span.address')
            ->each(function($node){
                return $node->text();
            });

            $zip2 = $crawler_2->filter($data->element_path)->filter('div.adds > span.zip')
            ->each(function($node){
                return $node->text();
            });

            $tel2 = $crawler_2->filter($data->element_path)->filter('div.tel')
            ->each(function($node){
                return $node->text();
            });

            Log::debug($tel2);
        }


        for($i = 0; $i < count($store2); $i++){
            $st_name2 = str_replace('PEACOCK STORE', '', $store2[$i]);
            $separate2 = Common::separate_address($address2[$i]);
            $state2 = $separate2['state'];
            $city2 = $separate2['city'];
            $district2 = $separate2['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store2[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store
                (shop_id, store_id, store_name, zip, store_address, store_tel, prefectures, town, ss_town) 
                values(15, $max_id, '$st_name2', '$zip2[$i]', '$address2[$i]', '$tel2[$i]', '$state2', '$city2', '$district2')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }
        
    }


    public function sanwa_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_40 = 'select url, element_path from scrape where id = 40';
        $s_40 = DB::select($sql_40);
        foreach($s_40 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('section.fd > div.acd-content > table')->first()
            ->filter('td:nth-child(1)')
            ->each(function($node){
                return $node->text();
            });

            $link = $crawler->filter($data->element_path)->filter('section.fd > div.acd-content > table')->first()
            ->filter('td:nth-child(1) > a')
            ->each(function($node){
                return $node->attr('href');
            });
            Log::debug($link);
            $time = $crawler->filter($data->element_path)->filter('section.fd > div.acd-content > table')->first()
            ->filter('td:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });
            
            $zip_adr = $crawler->filter($data->element_path)
            ->filter('section.fd > div.acd-content > table')->first()->filter('td:nth-child(3)')
            ->each(function($node){
                return $node->text();
            });
            
            $tel = $crawler->filter($data->element_path)->filter('section.fd > div.acd-content > table')->first()
            ->filter('td:nth-child(4)')
            ->each(function($node){
                return $node->text();
            });
            

        }


        for($i = 0; $i < count($store); $i++){
            preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
            preg_match('/(.{2,3}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[$i], $address);
            // 余分なスペースを削除
            $address = trim($address[0]);
            $separate = Common::separate_address($address);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, zip, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(151, $max_id, '$store[$i]', '$zip[0]', '$address', '$tel[$i]', '$link[$i]', '$time[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }
    
    }


    public function keio_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_41 = 'select url, element_path from scrape where id = 41';
        $s_41 = DB::select($sql_41);
        foreach($s_41 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('td.storeName > a')
            ->each(function($node){
                return $node->text();
            });

            $link = $crawler->filter($data->element_path)->filter('td.storeName > a')
            ->each(function($node){
                return $node->attr('href');
            });
            
            $time = $crawler->filter($data->element_path)->filter('td.time')
            ->each(function($node){
                return $node->text();
            });
            
            $address = $crawler->filter($data->element_path)->filter('td.address')
            ->each(function($node){
                return $node->text();
            });
            
            $tel = $crawler->filter($data->element_path)->filter('td.telNum')
            ->each(function($node){
                return $node->text();
            });
            // Log::debug($tel);

        }

        for($i = 0; $i < count($store); $i++){
            $st_url = 'https://www.keiostore.co.jp/business/' . $link[$i];
            $separate = Common::separate_address($address[$i]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(17, $max_id, '$store[$i]', '$address[$i]', '$tel[$i]', '$st_url', '$time[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }
    
    }


    public function santoku_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_43 = 'select url, element_path from scrape where id = 43';
        $s_43 = DB::select($sql_43);
        foreach($s_43 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('td.shoplist_title')
            ->each(function($node){
                return $node->text();
            });

            $link = $crawler->filter($data->element_path)->filter('td.shoplist_title > a')
            ->each(function($node){
                return $node->attr('href');
            });
            
            $time = $crawler->filter($data->element_path)->filter('td.shoplist_hours')
            ->each(function($node){
                return $node->text();
            });
            
            $tel = $crawler->filter($data->element_path)->filter('td > p.shoplist_tel')
            ->each(function($node){
                return $node->text();
            });
            // Log::debug($tel);

        }

        for($i = 0; $i < count($link); $i++){
            $crawler_2 = $client->request('GET', $link[$i]);
            $zip_adr = $crawler_2->filter('#shopinfo > ul > li:nth-child(1) > dl > dd > p')
            ->each(function($node){
                return $node->text();
            });

            // Log::debug($zip_adr);

            if(preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[0])){
                preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[0], $zip);
            }else{
                $zip[0] = '';
            }
            // Log::debug($zip);
            
            preg_match('/(.{2,3}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[0], $address);

            $address = str_replace('　', '', $address[0]);
            // Log::debug($address);

            $separate = Common::separate_address($address);
            // Log::debug($separate);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];

            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, zip, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(18, $max_id, '$store[$i]', '$zip[0]', '$address', '$tel[$i]', '$link[$i]', '$time[$i]', '$state', '$city', '$district')";
                Log::debug($sql);
                DB::insert($sql);
                DB::commit();
            }

        }
        
    }


    public function tobu_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_44 = 'select url, element_path from scrape where id = 44';
        $s_44 = DB::select($sql_44);
        foreach($s_44 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('dl > dt')
            ->each(function($node){
                return $node->text();
            });

            $link = $crawler->filter($data->element_path)->filter('dl > a')
            ->each(function($node){
                return $node->attr('href');
            });
            
            $address = $crawler->filter($data->element_path)->filter('dl > dd.address')
            ->each(function($node){
                return $node->text();
            });
            
            $time = $crawler->filter($data->element_path)->filter('dl > dd.open')
            ->each(function($node){
                return $node->text();
            });
            // Log::debug($time);

        }

        for($i = 0; $i < count($store); $i++){
            $separate = Common::separate_address($address[$i]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_url, business_hours, prefectures, town, ss_town) 
                values(20, $max_id, '$store[$i]', '$address[$i]', '$link[$i]', '$time[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }

    }



    public function ozam_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_45 = 'select url, element_path from scrape where id = 45';
        $s_45 = DB::select($sql_45);
        foreach($s_45 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr('href');
            });
        }

        foreach($link as $s_link){
            $crawler_2 = $client->request('GET', $s_link);

            $store = $crawler_2->filter('#header > div > div > div.sitename > h1')
            ->each(function($node){
                return $node->text();
            });
            
            $zip = $crawler_2->filter('tr:nth-child(1) > td > p:nth-child(1)')
            ->each(function($node){
                return $node->text();
            });

            $address = $crawler_2->filter('tr:nth-child(1) > td > p:nth-child(2)')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler_2->filter('tr:nth-child(2) > td')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler_2->filter('tr:nth-child(4) > td')
            ->each(function($node){
                return $node->text();
            });
            Log::debug($tel);

            for($i = 0; $i < count($store); $i++){

                $separate = Common::separate_address($address[$i]);
                $store_zip = str_replace('〒', '', $zip[$i]);
    
                $state = $separate['state'];
                $city = $separate['city'];
                $district = $separate['district'];
                
                $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
                $exist = DB::select($count);
    
                // Log::debug($district);
                if($exist[0]->cnt == 0){
                    $id = "select max(store_id) + 1 as max_id from store";
                    $max = DB::select($id);
                    $max_id = $max[0]->max_id;
    
                    $sql = "insert into store(shop_id, store_id, store_name, zip, store_address, store_tel, store_url, business_hours, prefectures, town, ss_town) 
                    values(21, $max_id, '$store[$i]', '$store_zip', '$address[$i]', '$tel[$i]', '$s_link', '$time[$i]', '$state', '$city', '$district')";
                    // dd($sql);
                    DB::insert($sql);
                    DB::commit();
                }
            }

        }
    
    }


    public function itoyokado(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_46 = 'select url, element_path from scrape where id = 46';
        $s_46 = DB::select($sql_46);
        foreach($s_46 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);

            $link = $crawler->filter('div.-item.cf > div.-c1 > h4 > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter('div.-item.cf > div.-c1 > h4 > a')
            ->each(function($node){
                return $node->text();
            });
            
            $zip_adr = $crawler->filter('div.adds > span.zip')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter('div.-item.cf > div.-c1 > p:nth-child(3)')
            ->each(function($node){
                return $node->text();
            });
            Log::debug($tel);
        }

        for($i = 0; $i < count($store); $i++){
            preg_match('/[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}/', $tel[$i], $store_tel);
            preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
            preg_match('/(.{2,3}?[都道府県])(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $zip_adr[$i], $address);
            $address = str_replace('　', '', $address[0]);
            $separate = Common::separate_address($address);
            $store_zip = str_replace('〒', '', $zip[0]);

            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, zip, store_address, store_tel, store_url, prefectures, town, ss_town) 
                values(2, $max_id, '$store[$i]', '$store_zip', '$address', '$store_tel[0]', '$link[$i]', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }

    }


    public function aeon_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_47 = 'select url, element_path from scrape where id = 47';
        $s_47 = DB::select($sql_47);
        foreach($s_47 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $store = $crawler->filter($data->element_path)->filter('dt')
            ->each(function($node){
                return $node->text();
            });

            $address = $crawler->filter($data->element_path)->filter('dd:nth-child(2) > span.shop-address')
            ->each(function($node){
                return $node->text();
            });

            Log::debug($address);

        }

        for($i = 0; $i < count($store); $i++){
            $separate = Common::separate_address($address[$i]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            $link = 'https://www.aeon.com/store/イオン/' . $store[$i] . '/';
            $st_name = str_replace('イオン', '', $store[$i]);
            Log::debug($st_name);
            $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
            $exist = DB::select($count);

            // Log::debug($district);
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;

                $sql = "insert into store(shop_id, store_id, store_name, store_address, store_url, prefectures, town, ss_town) 
                values(4, $max_id, '$st_name', '$address[$i]', '$link', '$state', '$city', '$district')";
                // dd($sql);
                DB::insert($sql);
                DB::commit();
            }
        }
    }


    public function superalps(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_48 = 'select url, element_path from scrape where id = 48';
        $s_48 = DB::select($sql_48);
        foreach($s_48 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);

            $link = $crawler->filter('.store-top-contents > section > div > section > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter('.store-top-contents > section > div > section > a > 
            .c-store-item__head > div > .c-store-box__info > .c-store-table > h4')
            ->each(function($node){
                return $node->text();
            });
            
            $address = $crawler->filter('.c-store-table > div.c-store-table__detail > 
            dl.c-store-table__adress > dd')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter('.c-store-table > div.c-store-table__detail > 
            dl.c-store-table__tel > dd')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter('.c-store-table > div.c-store-table__detail > 
            dl.c-store-table__hours > dd')
            ->each(function($node){
                return $node->text();
            });


            for($i = 0; $i < count($store); $i++){
                $separate = Common::separate_address($address[$i]);
                $state = $separate['state'];
                $city = $separate['city'];
                $district = $separate['district'];
                $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
                $exist = DB::select($count);
    
                // Log::debug($district);
                if($exist[0]->cnt == 0){
                    $id = "select max(store_id) + 1 as max_id from store";
                    $max = DB::select($id);
                    $max_id = $max[0]->max_id;
    
                    $sql = "insert into store(shop_id, store_id, store_name, store_address, store_tel, 
                    store_url, business_hours, prefectures, town, ss_town) 
                    values(22, $max_id, '$store[$i]', '$address[$i]', '$tel[$i]', '$link[$i]', '$time[$i]', '$state', '$city', '$district')";
                    // dd($sql);
                    DB::insert($sql);
                    DB::commit();
                }
            }


        }
        
    }


    public function york(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_49 = 'select url, element_path from scrape where id = 49';
        $s_49 = DB::select($sql_49);
        foreach($s_49 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);

            $link = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr('href');
            });

            $address = $crawler->filter('tr > td.td-store-info.store-td > p > span')
            ->each(function($node){
                return $node->text();
            });
           
        }

        for($i = 0; $i < count($address); $i++){

            $st_link = 'https://www.york-inc.com/' . $link[$i];
    
            
            $crawler_2 = $client->request('GET', $st_link);
            // [0]t [1]s
            $store_time = $crawler_2->filter('tr:nth-child(1) > td')
            ->each(function($node){
                return $node->text();
            });
            
            $store = str_replace('ヨーク', '', $store_time[1]);
            $time = $store_time[0];
            Log::debug($time);
           
            // [0]
            $zip = $crawler_2->filter('#address > span')
            ->each(function($node){
                return $node->text();
            });
            $st_zip = str_replace('〒', '', $zip);
            
            // [0]
            $tel = $crawler_2->filter('tr:nth-child(3) > td')
            ->each(function($node){
                return $node->text();
            });
            $st_tel = str_replace(' 【電話受付時間：開店～20時】', '', $tel);
        
            
            $separate = Common::separate_address($address[$i]);
            $state = $separate['state'];
            $city = $separate['city'];
            $district = $separate['district'];
            $count = "select count(*) as cnt from store where store_address = '$address[$i]'";
            $exist = DB::select($count);
            
            if($exist[0]->cnt == 0){
                $id = "select max(store_id) + 1 as max_id from store";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;
                
                $sql = "insert into store(shop_id, store_id, store_name, zip, store_address,
                store_tel, store_url, business_hours, prefectures, town, ss_town) 
                values(23, $max_id, '$store', '$st_zip[0]', '$address[$i]', '$st_tel[0]', 
                '$st_link', '$time', '$state', '$city', '$district')";
                DB::insert($sql);
                DB::commit();
            }
        }
    }


    public function ok_store(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_50 = 'select url, element_path from scrape where id = 50';
        $s_50 = DB::select($sql_50);
        foreach($s_50 as $data){
            $p2 = $data->url . 'page/2/';
            $p3 = $data->url . 'page/3/';
            $p4 = $data->url . 'page/4/';
            $p5 = $data->url . 'page/5/';
            $p6 = $data->url . 'page/6/';

            $u_array = [
                $data->url, $p2, $p3, $p4, $p5, $p6
            ];

            // dd($u_array);
            foreach($u_array as $data){
                $crawler = $client->request('GET', $data);

                $link = $crawler->filter('div.module-section > div > p > a.btn-red')
                ->each(function($node){
                    return $node->attr('href');
                });
                

                $store = $crawler->filter('div.module-section > div > h2')
                ->each(function($node){
                    return $node->text();
                });
                // Log::debug($store);

                $zip_adr = $crawler
                ->filter('.shop-outline > li:nth-child(1)')
                ->each(function($node){
                    return $node->text();
                });
                
                $tel = $crawler->filter('.shop-outline > li:nth-child(3)')
                ->each(function($node){
                    return $node->text();
                });

                $time = $crawler->filter('.shop-outline > li:nth-child(2)')
                ->each(function($node){
                    return $node->text();
                });

                for($i = 0; $i < count($store); $i++){
                    $st = str_replace('オーケー　', '', $store[$i]);
                    $st_tel = str_replace('電話番号：', '', $tel[$i]);
                    $st_time = str_replace('営業時間：', '', $time[$i]);
                    preg_match('/\d{3}(-(\d{4}|\d{2}))?/u', $zip_adr[$i], $zip);
                    $st_adr = preg_replace('/住所：〒\d{3}(-(\d{4}|\d{2}))? /u', '', $zip_adr[$i]);
                    $st_adr = preg_replace('/(.{2,3}?[都道府県])/u', '', $st_adr);
                    // Log::debug($st_adr);
                    $separate = Common::separate_address_one($st_adr);
                    $city = $separate['city'];
                    $district = $separate['district'];
                    // Log::debug($district);
                    // Log::debug($link[$i]);
                    $count = "select count(*) as cnt from store where store_name = '$st'";
                    $exist = DB::select($count);
                    
                    if($exist[0]->cnt == 0){
                        $id = "select max(store_id) + 1 as max_id from store";
                        $max = DB::select($id);
                        $max_id = $max[0]->max_id;
                        
                        $sql = "insert into store(shop_id, store_id, store_name, zip, store_address,
                        store_tel, store_url, business_hours, prefectures, town, ss_town) 
                        values(101, $max_id, '$st', '$zip[0]', '$st_adr', '$st_tel', 
                        '$link[$i]', '$st_time', '東京都', '$city', '$district')";
                        // dd($sql);
                        DB::insert($sql);
                        DB::commit();
                    }

                }

            }
        }

    }



    public function corp_mirai(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_55 = 'select url, element_path from scrape where id = 55';
        $s_55 = DB::select($sql_55);
        foreach($s_55 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);

            $link = $crawler->filter($data->element_path)->filter('p > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $store = $crawler->filter($data->element_path)->filter('p > a')
            ->each(function($node){
                return $node->text();
            });
            
            $address = $crawler->filter($data->element_path)->filter('div > dl.box01 > dd')
            ->each(function($node){
                return $node->text();
            });

            $tel = $crawler->filter($data->element_path)->filter('div > dl.box02 > dd > a')
            ->each(function($node){
                return $node->text();
            });

            $time = $crawler->filter($data->element_path)->filter('div > dl.box03 > dd')
            ->each(function($node){
                return $node->text();
            });
            
        }


        for($i = 0; $i < count($store); $i++){
            $separate = Common::separate_address_one($address[$i]);
            $city = $separate['city'];
            $district = $separate['district'];
            $st_link = 'https://shop-mirai.coopnet.or.jp/shop/tokyo' . $link[$i];

            if(preg_match('/^ミニコープ/', $store[$i])){
                $st_name = str_replace('ミニコープ', '', $store[$i]);

                $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
                $exist = DB::select($count);

                // Log::debug($district);
                if($exist[0]->cnt == 0){
                    $id = "select max(store_id) + 1 as max_id from store";
                    $max = DB::select($id);
                    $max_id = $max[0]->max_id;

                    $sql = "insert into store(shop_id, store_id, store_name, store_address, store_url, store_tel, town, ss_town) 
                    values(155, $max_id, '$st_name', '$address[$i]', '$st_link', '$tel[$i]', '$city', '$district')";
                    // dd($sql);
                    DB::insert($sql);
                }
            
            }elseif(preg_match('/^コープ/', $store[$i])){
                $st_name2 = str_replace('コープ', '', $store[$i]);

                $count = "select count(*) as cnt from store where store_name = '$store[$i]'";
                $exist = DB::select($count);

                // Log::debug($district);
                if($exist[0]->cnt == 0){
                    $id = "select max(store_id) + 1 as max_id from store";
                    $max = DB::select($id);
                    $max_id = $max[0]->max_id;

                    $sql_2 = "insert into store(shop_id, store_id, store_name, store_address, store_url, store_tel, town, ss_town) 
                    values(140, $max_id, '$st_name2', '$address[$i]', '$st_link', '$tel[$i]', '$city', '$district')";
                    // dd($sql_2);
                    DB::insert($sql_2);
                }
            }
            DB::commit();
            
        }
    }




}
