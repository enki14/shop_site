
// function getParam(url, name) {
//     if (!url) url = window.location.href;
//     name = name.replace("/[\[\]]/g", "\\$&");
//     var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)" ),
//         results = regex.exec(url);
//     if (!results) return null;
//     if (!results[2]) return '';
//     return decodeURIComponent(results[2].replace(/\+/g, " "));
// }

$(document).on('click', '.modal_link', function(){
    $("#list_modal").modal("hide");
    console.log("modal link click!!");
    let obj = $(this);

    console.log('obj=%o', obj);
    console.log("link data-value:" + obj.data('value'));
    // console.log(getParam(obj.data('value')));
    // let data_value = obj.data('value');


    const url = "map_data";
    let modal_body = $("#modalHtml");
    let csrf = $('input[name=_token]').val();
    let data = modal_body.append($('<input/>', { type: "hidden"}).attr(obj.data('value')));
        modal_body.append($('<input/>', {type: "hidden", name: "_token", value: csrf }));
        console.log(data.value);
    $.ajax({
        type:"GET",
        url: url,
        data: data,
        dataType: 'json',
        success: function(data){
            console.log(data.location);
            markerData = data.location;
            setMarker(markerData);


        },
        error: function(data){
            alert("例外が発生しました");
            console.log('Error:', data);
        }
    });
    
    

    let map;
    let icon;
    let marker = [];
    let infoWindow = [];

    function setMarker(markerData){
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
    }


    
});