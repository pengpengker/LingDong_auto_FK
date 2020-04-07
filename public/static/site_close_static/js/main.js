$(window).load(function() {
    $('.preloader').fadeOut('slow');
});
function initializeSite() {
    "use strict"; (function() {
        function centerInit() {
            var sphereContent = $('.sphere'),
            sphereHeight = sphereContent.height(),
            parentHeight = $(window).height(),
            topMargin = (parentHeight - sphereHeight) / 2;
            sphereContent.css({
                "margin-top": topMargin + "px"
            });
            var heroContent = $('.hero'),
            heroHeight = heroContent.height(),
            heroTopMargin = (parentHeight - heroHeight) / 2;
            heroContent.css({
                "margin-top": heroTopMargin + "px"
            });
        }
        $(document).ready(centerInit);
        $(window).resize(centerInit);
    })();
    $('#scene').parallax();
};
$(window).load(function() {
    initializeSite(); (function() {
        setTimeout(function() {
            window.scrollTo(0, 0);
        },
        0);
    })();
});