// Greetings
console.log('%cWelcome to Quick Dev Template!', 'color: green');

var displayVersion = function(name, version, installed) {
    console.log(name + ': %c' + version, 'color: ' + (installed ? 'green' : 'red'));
};

displayVersion('jQuery', (typeof jQuery != 'undefined' ? ('v' + jQuery.fn.jquery) : 'not installed!'), typeof jQuery != 'undefined');
displayVersion('Angular', (typeof angular != 'undefined' ? ('v' + angular.version.full) : 'not installed!'), typeof angular != 'undefined');
displayVersion('Angular2', (typeof ng != 'undefined' ? 'v2.1.0' : 'not installed!'), typeof ng != 'undefined');

(function($) {
    $(".nano").nanoScroller();

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
})(jQuery);