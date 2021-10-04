<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Lib\Common;
use Response;

class CalendarController extends Controller
{
    public function index(){
        return view('calendar');
    }

    public function eventCalendar(Request $request){
        $sql = "select sp.sp_code, s.shop_name, s2.store_name, s.shop_url, s2.store_url, 
        sp.sp_title, sp.sp_subtitle, sp.event_start, sp.event_end
        from shop s left join store s2 on s.shop_id = s2.shop_id
        left join sale_point sp on s.shop_id = sp.shop_id";
        $list = DB::select($sql);

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
                }elseif($data->sp_title != '' && $data->sp_subtitle == ''){
                    $output['title'] = $data->sp_title;
                }else{
                    $output['title'] = '';
                }
                
                
                if($end != '' && $start != ''){
                    $output['start'] = $start;
                    $output['end'] = $end;
                }elseif($end == '' && $start != ''){
                    $output['start'] = $start;   
                }

            }
                

            
            
            $response[] = $output;
            Log::debug($response);

        }

        return Response::json($response);

    }




}
