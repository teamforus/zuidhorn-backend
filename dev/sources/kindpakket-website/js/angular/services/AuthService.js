kindpakketApp.service('AuthService', [
    'ApiRequest',
    'CredentialsService',
    function(
        ApiRequest,
        CredentialsService
    ) {
        return new(function() {
            apiRequest = ApiRequest;

            this.signIn = function(values) {
                return ApiRequest.post('/../api/oauth/token', {
                    'grant_type': 'password',
                    'client_id': 2,
                    'client_secret': 'DKbwNT3Afz8bovp0BXvJX5jWudIRRW9VZPbzieVJ',
                    'username': values.email || '',
                    'password': values.password || '',
                    'scope': '*',
                });
            };

            this.activateVoucher = function(voucher, values) {
                return ApiRequest.post('/api/voucher/' + voucher + '/activate', values);
            };

            this.signOut = function(values) {
                CredentialsService.set(null);
            };

            this.getUser = function() {
                return ApiRequest.get('/api/user');
            };

            this.getVoucher = function() {
                return ApiRequest.get('/api/user/voucher');
            };

            this.getQrCode = function() {
                return ApiRequest.get('/api/user/voucher/qr-code');
            };
        });
    }
]);