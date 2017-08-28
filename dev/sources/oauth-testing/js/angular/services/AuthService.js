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
                return ApiRequest.post('/api/shop-keeper/device', values);
            };

            this.createDeviceToken = function(values) {
                return ApiRequest.post('/api/shop-keeper/device/token', values);
            };

            this.signUp = function(values) {
                return ApiRequest.post('/api/shop-keeper/sign-up', values);
            };

            this.signOut = function(values) {
                CredentialsService.set(null);
            };

            this.getUser = function(credentails) {
                return ApiRequest.get('/api/user');
            };
        });
    }
]);