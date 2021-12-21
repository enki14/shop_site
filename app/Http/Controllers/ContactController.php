<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactSendmail;

class ContactController extends Controller
{
    public function index(){
        return view('page.contact_P');
    }


    public function confirm(Request $request){
        //バリデーションを実行（結果に問題があれば処理を中断してエラーを返す）
        $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'body' => 'required' 
        ]);

        //フォームから受け取ったすべてのinputの値を取得
        $inputs = $request->all();

        //入力内容確認ページのviewに変数を渡して表示
        return view('page.confirm_P', [
            'inputs' => $inputs
        ]);
    }

    
    public function send(Request $request){
        $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'body' => 'required' 
        ]);

        $action = $request->input('action');

        $inputs = $request->except('action');


        if($action !== 'submit'){
            return redirect()
            ->route('page.contact_P')
            ->withInput($inputs);
        }else{

            \Mail::to($inputs['email'])->send(new ContactSendmail($inputs));
            $request->session()->regenerateToken();
            return view('page.thanks_P');

        }
    }
}
