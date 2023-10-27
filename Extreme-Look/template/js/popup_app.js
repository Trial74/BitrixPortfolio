$(document).ready(function () { //Мой код
    let datetoday = new Date,
        authApp = false;
    if ($(window).width() < '991' && !authApp) {
        if(!localStorage.popupLastView || localStorage.popupLastView < datetoday.getDate()) {
            setTimeout(function () {
                $('.popup-app').css('display', 'block');
            }, 3000)
        }
    }
    $('.popup-close').click(function () {
        $('.popup-app').css('display', 'none');
        localStorage.popupLastView = datetoday.getDate();
    })
});