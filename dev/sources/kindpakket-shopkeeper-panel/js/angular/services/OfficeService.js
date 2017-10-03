shopkeeperApp.service('OfficeService', [
    '$http',
    'ApiRequest',
    function(
        $http,
        ApiRequest
    ) {
        return new(function() {
            this.getOffices = function() {
                return ApiRequest.get('/api/offices');
            };

            this.getOffice = function(id) {
                return ApiRequest.get('/api/offices/' + id);
            };

            this.countOffices = function() {
                return ApiRequest.get('/api/offices/count');
            };

            this.updateOffice = function(id, values) {
                return ApiRequest.post('/api/offices/' + id, values);
            };

            this.create = function(values) {
                return ApiRequest.post('/api/offices', values);
            };

            this.update = function(id, values) {
                values._method = "PUT";

                return ApiRequest.post('/api/offices/' + id, values);
            };

            this.updatePhoto = function(id, image) {
                var formData = new FormData();

                formData.append('image', image);
                formData.append('_method', 'PUT');

                return ApiRequest.post('/api/offices/' + id + '/image', formData, {
                    'Content-Type': undefined
                });
            };
        });
    }
]);