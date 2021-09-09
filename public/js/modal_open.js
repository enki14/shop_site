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

        let name = $("input[name='map_search']").val();
        // console.log(name);

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
 

    
