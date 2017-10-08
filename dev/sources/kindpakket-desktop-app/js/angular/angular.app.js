var municipalityApp = angular.module('municipalityApp', ['ui.router']);

municipalityApp.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost('http://forus-mvp.dev.net/municipality');
    /// ApiRequestProvider.setHost('http://mvp.forus.io/client');
}]);

municipalityApp.config(['$qProvider', function($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);

municipalityApp.config(['$stateProvider', function($stateProvider) {
    // Base router
    $stateProvider
        .state({
            url: '/',
            name: 'welcome',
            controller: [
                '$rootScope', '$scope', '$state', 'AuthService', 'CredentialsService',
                function($rootScope, $scope, $state, AuthService, CredentialsService) {
                    var credentails = CredentialsService.get();

                    if ((credentails == null) || (!credentails.access_token))
                        return $state.go('sign-in');

                    AuthService.getUser().then(function(response) {
                        $rootScope.user = response.data;

                        $permission = $rootScope.user.permissions[0] || false;

                        switch ($permission.key) {
                            case 'buget_upload':
                                $state.go('buget-upload');
                                break;
                            case 'buget_manage':
                                $state.go('buget-manage');
                                break;
                            case 'shopkeeper_manage':
                                $state.go('shopkeeper-manage');
                                break;
                            default:
                                $state.go('sign-out');
                                break;
                        }

                    }, console.log);
                }
            ]
        });

    // Auth routes
    $stateProvider
        .state({
            url: '/auth/sign-in',
            name: 'sign-in',
            component: 'authSignInComponent',
            data: {
                title: "Welcome",
                layout: 'auth'
            }
        })
        .state({
            url: '/auth/sign-out',
            name: 'sign-out',
            controller: ['$scope', '$state', 'AuthService', function($scope, $state, AuthService) {
                AuthService.signOut();
                $state.go('welcome');

                $scope.$emit('auth:sign-out');
            }]
        });

    // Buget uploader
    $stateProvider
        .state({
            url: '/buget/upload',
            name: 'buget-upload',
            component: 'panelBugetUploadComponent',
            data: {
                title: "Offices",
                layout: 'panel'
            }
        });

    // Buget manage
    $stateProvider
        .state({
            url: '/buget/manage',
            name: 'buget-manage',
            component: 'panelBugetManageComponent',
            data: {
                title: "Offices",
                layout: 'panel'
            }
        });

    // Shopkeeper manage
    $stateProvider
        .state({
            url: '/shopkeeper/manage',
            name: 'shopkeeper-manage',
            component: 'panelShopkeeperManageComponent',
            data: {
                title: "Offices",
                layout: 'panel'
            }
        });
}]);

if (!document.location.hash)
    document.location.hash = '#!/';