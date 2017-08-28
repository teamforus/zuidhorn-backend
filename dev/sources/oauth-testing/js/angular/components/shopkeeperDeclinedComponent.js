oauth2App.component('shopkeeperDeclinedComponent', {
    templateUrl: './tpl/pages/shopkeeper-declined.html',
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