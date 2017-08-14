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
    var base_url = 'http://localhost:8000'

    return new(function() {
        this.login = function(email, pass) {
            return $http.post(base_url + '/oauth/token', {
                'grant_type': 'password',
                'client_id': 2,
                'client_secret': 'DKbwNT3Afz8bovp0BXvJX5jWudIRRW9VZPbzieVJ',
                'username': email,
                'password': pass,
                'scope': '*',
            });
        };

        this.getUser = function(auth_user) {
            return $http({
                'url': base_url + '/api/user',
                'data': {},
                headers: {
                    'Authorization': 'Bearer ' + auth_user.access_token
                }
            });
        };
    });
}]);

oauth2App.controller('BaseController', [
    '$http',
    'AuthService',
    function(
        $http,
        AuthService
    ) {
        var auth_user = window.localStorage.getItem('auth_user', false);

        if (auth_user)
            auth_user = JSON.parse(auth_user)

        var requestUserDetails = function() {
            AuthService.getUser(auth_user).then(function() {
                console.log('requestUserDetails', auth_user);
            });
        };

        if (!auth_user) {
            AuthService.login('hcn@rminds.nl', 'the-pass').then(function(response) {
                auth_user = response.data;

                if (response.status == 200) {
                    window.localStorage.setItem('auth_user', JSON.stringify(auth_user));
                }

                requestUserDetails();
            });
        } else {
            requestUserDetails();
        }
    }
]);