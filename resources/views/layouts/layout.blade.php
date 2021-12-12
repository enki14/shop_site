<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158885830-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-158885830-1');
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <meta name="description" itemprop="description" content="@yeild('description')">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.css" rel="stylesheet" />
        @yield('resultCss')
        @yield('subResultCss')
        {{--Bootstrapを利用するサイトで、ajax等を使いたい場合は通常版を使う(もしくはbundle)。slimではなく--}}  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        {{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>--}}
        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>--}}
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.js" defer></script>
    </head>
    {{-- オーバーレイの下に潜り込まないようにmodalをbodyタグの前に設置 --}}
    <style>
        /* headerの画像表示 ( +zoomアニメーション ) */
        /* style.css内では表示できないので仕方なく、ここに定義している */
        .jumbotron{
            background: url("{{ asset('/img/nrd-D6Tu_L3chLE-unsplash.jpg') }}") center no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        .jumbotron:hover{
            transform:scale(1.1, 1.1);
            transition:1s all;
        }

        .jumbotron{
            transition:1s all;
        }
    </style>
    <body>
        <div class="wrraper">
            @yield('layouts.header')
            @yield('layouts.content')
            @yield('layouts.footer')
        </div>
        <script src="{{ asset('js/header_animation.js') }}"></script>
        <script src="{{ asset('js/main_form_submit.js') }}"></script>
        <script src="{{ asset('js/home_back.js')}}"></script>
        @yield('resultScript')
    </body>
</html> 