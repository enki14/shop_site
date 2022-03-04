@section('layouts.header')
<header>
    <div id="fixed_nav" class="fixed-top" style="height:70px;">
      <div class="container-fulid">
        <div class="row">
            <div class="col-3">
              <a href="{{ url('/') }}" class="logo_link"><img src="{{ asset('/img/site_logo7.png') }}" class="m-2" alt="ポイントカード情報局のロゴ" aria-label="site logo" /></a>
            </div>
            <div class="col-6 board">
              <p id="animation-info" class="animation-info font-weight-bold pt-1 ml-2">地図の下&nbsp;&nbsp;検索の一覧を出しました</p>
            </div>
          </div>
        </div>
      </div>
    <nav class="navbar navbar-expand-lg navbar-light jumbotron p-0 mb-0">
      <div id="black_out" class="container-fluid p-0">
        {{-- web.phpのname()でページを指定している --}}
        @if(Request::routeIs('result-2'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">「 話題・キーワード 」検索</h1>
            </div>
          </div>
        @elseif(Request::routeIs('result'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">「 お得を探す 」の検索</h1>
            </div>
          </div>
        @elseif(Request::routeIs('profile'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">ポイント王国のご紹介</h1>
            </div>
          </div>
        @elseif(Request::routeIs('policy'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">Privacy Policy</h1>
            </div>
          </div>
        @elseif(Request::routeIs('disclaimer'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">免責事項</h1>
            </div>
          </div>
        @elseif(Request::routeIs('page.contact_P') || Request::routeIs('page.confirm_P') || Request::routeIs('page.thanks_P'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="header-title text-light col-md-8 m-5 font-weight-bold">お問い合わせ</h1>
            </div>
          </div>
        @else
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center"> 
              <div class="col-9">
                <h1 class="header-title text-light mt-5 font-weight-bold">スーパーマーケットの<br>ポイントカード情報 ♪</h1>
                <h4 class="description-top text-light mt-5">今はまだ東京限定です (>_<)</h4>
              </div>
            </div>
          </div>
        @endif
        <div class="container d-flex justify-content-end mr-0">
          <div class="row">
            <div class="col-1">
            <a href="http://www.facebook.com/share.php?u=https://point-everyday.com/" 
            class="facebook" rel="nofollow" target="_blank">
                <i class="fab fa-facebook fa-2x mb-5"></i>
            </a>
            <a href="https://twitter.com/share?url=https://point-everyday.com/
            &text=スーパーマーケットのポイントカード情報を提供します【 東京版 】
            &hashtags=スーパー,ポイントカード,クレジット,お得情報,東京"
            class="twitter" rel="nofollow" target="_blank">
                <i class="fab fa-twitter fa-2x mb-5"></i>
            </a>
            <a href="https://line.me/R/ti/p/%40156hwqfo" 
            class="line_button" rel="nofollow" target="_blank">
                <i class="fab fa-line fa-2x mb-5"></i>
            </a>
            </div>
          </div>
        </div>  
      </div>
    </nav>
  
</header>
@endsection