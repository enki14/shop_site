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

});
