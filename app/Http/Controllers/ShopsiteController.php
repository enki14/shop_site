<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Lib\common;
use Response;

class ShopsiteController extends Controller
{
    public function index(Request $request){
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        Log::debug($lat);
        Log::debug($lng);
        if($lat == '' or $lng == ''){
            // ないときは初期表示用の座標
            $search_lat = '35.704406';
            $search_lng = '139.610732';
            $shop_list = [];
        }else{
            $search_lat = $lat;
            $search_lng = $lng;

            // $shop_listでmapの結果一覧を取得する
            $sql = "select s.shop_name, s.shop_url, s2.store_name, 
            l.prefectures_name, l.town_name, l.ss_town_name, 
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
        return view('page.top', $output);
        
    }


    

    public function result(Request $request){

        // requestインスタンスはname値を呼び出す
        $schedule = $request->input('search-schedule');
        $shop = $request->input('search-shop');

        $base_sql = "select s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.sp_url, sp.event_start, sp.event_end
        from shop s left join store s2 on s.shop_id = s2.shop_id
        left join sale_point sp on s.shop_id = sp.shop_id where ";
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
            $add_where = "event_end >= curdate() ";
        }

        if($shop !== ''){
            $add_where = $add_where . "and (s.shop_name LIKE '%$shop%' or s2.store_name like '%$shop%'
            or s2.town LIKE '%$shop%' or s2.ss_town like '%$shop%') and (event_start is not null and event_start != '') 
            order by '%$shop%', event_start";
        }else{
            $add_where = $add_where . "and (event_start is not null and event_start != '')
            order by event_start";
        }

        // where構文はそれぞれ別だが$add_whereに統一することでif文にも対応できている
        $sql = $base_sql . $add_where;
        $s = DB::select($sql);

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
    
        return view('page.mainResult', $output);
    }



    public function keyRes(Request $request){
        $keyword = $request->input('keyword');

        $base_sql = "select s2.shop_name, s3.store_name, s2.shop_url, s3.store_url, 
        sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle, sp_url
        from shop s2 left join store s3 on s2.shop_id = s3.shop_id 
        left join sale_point sp on s2.shop_id = sp.shop_id where ";

        $add_where = "event_start between curdate() and ( curdate( ) + INTERVAL 30 DAY )
        and (sp.sp_title like '%$keyword%' or sp.sp_subtitle like '%$keyword%') and 
        (event_start is not null and event_start != '') order by sp.event_start";
                
        $sql = $base_sql . $add_where;
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
        $success_flg = $request->input('success_flg');


        if($success_flg == "true"){
            // MapS.bladeからのhiddenリクエスト( modal_open.jsからのajax )
            $S_lat = $request->input('lat');
            $S_lng = $request->input('lng');
            $L_lat = $request->input('L_lat');
            $L_lng = $request->input('L_lng');
            
            Log::debug($S_lat);
            Log::debug($S_lng);
            config(['database.connections.mysql.strict' => false]);
            DB::reconnect();

            $sql = "select s.shop_name, s2.store_name, l.prefectures_name, l.town_name, l.ss_town_name, 
            sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle,
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

        }else{
            // MapS.bladeからのhiddenリクエスト( modal_open.jsからのajax )
            $S_lat = '35.704406';
            $S_lng = '139.610732';
            $L_lat = '35.704406';
            $L_lng = '139.610732';
            
            Log::debug($S_lat);
            Log::debug($S_lng);
            config(['database.connections.mysql.strict' => false]);
            DB::reconnect();

            $sql = "select s.shop_name, s2.store_name, l.prefectures_name, l.town_name, l.ss_town_name, 
            sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle,
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

        }

        

        $location = DB::select($sql);

        Log::debug($location);
        $response['location'] = $location;
        return Response::json($response);
    
    }
    

    
    public function eventCalendar_2(Request $request){
        // 会社全体のイベントを取得するためのsql
        $sql = "select sp.sp_code, s.shop_name, s.shop_url, sp.sp_title, sp.sp_subtitle, 
        sp.event_start, sp.event_end, sp.sp_url, sp.shop_event_id
        from shop s left join sale_point sp on s.shop_id = sp.shop_id";
        $shop = DB::select($sql);

        // 店舗ごとのイベントを取得するためのsql
        $sql_2 = "select sp.sp_code, s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.event_start, sp.event_end, 
        sp.sp_url, sp.shop_event_id, sp.store_id
        from shop s left join store s2 on s.shop_id = s2.shop_id
        left join sale_point sp on s.shop_id = sp.shop_id";
        $store = DB::select($sql_2);


        $response = [];
        $output = [];

        // 会社イベントとして回す
        foreach($shop as $data){
            $output['id'] = $data->sp_code; 
            $start = Common::hyphenFormat($data->event_start);
            $end = Common::hyphenFormat($data->event_end);
            // shop_event_id の是非判定で会社全体としてのイベントだけを取得する
            // 会社イベントをインサートする際に shop_event_id に+1の番号が振られる
            if(empty($data->shop_event_id)){
                $data;
                continue;
            }else{
                // event_startが無ければ、そのイベントは無視される（要検討
                if($start == ''){
                    $data;
                    continue;
                }else{
                    // sp_title, sp_subtitleがあるかないかの判定
                    if($data->sp_title == '' && $data->sp_subtitle != ''){
                        $output['title'] = $data->shop_name . 'からのお知らせ';
                        $output['url'] = $data->sp_url;

                    }elseif($data->sp_title != '' && $data->sp_subtitle == ''){
                        $output['title'] = $data->sp_title;
                        $output['url'] = $data->sp_url;
                    }else{
                        $output['title'] = '';
                        $output['url'] = '';
                    }
                    
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
            }
            // $outputに諸々をセットしてから、$responseとして入れなおす？
            $response[] = $output;
        }        

        // 店舗イベントとして回す
        foreach($store as $data){
            $output['id'] = $data->sp_code;
            
            // varchar(8) にハイフンをつける
            $start = Common::hyphenFormat($data->event_start);
            $end = Common::hyphenFormat($data->event_end);
            // sale_pointテーブルのstore_idカラムと、storeテーブルのstore_idカラムを紐づけることで会社イベントを含めないようにしている
            // 店舗ごとのイベントをインサートする場合に限り、sale_pointのstore_idに該当店舗の番号が振られる
            if(!empty($data->store_id)){

                // event_startが無ければ、そのイベントは無視される（要検討）
                if($start == ''){
                    $data;
                    continue;
                }else{
                    // sp_title, sp_subtitleがあるかないかの判定
                    if($data->sp_title == '' && $data->sp_subtitle != ''){
                        $output['title'] = $data->shop_name . $data->store_name . 'からのお知らせ';
                        $output['url'] = $data->sp_url;
                    }elseif($data->sp_title != '' && $data->sp_subtitle == ''){
                        $output['title'] = $data->sp_title;
                        $output['url'] = $data->sp_url;
                    }else{
                        $output['title'] = '';
                        $output['url'] = '';
                    }
                    
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
        }else{
            $data;
            continue; 
        }
            // $outputに諸々をセットしてから、$responseとして入れなおす？           
            $response[] = $output;        
        }   
        Log::debug($response);
        return Response::json($response);
    }

}