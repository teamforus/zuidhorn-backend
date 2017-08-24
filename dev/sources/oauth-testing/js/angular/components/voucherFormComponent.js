oauth2App.component('voucherFormComponent', {
    templateUrl: './tpl/pages/voucher-form.html',
    controller: [
        '$scope',
        '$state',
        '$stateParams',
        'VoucherService',
        'FormBuilderService',
        function(
            $scope,
            $state,
            $stateParams,
            VoucherService,
            FormBuilderService) {
            var ctrl = this;

            ctrl.form = FormBuilderService.build();

            ctrl.submit = function(e) {
                e.preventDefault() & e.stopPropagation();
                
                if (ctrl.form.submited)
                    return false;

                ctrl.form.resetErrors();
                ctrl.form.submited = true;

                VoucherService.makeTransaction(
                    $stateParams.voucherCode,
                    ctrl.form.values
                ).then(function(response) {
                    $state.go('voucher-success');
                }, function(response) {
                    ctrl.form.submited = false;
                    ctrl.form.errors = response.data;
                });
            };
        }
    ],
    bindings: {
        voucher: '='
    }
});