app.service('DataUploadService', ['$http', function($http) {
    var service = {
        submitData: function(buget_name, data, categories) {
            return $http.post('/ajax/buget/submit-data', {
                buget_name: buget_name,
                data: data,
                categories: categories,
                _method: 'PUT'
            });
        }
    };

    return service;
}]);