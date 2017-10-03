shopkeeperApp.service('TransactionService', [
    '$http',
    'ApiRequest',
    function(
        $http,
        ApiRequest
    ) {
        return new(function() {
            this.getTransactions = function() {
                return ApiRequest.get('/api/transactions');
            };

            this.countTransactions = function() {
                return ApiRequest.get('/api/transactions/count');
            };
        });
    }
]);