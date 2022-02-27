    
$(window).on('load', function(){
    let setTime = new Array();
    const CLASSNAME = "-visible";
    const CLASSNAME_2 = "-visible_2";
    const TIMEOUT = 250;
    const $target = $(".header-eria");
    const $target_2 = $("#content-container");
    let request_flag = $('#h_request_flag').val();

    setTime = setInterval(() => {
        setTimeout(() => {
            $target.addClass(CLASSNAME);
        }, TIMEOUT);
    });

    setTime = setInterval(() => {
        setTimeout(() => {
            $target_2.addClass(CLASSNAME_2);
        }, TIMEOUT);
    });



    if(request_flag == true){
        clearInterval(setTime);
        $target.css('opacity', '1');
        $target_2.css('opacity', '1');
    }

    
});

    
    








