<?php

namespace App\Http\Controllers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Lib\common;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Log;


class DetectDocumentController extends Controller
{

    // つまり流れとしてはscrapingした上で、imgsrcのurlをocrの処理にセットさせればよい。そうすれば
    // 文字の読み込み要素とその他のスクレイピングの処理が一致する
    public function detect_document_text_2() {
        
       
        $imageAnnotator = new ImageAnnotatorClient();
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        // $pathは$imgpathと一致する。サーバーの方で文字を読み込むことができる
        $path = "https://www.aeonretail.jp/otoku/img/bnr_aeonapp2020030720.png";
        $image = file_get_contents($path);
        $response = $imageAnnotator->documentTextDetection($image);
        $annotation = $response->getFullTextAnnotation();

        # Log::debug out detailed and structured information about document text
        if ($annotation) {
            $allblockText = '';
            // getPages() : OCRによって検出されたページのリスト
            foreach ($annotation->getPages() as $page) {
                foreach ($page->getBlocks() as $block) {
                    $block_text = '';
                    foreach ($block->getParagraphs() as $paragraph) {
                        foreach ($paragraph->getWords() as $word) {
                            foreach ($word->getSymbols() as $symbol) {
                                $block_text .= $symbol->getText();
                            }
                            // ↓↓↓　あとで要るかもしれないけど、スペースがあると日付が抽出できないのでコメントアウトにしている
                            // $block_text .= ' ';
                        }
                        $block_text .= "\n";
                    }
                    $allblockText .= $block_text;
                    
                }
            }
            Log::debug($allblockText);    
        }    
        return redirect('/');
    }


    public function detect_document_text() {
        
        // 一般公開ではない画像ファイルはlaravel_project/storage配下に置く
        $files = Storage::files('vision_api');
        foreach($files as $file){
            // appフォルダ内までのフルパス
            $path = storage_path('app') . '/'. $file;
            
            $this->detect_document($path, $file);    
            // スラッシュ区切りで配列にする。vision_apiフォルダが[0]、$fileが[1]　
            $s_path = explode("/", $file);
            // 処理済みの画像をoldフォルダに引っ越し
            $old_path = $s_path[0] . '/old/' . $s_path[1];
            
            // リネームや存在するファイルを新しい場所へ移動する move('今までの場所', '新しい場所')
            Storage::move($file, $old_path);
        }

        return redirect('/');
    }

    private function detect_document ($path, $file) {
        // 検出されたエンティティを画像から返すためのインスタンス
        $imageAnnotator = new ImageAnnotatorClient();
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        
        $image = file_get_contents($path);
        $response = $imageAnnotator->documentTextDetection($image);
        $annotation = $response->getFullTextAnnotation();

        # Log::debug out detailed and structured information about document text
        if ($annotation) {
            $allblockText = '';
            // getPages() : OCRによって検出されたページのリスト
            foreach ($annotation->getPages() as $page) {
                foreach ($page->getBlocks() as $block) {
                    $block_text = '';
                    foreach ($block->getParagraphs() as $paragraph) {
                        foreach ($paragraph->getWords() as $word) {
                            foreach ($word->getSymbols() as $symbol) {
                                $block_text .= $symbol->getText();
                            }
                            // ↓↓↓　あとで要るかもしれないけど、スペースがあると日付が抽出できないのでコメントアウトにしている
                            // $block_text .= ' ';
                        }
                        $block_text .= "\n";
                    }
                    $allblockText .= $block_text;
                    
                }
            }
            // dd($allblockText);

            
            // ここにgoutteの記述でhref, imgsrc, altを取得
            $sql_15 = 'select url, element_path from scrape where id = 15';
            $s_15 = DB::select($sql_15);
            foreach($s_15 as $data){
                $url = $data->url;
                $crawler = $client->request('GET', $url);
                $sp_url = $crawler->filter($data->element_path)
                ->each(function($node){
                    return $node->attr('href');
                });
                $imgpath = $crawler->filter($data->element_path)->filter('img')
                ->each(function($node){
                    return $node->attr("src");
                });
                $sp_title = $crawler->filter($data->element_path)->filter('img')
                ->each(function($node){
                    return $node->attr("alt");
                });
            }



            for($i = 0; $i < count($sp_url); $i++){
                $imgFile = basename($imgpath[$i]);
                $img = 'vision_api/' . $imgFile; 

                $no_http = strpos($sp_url[$i], 'http') === false;

                if($no_http){
                    $url = str_replace('/otoku/', '', $url);
                    $sp_page = $url . $sp_url[$i];
                }else{
                    $sp_page = $sp_url[$i];
                }

                $id = "select max(sp_code) + 1 as max_id from sale_point";
                $max = DB::select($id);
                $max_id = $max[0]->max_id;
                

                if(strcmp($file, $img) == 0){
                    $ret = Common::regExpression($allblockText);

                    $sql = "insert into sale_point(shop_id, sp_code, sp_title, sp_subtitle, sp_url, event_start, event_end)
                    values(4, $max_id, '$sp_title[$i]', '$allblockText', '$sp_page', '$ret->START_DATE', '$ret->END_DATE')";
                    DB::select($sql);
                    DB::commit();
                    
                    
                }else{
                    continue;
                }

            }

        } else {
            // Log::debug('No text found' . PHP_EOL);
        }

        $imageAnnotator->close();
    }

    

