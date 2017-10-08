if (typeof require != 'undefined') {
    $ = jQuery = require('jquery');
}

$(function() { 
    $(function() {
        var showPopup = false;

        $('[toggle-popup]').unbind('click').bind('click', function(e) {
            if (e.preventDefault() & e.stopPropagation());

            var self = this;

            showPopup = !showPopup;

            if (showPopup) {
                $('body').addClass('popup-open');
                $('.popup-' + $(this).attr('toggle-popup')).addClass('active');
                $('.popup-' + $(this).attr('toggle-popup') + ' [modal-close]').unbind('click').bind('click', function() {
                    $(self).click();
                });
            } else {
                $('body').removeClass('popup-open');
                $('.popup-' + $(this).attr('toggle-popup')).removeClass('active');
                $('.popup-' + $(this).attr('toggle-popup') + ' [modal-close]').unbind('click');
            }
        });
    });
});