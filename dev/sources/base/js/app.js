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