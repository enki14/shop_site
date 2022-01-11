@section('layouts.header')
<header>
    <div id="fixed_nav" class="fixed-top" style="height:70px;">
      <div class="row">
        <div class="col-2">
          <a href="{{ url('/') }}" class="logo_link"><img src="{{ asset('/img/point-card_logo4.png') }}" class="m-2" alt="サイトのロゴ" aria-label="site logo" width="100px" /></a>
        </div>
        <div class="col-7">
          <p class="animation-info">
            地図下に近隣の店舗情報がございます
          </p>
        </div>
        <div class="col-3">
          {{-- SNS関係のシェアボタン --}}
          <div class="d-flex justify-content-end pt-3"> 
              <a href="http://www.facebook.com/share.php?u=https://point-everyday.com/" 
              class="facebook mr-3" rel="nofollow" target="_blank">
                  <i class="fab fa-facebook fa-2x"></i>
              </a>
              <a href="https://twitter.com/share?url=https://point-everyday.com/
              &text=スーパーマーケットのポイントカード情報を提供します【 東京版 】
              &hashtags=スーパー,ポイントカード,クレジット,お得情報,東京"
              class="twitter mr-3" rel="nofollow" target="_blank">
                  <i class="fab fa-twitter fa-2x"></i>
              </a>
              <a href="https://social-plugins.line.me/lineit/share?url=https://point-everyday.com/" 
              class="line_button mr-3" rel="nofollow" target="_blank">
                  <i class="fab fa-line fa-2x"></i>
              </a>
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
              <h1 class="midashi-top col-md-8 m-5">「 話題・キーワード 」検索</h1>
            </div>
          </div>
        @elseif(Request::routeIs('result'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="midashi-top col-md-8 m-5">「 お得を探す 」の検索</h1>
            </div>
          </div>
        @elseif(Request::routeIs('policy'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="midashi-top col-md-8 m-5">Privacy Policy</h1>
            </div>
          </div>
          @elseif(Request::routeIs('page.contact_P') || Request::routeIs('page.confirm_P') || Request::routeIs('page.thanks_P'))
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center">
              <h1 class="midashi-top col-md-8 m-5">お問い合わせ</h1>
            </div>
          </div>
        @else
          <div class="header-eria brand w-100 d-block">
            <div class="row justify-content-center"> 
              <div class="col-9">
                <h1 class="header-title text-light mt-5 font-weight-bold">スーパーマーケットのポイントカード情報 ♪</h1>
                <h4 class="description-top text-light mt-5">今はまだ東京限定です (>_<)</h4>
              </div>
            </div>
            
          </div>
        @endif  
      </div>
    </nav>
  
</header>
@endsection