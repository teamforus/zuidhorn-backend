oauth2App.component('deviceTokenComponent', {
    templateUrl: './tpl/pages/device-token.html',
    controller: [
        '$scope',
        '$state',
        'FormBuilderService',
        'AuthService',
        function(
            $scope,
            $state,
            FormBuilderService,
            AuthService
        ) {
            var ctrl = this;

            ctrl.device_authorized = false;

            ctrl.forms = {};
            ctrl.forms.authorize_token = FormBuilderService.build();

            ctrl.submitForm = function(e, form) {
                e && (e.preventDefault() & e.stopPropagation());

                var token = form.values.token || false;

                AuthService.authorizeToken(token).then(function(response) {
                    ctrl.device_authorized = true;
                }, console.log);
            };
        }
    ]
});