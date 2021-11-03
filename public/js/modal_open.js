let lat = "";
let lng = "";

function initMap(){
    
    return new Promise(function(resolve, reject) {
        setTimeout(function() {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    console.log("getCurrentPosition success!!");
                    lat = position.coords.latitude,
                    lng = position.coords.longitude
                    resolve();

                },
                (error) => {
                    console.log("getCurrentPosition error!!");
                    lat = "35.704452869003504",
                    lng = "139.61842598482167"
                    // 処理完了
                    resolve();
            });
        }, 100);
    });
}


initMap().then(function(){
    console.log("getNowPosition");
    console.log(lat + ":" + lng);
    let pos = {
        lat: lat,
        lng: lng
    }
    // 地図配置場所
    let convas = document.getElementById('map_convas');
    // 地図配置用件
    let map = new google.maps.Map(convas, {
        center: pos,
        zoom: 16
    });
    map.setCenter(pos);

    // ajax記載
    // 店舗用と地域用の座標を取得できるようにセット
    // 初期の座標でajaxに送信して、コントローラー側で改めて店舗用と地域用を取得している
    let latlng = {'lat': lat, 'lng': lng, 'L_lat': lat, 'L_lng': lng };
    console.log(latlng);
    const url = "map_data";
    // 初期表示用のajax（常に店舗と地域を表示させるためのもの）
    $.ajax({
        type:"GET",
        url: url,
        data: latlng,
        dataType: 'json',
        success: function(data){
            markerData = data.location;
            console.log(data);
            setMarker(markerData);
            
        },
        error: function(data){
            alert("駄目です");
            console.log('Error:', data);
        }
    });
    console.log("ajax…");
});


let map;
let icon;
let marker = [];
let infoWindow = [];

// マーカーや吹き出しの表示
function setMarker(markerData){

    for(let i = 0; i < markerData.length; i++){
        console.log(markerData);
        console.log(map);
        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);
        let latL = parseFloat(markerData[i]['L_lat']);
        let lngL = parseFloat(markerData[i]['L_lng']);
        console.log(latS);
        console.log(lngS);
        // コーテーションで囲わなくて良いのか

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
        
        


        let markerStore = new google.maps.LatLng({ lat:latS, lng:lngS });
        console.log(markerStore);
        let markerLocation  = new google.maps.LatLng({ lat: latL, lng: lngL });
        
        icon = new google.maps.MarkerImage('./img/cart.png');

        console.log(icon);
        marker[i] = new google.maps.Marker({
            position: markerStore,
            map: map,
            icon: icon
        });
        console.log(marker[i]);
        console.log(map);
        marker[i] = new google.maps.Marker({
            position: markerLocation,
            map: map,
        });

        infoWindow[i] = new google.maps.InfoWindow({
            content: markerData[i]['shop_name'] + store_name + '<br><br>'
            + event_start + wave + event_end + '<br><br>'
            + sp_title + col + sp_subtitle
        });
        // console.log(infoWindow);
    
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
    google.maps.event.addDomListener(marker[i], 'click', function(){
        if(openWindow){
            openWindow.close();
        }
    
        infoWindow[i].open(map, marker[i]);
        openWindow = infoWindow[i];
    
    })
}




