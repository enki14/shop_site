<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use App\Http\Lib\Common;
use Response;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Symfony\Component\HttpClient\HttpClient;

class EventSetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function seiyu_5pctOff(){
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));        

            // $sql_9 = 'select url, element_path from scrape where id = 9';
            // $s_9 = DB::select($sql_9);
            // foreach($s_9 as $data){
                $url = 'https://www.seiyu.co.jp/searchshop/tokyo/';
                $crawler = $client->request('GET', $url);
                $color = $crawler->filter('div.shop_search_individual_5off.typesquare_tags > ul > li:nth-child(1)')
                ->each(function($node){
                    return $node->text();
                });


            // }
            Log::debug($color);
           
        // }

    }

    public function event_list(){
        // 必要な素材：　img(src, alt), a(href), 添え付けられたテキスト
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        

        $obj = new \stdClass();

        $sql_42 = 'select url, element_path from scrape where id = 42';
        $s_42 = DB::select($sql_42);
        foreach($s_42 as $data){
           
            $url = $data->url;
            $crawler = $client->request('GET', $url);
            $link = $crawler->filter($data->element_path)->filter('ul > li > a')
            ->each(function($node){
                return $node->attr('href');
            });

            $src = $crawler->filter($data->element_path)->filter('div > img')
            ->each(function($node){
                return $node->attr('src');
            });

            $alt = $crawler->filter($data->element_path)->filter('div > img')
            ->each(function($node){
                return $node->attr('alt');
            });

            $text = $crawler->filter($data->element_path)->filter('p.campaign_banner_term')
            ->each(function($node){
                return $node->text();
            });

            Log::debug($link);

        }

        $imageAnnotator = new ImageAnnotatorClient();

        $seiyu = [];
        $shop_count = 0;
        for($i = 0; $i < count($src); $i++){
            
            $path = 'https://www.seiyu.co.jp' . $src[$i];  
            

            // Log::debug($eventLink);

            // Log::debug(print_r($obj, true));
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
                            $block_text .= " ";
                            // Log::debug($block_text);
                        }
                        $allblockText .= $block_text;
                        
                    }
                }
            }
            $imageAnnotator->close();

            $eventLink = 'https://www.seiyu.co.jp' . $link[$i]; 
            $obj->eventLink = $eventLink;
            $obj->text = $text[$i];
            $obj->alt = $alt[$i];  
            $obj->img_text = $allblockText;

            $seiyu[$shop_count] = $obj;
            $shop_count++;
            Log::debug($seiyu);
            
        }

        $output = [];
        $output['seiyu'] = $seiyu;
        return view('eventList', $output);
            
            
    }



    // public function event_list(){
    //     // 必要な素材：　img(src, alt), a(href), 添え付けられたテキスト
    //     $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

    //     // $obj = new \stdClass();

    //     $sql_42 = 'select url, element_path from scrape where id = 42';
    //     $s_42 = DB::select($sql_42);
    //     foreach($s_42 as $data){
           
    //         $url = $data->url;
    //         $crawler = $client->request('GET', $url);
    //         $link = $crawler->filter($data->element_path)->filter('ul > li > a')
    //         ->each(function($node){
    //             return $node->attr('href');
    //         });

    //         $src = $crawler->filter($data->element_path)->filter('div > img')
    //         ->each(function($node){
    //             return $node->attr('src');
    //         });

    //         $alt = $crawler->filter($data->element_path)->filter('div > img')
    //         ->each(function($node){
    //             return $node->attr('alt');
    //         });

    //         $text = $crawler->filter($data->element_path)->filter('p.campaign_banner_term')
    //         ->each(function($node){
    //             return $node->text();
    //         });

    //         Log::debug($link);

    //     }

    //     $imageAnnotator = new ImageAnnotatorClient();

    //     // $seiyu = [];
    //     // $shop_count = 0;
    //     for($i = 0; $i < count($src); $i++){
            
    //         $path = 'https://www.seiyu.co.jp' . $src[$i];  
            

    //         // Log::debug($eventLink);

    //         // Log::debug(print_r($obj, true));
    //         $image = file_get_contents($path);
    //         $response = $imageAnnotator->documentTextDetection($image);
    //         $annotation = $response->getFullTextAnnotation();

    //         # Log::debug out detailed and structured information about document text
    //         if ($annotation) {
    //             $allblockText = '';
    //             // getPages() : OCRによって検出されたページのリスト
    //             foreach ($annotation->getPages() as $page) {
    //                 foreach ($page->getBlocks() as $block) {
    //                     $block_text = '';
    //                     foreach ($block->getParagraphs() as $paragraph) {
    //                         foreach ($paragraph->getWords() as $word) {
    //                             foreach ($word->getSymbols() as $symbol) {
    //                                 $block_text .= $symbol->getText();
    //                             }
    //                             // ↓↓↓　あとで要るかもしれないけど、スペースがあると日付が抽出できないのでコメントアウトにしている
    //                             // $block_text .= ' ';
    //                         }
    //                         $block_text .= " ";
    //                         // Log::debug($block_text);
    //                     }
    //                     $allblockText .= $block_text;
                        
    //                 }
    //             }
    //         }
    //         $imageAnnotator->close();

    //         $seiyu = [];
    //         $seiyu['eventLink'] = 'https://www.seiyu.co.jp' . $link[$i]; 
    //         $seiyu['text'] = $text[$i];
    //         $seiyu['alt'] = $alt[$i];  
    //         $seiyu['img_text'] = $allblockText;

    //         $count = 0;
    //         $output = [];
    //         $output[$count] = $seiyu;
    //         $count++;
    //         Log::debug($output);
    //         return view('eventList', $output);
            
    //     }

            
            
    // }


}
