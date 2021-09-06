
function initMap(){
    let convas = document.getElementById('map_convas');
    let centerp = {lat:35.73529673720239, lng:139.6281261687641};

    map = new google.maps.Map(convas, {
        center: centerp,
        zoom: 16
    });
};




$(function(){

    $("#kensaku-map").on('click', function(e){

        let name = $("input[name='search']").val();

        if(name == ''){
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

        // let formData = {
        //     shop_name: name.shop_name,
        //     store_name: name.store_name,
        //     lat: name.lat,
        //     lng: name.lng,
        //     // L_lat: name.L_lat,
        //     // L_lng: name.L_lng,
        //     // distance: name.distance,
        //     // distance2: name.distance_2
        // }

        $('#modalHtml').empty();
        $.ajax({
            type:"GET",
            url: url,
            data: name,
            dataType: 'json',
            success: function(data){
                console.log(data);
                let modal_body = $("#modalHtml");
                for(let i = 0; i < data.list.length; i++){
                    let row = data.list[i];
                    let zahyo = row.lat + "," + row.lng;

                    let tag = $('<div>').append($('<a></a>', {href: '#'}).addClass("modal_link")
                    .text(row.shop_name).data('value', zahyo));

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

    function getParam(url, name) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)" ),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $(document).on('click', '.modal_link', function(){
        console.log("modal link click!!");
        let obj = $(this);

        console.log('obj=%o', obj);
        console.log("link data-value:" + obj.data('value'));
        console.log($(this));
        console.log(getParam(obj.data('value')));

        $("#list_modal").modal("hide");

        const url = "map_data";
        let modal_body = $("#modalHtml");
        let data = modal_body.append($('<input/>', {type: "hidden", value: data.list[i]}));
            modal_body.append($('<input/>', {type: "hidden", name: "_token", value: csrf }));
        $.ajax({
            type:"GET",
            url: url,
            data: data,
            dataType: 'json',
            success: function(data){
                console.log(data.location);
                markerData = data.location;

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

    
