

function initMap(){
    let convas = document.getElementById('map_convas');
    let centerp = {lat:35.73529673720239, lng:139.6281261687641};

    map = new google.maps.Map(convas, {
        center: centerp,
        zoom: 16
    });
};



$(function(){

    function modal_link(keido){
        console.log(keido);
    }
    $('.modal_link').on('click', function(){
        event.preventDefault();
        alert('テスト');
        let data = $(this).val();

        console.log(data);
        // return false;
        $("#list_modal").modal("hide");

        let markerD = [];

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            processData: false,
            data: {
                lat: data.lat,
                lng: data.lng,
                L_lat: data.L_lat,
                L_lng: data.L_lng

            },
            url: "map_data",
        })
        .done(function(data){
            console.log(data.maps);
            markerData = data.maps;

            let map;
            let marker = [];
            let infoWindow = [];

            for(let i = 0; i < markerData.length; i++){

                let latS = parseFloat(markerData[i]['lat']);
                let lngS = parseFloat(markerData[i]['lng']);
                let markerStore = {lat: latS, lng: lngS};
    
                let latL = parseFloat(markerData[i]['L_lat']);
                let lngL = parseFloat(markerData[i]['L_lng']);
                let markerLocal = {lat: latL, lng: lngL};
    
                icon = new google.maps.MarkerImage('./img/cart.png');
    
                marker[i] = new google.maps.Marker({
                    position: markerStore,
                    map: map,
                    icon: icon
                });
    
                marker[i] = new google.maps.Marker({
                    position: markerLocal,
                    map: map,
                });
    
                // console.log(marker[i]);
    
                infoWindow[i] = new google.maps.InfoWindow({
                    content: markerData[i]['shop_name'] + markerData[i]['store_name'] + '<br><br>'
                        + markerData[i]['event_start'] + ' ~ ' + markerData[i]['event_end'] + '<br><br>'
                        + markerData[i]['sp_title'] + ': ' + markerData[i]['sp_subtilte']
                });
    
                markerEvent(i);
            }
            
            let openWindow;

            function markerEvent(i){
                marker[i].addListener('click', function(){
                    myclick(i);
                });
            }


            function myclick(i){
                if(openWindow){
                    openWindow.close();
                }

                infoWindow[i].open(map, marker[i]);
                openWindow = infoWindow[i];

            }

        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown){
            alert("検索内容を取得できませんでした：" + textStatus + ":\n" + errorThrown);
        });

    });


    // let map;
    // let marker = [];
    // let infoWindow = [];

    // function setMarker(markerData){
    //     console.log(markerData);

    //     let icon;

    //     for(let i = 0; i < markerData.length; i++){

    //         let latS = parseFloat(markerData[i]['lat']);
    //         let lngS = parseFloat(markerData[i]['lng']);
    //         let markerStore = {lat: latS, lng: lngS};

    //         let latL = parseFloat(markerData[i]['L_lat']);
    //         let lngL = parseFloat(markerData[i]['L_lng']);
    //         let markerLocal = {lat: latL, lng: lngL};

    //         icon = new google.maps.MarkerImage('./img/cart.png');

    //         marker[i] = new google.maps.Marker({
    //             position: markerStore,
    //             map: map,
    //             icon: icon
    //         });

    //         marker[i] = new google.maps.Marker({
    //             position: markerLocal,
    //             map: map,
    //         });

    //         // console.log(marker[i]);

    //         infoWindow[i] = new google.maps.InfoWindow({
    //             content: markerData[i]['shop_name'] + markerData[i]['store_name'] + '<br><br>'
    //                 + markerData[i]['event_start'] + ' ~ ' + markerData[i]['event_end'] + '<br><br>'
    //                 + markerData[i]['sp_title'] + ': ' + markerData[i]['sp_subtilte']
    //         });

    //         markerEvent(i);
    //     }
    // }

    // let openWindow;

    // function markerEvent(i){
    //     marker[i].addListener('click', function(){
    //         myclick(i);
    //     });
    // }


    // function myclick(i){
    //     if(openWindow){
    //         openWindow.close();
    //     }

    //     infoWindow[i].open(map, marker[i]);
    //     openWindow = infoWindow[i];

    // }


});





    

