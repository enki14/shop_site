// あとでmodal_open.jsと一緒にしてみる
$(function(){

    // modalで店舗クリック
    $(document).on('click', '.store_link', function(){
        $("#list_modal").modal("hide");
        // console.log("modal link click!!");
        let latlng = $(this).data('value');
        // console.log(latlng);

        // コントローラー側に送るリクエストパラメーター
        let lat = latlng.split(',')[0];
        let lng = latlng.split(',')[1]; 

        let itiranData = { 'lat': lat, 'lng': lng, 'L_lat': lat, 'L_lng': lng}
        console.log(lat);
        $.ajax({
            type:"GET",
            url: "map_itiran",
            data: itiranData,
            dataType: "json",
            success: function(data){
                for(let i = 0; i < data.list_2.length; i++){
                    // console.log(location.href);
                    
                    let row = data.list_2[i];
                    console.log(row);
                    let card = $("#itiran2-card").children("#card_body");
                    let tag = $('<h5>').text(row.shop_name + row.store_name);
                    let card_list = card.find($("#shop-name").append(tag.clone()));
                    console.log(card_list);
                    
                }
                location.href = "?lat=" + lat + "&lng=" + lng + '#map div';
            },
            error: function(data){
                alert("一覧が表示できませんでした");
                console.log('Error:', data);
            }
        });  

    });


});





