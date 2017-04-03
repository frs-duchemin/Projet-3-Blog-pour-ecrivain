$(function () {
    $(".row2").slice(0, 2).addClass('display');
    $("#loadMore").on('click', function (e) {
        e.preventDefault();
        $(".row2:hidden").slice(0, 1).addClass('display');
        if ($(".row2:hidden").length == 0) {
            $("#load").fadeOut('slow');
        }
        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
    });
});

$('a[href=#top]').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 600);
    return false;
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
        $('.totop a').fadeIn();
    } else {
        $('.totop a').fadeOut();
    }
});
