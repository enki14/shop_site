@extends('layouts.layout')
@extends('layouts.footer')

@section('layouts.content')
<header>
    <div id="fixed_nav" class="fixed-top mb-5" style="height:70px;">
        <a href="{{ url('/') }}"><img src="{{ asset('/img/point-card_logo4.png') }}" class="m-2" alt="サイトのロゴ" aria-label="site logo" width="100px" /></a>
    </div>
    <div class="row mb-5">
        <div class="col-10 text-right" style="top: 100px; height:200px;">
            <a href="{{ url('/') }}">
                <img src="{{ asset('/img/home.png') }}" 
                class="text-right" width="15%" alt="homeのリンク" />
            </a>
        </div>
    </div>
</header>
    
    
        
<div class="container-fluid" style="margin-bottom: 200px">
    <div class="container my-5">
        <div class="row flex-center">
            <div class="col-5 py-5">
                <img src="{{ asset('/img/point-card_logo2.png') }}" alt="サイトのロゴ" aria-label="site logo" 
                class="profile_logo" /> 
            </div>
            <div class="col-5">
                <div class="row my-5">
                    <div class="col-8">
                        <h2 id="my-name" class="py-3 pl-4">カリストの砂</h2>
                    </div>
                    <div class="col-4">
                        <img src="{{ asset('/img/IMG_0224.JPG') }}" class="rounded-circle"  width="50%" alt="europa" />
                    </div>
                </div>
                <p>
                    現在は介護職を１０年ほど勤務。
                    プライベートは音楽を聴いたり本を読んだりと、普通ですが、一通りの趣味を嗜んでおります。
                    下手の横好きで多くの時間を費やしてまいりました。
                    そしてこのサイトも、そんな下手な横好きでド素人のゼロから作成したサイトになります。
                </p>
            </div>
        </div>
    </div>
    <section class="container my-5">
        <div class="row">
            <div class="col-1">
                <img src="{{ asset( '/img/cart_3.png') }}" class="cart pl-3 pt-1">
            </div>
            <div class="col-5">
                <h3 class="mb-5 border-h3">どうしてポイントカード？</h3>
            </div>
        </div>
        <p class="mb-5">
            このサイトはスーパーマーケットのポイントカードに焦点を当てています。
            「 ポイント５倍 」「 還元！ 」「 ○○セール 」など
            一消費者である私自身がそういった言葉に魅力を感じ、日々のお店選びを気にしております。
            しかし同時に、
            そこには特にポイントカードの存在が大きく、
            そのような経験から、どうせならポイントカードを主役にしたポータルサイトがあればいいなぁと思い作り始めたのです。
        </p>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-1">
                <img src="{{ asset( '/img/cart_4.png') }}" class="cart pl-3 pt-1">
            </div>
            <div class="col-5">
                <h3 class="mb-5 border-h3">ポイントカード情報局ってなに？</h3>
            </div>
        </div>
        <p class="mb-5">
            上にも記した通り、スーパーマーケットのポイントカードを対象にしたお得情報を発信するサイトです。
            お店によってはポイントカードを採用せず、クレジットカードや電子決済を利用した還元サービスを取り入れている企業様もございます。
            ですので、それらの情報も併せて皆様に有益な情報をお届けできればと思います。
            そしてスーパーマーケット全般という視野を持つために、企業体の垣根は関係しません。
            とにかくユーザー様がほしい情報に焦点を当てて分かりやすく正確に伝えるよう努めます。
            その分かりやすさというのは、例えば「 近所のスーパーのお得な日はいつか 」とか 「 この近所で一番安く買い物ができるスーパーはどこか 」
            などといった日々のありがちな疑問と願望を、一目見て解決できるようなものです。
            現在掲載している情報は東京都のスーパーに絞っておりますが、今後はすぐにでも関東全域を目指したいと思っています。
            そして日本全体を網羅することが、皆様の需要に答えるための礼儀のようなものだと思っています。
        </p>
    </section>
    
</div>
@endsection