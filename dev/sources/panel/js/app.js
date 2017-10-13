if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
        value: function assign(target, varArgs) { // .length of function is 2
            'use strict';
            if (target == null) { // TypeError if undefined or null
                throw new TypeError('Cannot convert undefined or null to object');
            }

            var to = Object(target);

            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];

                if (nextSource != null) { // Skip over if undefined or null
                    for (var nextKey in nextSource) {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}

if (typeof Object.values != 'function') {
    Object.values = function(obj) {
        var allowedTypes = ["[object String]", "[object Object]", "[object Array]", "[object Function]"];
        var objType = Object.prototype.toString.call(obj);

        if (obj === null || typeof obj === "undefined") {
            throw new TypeError("Cannot convert undefined or null to object");
        } else if (!~allowedTypes.indexOf(objType)) {
            return [];
        } else {
            // if ES6 is supported
            if (Object.keys) {
                return Object.keys(obj).map(function(key) {
                    return obj[key];
                });
            }

            var result = [];
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    result.push(obj[prop]);
                }
            }

            return result;
        }
    };
}

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