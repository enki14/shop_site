// あとでmodal_open.jsと一緒にしてみる


$(document).on('click', '.modal_link', function(){
    $("#list_modal").modal("hide");
    console.log("modal link click!!");
    let latlng = $(this).data('value');
    console.log(latlng);

    // コントローラー側に送るリクエストパラメーター
    let lat_lng = latlng.split(',');
    location.href = "?lat=" + lat_lng['lat'] + "&lng=" + lat_lng['lng'];
    console.log(location.href);
    
    

    // initMap(response);

});