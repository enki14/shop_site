<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Lib\Common;
use Response;

class ShopsiteController extends Controller
{
    // 初期表示　+　modalクリックしたときの処理
    public function index(Request $request){
        // Log::debug('index start!!');
        // リクエストパラメータから受け取った情報
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        // 住所か店舗を判断するフラグ（map_search.jsのパラメータ）
        $s_flag = $request->input('s_flag');


        Log::debug($lat);
        Log::debug($lng);
        if($lat == '' or $lng == ''){
            // ないときは初期表示用の座標
            $search_lat = '35.704406';
            $search_lng = '139.610732';
            $request_flag = false;
            $shop_list = [];
        }else{
            $search_lat = $lat;
            $search_lng = $lng;
            // リクエストパラメータありのフラグ(検索リクエストだったらtrue)
            $request_flag = true;

            // $shop_listでmapの結果一覧を取得する
            $sql = "select s.shop_name, s2.store_name, s2.store_url, s.img_src,
            l.prefectures_name, l.town_name, l.ss_town_name, sp.shop_id, sp.store_id,
            sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle, sp.sp_url,
            X(s2.location) as lat, Y(s2.location) as lng, X(l.L_location) as L_lat, Y(l.L_location) as L_lng,
                    GLength(GeomFromText(CONCAT('LineString($search_lat $search_lng, ', 
                        X(l.L_location), ' ', Y(l.L_location), ')'))) as distance, 
                    GLength(GeomFromText(CONCAT('LineString($search_lat $search_lng, ', 
                        X(s2.location), ' ', Y(s2.location), ')'))) as distance_2 
                        from shop s inner join store s2 on s.shop_id = s2.shop_id 
                        left join localdata l on s2.local_id = l.local_id 
                        left join sale_point sp on s.shop_id = sp.shop_id
                        GROUP BY s2.local_id, l.local_id HAVING greatest(distance, distance_2) <= 0.02694948 
                        ORDER BY greatest(distance, distance_2)";

            $shop_list = DB::select($sql);

        }
        Log::debug($shop_list);

        $output = [];
        $output['shop'] = '';
        $output['schedule'] = '';
        $output['keyword'] = '';
        $output['shop_list'] = $shop_list;
        $output['lat'] = $search_lat;
        $output['lng'] = $search_lng;
        $output['request_flag'] = $request_flag;
        $output['s_flag'] = $s_flag;
        return view('page.top', $output);
        
    }


    

