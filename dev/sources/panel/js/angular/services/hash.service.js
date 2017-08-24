app.service('HashService', ['$http', function($http) {
    var service = {
        SHA512: new Hashes.SHA512,
        hashWithSalt: function(text, salt) {
            return this.SHA512.hex_hmac(salt, text);
        }
    };

    return service;
}]);