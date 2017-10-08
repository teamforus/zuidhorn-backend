municipalityApp.service('BugetService', [
    '$http',
    '$q',
    'ApiRequest',
    function(
        $http,
        $q,
        ApiRequest
    ) {
        return new(function() {
            this.getBuget = function() {
                return ApiRequest.get('/api/buget');
            };

            this.updateBuget = function(values) {
                var values = JSON.parse(JSON.stringify(values));

                values._method = 'PUT';

                return ApiRequest.post('/api/buget', values);
            };
        });
    }
]);