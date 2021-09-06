
    let myMap;
    let service;
    //LatLng()メソッドは効かないのでオブジェクトで渡す
    let latlng = {lat:35.73529673720239, lng:139.6281261687641};

    function initMap(){
        let initPos = latlng;
        let opts = {
            zoom: 16,
            center: initPos,
            mapTypeId: 'roadmap',
            scrollwheel: true,
            scaleControl: true,
            panControl: true
        };
        let myMap = new google.maps.Map(document.getElementById("map_convas"), opts);

        
        //検索条件を指定
        let request = {
            location: initPos,
            radius: 500,
            types: 'grocery_or_supermarket'
        };
        let service = new google.maps.places.PlacesService(myMap);
        service.search(request, Result_Places);
    }

    //検索結果を受け取る
    function Result_Places(results, status){
        if(status == google.maps.places.PlasesServiceStatus){
            for(let i = 0; i < results.length; i++){
                let place = results[i];
                createMarker({
                    text: place.name,
                    //ジオコーディングや距離の計算ができるようになる拡張機能。
                    position: place.geometry.location
                });
            }
        }
    }
    
    //入力キーワードと表示範囲を設定
    function SearchGo(){
        let initPos = latlng;
        let mapOptions = {
            center: initPos,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // #map_canva要素にMapクラスの新しいインスタンスを作成
        myMap = new google.maps.Map(document.getElementById("map_convas"), mapOptions);
        service = new google.maps.places.PlacesService(myMap);
        // input要素に入力されたキーワードを検索の条件に設定
        let myword = document.getElementById("search");
        let request = {
            query: myword.value,
            radius: 5000,
            location: myMap.getCenter()
        };
        service.textSearch(request, result_search);
    }

    //検索の結果を受け取る
    function result_search(results, status){
        console.log(results);
        //位置座標の設定
        let bounds = new google.maps.LatLngBounds();
        results.forEach(function(val, i){
            //MarkerImageの２～４引数でマーカーの大きさを指定する。maps.sizeで縦横の全体の大きさ
            let gpmarker = new google.maps.MarkerImage(results[i].icon, null, null, null, new google.maps.Size(25, 25));
            if(i == results.length - 1){
                //createMakerでマーカーを生成する
                createMarker({
                    position: latlng,
                    map: myMap
                });
            }else{
                createMarker({
                    icon: gpmarker,
                    position: results[i].geometry.location,
                    text: '<span style="font-weight: bold;">'　+ results[i].name + '</span><br>' + results[i].formatted_address,
                    map: myMap
                });
            }
            //位置座標の追加
            bounds.extend(results[i].geometry.location);
        });
        //指定した範囲に地図がフィットするようにする
        myMap.fitBounds(bounds);
    }

    //該当する位置にマーカーを表示
    function createMarker(options){
        //console.log(options);
        let marker = new google.maps.Marker(options);
        //情報ウィンドウはマーカーをクリックしたときに出るその地点の内容
        let infoWnd = new google.maps.InfoWindow();
        //情報ウィンドウの対象をテキストでセット
        infoWnd.setContent(options.text);
        google.maps.event.addListener(marker, 'click', function(){
            //第２引数はmarkerのインスタンスを指定
            infoWnd.open(myMap, marker);
        });
        return marker;
    }

    // ページ読み込み完了後、googleマップを表示
    // windowオブジェクトがloadイベントを発報したときにinitMap関数が発動
    //これだと「 google is not defined 」になってしまう　↓↓↓
    //google.maps.event.addDomListener(window, 'load', initMap);
    window.onload = function(){
        initMap();
    }


