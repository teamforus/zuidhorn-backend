oauth2App.component('debugComponent', {
    templateUrl: './tpl/pages/debug.html',
    controller: [
        '$state',
        'DeviceIdService',
        'FormBuilderService',
        'ApiRequest',
        function(
            $state,
            DeviceIdService,
            FormBuilderService,
            ApiRequest
        ) {
            var ctrl = this;

            ctrl.response = false;
            ctrl.devices = DeviceIdService.getOptions();

            ctrl.form = FormBuilderService.build();

            ctrl.methods = [{
                "name": "GET",
                "id": "GET"
            }, {
                "name": "POST",
                "id": "POST"
            }];

            ctrl.form.values.device = DeviceIdService.getDeviceId();
            ctrl.form.values.endpoint = '/';
            ctrl.form.values.method = ctrl.methods[0];
            ctrl.form.values.params = JSON.stringify({}, null, '    ');
            ctrl.form.values.headers = JSON.stringify({}, null, '    ');

            ctrl.submit = function(e) {
                e.preventDefault() & e.stopPropagation();

                var values = JSON.parse(JSON.stringify(ctrl.form.values));

                values.headers = JSON.parse(values.headers);
                values.headers['Device-Id'] = ctrl.form.values.device.id;

                ApiRequest.ajax(
                    values.method.id,
                    values.endpoint,
                    JSON.parse(values.params),
                    values.headers,
                    true
                ).then(function(response) {
                    ctrl.response = response;
                    console.log(ctrl, response);
                }, function(response) {
                    ctrl.response = response;
                });
            };
        }
    ]
});