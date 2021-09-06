$(function(){
    $('#kensaku_btn').on('click', function(){
    
        let search_schedule = $('#search-schedule').val();
        let search_shop = $('#search-shop').val();
        let csrf = $('input[name=_token]').val();
        console.log(search_schedule);
        console.log(search_shop);
        if(search_schedule === null && search_shop === ''){
            $('#search-shop').trigger('focus');
            alert('日程を選択、または店名を入力してください。さらにどちらとも選択し絞り込めます。');
            return false;

        }else{
            let form = $('<form/>', {action: "result", method: "post"});
            form.append($('<input/>', {type: "hidden", name: "schedule", value: search_schedule}));
            form.append($('<input/>', {type: "hidden", name: "shop", value: search_shop}));
            // csrfトークンも一緒に送信する
            form.append($('<input/>', {type: "hidden", name: "_token", value: csrf }));
            form.appendTo(document.body).submit();
        }

    });  
});


