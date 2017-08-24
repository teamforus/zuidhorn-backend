oauth2App.component('welcomeComponent', {
    templateUrl: './tpl/pages/welcome.html',
    controller: [
        '$state',
        'CredentialsService',
        function(
            $state,
            CredentialsService
        ) {
            var ctrl = this;

            if (CredentialsService.get())
                $state.go('panel');
        }
    ]
});