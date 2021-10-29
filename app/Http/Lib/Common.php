<?php

namespace App\Http\Lib;

class Common {


    // 都道府県 | 市区町村 | 地区 毎に分割する関数
    public static function separate_address_one(string $address)
    {
        if (preg_match('/(.+?郡.+?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u', $address, $matches) !== 1) {
            return [
                'city' => null,
                'other' => null
            ];
        }
        $pattern = '/([\w\-\.]+)([0-9０-９]+|[一二三四五六七八九十百千万]+)*(([0-9０-９]+|[一二三四五六七八九十百千万]+)|(丁目|丁|番地|番|号|-|‐|ー|−|の|東|西|南|北){1,2})*(([0-9０-９]+|[一二三四五六七八九十百千万]}+)|(丁目|丁|番地|番|号){1,2})(.*)/';
        // dd($matches);
        // 地区の番地以降を削除する処理
        $matches[2] = preg_replace($pattern, '', $matches[2]);
        return [
            'city' => $matches[1],
            'district' => $matches[2],
        ];

    }



    // 8桁数字からの年月日の変換
    public static function dateFormat($ymd) {
        if(!empty($ymd)){
            return date('Y年n月j日', strtotime($ymd));
        }else{
            return '';
        }
        
    }

    // 8桁数字からのハイフン付き変換
    public static function  hyphenFormat($data){
        if(!empty($data)){
            return date('Y-m-d', strtotime($data));
        }
    }


    // 固定長の４桁数字生成（月日の４桁）。
    public static function date_ymd(string $mm, string $dd){
        // str_padで2桁の左寄せで0埋め
        $mm = str_pad($mm, 2, "0", STR_PAD_LEFT);
        $dd = str_pad($dd, 2, "0", STR_PAD_LEFT);
        if($mm == '01'){
            // dateの第１引数はstringなのでYにクォーテーションをつける
            $yyyy = date('Y', strtotime('+1 year'));
        }else{
            $yyyy = date('Y');
        }
        return $yyyy . $mm . $dd;

    }

    // ○○月○○日、及び○○/○○の日付抽出の関数。
    public static function regExpression($replace_text){

        $retObj = new \stdClass();
        $retObj->START_DATE = "";
        $retObj->END_DATE = "";

        $date_wa_pattern = '/(0[1-9]{1}|1[0-2]{1}|[1-9]{1})[\/\-\.\／－．月](3[0-1]{1}|[1-2]{1}[0-9]{1}|0[1-9]{1}|[1-9]{1})[\/\-\.\／－．日]/u';
        $date_slash_pattern = '/(0[1-9]{1}|1[0-2]{1}|[1-9]{1})\/(3[0-1]{1}|[1-2]{1}[0-9]{1}|0[1-9]{1}|[1-9]{1})/u';

        // ○○月○○日分のSTART_DATEとEND_DATEの生成
        preg_match($date_wa_pattern, $replace_text, $match);

        if(count($match) > 0){
            // $match[1]が○○月の数字のみ、$match[2]が○○日の数字のみ　　// 上のdateFormat関数で2桁分の0埋めを実施している　　// dateFormatで年も取ってくれる
            $retObj->START_DATE = self::date_ymd($match[1], $match[2]);
            

            $replace_text = mb_ereg_replace($match[0], '', $replace_text);

            preg_match($date_wa_pattern, $replace_text, $match);

            if(count($match) > 0){
                $retObj->END_DATE = self::date_ymd($match[1], $match[2]);
                $replace_text = mb_ereg_replace($match[0], '', $replace_text);
            }
        }

        // ○○/○○分のSTART_DATEとEND_DATEの生成
        preg_match($date_slash_pattern, $replace_text, $match);

        if(count($match) > 0 && $retObj->START_DATE == ""){
            $retObj->START_DATE = self::date_ymd($match[1], $match[2]);
            $replace_text = mb_ereg_replace($match[0], '', $replace_text);

            preg_match($date_slash_pattern, $replace_text, $match);

            if(count($match) > 0){
                $retObj->END_DATE = self::date_ymd($match[1], $match[2]);
                $replace_text = mb_ereg_replace($match[0], '', $replace_text);
            }
        }

        return $retObj;
    }


}