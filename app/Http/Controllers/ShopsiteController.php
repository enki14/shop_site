<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

class ShopsiteController extends Controller
{
    public function index(){
        $output = [];
        $output['shop'] = '';
        $output['schedule'] = '';
        $output['key_1'] = '';
        $output['key_2'] = '';
        $output['key_3'] = '';
        $output['key_4'] = '';
        $output['key_5'] = '';
        $output['key_6'] = '';
        $output['key_7'] = '';
        $output['key_8'] = '';
        $output['key_9'] = '';
        $output['map_search'] = '';
        
        // dd($output);
        return view('page.top', $output);
    }

    public function result(Request $request){

        // requestインスタンスはname値を呼び出す
        $schedule = $request->input('search-schedule');
        $shop = $request->input('search-shop');

        // dd($schedule);
        // dd($shop);
        $base_sql = "select sp.shop_id, s.shop_name, sp.sp_title, sp.event_start, sp.event_end 
        from shop s inner join sale_point sp on s.shop_id = sp.shop_id where ";
        // 「今日・明日」の場合
        if ($schedule == '1') {
            $add_where = "event_end = curdate() or event_end = date_add(curdate(), interval 1 day) ";
        // １週間の場合
        }elseif($schedule == '2'){
            $add_where = "event_end between curdate() and date_add(curdate(), interval 7 day) ";
        }elseif($schedule == '3'){
            $add_where = "event_end between curdate() and date_add(curdate(), interval 30 day) ";
        }else{
            $add_where = "event_end > now() ";
        }

        if($shop !== '' && $add_where !== ''){
            $add_where = $add_where . "and s.shop_name LIKE '%$shop%' order by event_end desc";
        }elseif($shop !== '' && $add_where == ''){
            $add_where = $add_where . "s.shop_name LIKE '%$shop%'";
        }elseif($shop == '' && $add_where !== ''){
            $add_where . "order by event_end desc";
        }

        // where構文はそれぞれ別だが$add_whereに統一することでif文にも対応できている
        $sql = $base_sql . $add_where;
        $s = DB::select($sql);

        $collect = collect($s); 
        
        $sch = new LengthAwarePaginator(
            $collect->forpage($request->page, 2),
            $collect->count(),
            2,
            $request->page,
            ['path'=> $request->url()]
        );
        // dd($collect);
        // dd($sch);
        
        $output = [];
        $output['sch'] = $sch;
        $output['schedule'] = $schedule;
        $output['shop'] = $shop;
        $output['pagenate_params'] = ['search-schedule'=> $schedule, 'search-shop'=> $shop];
        // dd($output);
        return view('page.mainResult', $output);
    }



    public function keyRes(Request $request){
        $key_1 = $request->input('key_1');
        $key_2 = $request->input('key_2');
        $key_3 = $request->input('key_3');
        $key_4 = $request->input('key_4');
        $key_5 = $request->input('key_5');
        $key_6 = $request->input('key_6');
        $key_7 = $request->input('key_7');
        $key_8 = $request->input('key_8');
        $key_9 = $request->input('key_9');

        dd($key_1);
        // dd($key_1);
        $base_sql = "select s2.shop_id, s3.store_id, s2.shop_name, s3.store_name, 
        sp.event_start, sp.event_end, sp.sp_title
        from shop s2 left join store s3 on s2.shop_id = s3.shop_id 
        left join sale_point sp on s2.shop_id = sp.shop_id where ";

        if($key_1){
            $add_where = "sp.sp_title like "%お中元%" or sp.sp_title like "%お盆%" or sp.sp_title like "%ギフト%"";
        }elseif($key_2){
            $add_where = "sp.sp_title like "%祭り%" or sp.sp_title like "%納涼%"";
        }

        $sql = $base_sql . $add_where;
        $list = DB::select($sql);

        $collect = collect($list); 
        
        $sch = new LengthAwarePaginator(
            $collect->forpage($request->page, 2),
            $collect->count(),
            2,
            $request->page,
            ['path'=> $request->url()]
        );
        // dd($collect);
        dd($sch);
        $key = array( 
            'key_1' => $key_1,
            'key_2' => $key_2,
            'key_3' => $key_3,
            'key_4' => $key_4,
            'key_5' => $key_5,
            'key_6' => $key_6,
            'key_7' => $key_7,
            'key_8' => $key_8,
            'key_9' => $key_9
        );


        $output = [];
        $output['sch'] = $sch;
        $output['key_1'] = $key_1;
        $output['key_2'] = $key_2;
        $output['key_3'] = $key_3;
        $output['key_4'] = $key_4;
        $output['key_5'] = $key_5;
        $output['key_6'] = $key_6;
        $output['key_7'] = $key_7;
        $output['key_8'] = $key_8;
        $output['key_9'] = $key_9;
        $output['pagenate_params'] = ['key'=> $key];
        // dd($output);
        return view('page.subResult', $output);
    }



    public function mapModal(Request $request){
        $req = $request->input('name');
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
        Log::debug($list);
        $response = [];
        $response['list'] = $list;
        $response['map_search'] = $req; 
        return Response::json($response);
    
    }

    public function mapData(Request $request){
        $lat = $request->input('data.lat');
        $lng = $request->input('data.lng');
        $L_lat = $request->input('data.L_lat');
        $L_lng = $request->input('data.L_lng');

        Log::debug($lat);
        config(['database.connections.mysql.strict' => false]);
        DB::reconnect();

        $sql = "select s.shop_name, s2.store_name, l.prefectures_name, l.town_name, l.ss_town_name, 
        sp.event_start, sp.event_end, sp.sp_title, sp.sp_subtitle,
        X(s2.location) as lat, Y(s2.location) as lng, X(l.L_location) as L_lat, Y(l.L_location) as L_lng,
                GLength(GeomFromText(CONCAT('LineString('$L_lat' '$L_lng', ', 
                       X(l.L_location), ' ', Y(l.L_location), ')'))) as distance, 
                GLength(GeomFromText(CONCAT('LineString('$lat' '$lng', ', 
                       X(s2.location), ' ', Y(s2.location), ')'))) as distance_2 
                       from shop s inner join store s2 on s.shop_id = s2.shop_id 
                       left join localdata l on s2.local_id = l.local_id 
                       left join sale_point sp on s.shop_id = sp.shop_id
                       GROUP BY s2.local_id, l.local_id HAVING greatest(distance, distance_2) <= 0.02694948 
                       ORDER BY greatest(distance, distance_2)";

        $location = DB::select($sql);

        // dd($list);
        Log::debug($location);
        $response = [];
        $response['location'] = $location;
        return Response::json($response);
    
    }



}