    public function itoyokado_event(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $sql_16 = 'select url, element_path from scrape where id = 16';
        $s_16 = DB::select($sql_16);
        foreach($s_16 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $event_url = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr('href');
            });
        }


        $sql_17 = 'select url, element_path from scrape where id = 17';
        $s_17 = DB::select($sql_17);
        foreach($s_17 as $data){
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $imgpath = $crawler->filter($data->element_path)
            ->each(function($node){
                return $node->attr("src");
            });
        }
        // dd($event_url);

        $imageAnnotator = new ImageAnnotatorClient();

        $sp_url = array_slice($event_url, 3, 7);
        for($i = 0; $i < count($sp_url); $i++){
            $path = 'https://www.itoyokado.co.jp' . $imgpath[$i];
            // Log::debug($path);
            $image = file_get_contents($path);
            $response = $imageAnnotator->documentTextDetection($image);
            $annotation = $response->getFullTextAnnotation();

            # Log::debug out detailed and structured information about document text
            if ($annotation) {
                $allblockText = '';
                // getPages() : OCRによって検出されたページのリスト
                foreach ($annotation->getPages() as $page) {
                    foreach ($page->getBlocks() as $block) {
                        $block_text = '';
                        foreach ($block->getParagraphs() as $paragraph) {
                            foreach ($paragraph->getWords() as $word) {
                                foreach ($word->getSymbols() as $symbol) {
                                    $block_text .= $symbol->getText();
                                }
                                // ↓↓↓　あとで要るかもしれないけど、スペースがあると日付が抽出できないのでコメントアウトにしている
                                // $block_text .= ' ';
                            }
                            $block_text .= "\n";
                        }
                        $allblockText .= $block_text;
                        
                    }
                }
            }
            $imageAnnotator->close();


            Log::debug($allblockText);
            $allblockText = str_replace('11-3-', '', $allblockText);

            $ret = Common::regExpression($allblockText);

            $id = "select max(sp_code) + 1 as max_id from sale_point";
            $max = DB::select($id);
            $max_id = $max[0]->max_id;

            $sql = "insert into sale_point(shop_id, sp_code, sp_subtitle, sp_url, event_start, event_end) 
            values(2, $max_id, '$allblockText', '$sp_url[$i]', '$ret->START_DATE', '$ret->END_DATE')";
            // dd($sql);
            DB::insert($sql);
            DB::commit();
        }

        return redirect('/');
    }


}
