<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

class ShopsiteController extends Controller
{
    public function index(Request $request){
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        
        if($lat == '' or $lng == ''){
            // ないときは初期表示用の座標
            $search_lat = '35.73529673720239';
            $search_lng = '139.6281261687641';
        }else{
            $search_lat = $lat;
            $search_lng = $lng;
        }
        

        $output = [];
        $output['shop'] = '';
        $output['schedule'] = '';
        $output['keyword'] = '';
        $output['lat'] = $search_lat;
        $output['lng'] = $search_lng;
        return view('page.top', $output);

    
    }

    public function result(Request $request){

        // requestインスタンスはname値を呼び出す
        $schedule = $request->input('search-schedule');
        $shop = $request->input('search-shop');

        // dd($schedule);
        // dd($shop);
        $base_sql = "select s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.event_start, sp.event_end
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

        
        // $retObj = new \stdClass();
        // $retObj->event_start = "";
        // $retObj->event_end = "";

        
        // for($i = 0; $i < count($s); $i++){
        //     if(!empty($s[$i]->event_end)){
        //         $event_start_half = str_split($s[$i]->event_start, 4);
        //         $event_end_half = str_split($s[$i]->event_end, 4);
        //         $mmdd_start = str_split($event_start_half[1], 2);
        //         $mmdd_end = str_split($event_end_half[1], 2);
        //         $yyyy_start = $event_start_half[0];
        //         $yyyy_end = $event_end_half[0];
    
        //         $retObj->event_start = $yyyy_start . '年' . ltrim($mmdd_start[0], '0') . '月' . ltrim($mmdd_start[1], '0') . '日';
        //         $retObj->event_end = $yyyy_end . '年' . ltrim($mmdd_end[0], '0') . '月' .  ltrim($mmdd_start[1], '0') . '日';

        //         // dd($retObj->event_start);
        //     }else{
        //         $event_start_half = str_split($s[$i]->event_start, 4);
        //         $mmdd_start = str_split($event_start_half[1], 2);
        //         $yyyy_start = $event_start_half[0];
    
        //         $retObj->event_start = $yyyy_start . '年' . ltrim($mmdd_start[0], '0') . '月' . ltrim($mmdd_start[1], '0') . '日';
        //     }

        // }
        
            
        
        $output = [];
        $output['pagenate'] = $pagenate;
        $output['schedule'] = $schedule;
        $output['shop'] = $shop;
        $output['pagenate_params'] = ['search-schedule'=> $schedule, 'search-shop'=> $shop];
        // dd($output);
        return view('page.mainResult', $output);
    }



    public function keyRes(Request $request){
        $keyword = $request->input('keyword');

        // Log::debug($keyword);
        // dd($key_1);
        $base_sql = "select s2.shop_name, s3.store_name, s2.shop_url, s3.store_url, 
        sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle
        from shop s2 left join store s3 on s2.shop_id = s3.shop_id 
        left join sale_point sp on s2.shop_id = sp.shop_id where ";

        $add_where = "event_start between curdate() and ( curdate( ) + INTERVAL 30 DAY )
        and (sp.sp_title like '%$keyword%' or sp.sp_subtitle like '%$keyword%') and 
        (event_start is not null and event_start != '') order by sp.event_start";
        
        
        $sql = $base_sql . $add_where;
        Log::debug($sql);
        $list = DB::select($sql);

        
        $collect = collect($list); 
        
        $pagenate = new LengthAwarePaginator(
            $collect->forpage($request->page, 10),
            $collect->count(),
            10,
            $request->page,
            ['path'=> $request->url()]
        );

        Log::debug($pagenate);

        $output = [];
        $output['keyword'] = $keyword;
        $output['pagenate_params'] = [ 'keyword'=> $keyword ];
        $output['pagenate'] = $pagenate;
       
        return view('page.subResult', $output);
    }


    // モーダル表示用メソッド
    public function mapModal(Request $request){
        $req = $request->input('namae');
        Log::debug($req);
        

        // dd($req);
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();

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
    

        // dd($list);
        // Log::debug($list);
        $response = [];
        $response['list'] = $list; 
        return Response::json($response);
    
    }

    // 店舗と地域の初期表示用
    public function mapData(Request $request){
        $S_lat = $request->input('lat');
        $S_lng = $request->input('lng');
        $L_lat = $request->input('L_lat');
        $L_lng = $request->input('L_lng');
        
        // Log::debug($S_lat);
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
        // Log::debug($sql);

        $location = DB::select($sql);

        // dd($list);
        // Log::debug($location);
        $response = [];
        $response['location'] = $location;
        return Response::json($response);
    
    }



}