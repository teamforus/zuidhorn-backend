oauth2App.service('TransactionService', [
    '$http',
    'ApiRequest',
    function(
        $http,
        ApiRequest
    ) {
        return new(function() {
            this.doRefund = function(transactions) {
                return ApiRequest.get('/api/transactions/refund', {
                    transactions: transactions
                });
            };
        });
    }
]);