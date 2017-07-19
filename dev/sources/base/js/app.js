// Greetings
console.log('%cWelcome to Quick Dev Template!', 'color: green');

var displayVersion = function(name, version, installed) {
    console.log(name + ': %c' + version, 'color: ' + (installed ? 'green' : 'red'));
};

displayVersion('jQuery', (typeof jQuery != 'undefined' ? ('v' + jQuery.fn.jquery) : 'not installed!'), typeof jQuery != 'undefined');
displayVersion('Angular', (typeof angular != 'undefined' ? ('v' + angular.version.full) : 'not installed!'), typeof angular != 'undefined');
displayVersion('Angular2', (typeof ng != 'undefined' ? 'v2.1.0' : 'not installed!'), typeof ng != 'undefined');

(function($) {
    $.prototype.confirmBox = function(_args) {
        if (this.length === 0)
            return;

        var confirmBox = function($node) {
            var self = this;

            self.bind = function() {
                $node.unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var href = $node.attr('href');
                    var title = $node.data('box-title');
                    var text = $node.data('box-text');

                    swal({
                        title: title,
                        text: text,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: true,
                    }, function() {
                        document.location = href;
                    });
                });
            };

            self.bind();
        };

        for (var i = 0; i < this.length; i++) {
            new confirmBox($(this[i]));
        }
    };
})(jQuery);

$(function() {
    $('[confirm-box]').confirmBox();
});