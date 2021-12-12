
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
                // setMarker(markerD);
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


        markerEvent(i);
    }
    
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

function SearchGo(){
    let search = $("search").val();

    let formData = {
        'namae': search,
    }
    const url = "goo_result";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // inputテキストからのリクエスト
    $.ajax({
        type:"GET",
        url: url,
        data: formData,
        dataType: 'json',
        success: function(data){
            
            // listのlength分として計算する必要がある
            if(data.list.length == 0){
                alert('該当する店舗はありませんでした...');
                
            }else{
                for(let i = 0; i < data.list.length; i++){
                    
                    let row = data.list[i];
                    markerData = row;
                    setMarker(markerData);
        
                }
            }

        },
        error: function(data){
            alert("例外が発生しました");
            console.log('Error:', data);
        }
    });
}


