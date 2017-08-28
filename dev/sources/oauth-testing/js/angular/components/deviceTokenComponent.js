oauth2App.component('deviceTokenComponent', {
    templateUrl: './tpl/pages/device-token.html',
    controller: [
        '$scope',
        '$state',
        'AuthService',
        function(
            $scope,
            $state,
            AuthService
        ) {
            var ctrl = this;

            AuthService.createDeviceToken().then(function(response) {
                ctrl.token = response.data.token;
            });
        }
    ]
});