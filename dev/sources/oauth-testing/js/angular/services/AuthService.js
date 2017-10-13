oauth2App.service('AuthService', [
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

            this.authorizeToken = function(token) {
                return ApiRequest.post('/shop-keepers/devices/token/' + token);
            };

            this.signUp = function(values) {
                return ApiRequest.post('/shop-keepers/sign-up', values);
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