    public function result(Request $request){
        Log::debug('result start!!');
        // requestインスタンスはname値を呼び出す
        $schedule = $request->input('search-schedule');
        $shop = $request->input('search-shop');

        
    
        
        // 「今日・明日」の場合
        if ($schedule == '今日・明日') {
            $add_where = "(event_end between curdate() and ( curdate( ) + INTERVAL 1 DAY ) 
            or event_start between curdate() and ( curdate( ) + INTERVAL 1 DAY )) ";
        // １週間の場合
        }elseif($schedule == '１週間'){
            $add_where = "(event_end between curdate() and ( curdate( ) + INTERVAL 6 DAY ) 
            or event_start between curdate() and ( curdate( ) + INTERVAL 6 DAY )) ";
        }elseif($schedule == '１ヵ月'){
            $add_where = "(event_end between curdate() and ( curdate( ) + INTERVAL 29 DAY ) 
            or event_start between curdate() and ( curdate( ) + INTERVAL 29 DAY ))";
        }else{
            $add_where = "event_start >= curdate() ";
        }

        if($shop !== ''){
            $add_shop_where = "and s.shop_name LIKE '%$shop%' ";
            $add_store_where = "and (s2.store_name like '%$shop%'
            or s2.town LIKE '%$shop%' or s2.ss_town like '%$shop%') ";
            $add_order = "order by '%$shop%', event_start";
        }else{
            $add_order = "order by event_start";
        }

        $sql = "select sp.shop_id, sp.store_id, s.shop_name, 
            s2.store_name, s.shop_url, s2.store_url, s.img_src, sp.cash_kubun,
            sp.sp_title, sp.sp_subtitle, sp.sp_url, sp.event_start, sp.event_end
            from shop s
            inner join sale_point sp on s.shop_id = sp.shop_id
            left join store s2 on sp.store_id = s2.store_id
            where "
            . $add_where . $add_shop_where 
            ."union all 
            select sp.shop_id, sp.store_id, s.shop_name, 
            s2.store_name, s.shop_url, s2.store_url, s.img_src, sp.cash_kubun,
            sp.sp_title, sp.sp_subtitle, sp.sp_url, sp.event_start, sp.event_end
            from store s2
            inner join sale_point sp on s2.store_id = sp.store_id
            left join shop s on s2.shop_id = s.shop_id
            where "
            . $add_where . $add_store_where
            . $add_order;

        // where構文はそれぞれ別だが$add_whereに統一することでif文にも対応できている
        $s = DB::select($sql);
        // Log::debug('sql:' .$sql);
        $collect = collect($s); 
        
        $pagenate = new LengthAwarePaginator(
            $collect->forpage($request->page, 10),
            $collect->count(),
            10,
            $request->page,
            ['path'=> $request->url()]
        );

        // dd($pagenate);
        $output = [];
        $output['pagenate'] = $pagenate;
        $output['schedule'] = $schedule;
        $output['shop'] = $shop;
        $output['pagenate_params'] = ['search-schedule'=> $schedule, 'search-shop'=> $shop];
        Log::debug('result end');
        return view('page.mainResult', $output);
    }



    public function keyRes(Request $request){
        Log::debug('keyRes start!!');
        $keyword = $request->input('keyword');

        $add_where = "event_start between curdate() and ( curdate( ) + INTERVAL 30 DAY )
        and (sp.sp_title like '%$keyword%' or sp.sp_subtitle like '
        %$keyword%' or sp.keyword like '%$keyword%') ";

        $add_order = "order by event_start";

        $sql = "select sp.shop_id, sp.store_id, s.shop_name, 
            s2.store_name, s.shop_url, s2.store_url, s.img_src, sp.cash_kubun,
            sp.sp_title, sp.sp_subtitle, sp.sp_url, sp.event_start, sp.event_end
            from shop s
            inner join sale_point sp on s.shop_id = sp.shop_id
            left join store s2 on sp.store_id = s2.store_id 
            where "
            . $add_where .
            "union all 
            select sp.shop_id, sp.store_id, s.shop_name, 
            s2.store_name, s.shop_url, s2.store_url, s.img_src, sp.cash_kubun,
            sp.sp_title, sp.sp_subtitle, sp.sp_url, sp.event_start, sp.event_end
            from store s2
            inner join sale_point sp on s2.store_id = sp.store_id
            left join shop s on s2.shop_id = s.shop_id
            where "
            .$add_where . $add_order;

        $list = DB::select($sql);
        $collect = collect($list); 
        
        $pagenate = new LengthAwarePaginator(
            $collect->forpage($request->page, 10),
            $collect->count(),
            10,
            $request->page,
            ['path'=> $request->url()]
        );

        $output = [];
        $output['keyword'] = $keyword;
        // リクエストパラメータのキーは上のキーと合わせるようにする
        $output['pagenate_params'] = [ 'keyword'=> $keyword ];
        $output['pagenate'] = $pagenate;
        Log::debug('keyRes end!!');
        return view('page.subResult', $output);
    }


    // モーダル表示用メソッド
    public function mapModal(Request $request){
        // MapS.bladeのmap_search, name属性
        $req = $request->input('namae');

        // Log::debug($req);
        // mysqlに無効な値が挿入されてもエラーを吐き出さないようにしている
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();

        // GLengthで２地点距離の計算式をカラムにした。検索結果から近い順に店舗をselectしている。
        // 他、同じsql文がindex(), mapData()にある。
        $sql = "select s.shop_name, s2.store_name, l.prefectures_name, l.town_name, l.ss_town_name,
        X(s2.location) as lat, Y(s2.location) as lng, X(l.L_location) as L_lat, Y(l.L_location) as L_lng, 
        GLength(GeomFromText(CONCAT('LineString(35.73529673720239 139.6281261687641, ', 
               X(l.L_location), ' ', Y(l.L_location), ')'))) as distance, 
        GLength(GeomFromText(CONCAT('LineString(35.73529673720239 139.6281261687641, ', 
               X(s2.location), ' ', Y(s2.location), ')'))) as distance_2 
               from shop s inner join store s2 on s.shop_id = s2.shop_id 
               left join localdata l on s2.local_id = l.local_id 
               where s.shop_name LIKE '%$req%' or s2.store_name like '%$req%' 
               GROUP BY s2.local_id, l.local_id HAVING greatest(distance, distance_2) <= 0.02694948 
               ORDER BY greatest(distance, distance_2)";
        $list = DB::select($sql);

        $response = [];
        $response['list'] = $list; 
        return Response::json($response);
    
    }

    // map内での店舗と地域の初期表示用
    public function mapData(Request $request){
        $S_lat = $request->input('lat');
        $S_lng = $request->input('lng');
        $L_lat = $request->input('L_lat');
        $L_lng = $request->input('L_lng');
        
        Log::debug($S_lat);
        Log::debug($S_lng);
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();

        $sql = "select s.shop_name, s2.store_name, l.prefectures_name, l.town_name, l.ss_town_name, 
        sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle, sp.sp_url,
        X(s2.location) as lat, Y(s2.location) as lng, X(l.L_location) as L_lat, Y(l.L_location) as L_lng,
        GLength(GeomFromText(CONCAT('LineString($L_lat $L_lng, ', 
        X(l.L_location), ' ', Y(l.L_location), ')'))) as distance, 
        GLength(GeomFromText(CONCAT('LineString($S_lat $S_lng, ', 
        X(s2.location), ' ', Y(s2.location), ')'))) as distance_2 
        from shop s inner join store s2 on s.shop_id = s2.shop_id 
        left join localdata l on s2.local_id = l.local_id 
        left join sale_point sp on s.shop_id = sp.shop_id
        GROUP BY s2.local_id, l.local_id HAVING greatest(distance, distance_2) <= 0.02694948 
        ORDER BY greatest(distance, distance_2)";

        $location = DB::select($sql);

        Log::debug($location);
        $response['location'] = $location;
        return Response::json($response);
    
    }
    

    
    public function eventCalendar_2(Request $request){
        // 会社全体のイベントを取得するためのsql
        $sql = "select sp.sp_code, s.shop_name, s.shop_url, sp.sp_title, 
        sp.sp_subtitle, sp.event_start, sp.event_end, sp.sp_url
        from shop s left join sale_point sp on s.shop_id = sp.shop_id
        where sp_code is not null";
        $shop = DB::select($sql);

        // 店舗ごとのイベントを取得するためのsql
        $sql_2 = "select sp.sp_code, s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.event_start, sp.event_end, sp.sp_url 
        from shop s left join store s2 on s.shop_id = s2.shop_id
        left join sale_point sp on s2.store_id = sp.store_id
        where sp_code is not null";
        $store = DB::select($sql_2);


        $response = [];
        $output = [];

        // 会社イベントとして回す
        foreach($shop as $data){
            $output['id'] = $data->sp_code;
            $output['main_title'] = $data->sp_title;
            $output['description'] = $data->sp_subtitle;
            $output['url'] = $data->sp_url;
            $output['title'] = $data->shop_name . 'からのお知らせ';
            
            // varchar(8) にハイフンをつける
            $start = Common::hyphenFormat($data->event_start);
            $end = Common::hyphenFormat($data->event_end);
            // shop_id の是非判定で会社全体としてのイベントだけを取得する
            // 会社イベントをインサートする際に sp.shop_id に+1の番号が振られる
            
            // event_startが無ければ、そのイベントは無視される（要検討
            if($start == ''){
                $data;
                continue;
            }else{        
                // event_startとevent_endのセット
                if($end != '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = $end;
                }elseif($end == '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = null;   
                }elseif($end != '' && $start == ''){
                    $output['start'] = null;
                    $output['end'] = $end; 
                }else{
                    $output['start'] = null;
                    $output['end'] = null;
                }
            }
            // $outputに諸々をセットしてから、$responseとして入れなおす？
            $response[] = $output;
        }        

        // 店舗イベントとして回す
        foreach($store as $data){
            $output['id'] = $data->sp_code;
            // カレンダー上のイベントタイトルとは分けている
            $output['main_title'] = $data->sp_title;
            $output['description'] = $data->sp_subtitle;
            $output['url'] = $data->sp_url;
            $output['title'] = $data->shop_name . $data->store_name;
            
            // varchar(8) にハイフンをつける
            $start = Common::hyphenFormat($data->event_start);
            $end = Common::hyphenFormat($data->event_end);
            // sale_pointテーブルのstore_idカラムと、storeテーブルのstore_idカラムを紐づけることで会社イベントを含めないようにしている
            // 店舗ごとのイベントをインサートする場合に限り、sale_pointのstore_idに該当店舗の番号が振られる
            

            // event_startが無ければ、そのイベントは無視される（要検討）
            if($start == ''){
                $data;
                continue;
            }else{
                // event_startとevent_endのセット
                if($end != '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = $end;
                }elseif($end == '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = null;   
                }elseif($end != '' && $start == ''){
                    $output['start'] = null;
                    $output['end'] = $end; 
                }else{
                    $output['start'] = null;
                    $output['end'] = null;
                }

            }   
            // $outputに諸々をセットしてから、$responseとして入れなおす？           
            $response[] = $output;
                    
        }   
        // Log::debug($response);
        return Response::json($response);
    }

}