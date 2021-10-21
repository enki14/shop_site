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
        
        if($lat == '' or $lng == ''){
            // ないときは初期表示用の座標
            $search_lat = '35.73529673720239';
            $search_lng = '139.6281261687641';
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

        // dd($schedule);
        // dd($shop);
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
        $req = $request->input('namae');
        // Log::debug($req);
        

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
        
        // Log::debug($S_lng);
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

        $location = DB::select($sql);


        $response['location'] = $location;
        return Response::json($response);
    
    }
    

    
    public function eventCalendar_2(Request $request){
        $sql = "select sp.sp_code, s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.event_start, sp.event_end, sp.sp_url
        from shop s left join store s2 on s.shop_id = s2.shop_id
        left join sale_point sp on s.shop_id = sp.shop_id";
        $list = DB::select($sql);

        // dd($list);
        $response = [];
        $output = [];
        foreach($list as $data){
            $output['id'] = $data->sp_code;
            
            $start = Common::hyphenFormat($data->event_start);
            $end = Common::hyphenFormat($data->event_end);
            // Log::debug($start);

            if($start == ''){
                $data;
                continue;
            }else{
                if($data->sp_title == '' && $data->sp_subtitle != ''){
                    $output['title'] = $data->shop_name . $data->store_name . 'からのお知らせ';
                    $output['description'] = $data->sp_subtitle;
                }elseif($data->sp_title != '' && $data->sp_subtitle == ''){
                    $output['title'] = $data->sp_title;
                    $output['description'] = $data->sp_title;
                }else{
                    $output['title'] = '';
                    $output['description'] = '';
                }
                
                
                if($end != '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = $end;
                }elseif($end == '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = null;   
                }
                
            }        
            
            Log::debug($output);
            $response[] = $output;
            

        }

        return Response::json($response);
    }



}