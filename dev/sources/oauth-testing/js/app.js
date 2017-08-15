// Greetings
console.log('%cWelcome to Quick Dev Template!', 'color: green');

var displayVersion = function(name, version, installed) {
    console.log(name + ': %c' + version, 'color: ' + (installed ? 'green' : 'red'));
};

displayVersion('jQuery', (typeof jQuery != 'undefined' ? ('v' + jQuery.fn.jquery) : 'not installed!'), typeof jQuery != 'undefined');
displayVersion('Angular', (typeof angular != 'undefined' ? ('v' + angular.version.full) : 'not installed!'), typeof angular != 'undefined');
displayVersion('Angular2', (typeof ng != 'undefined' ? 'v2.1.0' : 'not installed!'), typeof ng != 'undefined');


var oauth2App = angular.module('oauth2App', []);

oauth2App.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.post['Accept'] = 'application/json';
}]);

oauth2App.service('AuthService', ['$http', function($http) {
    var base_url = 'http://forus-mvp.dev.net'

    return new(function() {
        this.signIn = function(values) {
            return $http.post(base_url + '/oauth/token', {
                'grant_type': 'password',
                'client_id': 2,
                'client_secret': 'DKbwNT3Afz8bovp0BXvJX5jWudIRRW9VZPbzieVJ',
                'username': values.email || '',
                'password': values.password || '',
                'scope': '*',
            });
        };

        this.signUp = function(values) {
            return $http.post(base_url + '/api/shop-keeper/sign-up', values);
        };

        this.getUser = function(credentails) {
            return $http({
                'url': base_url + '/api/user',
                'data': {},
                headers: {
                    'Authorization': 'Bearer ' + credentails.access_token
                }
            });
        };
    });
}]);

oauth2App.service('VoucherService', ['$http', function($http) {
    var base_url = 'http://forus-mvp.dev.net'

    return new(function() {
        this.checkCode = function(credentails, code) {
            return $http({
                'url': base_url + '/api/vouchers/' + code,
                'data': {},
                headers: {
                    'Authorization': 'Bearer ' + credentails.access_token
                }
            });
        };

        this.makeTransaction = function(credentails, code, values) {
            var values = JSON.parse(JSON.stringify(values));

            values._method = 'PUT';

            return $http({
                'url': base_url + '/api/vouchers/' + code,
                'data': values,
                'method': 'POST',
                headers: {
                    'Authorization': 'Bearer ' + credentails.access_token
                }
            });
        };
    });
}]);

oauth2App.service('FormBuilderService', ['$http', function($http) {
    return new(function() {
        this.build = function() {
            return {
                values: {},
                errors: {},
                resetValues: function() {
                    return this.values = {};
                },
                resetErrors: function() {
                    return this.errors = {};
                },
                reset: function() {
                    return this.resetValues() & this.resetErrors();
                },
            };
        };
    });
}]);

oauth2App.directive('appView', [function() {
    return {
        templateUrl: './tpl/app-view.html'
    };
}]);

oauth2App.filter('pretty_json', function() {
    return function(_in) {
        return JSON.stringify(_in, null, '    ');
    }
});

oauth2App.filter('to_fixed', function() {
    return function(_in, size) {
        return parseFloat(_in).toFixed(size);
    }
});

oauth2App.controller('BaseController', [
    '$scope',
    '$http',
    'AuthService',
    'VoucherService',
    'FormBuilderService',
    function(
        $scope,
        $http,
        AuthService,
        VoucherService,
        FormBuilderService,
    ) {
        /*AuthService.signUp(
            "RAND-43598327523",
            "MD87MO2259ASV72028867100",
            "hcn@rminds.nl",
            "alpha silver jump 3 honey"
            ).then(function() {
            console.log('requestUserDetails', credentails);
        });*/

        $scope.view = "welcome";

        $scope.titles = {
            'welcome': 'Welcome',
            'sign_up': 'Sign Up',
            'sign_in': 'Sign In',
            'panel': 'Panel',
            'scan_screen': 'Scan QR-Code',
            'voucher_screen': 'Voucher found',
        };


        $scope.forms = {
            sign_in: FormBuilderService.build(),
            sign_up: FormBuilderService.build(),
            voucher: FormBuilderService.build(),
        }

        $scope.data = {};
        $scope.navigation = {};

        $scope.navigation.signOut = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.data = {};
            window.localStorage.clear();

            $scope.navigation.loadWelcome();
        };

        $scope.navigation.signIn = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.forms.sign_in.reset();
            $scope.view = "sign_in";
        };

        $scope.navigation.signInCancel = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.forms.sign_in.reset();
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
                form.errors.email = "Wrong E-mail or Password.";
            });
        };

        $scope.navigation.signUpCancel = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.forms.sign_in.reset();
            $scope.navigation.loadWelcome();
        };

        $scope.navigation.signUpSubmit = function(e, form) {
            e && (e.stopPropagation() & e.preventDefault());

            form.resetErrors();

            AuthService.signUp(form.values).then(function(response) {
                if (response.data.success)
                    $scope.navigation.signInSubmit(false, form);

                form.reset();
            }, function() {
                form.errors.email = "Wrong E-mail or Password.";
            });
        };

        $scope.navigation.signUp = function(e) {
            e && (e.stopPropagation() & e.preventDefault());

            $scope.view = "sign_up";
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
            }, function() {
                $scope.navigation.signOut();
            });
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
                'Well, we are not going to really scan an QR-Code, ' +
                'all this is still just an awesome simulation. That\'s ' +
                'why, you need to enter code yourself. \n' + 
                'One more thing, i have added one valid for you.', 
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
    }
]);