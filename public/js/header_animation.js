$(window).on('load', function(){
    const CLASSNAME = "-visible";
    const TIMEOUT = 500;
    const $target = $(".header-eria");

    setInterval(() => {
        setTimeout(() => {
            $target.addClass(CLASSNAME);
        }, TIMEOUT);
    });
});