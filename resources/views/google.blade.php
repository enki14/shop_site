<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>google maps api</title>
</head>
<body>
    <div class="m-5">
        <input tyep="text" size="55" id="search" value="店名で検索" />
        <input type="button" size="55" value="検索" onClick="SearchGo()" />
        <div id="map_convas" style="width:750px; height:562px;"></div>
    </div>
    <div class="m-5">
        <div id="target" style="width:750px; height:562px;"></div>
        <div id="sidebar"></div>
        <div class="container py-4">
            <h4 class="py-3 pl-5">絞り込み条件</h4>
            <p class="py-3 pl-5">○○駅付近</p>
            <div class="card mx-auto rounded-3" style="width: 50.5rem;">
                <div class="card-body shadow">
                    @csrf
                    <div class="row">
                        <h5 class="col-md-12 text-center font-weight-bold mt-2">○○○○店</h5>
                    </div>
                    <div class="row g-0 mx-auto">
                        <div class="col-md-4">
                            <img src="#" alt="テスト画像">
                        </div>
                        <div class="col-md-8"> 
                            <p class="card-text">お支払方法：○○××▢▢</p>
                            <p class="card-text">期間：◇▼✖◇▼✖</p>
                                <h5 class="card-title">○○○○</h5>
                                <p class="card-text">
                                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                                </p>
                            <p class="card-text"><a href="#"><small class="text-muted">詳しくはこちら</small></a></p>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <!-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBAEY8ljaq0u8SXzKNz_M2GGKGahHJYpAo&callback=initMap&libraries=places" async defer></script>
    <script src="{{ asset('js/map_db.js') }}"></script>

</body>
</html>



