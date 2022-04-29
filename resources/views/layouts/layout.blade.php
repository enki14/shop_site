<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158885830-1"></script>
        <script defer>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-158885830-1');
        </script>
        <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <meta name="description" itemprop="description" content="@yield('description')">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta property="og:title" content="スーパーマーケットのポイントカード情報 ♪" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url('/') }}" />
        <meta property="og:image" content="{{ asset('img/nrd-D6Tu_L3chLE-unsplash.jpg') }}" />
        <meta property="og:site_name" content="ポイント王国" />
        <meta property="og:description" content="グループ企業やサービスの垣根を越えて、皆様が必要とするポイントカードの情報をお届けします‼  
        今のところは東京限定ですm(__)m" />
        <!-- Facebook用設定 -->
        <meta property="fb:app_id" content="586095538608500" />
        <!-- ※Twitter共通設定 -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@3vK7f9aIfDi" />
        <meta name="twitter:player" content="@3vK7f9aIfDi" />
        <link rel="stylesheet" rel="preload" as="style" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <script>.wrraper{overflow:hidden}nav{position:relative;padding:0;width:100%;height:700px;z-index:1}@media only screen and (max-width:420px){p,a,span,button{font-size:14px}::placeholder{font-size:13px}}.logo_link{position:relative;text-decoration:none;outline:none;overflow:hidden}.logo_link img{width:170px}@media only screen and (max-width:420px){.logo_link img{width:120px}}.fa-facebook{color:#1778f2}.fa-twitter{color:#1da1f2}.fa-line{color:#00b900}#black_out{height:700px;z-index:2;background-color:rgba(0,0,0,0.3)}#fixed_nav{background-color:#f2f28c;-webkit-box-shadow:0 3px 5px rgba(0,0,0,0.3);box-shadow:0 3px 5px rgba(0,0,0,0.3)}.animation-info{font-size:1.5em;color:#333;text-shadow:2px 2px 4px rgba(0,0,0,0.5);position:relative;padding-left:30%;white-space:nowrap;animation:marquee 14s linear infinite;letter-spacing:0.2em;font-family:"メイリオ","Meiryo","ヒラギノ角ゴ ProN W3","Hiragino Kaku Gothic ProN","ＭＳ Ｐゴシック","MS P Gothic",Verdana,Arial,Helvetica,sans-serif}@keyframes marquee{from{transform:translate(100%)}to{transform:translate(-200%)}}.board{top:5px;height:3.7em;background:#d9e5f2;box-shadow:0px 3px 8px 3px #ccc inset;overflow:hidden;border:2.5mm ridge rgba(242,242,140,1);display:none}@media only screen and (max-width:780px){.board{left:10%}#animation-info{top:10%;font-size:1.3em;padding-left:20px}}@media only screen and (max-width:420px){.board{height:3.7em}#animation-info{font-size:1em;padding-left:80px;padding-right:80px}.fa-facebook,.fa-line,.fa-twitter{font-size:1.2em}}@import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');.header-eria{height:300px;top:150px;font-family:'Josefin Sans',sans-serif;text-align:center;position:absolute}.header-eria:before{display:block;position:absolute;top:0;left:0;transform:translate(0,100%);content:''}.header-title{opacity:0;transform:translate(0,30px);letter-spacing:0.2em;line-height:1.6em;font-family:"メイリオ","Meiryo","ヒラギノ角ゴ ProN W3","Hiragino Kaku Gothic ProN","ＭＳ Ｐゴシック","MS P Gothic",Verdana,Arial,Helvetica,sans-serif}.header-title br{display:none}.description-top{opacity:0;transform:translate(0,30px)}@media only screen and (max-width:780px){.header-title{font-size:30px}.description-top{font-size:20px}.header-title br{display:inline-block}}@media only screen and (max-width:420px){.header-title{font-size:22px}.description-top{font-size:15px}.header-title br{display:inline-block}}@media only screen and (max-width:300px){.header-title{font-size:15px}.description-top{font-size:13px}}#content-main{background:#f9fdba;width:100%;z-index:2}#content_row{margin:auto 0}#lens{text-decoration:none;outline:none;display:inline;color:#2F4F4F;box-shadow:0px 3px 8px 3px #ccc inset}#lens::before{border-radius:50%;background:linear-gradient(to right,rgba(255,255,255,0) 0%,rgba(255,255,255,.3) 100%);transform:skewX(-25deg)}#content-container::before{display:block;position:absolute;top:0;left:0;opacity:0;transform:translate(0,100%)}#content-container{top:-200px;z-index:3;background:#decc54;padding-top:70px;padding-bottom:100px;border-radius:20px;filter:drop-shadow(0px 0px 10px rgba(0,0,0,0.6));opacity:0;transform:translate(0,40px)}#smart-dis{border-radius:3px;box-shadow:0px 3px 8px 3px #ccc inset}@media only screen and (max-width:420px){#content-container{padding-left:0;padding-right:0;background-color:#ecdc70}}h2{font-family:'Tsukushi A Round Gothic','Tsukushi B Round Gothic','YuGothic','Hiragino Sans'}.horizon{position:relative;height:3px;border-width:0;background-color:#00bcd4;background-image:-webkit-linear-gradient(135deg,#FD6585 10%,#0D25B9 100%);background-image:linear-gradient(135deg,#FD6585 10%,#0D25B9 100%)}#content-h2{margin-bottom:20px;font-size:3em;line-height:0.95em;letter-spacing:0.3em;font-weight:bold;text-shadow:0 0.03em 0.03em #FFAB91,0 0.03em 0.03em #000,0 0.03em 0.03em #FBE9E7}.form-content{margin-top:35px}.search-schedule{width:10rem;height:3.8rem;border-radius:3px}.search-shop{width:22rem;height:3.8rem}#search-form{margin-top:1.5rem}.kensaku-btn{width:10rem;height:3.6rem;margin-right:1rem;vertical-align:top;background-color:#e5ed5a;-webkit-box-shadow:0 3px 5px rgba(0,0,0,0.3);box-shadow:0 3px 5px rgba(0,0,0,0.3)}.keys-container{text-align:center;z-index:4}@media only screen and (max-width:780px){#content-h2{font-size:1.8em}.kensaku-btn{background-color:#dfd433}}@media only screen and (max-width:420px){#content-h2{font-size:1.5em}#search-form{width:90%}.top-card .row{justify-content:start}#search-schedule{height:60px}#search-shop{height:60px}#kensaku-main{height:60px}#search-form{margin-top:0}}@media only screen and (max-width:300px){#content-h2{font-size:1.2em}}#modal_frame{padding:60px 20px 50px;background:#eee55d;border-radius:5%;box-shadow:3px 3px 4px 3px #111111}.modal_conta{box-shadow:0px 5px 10px 3px #ccc inset}#modalShop,.modal-body{letter-spacing:0.2em;line-height:1.5em}#modalShop{font-size:24px}#modal-date{letter-spacing:0.2em}.c_name{font-size:14px}@media only screen and (max-width:420px){#modal_frame{padding:40px 10px 30px}#modal-date{letter-spacing:0.2em;font-size:13px}#modal_description{font-size:13px}#modalShop{font-size:20px}}@media only screen and (max-width:300px){#modal-date{padding-left:0}}@media only screen and (max-width:420px){#smart-dis{padding:0!important}}</script>
        <link rel="stylesheet" rel="preload" as="style" type="text/css" href="{{ asset('css/style.css') }}">
        @yield('profileCss')
        @yield('contactCss')
        @yield('disclaimerCss')
        @yield('policyCss')
        <meta http-equiv='x-dns-prefetch-control' content='on'>
        <link rel="preconnect dns-prefetch" href="//www.google.com/maps">
        <link rel="stylesheet" rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.css" rel="stylesheet" rel="preload" as="style" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" rel="preload" as="style">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
        <link rel="shortcut icon" href="{{ asset('img/site_logo5.png') }}">
        {{--Bootstrapを利用するサイトで、ajax等を使いたい場合は通常版を使う(もしくはbundle)。slimではなく--}}  
        <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.js" defer></script>
    </head>
    {{-- オーバーレイの下に潜り込まないようにmodalをbodyタグの前に設置 --}}
    <style>
        /* headerの画像表示 ( +zoomアニメーション ) */
        /* style.css内では表示できないので仕方なく、ここに定義している */
        .jumbotron{
            background: url("{{ asset('/img/nrd-D6Tu_L3chLE-unsplash.webp') }}") center no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        .jumbotron{
            transition:1s all;
        }
    </style>
    <body>
        <div class="wrraper">
            @yield('layouts.header')
            @yield('layouts.content')
            @yield('layouts.sidebar')
            @yield('layouts.footer')
        </div>
        <script defer src="{{ asset('js/header_animation.js') }}"></script>
        <script defer src="{{ asset('js/main_form_submit.js') }}"></script>
        @yield('resultScript')
    </body>
</html> 