app.service('CategoryService', ['$http', function($http) {
    return {
        categoryOptions: function() {
            return $http.get('/ajax/category/select-option');
        }
    };
}]);