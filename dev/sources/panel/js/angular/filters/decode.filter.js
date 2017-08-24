app.filter('decode', function() {
    "use strict";

    function htmlDecode(input) {
        var e = document.createElement('div');
        
        e.innerHTML = input;

        return e.childNodes[0].nodeValue;
    }

    return function(input) {
        return htmlDecode(input);
    }
});