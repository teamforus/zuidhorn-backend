oauth2App.service('VoucherService', [
    '$http',
    'DeviceIdService',
    'ApiRequest',
    function(
        $http,
        DeviceIdService,
        ApiRequest
    ) {
        return new(function() {
            this.checkCode = function(credentails, code) {
                return ApiRequest.get('/api/voucher/' + code);
            };

            this.makeTransaction = function(credentails, code, values) {
                var values = JSON.parse(JSON.stringify(values));

                values._method = 'PUT';
                
                return ApiRequest.post('/api/voucher/' + code, values);
            };
        });
    }
]);