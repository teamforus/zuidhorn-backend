oauth2App.component('signInComponent', {
    templateUrl: './tpl/pages/sign-in.html',
    controller: [
        '$scope',
        '$state',
        '$timeout',
        '$rootScope',
        'AuthService',
        'FormBuilderService',
        'CredentialsService',
        function(
            $scope,
            $state,
            $timeout,
            $rootScope,
            AuthService,
            FormBuilderService,
            CredentialsService
        ) {
            var ctrl = this;
            var destroyed = false;

            ctrl.form = FormBuilderService.build();

            ctrl.timeout = false;
            ctrl.interval = false;

            var checkToken = function(token, success, error) {
                AuthService.checkTokenState(token).then(function(response) {
                    if (!destroyed) {
                        if (response.data.authorized) {
                            success(response);
                        } else {
                            error(response);
                        }
                    }
                }, function(response) {
                    if (!destroyed)
                        error(response);
                });
            };

            AuthService.generateToken().then(function(response) {
                ctrl.token = response.data.token;
                var count = 0;

                var qr_code = document.createElement("div");

                var qrcode = new QRCode(qr_code, {
                    text: ctrl.token,
                    width: 300,
                    height: 300,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                $(qr_code).find('img').bind('load', function() {
                    ctrl.timeout = $timeout(function() {
                        ctrl.qr_code = $(qr_code).find('img').attr('src');
                    }, 0);
                });

                var successHandler = function(response) {
                    CredentialsService.set(response.data);
                    ctrl.form.reset();
                    $state.go('panel');

                    $scope.$emit('auth:sign-in');
                };

                var errorHandler = function() {
                    ctrl.timeout = $timeout(function() {
                        checkToken(ctrl.token, successHandler, errorHandler);
                    }, 2500);
                };

                checkToken(ctrl.token, successHandler, errorHandler);
            });

            $scope.$on("$destroy", function() {
                $timeout.cancel(ctrl.timeout);
                destroyed = true;
            });
        }
    ]
});