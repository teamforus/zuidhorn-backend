var oauth2App = angular.module('oauth2App', ['ui.router', 'ngSanitize']);

oauth2App.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost(env_data.apiUrl);
}]);

oauth2App.config(['$stateProvider', '$locationProvider', function($stateProvider, $locationProvider) {
    if (env_data.html5Mode.enable)
        $locationProvider.html5Mode(true);

    $stateProvider
        .state({
            url: '/',
            name: 'welcome',
            component: 'welcomeComponent',
            data: {
                title: "Welcome"
            }
        })
        .state({
            url: '/sign-in',
            name: 'sign-in',
            component: 'signInComponent',
            data: {
                title: "Sign In"
            },
            onExit: function() {
                console.log('arguments', arguments);
            }
        })
        .state({
            url: '/sign-up',
            name: 'sign-up',
            component: 'signUpComponent',
            data: {
                title: "Sign Up"
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
        })
        .state({
            url: '/device-token',
            name: 'device-token',
            component: 'deviceTokenComponent',
            data: {
                title: "Device Token"
            }
        })
        .state({
            url: '/settings',
            name: 'settings',
            component: 'settingsComponent',
            data: {
                title: "Settings"
            }
        })
        .state({
            url: '/debug',
            name: 'debug',
            component: 'debugComponent',
            data: {
                title: "Debug tool"
            }
        })
        .state({
            url: '/panel',
            name: 'panel',
            component: 'panelComponent',
            data: {
                title: "Panel"
            }
        })
        .state({
            url: '/voucher-form/:voucherCode',
            name: 'voucher-form',
            component: 'voucherFormComponent',
            data: {
                title: "Voucher details"
            },
            resolve: {
                voucher: ['VoucherService', '$stateParams', function(VoucherService, $stateParams) {
                    return VoucherService.checkCode($stateParams.voucherCode).then(function(response) {
                        return response.data;
                    });
                }]
            }
        })
        .state({
            url: '/voucher-success',
            name: 'voucher-success',
            component: 'voucherSuccessComponent',
            data: {
                title: "Voucher success"
            }
        })
        .state({
            url: '/device-pending',
            name: 'device-pending',
            component: 'devicePendingComponent',
            data: {
                title: "Unauthorised device"
            }
        })
        .state({
            url: '/shopkeeper-pending',
            name: 'shopkeeper-pending',
            component: 'shopkeeperPendingComponent',
            data: {
                title: "Shopkeeper pending"
            }
        })
        .state({
            url: '/shopkeeper-declined',
            name: 'shopkeeper-declined',
            component: 'shopkeeperDeclinedComponent',
            data: {
                title: "Shopkeeper declined"
            }
        });
}]);

if (!env_data.html5Mode.enable)
    if (!document.location.hash)
        document.location.hash = '#!/';