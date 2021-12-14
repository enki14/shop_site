let map;
let icon;
let markerData = [];
let marker = [];
let infoWindow = [];


function initMap(){

    // 初期値座標(文字列→浮動小数点)
    let init_lat = parseFloat($('#h_lat').val());
    let init_lng = parseFloat($('#h_lng').val());
    

    let convas = document.getElementById('map_convas');
    let centerp = {
        lat: init_lat,
        lng: init_lng
    };

    // マップ表示
    map = new google.maps.Map(convas, {
        center: centerp,
        zoom: 16
    });    

};

    
$(function() {
    let request_flag = $('#h_request_flag').val();
    // リクエストパラメータに座標が渡されていなかったら
    if(request_flag == false){
        if (navigator.geolocation) {
            let options = {
                // enableHighAccuracyは、GPSの精度でtrueかfalseをセットする
                // trueだと精度が高くなる
                enableHighAccuracy: true,
                //timeoutは、タイムアウト時間でミリ秒単位
                timeout: 1000,
                // maximumAgeは、キャッシュ有効時間でミリ秒で表す
                // 0の場合、常に最新の情報を取得する
                maximumAge: 0
            }
        
            // 現在地を取得
            navigator.geolocation.getCurrentPosition(success, error, options);
        
        }
        // 現在位置を取得できなかった場合の値はShopsiteController@indexに記載あり
    }else{
        error();
        window.addEventListener('load', function(){
            $("#fixed_nav").children("p").show();
           
        });
    }
    
});
// 取得成功した場合
function success(position) {
    console.log("success");
    let crd = position.coords;
    let now_lat = crd.latitude;
    let now_lng = crd.longitude;

    /************ ★ 現在位置で地図の中心位置を再設定 ★ ************/

    let latlng = new google.maps.LatLng(now_lat, now_lng);
    // 緯度・経度を変数に格納
    map.setCenter(latlng);
    
    let sendData = {
        "success_flg": true,
        "lat": now_lat,
        "lng": now_lng,
    };
    
     // ajaxセットアップ
     $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $(function() {
        // 初期表示用のajax（常に店舗と地域を表示させるためのもの）
        $.ajax({
            type:"POST",
            url: "map_data",
            data: sendData,
            dataType: 'json',
            success: function(data){
                markerData = data.location;
                setMarker(markerData);
            },

        });

    });
    
};

// 現在位置取得失敗した場合
function error(error) {
    console.log("error");

    // デフォルト値の座標
    let now_lat = parseFloat($('#h_lat').val());
    let now_lng = parseFloat($('#h_lng').val());

    let sendData = {
        "success_flg": true,
        "lat": now_lat,
        "lng": now_lng,
    };

     // ajaxセットアップ
     $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    $(function(){
        $.ajax({
            type:"POST",
            url: "map_data",
            data: sendData,
            dataType: 'json',
            success: function(data){
                markerData = data.location;
                setMarker(markerData);
            },
    
        });

    });
};


// マーカーや吹き出しの表示
function setMarker(markerData){
    // console.log(markerData);

    for(let i = 0; i < markerData.length; i++){
        console.log(markerData[i]['sp_title']);
        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);

        let start = DateFormat(markerData[i]['event_start']);
        let end = DateFormat(markerData[i]['event_end']);
        let wave = waveDash(start, end);
        let col = colon(markerData[i]['sp_subtilte']);
        
        // nullやundefiendが表示されないようにパイプで空も設定している
        // 空値も変数にして含めないと、逆にほとんど表示されなくなる
        let event_start = start || '';
        let event_end = end || ''; 
        let shop = markerData[i]['shop_name'] || '';
        let store = markerData[i]['store_name'] || '';
        let sp_title = markerData[i]['sp_title'] || '';
        let shop_id = markerData[i]['shop_id'];


        let markerStore = new google.maps.LatLng({ lat: latS, lng: lngS });
        
        // icon = new google.maps.MarkerImage('http://maps.google.co.jp/mapfiles/ms/icons/ltblue-dot.png');

        marker[i] = new google.maps.Marker({
            position: markerStore,
            map: map,
            path: google.maps.SymbolPath.CIRCLE,
            animation: google.maps.Animation.DROP
            // icon: icon
        });

        console.log(marker[i])
        
        let request_flag = $('#h_request_flag').val();
        // リクエストパラメータに座標が渡されていなかったら
        if(request_flag == false){
            marker[i].setMap(null);
 
        }else{
            marker[i].setMap(map);
            seachMarker(i);
            clickMarker(i);
        }

        var contentString = 
        "<div class='info_win'>" +
            "<h5 class='firstHeading'>" + shop + store + "</h5>"+
            "<div class='info_Content'>" +
                "<p>"+ event_start + wave + event_end + "</p>" +
                "<p>" + sp_title + "</p>" +
                eventExist(markerData[i]['sp_url']) + 
            "</div>" +
        "</div>";     

        infoWindow[i] = new google.maps.InfoWindow({
            content: contentString
        });

    }
}  

