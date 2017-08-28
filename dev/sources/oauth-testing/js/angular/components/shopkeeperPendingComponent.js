oauth2App.component('shopkeeperPendingComponent', {
    templateUrl: './tpl/pages/shopkeeper-pending.html',
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