var shopkeeperApp = angular.module('shopkeeperApp', ['ui.router']);

shopkeeperApp.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost(env_data.apiUrl);
}]);

shopkeeperApp.config(['$stateProvider', '$locationProvider', function($stateProvider, $locationProvider) {
    if (env_data.html5Mode.enable)
        $locationProvider.html5Mode(true);

    $stateProvider
        .state({
            url: '/',
            name: 'welcome',
            component: 'landingComponent',
            data: {
                title: "Home"
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
                title: "Edit Profile"
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
                title: "Add office"
            }
        })
        .state({
            url: '/panel/offices/:id/edit',
            name: 'panel-offices-edit',
            component: 'panelOfficesEditComponent',
            data: {
                title: "Edit office"
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
                title: "Transactions"
            }
        });
}]);

if (!env_data.html5Mode.enable)
    if (!document.location.hash)
        document.location.hash = '#!/';