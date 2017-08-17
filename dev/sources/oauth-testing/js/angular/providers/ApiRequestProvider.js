oauth2App.provider('ApiRequest', function() {
    var base_url = 'http://forus-mvp.dev.net'

    return new(function() {
        var host = false;

        this.setHost = function(_host) {
            host = _host;
        };

        this.$get = [
            '$q',
            '$http',
            '$rootScope',
            'DeviceIdService',
            function(
                $q,
                $http,
                $rootScope,
                DeviceIdService
            ) {

                var makeHeaders = function() {
                    var credentails = JSON.parse(localStorage.getItem('credentails'));

                    return {
                        'Authorization': 'Bearer ' + (credentails ? credentails.access_token : ''),
                        'Device-Id': DeviceIdService.getDeviceId().id,
                    };
                };

                var get = function(endpoint, data, headers) {
                    return ajax('GET', endpoint, data, headers);
                };

                var post = function(endpoint, data, headers) {
                    return ajax('POST', endpoint, data, headers);
                };

                var ajax = function(method, endpoint, data, headers) {
                    var params = {};

                    params.data = data || {};
                    params.headers = Object.assign(headers || {}, makeHeaders());

                    params.url = host + endpoint;
                    params.method = method;

                    return $q(function(done, reject) {
                        $http(params).then(function(response) {
                            done(response);
                        }, function(response) {
                            if (response.status == 401) {
                                if ((response.data.error == 'device-pending') ||
                                    (response.data.error == 'device-unknown'))
                                    return $rootScope.$broadcast(
                                        'device:unauthorized',
                                        response.data);
                            }

                            reject(response);
                        });
                    });
                };

                return {
                    get: get,
                    post: post,
                    ajax: ajax,
                }
            }
        ];
    });
});