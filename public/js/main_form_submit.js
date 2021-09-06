$(function(){
    $('#search-form').on('submit', function(){
        let search_schedule = $('#search-schedule').val();
        let search_shop = $('#search-shop').val();
        console.log(search_schedule);
        console.log(search_shop);
        // console.logで値を確認して「null」か「''」かを判断できる。
        if(search_schedule === null && search_shop === ''){
            $('[data-toggle="popover"]')
            .tooltip({
                trigger: "manual",
                title: "日程を選択、もしくは店名を入力してください。",
                placement: "top"
            });
            $('[data-toggle="popover"]').tooltip("show");
            $('[data-toggle="popover"]').on('mouseover', function(){
                $(this).tooltip("hide");
            });
            return false;
        }

    });


});