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
            this.checkCode = function(code) {
                return ApiRequest.get('/api/vouchers/' + code);
            };

            this.makeTransaction = function(code, values) {
                return ApiRequest.post('/api/vouchers/' + code + '/transactions', values);
            };

            this.markTransactionForRefund = function(code, transaction, values) {
                var values = JSON.parse(JSON.stringify(values));

                values._method = 'PUT';
                
                return ApiRequest.post('/api/vouchers/' + code + '/transactions/' + transaction + '/refund');
            };

            this.getTransactions = function(code) {
                return ApiRequest.get('/api/vouchers/' + code + '/transactions');
            }
        });
    }
]);