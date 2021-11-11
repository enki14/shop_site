let map;
let icon;
let markerData = [];
let marker = [];
let infoWindow = [];


function initMap(){
    console.log("initMap");

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
    }else{
        error();
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
    console.log(latlng);
    // 緯度・経度を変数に格納
    map.setCenter(latlng);
    
    let sendData = {
        "success_flg": true,
        "lat": now_lat,
        "lng": now_lng,
        "L_lat": now_lat,
        "L_lng": now_lng
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
                console.log(data);
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
        "L_lat": now_lat,
        "L_lng": now_lng
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
                console.log(data.location);
                markerData = data.location;
                setMarker(markerData);
            },
    
        });

    });
};


// マーカーや吹き出しの表示
function setMarker(markerData){
    console.log(markerData);

    for(let i = 0; i < markerData.length; i++){

        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);
        let latL = parseFloat(markerData[i]['L_lat']);
        let lngL = parseFloat(markerData[i]['L_lng']);


        let start = DateFormat(markerData[i]['event_start']);
        let end = DateFormat(markerData[i]['event_end']);
        let wave = waveDash(start, end);
        let col = colon(markerData[i]['sp_subtilte']);
        
        // nullやundefiendが表示されないようにパイプで空も設定している
        // 空値も変数にして含めないと、逆にほとんど表示されなくなる
        let event_start = start || '';
        let event_end = end || '';
        let store_name = markerData[i]['store_name'] || '';
        let sp_title = markerData[i]['sp_title'] || '';
        let sp_subtitle = markerData[i]['sp_subtilte'] || '';
        
        


        let markerStore = new google.maps.LatLng({ lat: latS, lng: lngS });
        // let markerLocation  = new google.maps.LatLng({ lat: latL, lng: lngL });
        
        icon = new google.maps.MarkerImage('./img/cart.png');

        marker[i] = new google.maps.Marker({
            position: markerStore,
            map: map,
            icon: icon
        });

        // marker[i] = new google.maps.Marker({
        //     position: markerLocation,
        //     map: map,
        // });

        infoWindow[i] = new google.maps.InfoWindow({
            content: markerData[i]['shop_name'] + store_name + '<br><br>'
            + event_start + wave + event_end + '<br><br>'
            + sp_title + col + sp_subtitle
        });
        console.log(infoWindow);
    
        markerEvent(i);
        
    }
}  

// event_startやevent_endの日付フォーマット
function DateFormat(date){
    if(date != null){
        let yyyy = date.slice(0, 4);
        let mm = date.slice(4, 6);
        let dd = date.slice(6, 8);
        return yyyy + '年' + mm + '月' + dd + '日';
    }
    
}

// infoWindowの波ダッシュ
function waveDash(start, end){
    if(start || end){
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


let openWindow;

function markerEvent(i){
    // マーカーをクリックしたときのイベント
    google.maps.event.addDomListener(marker[i], 'click', function(){
        // 開けたままにしておかない
        if(openWindow){
            openWindow.close();
        }
        // クリックすると吹き出しが開いて中身のデータが入っている
        infoWindow[i].open(map, marker[i]);
        openWindow = infoWindow[i];
    
    })
}




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
                title: "待ってくださーい‼　店名・または地域名を入力してくださいね～",
                placement: "top"
            });
            $('[data-toggle="tooltip"]').tooltip("show");
            $('[data-toggle="tooltip"]').on('click', function(){
                $(this).tooltip("hide");
            });
            return false;
        }

        let formData = {'namae': namae}
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
                console.log(data);
                let modal_body = $("#modalHtml");
                
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.L_lat + "," + row.L_lng;
                    console.log(zahyo);

                    let tag = $('<div>').append($('<a></a>', {href: '#', css:{color: '#ff4500'} }).addClass("local_link")
                    .text(row.prefectures_name + row.town_name + row.ss_town_name).data('value', zahyo));
                    // console.log(tag);
                    modal_body.append(tag);
                }
                
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.lat + "," + row.lng;
                    console.log(zahyo);

                    let tag = $('<div>').append($('<a></a>', {href: '#'}).addClass("store_link")
                    .text(row.shop_name + row.store_name).data('value', zahyo));

                    modal_body.append(tag);
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
 

    