// event_startやevent_endの日付フォーマット
function DateFormat(date){
    if(date){
        let yyyy = date.slice(0, 4);
        let mm = date.slice(4, 6);
        let dd = date.slice(6, 8);
        return yyyy + '年' + mm + '月' + dd + '日';
    }
    
}

// infoWindowの波ダッシュ
function waveDash(start, end){
    if(start & end){
        return ' ~ ';
    }else{
        return '';
    }
}

// infoWindowのコロン
function colon(title){
    if(!title){
        return '';
    }else{
        return ': ';
    }
}

// functionの中にfunctionを書かないように注意！
// sp_urlの値があるかどうかの判定
function eventExist(sp_url){
    if(sp_url){
        // ダブルコーテーションで文字列を囲い、シングルで属性値を囲う
        // この場合、sp_urlが文字列とつながるようにダブルをシングルで覆う
        // returnも忘れずに
        return "<small><a href='"+ sp_url +"' target='_blank'>詳しくはこちら</a></small>";
    }else{
        return '';
    }
}


let openWindow;

function seachMarker(i){
    // マップ検索直後のイベント
    google.maps.event.addListener(marker[i],  "animation_changed", function(){
        // ここでinfoWindowのスタイリング
        var iwOuter = $('.gm-style-iw');
        let custumIw = iwOuter.parent().addClass('custum-iw');
        let iwCloseBtn = custumIw.next();

        // 閉じるボタンにcss付与
        iwCloseBtn.addClass("closebtn");
        // fontawesomeのcloseボタン追加
        iwCloseBtn.prepend('<i class="fas fa-times-circle"></i>');

        // 開けたままにしておかない
        if(openWindow){
            openWindow.close();
        }
        // クリックすると吹き出しが開いて中身のデータが入っている
        infoWindow[i].open(map, marker[i]);
        openWindow = infoWindow[i];
    
    })

};

function clickMarker(i){
    // マーカーをクリックしたときのイベント
    google.maps.event.addListener(marker[i], "click", function(){
        // ここでinfoWindowのスタイリング
        var iwOuter = $('.gm-style-iw');
        let custumIw = iwOuter.parent().addClass('custum-iw');
        let iwCloseBtn = custumIw.next();

        // 閉じるボタンにcss付与
        iwCloseBtn.addClass("closebtn");
        // fontawesomeのcloseボタン追加
        iwCloseBtn.prepend('<i class="fas fa-times-circle"></i>');

        // 開けたままにしておかない
        if(openWindow){
            openWindow.close();
        }
        // クリックすると吹き出しが開いて中身のデータが入っている
        infoWindow[i].open(map, marker[i]);
        openWindow = infoWindow[i];
    
    })

};

// 検索クリックからのmodal表示
$(function(){
    

    $('#map_search').on('keydown', function(e){
        if(e.key === "Enter"){
            $("#kensaku-map").click();
        }
    });

    $("#kensaku-map").on('click', function(e){
        
        
        let namae = $("#map_search").val();

        if(namae == ''){
            $('[data-toggle="tooltip"]')
            .tooltip({
                trigger: "manual",
                title: "店名・または地域名を入力してください m(__)m",
                placement: "top"
            });
            $('[data-toggle="tooltip"]').tooltip("show");
            $('[data-toggle="tooltip"]').on('click', function(){
                $(this).tooltip("hide");
            });
            return false;
        }

        let now_lat = parseFloat($('#h_lat').val());
        let now_lng = parseFloat($('#h_lng').val());
        let formData = {
            'namae': namae,
            'lat': now_lat,
            'lng': now_lng
        }
        const url = "map_modal";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();


        $('#modalHtml').empty();
        // inputテキストからのリクエスト
        $.ajax({
            type:"GET",
            url: url,
            data: formData,
            dataType: 'json',
            success: function(data){
                let modal_body = $("#modalHtml");
                
                // listのlength分として計算する必要がある
                if(data.list.length == 0){
                    let comment = $('<p></p>').text('該当する店舗はありませんでした...');
                    modal_body.append(comment);
                }else{
                    for(let i = 0; i < data.list.length; i++){
                        
                        let row = data.list[i];
                        let zahyo = row.lat + "," + row.lng;

                        let parent = $('<div>');
                        parent.append($('<a></a>', {href: '#'}).addClass("store_link")
                        .text(row.shop_name + row.store_name).data('value', zahyo));
                        parent.append($('<p></p>').text(row.store_address)); 
                        modal_body.append(parent);
            
                    }
                }
                
                
                $("#list_modal").modal('show');

            },
            error: function(data){
                alert("例外が発生しました");
                console.log('Error:', data);
            }
        });
        
    });    

});


// modalクリックした直後にリクエストパラメータを渡す
$(function(){ 

    // modalクリック
    $(document).on('click', '.store_link', function(){
        $("#list_modal").modal("hide");
        let latlng = $(this).data('value');

        // コントローラー側に送るリクエストパラメーター
        let lat = latlng.split(',')[0];
        let lng = latlng.split(',')[1]; 

        location.href = "?lat=" + lat + "&lng=" + lng + '&s_flag=store#maps-container';
    });

});