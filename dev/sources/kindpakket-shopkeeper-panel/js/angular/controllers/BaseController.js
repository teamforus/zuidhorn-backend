shopkeeperApp.controller('BaseController', [
    '$rootScope',
    '$scope',
    '$state',
    'AuthService',
    'CategoryService',
    'FormBuilderService',
    'CredentialsService',
    'TransactionService',
    'ShopKeeperService',
    'OfficeService',
    function(
        $rootScope,
        $scope,
        $state,
        AuthService,
        CategoryService,
        FormBuilderService,
        CredentialsService,
        TransactionService,
        ShopKeeperService,
        OfficeService
    ) {
        $rootScope.$state = $state; 
        $rootScope.html5Mode = env_data.html5Mode; 
        
        $scope.$on('auth:sign-in', function() {
            $rootScope.credentials = CredentialsService.get();
            $scope.initializePanel();
        });

        $scope.$on('auth:sign-up', function() {
            $rootScope.credentials = CredentialsService.get();
        });

        $scope.$on('auth:sign-out', function() {
            $rootScope.credentials = false;
        });

        $scope.$on('auth:unauthenticated', function() {
            $state.go('sign-out');
        });

        $scope.$on('user:sync', function() {
            syncUserData();
        });

        $scope.$on('offices:sync', function() {
            syncOfficesData();
        });

        var syncUserData = function() {
            AuthService.getUser().then(function(response) {
                $rootScope.user = response.data || {};

                ShopKeeperService.getShopKeeperCategories($rootScope.user.shop_keeper.id).then(function(response) {
                    $rootScope.shopkeeper_categories = response.data || {};
                });
            });
        }

        var syncOfficesData = function() {
            OfficeService.countOffices().then(function(response) {
                $rootScope.count_offices = response.data.count || 0;
            });
        }

        $rootScope.credentials = CredentialsService.get();

        $scope.initializePanel = function() {

            TransactionService.countTransactions().then(function(response) {
                $rootScope.count_transactions = response.data.count || 0;
            });

            syncUserData();
            syncOfficesData();
        };

        if ($rootScope.credentials)
            $scope.initializePanel();
    }
]);