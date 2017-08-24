oauth2App.component('signInComponent', {
    templateUrl: './tpl/pages/sign-in.html',
    controller: [
        '$scope',
        '$state',
        'AuthService',
        'FormBuilderService',
        'CredentialsService',
        function(
            $scope,
            $state,
            AuthService,
            FormBuilderService,
            CredentialsService
        ) {
            var ctrl = this;

            ctrl.form = FormBuilderService.build();

            ctrl.submit = function(e) {
                e.preventDefault() & e.stopPropagation();
                
                if (ctrl.form.submited)
                    return false;

                ctrl.form.resetErrors();
                ctrl.form.submited = true;

                AuthService.signIn(ctrl.form.values).then(function(response) {
                    CredentialsService.set(response.data);
                    ctrl.form.reset();
                    $state.go('panel');

                    $scope.$emit('auth:sign-in');
                }, function() {
                    ctrl.form.submited = false;
                    ctrl.form.errors.email = ["Wrong E-mail or Password."];
                });
            };
        }
    ]
});