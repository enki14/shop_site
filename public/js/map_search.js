// あとでmodal_open.jsと一緒にしてみる
$(function(){

    // modalで地域クリック
    $(document).on('click', '.local_link', function(){
        $("#list_modal").modal("hide");
        // console.log("modal link click!!");
        let latlng = $(this).data('value');
        console.log(latlng);

        // コントローラー側に送るリクエストパラメーター
        let latL = latlng.split(',')[0];
        let lngL = latlng.split(',')[1]; 

        location.href = "?lat=" + latL + "&lng=" + lngL + '#maps-container';

    });

        
    // modalで店舗クリック
    $(document).on('click', '.store_link', function(){
        $("#list_modal").modal("hide");
        let latlng = $(this).data('value');
        // コントローラー側に送るリクエストパラメーター
        let lat = latlng.split(',')[0];
        let lng = latlng.split(',')[1]; 

        location.href = "?lat=" + lat + "&lng=" + lng + '#maps-container';

    });


});





