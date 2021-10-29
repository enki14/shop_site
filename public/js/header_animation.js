$(window).on('load', function(){
    const CLASSNAME = "-visible";
    const TIMEOUT = 500;
    const $target = $(".header-eria");

    setInterval(() => {
        setTimeout(() => {
            $target.addClass(CLASSNAME);
        }, TIMEOUT);
    });


    // var windowHeight = $(window).height(),
    //     topWindow = $(window).scrollTop();
    // $('body').each(function(){
    //     var targetPosition = $(this).offset().top;
    //     if(topWindow > targetPosition - windowHeight + 100){
    //         $(this).addClass("fadeInDown");
    //     }
    // });

    
});



