municipalityApp.component('panelBugetManageComponent', {
    templateUrl: './tpl/pages/buget/manage.html',
    controller: [
        '$rootScope',
        '$state',
        '$scope',
        '$timeout',
        'BugetService',
        'CredentialsService',
        'FormBuilderService',
        function(
            $rootScope,
            $state,
            $scope,
            $timeout,
            BugetService,
            CredentialsService,
            FormBuilderService
        ) {
            var ctrl = this;

            ctrl.forms = {};
            ctrl.forms.buget = FormBuilderService.build();

            BugetService.getBuget().then(function(response) {
                var buget = ctrl.buget = response.data;

                ctrl.forms.buget.fillValues(buget, ['name', 'amount_per_child']);
            }, console.log);

            ctrl.submitBugetForm = function(e, form) {
                e && (e.stopPropagation() & e.preventDefault());

                if (form.is_locked)
                    return;

                form.lock();

                BugetService.updateBuget(form.values).then(function(response) {
                    form.resetErrors().unlock();
                    form.success = true;

                    if (typeof form.successTimeout == 'object')
                        $timeout.cancel(form.successTimeout);

                    form.successTimeout = $timeout(function() {
                        form.success = false;
                        form.successTimeout = false;
                    }, 1000);
                }, function(response) {
                    form.fillErrors(response.data).unlock();
                });
            };
        }
    ]
});