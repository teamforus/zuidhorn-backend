oauth2App.controller('BaseController', [
    '$scope',
    '$rootScope',
    '$state',
    '$http',
    'AuthService',
    'VoucherService',
    'DeviceIdService',
    'CredentialsService',
    'FormBuilderService',
    function(
        $scope,
        $rootScope,
        $state,
        $http,
        AuthService,
        VoucherService,
        DeviceIdService,
        CredentialsService,
        FormBuilderService
    ) {
        $rootScope.$state = $scope.$state = $state;

        $scope.$on('auth:sign-in', function() {
            $scope.credentials = CredentialsService.get();
        });

        $scope.$on('auth:sign-up', function() {
            $scope.credentials = CredentialsService.get();
        });

        $scope.$on('auth:sign-out', function() {
            $scope.credentials = false;
        });

        $scope.$on('device:unauthorized', function() {
            $state.go('device-pending');
        });

        $scope.$on('shopkeeper:pending', function() {
            $state.go('shopkeeper-pending');
        });

        $scope.$on('shopkeeper:declined', function() {
            $state.go('shopkeeper-declined');
        });

        $scope.$on('auth:unauthenticated', function() {
            AuthService.signOut();
            $state.go('welcome');
        });

        $scope.credentials = CredentialsService.get();

        setTimeout(function() {
            if ($scope.credentials && $state.current.name == 'welcome')
                $state.go('panel');
        }, 100);
    }
]);