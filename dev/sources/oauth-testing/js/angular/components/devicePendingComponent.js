oauth2App.component('devicePendingComponent', {
    templateUrl: './tpl/pages/device-pending.html',
    controller: [
        '$state',
        'AuthService',
        function(
            $state,
            AuthService
        ) {
            var ctrl = this;

            ctrl.refresh = function(e) {
                e.preventDefault() & e.stopPropagation();

                AuthService.getUser().then(function(response) {
                    $state.go('panel');
                });
            }
        }
    ]
});