var shopkeeperApp = angular.module('shopkeeperApp', ['ui.router']);

shopkeeperApp.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost('http://192.168.0.108:8000');
    /// ApiRequestProvider.setHost('http://mvp.forus.io/client');
}]);

shopkeeperApp.config(['$stateProvider', function($stateProvider) {
    $stateProvider
        .state({
            url: '/',
            name: 'welcome',
            component: 'landingComponent',
            data: {
                title: "Welcome"
            }
        })
        .state({
            url: '/sign-out',
            name: 'sign-out',
            controller: ['$scope', '$state', 'AuthService', function($scope, $state, AuthService) {
                AuthService.signOut();
                $state.go('welcome');

                $scope.$emit('auth:sign-out');
            }]
        });

    // Profile
    $stateProvider
        .state({
            url: '/profile/edit',
            name: 'profile-edit',
            component: 'panelProfileEditComponent',
            data: {
                title: "Offices"
            }
        })

    // Offices crud
    $stateProvider
        .state({
            url: '/panel/offices',
            name: 'panel-offices',
            component: 'panelOfficesComponent',
            data: {
                title: "Offices"
            }
        })
        .state({
            url: '/panel/offices/create',
            name: 'panel-offices-create',
            component: 'panelOfficesCreateComponent',
            data: {
                title: "Offices"
            }
        })
        .state({
            url: '/panel/offices/:id/edit',
            name: 'panel-offices-edit',
            component: 'panelOfficesEditComponent',
            data: {
                title: "Offices"
            },
            params: {
                id: null
            }
        });

    // Transactions crud
    $stateProvider
        .state({
            url: '/panel/transactions',
            name: 'panel-transactions',
            component: 'panelTransactionsComponent',
            data: {
                title: "Offices"
            }
        });
}]);

if (!document.location.hash)
    document.location.hash = '#!/';