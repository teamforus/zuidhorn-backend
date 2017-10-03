(function($) {
    $.prototype.slowScroll = function() {
        if (this.length == 0) return;

        var slowScroll = function($root) {
            var target = $root.attr('href');

            $root.unbind('click').bind('click', function() {
                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, 1600);
            });
        };

        for (var i = 0; i < this.length; i++) {
            new slowScroll($(this[i]));
        }
    };

    $.prototype.tabulation = function() {
        if (this.length == 0) return;

        var tabulation = function($root) {
            var $tabs = $root.find('[tabulation-tab]');
            var $panes = $root.find('[tabulation-pane]');

            $tabs.unbind('click').bind('click', function(e) {
                e.preventDefault() && e.stopPropagation();

                $panes.removeClass('active');
                $tabs.removeClass('active');

                $root.find('[tabulation-pane="' + $(this).attr('tabulation-tab') + '"]')
                    .addClass('active');

                $(this).addClass('active');
            });

            $tabs[0].click();
        };

        for (var i = 0; i < this.length; i++) {
            new tabulation($(this[i]));
        }
    };

    $('[slow-scroll]').slowScroll();
    $(".nano").nanoScroller();

    moment.locale('nl');
})(jQuery);