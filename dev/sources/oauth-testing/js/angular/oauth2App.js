var oauth2App = angular.module('oauth2App', []);

oauth2App.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost('http://forus-mvp.dev.net');
}]);

oauth2App.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.headers.post['Accept'] = 'application/json';
}]);
