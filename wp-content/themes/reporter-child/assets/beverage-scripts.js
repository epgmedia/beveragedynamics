/**
 *
 */

jQuery(document).ready(function($) {
    $('li.cat-item.has-children').hover(
        function () {
            $("ul:first", this).css({
                visibility: "visible",
                display: "none"
            }).fadeIn(100);
        }, function () {
            $("ul:first", this).css({
                visibility: "hidden",
                display: "none"
            });
        });

    // Preserves the mouse-over on top-level menu elements when hovering over children
    $(".has-children .children").each(
        function (i) {
            $(this).hover(
                function () {
                    $(this).parent().slice(0, 1).addClass("active");
                }, function () {
                    $(this).parent().slice(0, 1).removeClass("active");
                });
        });

    $(window).resize(function() {
    });
});


jQuery(document).ready(function($) {
    $("span.entry-comments").hide();
});