oauth2App.service('VoucherService', [
    '$http',
    'DeviceIdService',
    'ApiRequest',
    function(
        $http,
        DeviceIdService,
        ApiRequest
    ) {
        var base_url = 'http://forus-mvp.dev.net'

        return new(function() {
            this.checkCode = function(credentails, code) {
                return ApiRequest.get('/api/vouchers/' + code);
            };

            this.makeTransaction = function(credentails, code, values) {
                var values = JSON.parse(JSON.stringify(values));

                values._method = 'PUT';
                
                return ApiRequest.post('/api/vouchers/' + code, values);
            };
        });
    }
]);