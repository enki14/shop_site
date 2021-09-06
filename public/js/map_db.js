
function initMap(){
    let target = document.getElementById('target');
    let centerp = {lat:35.73529673720239, lng:139.6281261687641};

    map = new google.maps.Map(target, {
        center: centerp,
        zoom: 16
    });
};

let markerD = [];

$(function(){

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "google_shop",
        dataType: "json",
        success: function(data){
                markerD = data.list;
                setMarker(markerD);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert('Error : '+ errorThrown);
        }
    });
});


let map;
let marker = [];
let infoWindow = [];

function setMarker(markerData){
    console.log(markerData);
    console.log(markerData.length);

    let sidebar_html = "";
    let icon;

    for(let i = 0; i < markerData.length; i++){
        let latNum = parseFloat(markerData[i]['lat']);
        let lngNum = parseFloat(markerData[i]['lng']);
        let markerLatLng = {lat: latNum, lng: lngNum};

        icon = new google.maps.MarkerImage('./img/cart.png');

        marker[i] = new google.maps.Marker({
            position: markerLatLng,
            map: map,
            icon: icon
        });

        // console.log(marker[i]);

        infoWindow[i] = new google.maps.InfoWindow({
            content: markerData[i]['shop_name'] + markerData[i]['store_name'] + '<br><br>'
                + markerData[i]['event_start'] + ' ~ ' + markerData[i]['event_end'] + '<br><br>'
                + markerData[i]['sp_title']
        });

        sidebar_html += '<a href="javascript:myclick('+ i +')">' 
        + markerData[i]['shop_name'] + markerData[i]['store_name'] + '<\/a><br />';

        markerEvent(i);
    }
    // sidebarのhtml要素を、sidebar_htmlに変更するというもの。
    document.getElementById("sidebar").innerHTML = sidebar_html;
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