

// 注：　modal_open.jsに統合されました！



$(function(){    

    // modalクリック
    $(document).on('click', '.store_link', function(){
        $("#list_modal").modal("hide");
        let latlng = $(this).data('value');

        // コントローラー側に送るリクエストパラメーター
        let lat = latlng.split(',')[0];
        let lng = latlng.split(',')[1]; 

        location.href = "?lat=" + lat + "&lng=" + lng + '&s_flag=store#maps-container';
    });

});





