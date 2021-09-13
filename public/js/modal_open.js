function initMap(){
    let convas = document.getElementById('map_convas');
    let h_lat = parseFloat($('#h_lat').val());
    let h_lng = parseFloat($('#h_lng').val());
    let centerp = {lat: h_lat, lng: h_lng};
    // console.log(h_lat);
    // console.log(h_lng);
    map = new google.maps.Map(convas, {
        center: centerp,
        zoom: 16
    });

    
    // 地域用のL_lat,L_lngもそれぞれの変数に渡して、下記を訂正
    let S_lat = parseFloat($('#S_lat').val());
    let S_lng = parseFloat($('#S_lng').val());
    let L_lat = parseFloat($('#L_lat').val());
    let L_lng = parseFloat($('#L_lng').val());
    // for(let i = 0; i < S_lat.length; i++){
    let latlng = {'lat': S_lat, 'lng': S_lng, 'L_lat': L_lat, 'L_lng': L_lng };
    
    // let latlng = {markerStore, markerLocal};
    const url = "map_data";
    // 初期表示用のajax
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
            console.log('Error:', data);
        }
    });
    // }
    
    
};

let map;
let icon;
let marker = [];
let infoWindow = [];

function setMarker(markerData){
    for(let i = 0; i < markerData.length; i++){

        let latS = parseFloat(markerData[i]['lat']);
        let lngS = parseFloat(markerData[i]['lng']);
        let markerStore = new google.maps.LatLng({ lat: latS, lng: lngS });
        

        // let latL = parseFloat(markerData[i]['L_lat']);
        // let lngL = parseFloat(markerData[i]['L_lng']);
        // let markerLocal = new google.maps.LatLng({ latL: latL, lngL: lngL });
        

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

    
        const url = "map_modal";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();


        $('#modalHtml').empty();
        $.ajax({
            type:"GET",
            url: url,
            data: namae,
            dataType: 'json',
            success: function(data){
                // console.log(data);
                let modal_body = $("#modalHtml");
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.lat + "," + row.lng;

                    let tag = $('<div>').append($('<a></a>', {href: '#'}).addClass("modal_link")
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

   
    



//         let name = $("input[name='search']").val();

//         if(name == ''){
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

//         console.log(name);
//         $('#modalHtml').empty();
//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             type: "GET",
//             data: {
//                 name: name
//             },
//             url: "modal",
//             dataType: "json",
//         })
//         .done(function(data){
//             console.log(data);
//             // locationはShopSiteController.php@mapModalのresponse
            
//             for(let i = 0; i < data.location.length; i++){
//                 let local = $("#modalHtml").append('<p></p>');

//                 let keido = data.location[i].lat + " " + data.location[i].lng;
//                 local.append($('<a></a>', {href: '', class: 'modal_link', value: keido, onclick: "modal_link('"+ keido + "')", css: {color: 'blue'}})
//                 .text(data.location[i].prefectures_name + data.location[i].town_name + data.location[i].ss_town_name + '近辺'));
//             }  
                    
//             for(let i = 0; i < data.location.length; i++){    
//                 let store = $("#modalHtml").append('<p></p>');

//                 store.append($('<a href="#"></a>', {href: '#', css: {color: 'red'}})
//                 .text(data.location[i].shop_name + data.location[i].store_name));
//             }      
//             $("#list_modal").modal('show');
//         })
//         .fail(function(XMLHttpRequest, textStatus, errorThrown){
//             alert("検索内容を取得できませんでした：" + textStatus + ":\n" + errorThrown);
//         });

//     });

    
});
 

    
