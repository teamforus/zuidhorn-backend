app.service('DataUploadService', ['$http', function($http) {
    var service = {
        submitData: function(data) {
            return $http.post('/ajax/buget/submit-data', {
                data: data,
                _method: 'PUT'
            });
        }
    };

    return service;
}]);