@extends('layouts.layout')
@extends('layouts.footer')

@section('layouts.content')
<header>
    <div id="fixed_nav" class="fixed-top mb-5" style="height:70px;">
        <a href="{{ url('/') }}"><img src="{{ asset('/img/point-card_logo4.png') }}" class="m-2" alt="サイトのロゴ" aria-label="site logo" width="100px" /></a>
    </div>
    <div class="row my-5">
        <div class="col-9 text-right" style="top: 5rem; height:15rem;">
            <a href="{{ url('/') }}" class="history_back_2"><i class="fas fa-home fa-4x"></i></a>
        </div>
    </div>
</header>    
<div id="profile-eria" class="container-fluid" style="margin-bottom: 10rem">
    <div class="container-fluid my-5 profile">
        <div class="row d-flex justify-content-center">
            <div class="col-3 py-5">
                <img src="{{ asset('/img/point-card_logo2.png') }}" alt="サイトのロゴ" aria-label="site logo" 
                class="profile_logo" /> 
            </div>
            <div class="col-lg-5 my_profile">
                <div class="row my-5">
                    <div class="col-lg-7">
                        <h2 id="my-name" class=" d-flex justify-content-center py-3 pl-4">カリストの砂</h2>
                    </div>
                    <div class="col-4">
                        <img src="{{ asset('/img/IMG_0224.JPG') }}" class="europa d-flex justify-content-start rounded-circle mt-1"  width="40%" alt="europa" />
                    </div>
                </div>
                <p class="mb-5 text-break p_writing">
                    宮城県出身、東京在住。<br>
                    当ウェブサイトを一人で切り盛りする趣味程度のプログラマです。<br>
                    現在は介護に従事。好きな仕事ですが以前よりも勤務量を減らし、<br>
                    今は自分の最も望むことに信念を燃やしています。<br>
                    <br>
                    Today is the first day of the rest of your life ...<br>  
                    <br>
                    これは私の座右の銘です。
                </p>
            </div>
        </div>
    </div>


    <section id="one" class="container" style="margin-bottom: 5rem;">
        <div class="row justify-content-start" style="padding-left: 5rem;">
            <div class="col-1 pt-2">
                <img src="{{ asset( '/img/cart_3.png') }}" class="cart pl-3">
            </div>
            <div class="col-lg-6 d-flex justify-content-start bottom-line">
                <h3 class="mb-3 border-h3">ポイントカード情報局とは</h3>
            </div>
        </div>
        <p class="mt-4 mb-5 text-break writing">
            スーパーマーケットのポイントカードを対象にしたお得情報を発信するサイト。<br>
            ポイントカードと一口で言っても、電子決済を取り入れているものや、クレジットカード、<br>
            アプリ対応などさまざま。そういった種類別の情報や、<br>
            どこのお店で何が使えるのかといった詳細もできるだけ分かりやすく発信できるように努めます。<br>
            使っていただく皆様に便利だと思えるようなコンテンツを今後も増やしていくつもりです。<br>
            また現在掲載している情報は、東京都のスーパーに絞っていますが、<br>
            今後は近いうちに関東全域を目指したいなぁと思っています。<br>
        </p>
    </section>
    <section id="two" class="container" style="margin-bottom: 5rem;">
        <div class="row justify-content-start" style="padding-left: 5rem;">
            <div class="col-1 pt-2">
                <img src="{{ asset( '/img/cart_4.png') }}" class="cart pl-3">
            </div>
            <div class="col-lg-6 d-flex justify-content-start bottom-line">
                <h3 class="mb-3 border-h3">なぜポイントカードなのか</h3>
            </div>
        </div>
        <p class="mt-4 mb-5 text-break writing">
            単純にポイントが貯まることに魅力を感じているからです。<br>
            お店によってはポイントは付かないけど、そのカードがあることで割安で商品が手に入る。<br>
            それもまた大きな希望です。<br>
            もしかするとそんな家計の助けが、人の心をうれしくさせるかもしれません。<br>
            それなのに世の中にたくさんのスーパーがあるにもかかわらず、<br>
            ポイントカード自体は謎が多く、また世間に出回る情報も少ないと感じていました。<br>
            なので、こんなポータルサイトあったら便利だなと思い描きながら立ち上げたのが、<br>
            当サイトになります。
        </p>
    </section>
    <section id="three" class="container" style="margin-bottom: 5rem;">
        <div class="row justify-content-start" style="padding-left: 5rem;">
            <div class="col-1 pt-2">
                <img src="{{ asset( '/img/cart_3.png') }}" class="cart pl-3">
            </div>
            <div class="col-lg-6 d-flex justify-content-start bottom-line">
                <h3 class="mb-3 border-h3">このサイトの使い方</h3>
            </div>
        </div>
        <p class="mt-4 mb-5 text-break writing">
            店名や日程、地域など必要なワードに合わせて検索してください。<br>
            とくにポイントやセールに関係するイベントごとはもちろん、<br>
            ご近所にあるお店のカードの特徴を調べるのにもご活用ください。<br>
            <br>
            今はまだ情報を増やしている段階です。
        </p>
    </section>
</div>
@endsection