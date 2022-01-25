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
        zoom: 15
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
        // リクエストがあったときに右から左に流れるやつ
        window.addEventListener('load', function(){
            let a_info = $(".animation-info");
            let props = {
                "display": "inline-block",
            }
            a_info.fadeIn(1000).css(props);
            

           
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
        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);

        let sh_start = markerData[i]['sh_start'] || '';
        let sh_end = markerData[i]['sh_end'] || '';
        let st_start = markerData[i]['st_start'] || '';
        let st_end = markerData[i]['st_end'] || '';
        let sh_day = DateFormat(sh_start, sh_end);
        let st_day = DateFormat(st_start, st_end);
        
        // nullやundefiendが表示されないようにパイプで空も設定している
        // 空値も変数にして含めないと、逆にほとんど表示されなくなる
        let shop = markerData[i]['shop_name'] || '';
        let store = markerData[i]['store_name'] || '';
        let sh_title = markerData[i]['sh_title'] || '';
        let st_title = markerData[i]['st_title'] || '';
        let sh_subtitle = markerData[i]['sh_subtitle'] || '';
        let st_subtitle = markerData[i]['st_subtitle'] || '';
        let spsh_url = markerData[i]['spsh_url'] || '';
        let spst_url = markerData[i]['spst_url'] || '';
        let card_name = markerData[i]['card_name'] || '';
        let link = markerData[i]['link'] || '';

        let markerStore = new google.maps.LatLng({ lat: latS, lng: lngS });


        marker[i] = new google.maps.Marker({
            position: markerStore,
            map: map,
            path: google.maps.SymbolPath.CIRCLE,
            animation: google.maps.Animation.DROP
            // icon: icon
        });
        
        let request_flag = $('#h_request_flag').val();
        // リクエストパラメータに座標が渡されていなかったら
        if(request_flag == false){
            marker[i].setMap(null);
 
        }else{
            marker[i].setMap(map);
            clickMarker(i);
            // seachMarker(i);
        }

        var contentString = 
        "<div class='info_win'>" +
            "<h5 class='firstHeading'>" + shop + store + "</h5>"+
            "<div class='info_Content'>" +
                spTitle(sh_title, st_title, spsh_url, spst_url) + 
                spSubtitle(sh_subtitle, st_subtitle) +
                eventExist(markerData[i]['sp_url']) + 
                eventDay(sh_day, st_day) +
                "<div class='container mt-5'>" + 
                    "<div class='row'>" +
                        "<small class='cocode col-8 d-flex justify-content-start'><b>ココで使えるカード&ensp;<i class='fas fa-angle-double-right'>&ensp;</i></small>" + 
                        "<small class='col-4'>" + cardOutput(card_name, link) + "</small>" +
                    "</div>"
            "</div>" +
        "</div>";     

        infoWindow[i] = new google.maps.InfoWindow({
            content: contentString
        });

    }
}  


// event_startやevent_endの日付フォーマット
function DateFormat(start, end){
    let yS = start.slice(0, 4);
    let mS = start.slice(4, 6);
    let dS = start.slice(6, 8);
    let mE = end.slice(4, 6);
    let dE = end.slice(6, 8);
    if(start && end){
        return yS + '年' + mS + '月' + dS + '日 ~ ' + mE + '月' + dE + '日';
    }else if(start && !end){
        return yS + '年' + mS + '月' + dS + '日';
    }else{
        return '';
    }
    
}

// DateFormat関数を受けて、ショップ情報の日付かストア情報の日付かを判断している
function eventDay(sh_day, st_day){
    if(sh_day){
        return "<small class='modal-mDate'>" + sh_day + "</small>";
    }else{
        return "<small class='modal-mDate'>" + st_day + "</small>";
    }

}


// jsで出力する際に、カラムはショップ情報とストア情報とそれぞれ別名義にする必要があった
// 因みにbladeに渡した際はsqlには as で別名義にしなくても表示された
function spTitle(sh_title, st_title, spsh_url, spst_url){
    if(sh_title){
        if(spsh_url){
            return "<a href='" + spsh_url + "' target='_blank'><p class='spTitle font-weight-bold'>" + sh_title + "</p></a>";
        }else{
            return "<p class='spTitle font-weight-bold'>" + sh_title + "</p>";
        }    
    }else if(st_title){
        if(spst_url){
            return "<a href='" + spst_url + "' target='_blank'><p class='spTitle font-weight-bold'>" + st_title + "</p></a>";
        }else{
            return "<p class='spTitle font-weight-bold'>" + st_title + "</p>";
        }
    }else{
        return "";
    }

}


function spSubtitle(sh_subtitle, st_subtitle){
    if(sh_subtitle){
        return "<p class='spSubtitle'>" + sh_subtitle + "</p>";
    }else if(st_subtitle){
        return "<p class='spSubtitle'>" + st_subtitle + "</p>";
    }else{
        return "";
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

// カード情報を吹き出しの中で出力
function cardOutput(card_name, link){
    // カンマ区切りの複数のカード情報があるかないか
    if(card_name.match(/,/) && link.match(/,/)){
        card_name = card_name.split(',');
        // console.log(card_name);
        link = link.split(',');
        console.log(link);
        for(let i = 0; i < link.length; i++){
            return "<a href="+ link[i] +" target='_blank' class='pl-2'>" + card_name[i] + "</a>";
        }
    }else{
        return "<a href="+ link +" target='_blank' class='pl-2'>" + card_name + "</a>";
    }

}


let openWindow;

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