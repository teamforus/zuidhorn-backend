shopkeeperApp.service('CategoryService', [
    '$http',
    '$q',
    'ApiRequest',
    function(
        $http,
        $q,
        ApiRequest
    ) {
        return {
            getCategories: function() {
                return ApiRequest.get('/categories');
            }
        };
    }
]);