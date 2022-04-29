/******************** 下降するボタンの仕様　↓↓↓ *********************/
window.addEventListener('DOMContentLoaded', function(){
    
    let speed = 600;
    let position = $("#content-container").offset().top;

    $('.smart-button').on('click', function(){
        $("html, body").animate({scrollTop: position}, speed, 'swing');
        return false;
    });


});

