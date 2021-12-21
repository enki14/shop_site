/******************** 下降するボタンの仕様　↓↓↓ *********************/
$(function(){
    let speed_b = 600;
    let position_b = $("#maps-container").offset().top;
    let speed_t = 400;
    let position_t = $(".header-eria").offset().top;
        

    // if(position_b > 0){
        $('.top_down').on('click', function(){ 
            console.log(position_b);
            $("html, body").animate({scrollTop: position_b}, speed_b, 'swing');
            return false;
        });
    // }else if(position_t > 0){
        $('.top_down').on('click', function(){
            console.log(position_t);
            $("html, body").animate({scrollTop: position_t}, speed_t, 'swing');
            return false;
        });
    // }
});