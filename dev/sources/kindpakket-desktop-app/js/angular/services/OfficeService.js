municipalityApp.service('OfficeService', [
    '$http',
    'ApiRequest',
    function(
        $http,
        ApiRequest
    ) {
        return new(function() {
            this.list = function() {
                return ApiRequest.get('/api/offices');
            };

            this.find = function(id) {
                return ApiRequest.get('/api/offices/' + id);
            };
        });
    }
]);