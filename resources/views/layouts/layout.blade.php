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