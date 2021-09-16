// あとでmodal_open.jsと一緒にしてみる


$(document).on('click', '.modal_link', function(){
    $("#list_modal").modal("hide");
    console.log("modal link click!!");
    let latlng = $(this).data('value');
    console.log(latlng);

    // コントローラー側に送るリクエストパラメーター
    let lat = latlng.split(',')[0];
    let lng = latlng.split(',')[1];
    location.href = "?lat=" + lat + "&lng=" + lng;
    console.log(location.href);
    
    

    // initMap(response);

});