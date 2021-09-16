<?php

namespace App\Http\Vender;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class callTwitterApi
{
    
    private $t;
    
    public function __construct()
    {
        $this->t = new TwitterOAuth(
            env('TWITTER_CLIENT_KEY'),
            env('TWITTER_CLIENT_SECRET'),
            env('TWITTER_CLIENT_ID_ACCESS_TOKEN'),
            env('TWITTER_CLIENT_ID_ACCESS_TOKEN_SECRET'),
            env('TWITTER_CALLBACK_SECRET')
        );
    }
    

    public function statusTweet(String $searchtweet)
    {
        $st = $this->t->get('users/lookup',[
            'user_id' => $searchtweet,
            'count' => '7'
        ]);

        return $st;
    }

    // タイムライン　
    public function userTimeline(String $searchWord)
    {
        $tl = $this->t->get('statuses/user_timeline', [
            'user_id' => $searchWord,
            'count' => '5'
        ]);

        return $tl;
    }

    //ユーザー検索
    public function searchUser(String $searchUser)
    {
        $su = $this->t->get('users/search', [
            'q' => $searchUser,
            'count' => '1'
        ]);

        // dd($su);
        if(isset($su)){
            return $su;
        }
        
    }

}