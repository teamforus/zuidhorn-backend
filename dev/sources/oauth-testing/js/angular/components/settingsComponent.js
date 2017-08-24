oauth2App.component('settingsComponent', {
    templateUrl: './tpl/pages/settings.html',
    controller: [
        '$state',
        'DeviceIdService',
        'FormBuilderService',
        function(
            $state,
            DeviceIdService,
            FormBuilderService
        ) {
            var ctrl = this;

            ctrl.devices = DeviceIdService.getOptions();

            ctrl.form = FormBuilderService.build();
            ctrl.form.values.device = DeviceIdService.getDeviceId();

            ctrl.submit = function(e) {
                e.preventDefault() & e.stopPropagation();

                DeviceIdService.setDeviceId(ctrl.form.values.device);

                $state.go('welcome');
            };
        }
    ]
});