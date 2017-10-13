kindpakketApp.service('CategoryService', ['$http', 'ApiRequest', function($http, ApiRequest) {
    var getCategories = function() {
        return ApiRequest.get('/categories');
    };

    return {
        getCategories: getCategories
    };
}]);