// あとでmodal_open.jsと一緒にしてみる


// modalで店舗クリック
$(document).on('click', '.store_link', function(){
    $("#list_modal").modal("hide");
    console.log("modal link click!!");
    let latlng = $(this).data('value');
    console.log(latlng);

    // コントローラー側に送るリクエストパラメーター
    let lat = latlng.split(',')[0];
    let lng = latlng.split(',')[1];
    location.href = "?lat=" + lat + "&lng=" + lng + '#map div';
    console.log(location.href);   

});

// modalで地域クリック
$(document).on('click', '.local_link', function(){
    $("#list_modal").modal("hide");
    console.log("modal link click!!");
    let latlng = $(this).data('value');
    console.log(latlng);
    

    // コントローラー側に送るリクエストパラメーター
    let lat = latlng.split(',')[0];
    let lng = latlng.split(',')[1];
    location.href = "?lat=" + lat + "&lng=" + lng + '#map div';
    console.log(location.href);
   

});
