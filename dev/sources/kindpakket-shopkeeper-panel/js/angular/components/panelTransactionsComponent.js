shopkeeperApp.component('panelTransactionsComponent', {
    templateUrl: './tpl/pages/panel/transactions.html',
    controller: [
        '$rootScope',
        '$state',
        '$scope',
        '$timeout',
        'TransactionService',
        function(
            $rootScope,
            $state,
            $scope,
            $timeout,
            TransactionService
        ) {
            var ctrl = this;
            
            ctrl.$onInit = function() {
                TransactionService.getTransactions().then(function(response) {
                    ctrl.transactions = response.data;
                }, console.log);
            };
        }
    ]
});