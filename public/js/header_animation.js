    
$(window).on('load', function(){
    const CLASSNAME = "-visible";
    const CLASSNAME_2 = "-visible_2";
    const TIMEOUT = 250;
    const $target = $(".header-eria");
    const $target_2 = $("#content-container");

    setInterval(() => {
        setTimeout(() => {
            $target.addClass(CLASSNAME);
        }, TIMEOUT);
    });

    setInterval(() => {
        setTimeout(() => {
            $target_2.addClass(CLASSNAME_2);
        }, TIMEOUT);
    });
    
});






