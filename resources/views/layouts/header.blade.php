@section('layouts.header')
<header>
  <div id="fixed_nav" class="fixed-top" style="height:50px;"></div>
  <nav class="navbar navbar-expand-lg navbar-light jumbotron p-0 mb-0">
    {{-- web.phpのname()でページを指定している --}}
    @if(Request::routeIs('result-2'))
      <div class="header-eria brand w-100 d-block mt-5">
        <div class="row justify-content-center">
          <h1 class="midashi-top col-md-8 m-5">「 話題・キーワード 」検索</h1>
        </div>
      </div>
    @elseif(Request::routeIs('result'))
      <div class="header-eria brand w-100 d-block mt-5">
        <div class="row justify-content-center">
          <h1 class="midashi-top col-md-8 m-5">「 お得を探す 」の検索</h1>
        </div>
      </div>
    @else
      <div class="header-eria brand w-100 d-block">
        <div class="row justify-content-center">  
          <h1 class="midashi-top col-md-8 m-5">セールポイントスーパーマーケット</h1>
        </div>
        <div class="row justify-content-center">
          <h4 class="description-top col-md-8 mt-3">スーパーマーケットのポイント情報や、セール情報が検索できるサイトです</h4>
        </div>
      </div>
    @endif  
  </nav>
</header>
@endsection