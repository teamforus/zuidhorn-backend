shopkeeperApp.service('AuthService', [
    'ApiRequest',
    'CredentialsService',
    function(
        ApiRequest,
        CredentialsService
    ) {
        return new(function() {
            apiRequest = ApiRequest;

            this.signIn = function(values) {
                return ApiRequest.post('/shop-keepers/devices', values);
            };

            this.generateToken = function() {
                return ApiRequest.get('/shop-keepers/devices/token');
            };

            this.checkTokenState = function(token) {
                return ApiRequest.get('/shop-keepers/devices/token/' + token + '/state');
            };

            this.signOut = function(values) {
                CredentialsService.set(null);
            };

            this.getUser = function(credentails) {
                return ApiRequest.get('/user');
            };
        });
    }
]);