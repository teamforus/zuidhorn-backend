shopkeeperApp.service('OfficeService', [
    '$http',
    'ApiRequest',
    function(
        $http,
        ApiRequest
    ) {
        return new(function() {
            this.getOffices = function() {
                return ApiRequest.get('/offices');
            };

            this.getOffice = function(id) {
                return ApiRequest.get('/offices/' + id);
            };

            this.countOffices = function() {
                return ApiRequest.get('/offices/count');
            };

            this.updateOffice = function(id, values) {
                return ApiRequest.post('/offices/' + id, values);
            };

            this.create = function(values) {
                return ApiRequest.post('/offices', values);
            };

            this.update = function(id, values) {
                values._method = "PUT";

                return ApiRequest.post('/offices/' + id, values);
            };

            this.updatePhoto = function(id, image) {
                var formData = new FormData();

                formData.append('image', image);
                formData.append('_method', 'PUT');

                return ApiRequest.post('/offices/' + id + '/image', formData, {
                    'Content-Type': undefined
                });
            };
        });
    }
]);