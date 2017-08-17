oauth2App.service('VoucherService', [
    '$http',
    'DeviceIdService',
    function(
        $http,
        DeviceIdService
    ) {
        var base_url = 'http://forus-mvp.dev.net'

        return new(function() {
            this.checkCode = function(credentails, code) {
                return $http({
                    'url': base_url + '/api/vouchers/' + code,
                    'data': {},
                    headers: {
                        'Authorization': 'Bearer ' + credentails.access_token,
                        'Device-Id': DeviceIdService.getDeviceId().id,
                        'Accept': 'application/json'
                    }
                });
            };

            this.makeTransaction = function(credentails, code, values) {
                var values = JSON.parse(JSON.stringify(values));

                values._method = 'PUT';

                return $http({
                    'url': base_url + '/api/vouchers/' + code,
                    'data': values,
                    'method': 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + credentails.access_token,
                        'Device-Id': DeviceIdService.getDeviceId().id,
                        'Accept': 'application/json'
                    }
                });
            };
        });
    }
]);