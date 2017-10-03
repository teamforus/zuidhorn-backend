shopkeeperApp.component('landingComponent', {
    templateUrl: './tpl/pages/landing.html',
    controller: [
        '$rootScope',
        '$state',
        '$scope',
        'CredentialsService',
        function(
            $rootScope,
            $state,
            $scope,
            CredentialsService
        ) {
            var ctrl = this;
        }
    ]
});