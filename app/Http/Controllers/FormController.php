<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;


class FormController extends Controller
{
    public function index(){
        $sql = 'select * from shop_info';
        $list = DB::select($sql);

        return view('form', ['list' => $list]);
    }
    
    public function insert(Request $request){
        
        $name = $request->input('name');
            
        
        
        $sql = 'select max(si.shop_id) + 1 as max_id from shop_info si';
        $max = DB::select($sql);
        $max_id = $max[0]->max_id;
        
        DB::insert("insert into shop_info(shop_id, shop_name) values($max_id, '$name')");
        DB::commit();
        
        return redirect('/form');
        
    }
    
    
    public function update(Request $request){
        
        $id = $request->input('id');
        $name = $request->input('name');
        
        // int型なのかstring型なのかでコーテーションを付けるかつけないか意識する
        DB::update("update shop_info set shop_name = '$name' where shop_id = $id");
        DB::commit();
        
        return redirect('/form');
        
    }
    
    
    
    public function delete(Request $request){
        
        
        $id = $request->input('id');    
        
        DB::delete("delete from shop_info where shop_id = $id");
        DB::commit();
        
        return redirect('/form');        
        
    }
    
}
