if (window.location.hash!='') {
    window.hashName = window.location.hash;
    window.location.hash = '';
    $(document).ready(function() {
        $('html').animate({scrollTop: $(window.hashName).offset().top}, 2000, function() {
            window.location.hash = window.hashName;
        });
    });

$(function(){
  $('a[href^="#"]').on('click', function(event) {
    // отменяем стандартное действие
    event.preventDefault();

    var sc = $(this).attr("href"),
        dn = $(sc).offset().top;
    /*
    * sc - в переменную заносим информацию о том, к какому блоку надо перейти
    * dn - определяем положение блока на странице
    */

    $('html, body').animate({scrollTop: dn}, 1000);

    /*
    * 1000 скорость перехода в миллисекундах
    */
  });
});

$(document).ready(function(){
    $("#navmenu").on("click","a", function (event) {
        event.preventDefault();
        var id  = $(this).attr('href'),
            top = $(id).offset().top;
        $('body,html').animate({scrollTop: top}, 1500);
    });

    if (window.location.hash!='') {
        window.hashName = window.location.hash;
        window.location.hash = '';
        $(document).ready(function() {
            $('html').animate({scrollTop: $(window.hashName).offset().top}, 2000, function() {
                window.location.hash = window.hashName;
            });
        });
});

// function autoResize(iframe) {
//     $(iframe).width($(iframe).contents().find('html').width());
// }
