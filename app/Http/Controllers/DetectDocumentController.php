<?php

namespace App\Http\Controllers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Log;


class DetectDocumentController extends Controller
{
    public function detect_document_text() {
        
        // 一般公開ではない画像ファイルはlaravel_project/storage配下に置く
        $files = Storage::files('vision_api');

        foreach($files as $file){
            // appフォルダ内までのフルパス
            $path = storage_path('app') . '/'. $file;
            Log::debug($path);
            $this->detect_document($path);
            // スラッシュ区切りで配列にする。vision_apiフォルダが[0]、$fileが[1]　
            $s_path = explode("/", $file);
            // dd($s_path);
            $old_path = $s_path[0] . '/old/' . $s_path[1];
            Log::debug($old_path);
            // リネームや存在するファイルを新しい場所へ移動する move('今までの場所', '新しい場所')
            Storage::move($file, $old_path);
        }

        Log::debug($files);

        return redirect('/');
    }

    private function detect_document ($path) {
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        $image = file_get_contents($path);
        $response = $imageAnnotator->documentTextDetection($image);
        $annotation = $response->getFullTextAnnotation();

        # Log::debug out detailed and structured information about document text
        if ($annotation) {
            $allblockText = '';
            foreach ($annotation->getPages() as $page) {
                foreach ($page->getBlocks() as $block) {
                    $block_text = '';
                    foreach ($block->getParagraphs() as $paragraph) {
                        foreach ($paragraph->getWords() as $word) {
                            foreach ($word->getSymbols() as $symbol) {
                                $block_text .= $symbol->getText();
                            }
                            $block_text .= ' ';
                        }
                        $block_text .= "\n";
                    }
                    // Log::debug($block_text);
                    $allblockText .= $block_text;
                    
                }
            }
            // Log::debug($allblockText);

        } else {
            // Log::debug('No text found' . PHP_EOL);
        }

        $imageAnnotator->close();
    }


}
