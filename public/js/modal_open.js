function initMap(){
    let convas = document.getElementById('map_convas');
    let h_lat = parseFloat($('#h_lat').val());
    let h_lng = parseFloat($('#h_lng').val());
    
    // console.log(h_lat);
    // console.log(h_lng);
    let centerp = {'lat': h_lat, 'lng': h_lng};
    map = new google.maps.Map(convas, {
        center: centerp,
        zoom: 16
    });

    // 店舗用と地域用の座標を取得できるようにセット
    // 初期の座標でajaxに送信して、コントローラー側で改めて店舗用と地域用を取得している
    let latlng = {'lat': h_lat, 'lng': h_lng, 'L_lat': h_lat, 'L_lng': h_lng };
    // console.log(latlng);
    
    const url = "map_data";
    // 初期表示用のajax（常に店舗と地域を表示させるためのもの）
    $.ajax({
        type:"GET",
        url: url,
        data: latlng,
        dataType: 'json',
        success: function(data){
            // console.log(data.location);
            markerData = data.location;
            setMarker(markerData);


        },
        error: function(data){
            alert("駄目です");
            // console.log('Error:', data);
        }
    });
    
};

let map;
let icon;
let marker = [];
let infoWindow = [];

// マーカーや吹き出しの表示
function setMarker(markerData){
    for(let i = 0; i < markerData.length; i++){

        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);
        // console.log(latS);
        // console.log(lngS);
        // コーテーションで囲わなくて良いのか
        let markerStore = new google.maps.LatLng({ lat: latS, lng: lngS });
        

        icon = new google.maps.MarkerImage('./img/cart.png');

        marker[i] = new google.maps.Marker({
            position: markerStore,
            map: map,
            icon: icon
        });

        infoWindow[i] = new google.maps.InfoWindow({
            content: markerData[i]['shop_name'] + markerData[i]['store_name'] + '<br><br>'
            + markerData[i]['event_start'] + ' ~ ' + markerData[i]['event_end'] + '<br><br>'
            + markerData[i]['sp_title'] + ': ' + markerData[i]['sp_subtilte']
        });
        // console.log(infoWindow);

        markerEvent(i);
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
        console.log(namae);

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
                // console.log(data);
                let modal_body = $("#modalHtml");
                
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.L_lat + "," + row.L_lng;

                    let tag = $('<div>').append($('<a></a>', {href: '#', css:{color: '#ff4500'} }).addClass("local_link")
                    .text(row.prefectures_name + row.town_name + row.ss_town_name).data('value', zahyo));

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
 

    
