oauth2App.service('AuthService', ['ApiRequest', function(ApiRequest) {
    return new(function() {
        apiRequest = ApiRequest;
        
        this.signIn = function(values) {
            return ApiRequest.post('/api/oauth/token', {
                'grant_type': 'password',
                'client_id': 2,
                'client_secret': 'DKbwNT3Afz8bovp0BXvJX5jWudIRRW9VZPbzieVJ',
                'username': values.email || '',
                'password': values.password || '',
                'scope': '*',
            });
        };

        this.signUp = function(values) {
            return ApiRequest.post('/api/shop-keeper/sign-up', values);
        };

        this.signOut = function(values) {
            localStorage.clear();
        };

        this.getUser = function(credentails) {
            return ApiRequest.get('/api/user');
        };
    });
}]);