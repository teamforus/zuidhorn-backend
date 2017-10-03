var kindpakketApp = angular.module('kindpakketApp', []);

kindpakketApp.config(['ApiRequestProvider', function(ApiRequestProvider) {
    ApiRequestProvider.setHost('http://forus-mvp.dev.net/client');
    /// ApiRequestProvider.setHost('http://mvp.forus.io/client');
}]);