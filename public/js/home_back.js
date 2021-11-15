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
        let Title = "トップページへ戻れます";

        openTooltip(backtip, Title);
    });


    
/******************** 下降するボタンの仕様　↓↓↓ *********************/
});

$(function(){
    let speed_b = 600;
    let position_b = $("#maps-container").offset().top;
    let speed_t = 400;
    let position_t = $(".header-eria").offset().top;
        

    if(scrollTop(position_b)){
        $('.top_down').on('click', function(){ 
            console.log(position_b);
            $("html, body").animate({scrollTop: position_b}, speed_b, 'swing');
            return false;
        });
    }else{
        $('.top_down').on('click', function(){
            console.log(position_t);
            $("html, body").animate({scrollTop: position_t}, speed_t, 'swing');
            return false;
        });
    }
});