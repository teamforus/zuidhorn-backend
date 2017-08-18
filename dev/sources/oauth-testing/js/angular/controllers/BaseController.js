oauth2App.controller('BaseController', [
    '$scope',
    '$http',
    'AuthService',
    'VoucherService',
    'DeviceIdService',
    'FormBuilderService',
    function(
        $scope,
        $http,
        AuthService,
        VoucherService,
        DeviceIdService,
        FormBuilderService,
    ) {
        $scope.view = "welcome";

        $scope.titles = {
            'welcome': 'Welcome',
            'sign_up': 'Sign Up',
            'sign_in': 'Sign In',
            'panel': 'Panel',
            'scan_screen': 'Scan QR-Code',
            'voucher_screen': 'Voucher found',
            'settings': 'Settings',
            'device_pending_screen': 'Device confirmation',
        };

        $scope.forms = {
            sign_in: FormBuilderService.build(),
            sign_up: FormBuilderService.build(),
            voucher: FormBuilderService.build(),
            settings: FormBuilderService.build(),
        }

        $scope.data = {};
        $scope.navigation = {};

        $scope.deviceIdService = DeviceIdService;

        if ($scope.deviceIdService.getDeviceId() === null)
            $scope.deviceIdService.setDeviceId($scope.deviceIdService.getOptions()[0]);

        $scope.forms.settings.values.device_id = $scope.deviceIdService.getDeviceId();

        $scope.navigation.signOut = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.data = {};
            AuthService.signOut();

            $scope.navigation.loadWelcome();
        };

        $scope.navigation.signIn = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "sign_in";
        };

        $scope.navigation.signInCancel = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.reset();

            $scope.navigation.loadWelcome();
        };

        $scope.navigation.signInSubmit = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.resetErrors();

            AuthService.signIn(form.values).then(function(response) {
                $scope.data.credentails = response.data;

                window.localStorage.setItem(
                    'credentails', JSON.stringify($scope.data.credentails));

                form.reset();
                $scope.navigation.loadPanel();
            }, function() {
                form.errors.email = ["Wrong E-mail or Password."];
            });
        };

        $scope.navigation.signUp = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "sign_up";
        };

        $scope.navigation.signUpSubmit = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.resetErrors();

            AuthService.signUp(form.values).then(function(response) {
                $scope.data.credentails = response.data;

                window.localStorage.setItem(
                    'credentails', JSON.stringify($scope.data.credentails));

                form.reset();
                $scope.navigation.loadPanel();
            }, function(response) {
                form.errors = response.data;
            });
        };

        $scope.navigation.signUpCancel = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.reset();

            $scope.navigation.loadWelcome();
        };

        $scope.navigation.settingsFormCancel = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.navigation.loadWelcome();
        };

        $scope.navigation.settingsFormSubmit = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.resetErrors();
            $scope.deviceIdService.setDeviceId(form.values.device_id);

            $scope.navigation.loadWelcome();
        };


        $scope.navigation.voucherFormCancel = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.forms.sign_in.reset();
            $scope.navigation.loadPanel();
        };

        $scope.navigation.voucherFormSubmit = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.resetErrors();

            VoucherService.makeTransaction(
                $scope.data.credentails,
                $scope.data.voucher.code,
                form.values
            ).then(function(response) {
                $scope.navigation.loadVoucherSuccessScreen();
            }, function(response) {
                form.errors = response.data;
            });
        };

        $scope.navigation.loadVoucherSuccessScreen = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "voucher_success_screen";
        };

        $scope.navigation.closeVoucherSuccessScreen = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.navigation.loadPanel();
        };

        $scope.navigation.loadWelcome = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "welcome";
        };

        $scope.navigation.loadSettings = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "settings";
        }

        $scope.navigation.loadPanel = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.data.credentails = window.localStorage.getItem('credentails', false);

            if (!$scope.data.credentails)
                return $scope.navigation.loadWelcome();

            $scope.data.credentails = JSON.parse($scope.data.credentails)

            if ($scope.data.auth_user)
                return $scope.view = 'panel';

            $scope.data.auth_user = {};

            AuthService.getUser($scope.data.credentails).then(function(response) {
                $scope.data.auth_user = response.data.response;

                $scope.view = 'panel';
            }, function(response) {
                /*if (response.status == 401 && ((response.data.error == 'device-pending') || (response.data.error == 'device-unknown')))
                    return $scope.navigation.loadDevicePendingPage();*/

                // return $scope.navigation.signOut();
            });
        };

        $scope.navigation.refresh = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            document.location.reload();
        };

        $scope.navigation.loadDevicePendingPage = function() {
            $scope.view = 'device_pending_screen';
        };

        $scope.navigation.loadVoucherScanScreen = function() {
            $scope.view = 'voucher_scan_screen';
        };

        $scope.navigation.loadVoucherFormScreen = function() {
            $scope.view = 'voucher_form_screen';
        };

        $scope.navigation.scanQrCode = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            var qr_code = window.prompt(
                'QR-Code.',
                'VIES-2F9M-J8RR-TC5W');

            $scope.navigation.loadVoucherScanScreen();

            VoucherService.checkCode(
                $scope.data.credentails,
                qr_code
            ).then(function(response) {
                console.log(response);

                $scope.data.voucher = response.data.response;

                $scope.navigation.loadVoucherFormScreen();
            }, function(response) {
                window.alert(
                    'Sorry, but nothing found for "' + qr_code + '"!');

                $scope.navigation.loadPanel();
            });
        };

        $scope.navigation.loadPanel();

        $scope.$on('device:unauthorized', function(e, data) {
            $scope.navigation.loadDevicePendingPage();
        });
    }
]);