$(function(){

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
                    // console.log(zahyo);

                    let tag = $('<div>').append($('<a></a>', {href: '#', css:{color: '#ff4500'} }).addClass("local_link")
                    .text(row.prefectures_name + row.town_name + row.ss_town_name).data('value', zahyo));
                    // console.log(tag);
                    modal_body.append(tag);
                }
                
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.lat + "," + row.lng;

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



// function initMap(){
//     let convas = document.getElementById('map_convas');
//     let center_lat = '';
//     let center_lng = '';
//     let h_lat = parseFloat($('#h_lat').val());
//     let h_lng = parseFloat($('#h_lng').val());

//     let map = new google.maps.Map(convas, {
//         center: {lat:35.704452869003504, lng:139.61842598482167},
//         zoom: 16
//     });

    
//     if (navigator.geolocation) {
//         // 現在地を取得
//         navigator.geolocation.getCurrentPosition(
//             // 取得成功した場合

//             function (position) {
//                 // console.log(position);               
//                 let pos = {
//                     lat: position.coords.latitude,
//                     lng: position.coords.longitude
//                 } 
//                 map.setCenter(pos);
                
                
//             },
//             // 取得失敗した場合
//             function(error) {
//             // エラーメッセージを表示
//                 switch(error.code) {
//                     case 1: // PERMISSION_DENIED
//                     alert("位置情報の利用が許可されていません");
//                     break;
//                     case 2: // POSITION_UNAVAILABLE
//                     alert("現在位置が取得できませんでした");
//                     break;
//                     case 3: // TIMEOUT
//                     alert("タイムアウトになりました");
//                     break;
//                     default:
//                     alert("その他のエラー(エラーコード:"+error.code+")");
//                     break;
//                 }
//             }
//         );
//     // Geolocation APIに対応していない
//     } else {
//         alert("この端末では位置情報が取得できません");
//     }
        
//     console.log(h_lat);
//     console.log(h_lng);
//     if(isNaN(h_lat) == false && isNaN(h_lng) == false){
//         console.log('リクエストからの検索');
        
//         center_lat = h_lat;
//         center_lng = h_lng;
//     }

//     // 店舗用と地域用の座標を取得できるようにセット
//     // 初期の座標でajaxに送信して、コントローラー側で改めて店舗用と地域用を取得している
//     let latlng = {'lat': center_lat, 'lng': center_lng, 'L_lat': center_lat, 'L_lng': center_lng };
//     console.log(latlng);
//     const url = "map_data";
//     // 初期表示用のajax（常に店舗と地域を表示させるためのもの）
//     $.ajax({
//         type:"GET",
//         url: url,
//         data: latlng,
//         dataType: 'json',
//         success: function(data){
//             markerData = data.location;
//             console.log(markerData);
//             setMarker(markerData);
            
//         },
//         error: function(data){
//             alert("駄目です");
//             console.log('Error:', data);
//         }
//     });
    
// };



// let map;
// let icon;
// let marker = [];
// let infoWindow = [];

// // マーカーや吹き出しの表示
// function setMarker(markerData){

//     for(let i = 0; i < markerData.length; i++){
//         console.log(markerData);

//         let latS = parseFloat(markerData[i]['lat']);
//         let lngS = parseFloat(markerData[i]['lng']);
//         let latL = parseFloat(markerData[i]['L_lat']);
//         let lngL = parseFloat(markerData[i]['L_lng']);
//         console.log(latS);
//         console.log(lngS);
//         // コーテーションで囲わなくて良いのか

//         let start = DateFormat(markerData[i]['event_start']);
//         let end = DateFormat(markerData[i]['event_end']);
//         let wave = waveDash(start, end);
//         let col = colon(markerData[i]['sp_subtilte']);
        
//         // nullやundefiendが表示されないようにパイプで空も設定している
//         // 空値も変数にして含めないと、逆にほとんど表示されなくなる
//         let event_start = start || '';
//         let event_end = end || '';
//         let store_name = markerData[i]['store_name'] || '';
//         let sp_title = markerData[i]['sp_title'] || '';
//         let sp_subtitle = markerData[i]['sp_subtilte'] || '';
        
        


//         let markerStore = new google.maps.LatLng({ 'lat':latS, 'lng':lngS });
//         console.log(markerStore);
//         let markerLocation  = new google.maps.LatLng({ lat: latL, lng: lngL });
        
//         icon = new google.maps.MarkerImage('./img/cart.png');

//         console.log(icon);
//         marker[i] = new google.maps.Marker({
//             position: markerStore,
//             map: map,
//             icon: icon
//         });
//         console.log(marker[i]);
//         console.log(map);
//         marker[i] = new google.maps.Marker({
//             position: markerLocation,
//             map: map,
//         });

//         infoWindow[i] = new google.maps.InfoWindow({
//             content: markerData[i]['shop_name'] + store_name + '<br><br>'
//             + event_start + wave + event_end + '<br><br>'
//             + sp_title + col + sp_subtitle
//         });
//         // console.log(infoWindow);
    
//         markerEvent(i);
        
//     }
// }  

// // event_startやevent_endの日付フォーマット
// function DateFormat(date){
//     if(date != null){
//         let yyyy = date.slice(0, 4);
//         let mm = date.slice(4, 6);
//         let dd = date.slice(6, 8);
//         return yyyy + '年' + mm + '月' + dd + '日';
//     }
    
// }

// // infoWindowの波ダッシュ
// function waveDash(start, end){
//     if(start || end){
//         return ' ~ ';
//     }else{
//         return '';
//     }
// }

// // infoWindowのコロン
// function colon(title){
//     if(!title){
//         return '';
//     }else{
//         return ': ';
//     }
// }


// let openWindow;

// function markerEvent(i){
//     google.maps.event.addDomListener(marker[i], 'click', function(){
//         if(openWindow){
//             openWindow.close();
//         }
    
//         infoWindow[i].open(map, marker[i]);
//         openWindow = infoWindow[i];
    
//     })
// }




// $(function(){

//     $("#kensaku-map").on('click', function(e){
        
//         let namae = $("#map_search").val();

//         if(namae == ''){
//             $('[data-toggle="tooltip"]')
//             .tooltip({
//                 trigger: "manual",
//                 title: "待ってくださーい‼　店名・または地域名を入力してくださいね～",
//                 placement: "top"
//             });
//             $('[data-toggle="tooltip"]').tooltip("show");
//             $('[data-toggle="tooltip"]').on('click', function(){
//                 $(this).tooltip("hide");
//             });
//             return false;
//         }

//         let formData = {'namae': namae}
//         const url = "map_modal";
//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });
//         e.preventDefault();


//         $('#modalHtml').empty();
//         // inputテキストからのリクエスト
//         $.ajax({
//             type:"GET",
//             url: url,
//             data: formData,
//             dataType: 'json',
//             success: function(data){
//                 console.log(data);
//                 let modal_body = $("#modalHtml");
                
//                 for(let i = 0; i < data.list.length; i++){
//                     let row = data.list[i];
//                     let zahyo = row.L_lat + "," + row.L_lng;
//                     // console.log(zahyo);

//                     let tag = $('<div>').append($('<a></a>', {href: '#', css:{color: '#ff4500'} }).addClass("local_link")
//                     .text(row.prefectures_name + row.town_name + row.ss_town_name).data('value', zahyo));
//                     // console.log(tag);
//                     modal_body.append(tag);
//                 }
                
//                 for(let i = 0; i < data.list.length; i++){
//                     let row = data.list[i];
//                     let zahyo = row.lat + "," + row.lng;

//                     let tag = $('<div>').append($('<a></a>', {href: '#'}).addClass("store_link")
//                     .text(row.shop_name + row.store_name).data('value', zahyo));

//                     modal_body.append(tag);
//                 }
//                 $("#list_modal").modal('show');

//             },
//             error: function(data){
//                 alert("例外が発生しました");
//                 console.log('Error:', data);
//             }
//         });
        
//     });    

// });
 

    
