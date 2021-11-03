$(function(){
    
    function openTooltip(data_element, Title){
        let scrollBottom = $(window).scrollTop() + $(window).height();

        if($(window).scrollTop() > 1500){
            // $('history_back').slideDown(600);
            $(data_element)
            .tooltip({
                trigger: "manual",
                title: Title,
                placement: "top"
            });
            $(data_element).tooltip("show");
            $(data_element).on('mouseover', function(){
                $(this).tooltip("hide");
            });
            
        }else if(scrollBottom > 1000){
            $('[data-toggle="backtip"]').tooltip("hide");
            
        }
    }

    $(window).on('scroll', function(){
        let backtip = $('[data-toggle="backtip"]');
        let Title_1 = "トップページへ戻れます";

        // let scroll_top =  $('[data-toggle="scroll_top"]');
        // let Title_2 = "ページ上部へ";

        openTooltip(backtip, Title_1);
        // openTooltip(scroll_top, Title_2);
    });


    /******************** 下降するボタンの仕様　↓↓↓ *********************/
    $('.top_down').on('click', function(){
        let down = $('#maps-container').offset().top;
        // let up = $(window).offset().top;
        if($(window).scrollTop() < down){
            $('html, body').animate({
                scrollTop: down
            }, 1000);
            return false;
        }
    });
    
});