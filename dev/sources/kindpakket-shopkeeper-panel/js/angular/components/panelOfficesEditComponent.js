shopkeeperApp.component('panelOfficesEditComponent', {
    templateUrl: './tpl/pages/panel/offices-edit.html',
    controller: [
        '$q',
        '$rootScope',
        '$state',
        '$stateParams',
        '$scope',
        'AuthService',
        'CategoryService',
        'OfficeService',
        'FormBuilderService',
        'CredentialsService',
        function(
            $q,
            $rootScope,
            $state,
            $stateParams,
            $scope,
            AuthService,
            CategoryService,
            OfficeService,
            FormBuilderService,
            CredentialsService
        ) {
            var ctrl = this;

            ctrl.form = {};
            ctrl.form.office = FormBuilderService.build();

            var unwatcher = $rootScope.$watch('user', function(user) {
                if (!user)
                    return;

                init();
                unwatcher();
            });

            var init = function() {
                var promises = [];

                var map = {
                    office: 0,
                };

                promises[map.office] = $q(function(resolve, reject) {
                    OfficeService.getOffice($stateParams.id).then(function(response) {
                        resolve(response.data);
                    });
                });

                $q.all(promises).then(function(promises) {
                    var office = promises[map.office];

                    ctrl.office = office;
                    ctrl.schedule_options = {};

                    for (var i = 0; i < 24; i++) {
                        var hour = (i <= 9 ? '0' : '') + i;

                        ctrl.schedule_options[hour + ':00'] = hour + ':00';
                        ctrl.schedule_options[hour + ':30'] = hour + ':30';
                    }

                    // fill profile form values
                    ctrl.form.office.fillValues(office, ["email", "phone", "address", 'schedules']);

                    // submit form to api
                    ctrl.submitForm = function(e, form) {
                        e && e.preventDefault() && e.stopPropagation();

                        if (form.is_locked)
                            return;

                        form.lock();

                        OfficeService.update(office.id, form.values).then(function() {
                            form.reset().unlock();

                            $scope.$emit('user:sync');
                            $state.go('panel-offices');
                        }, function(response) {
                            form.fillErrors(response.data).unlock();
                        });
                    };

                    ctrl.selectPhoto = function(e) {
                        e && e.preventDefault() && e.stopPropagation();

                        var input = $('<input type="file" />');

                        input.click();

                        input.unbind('change').bind('change', function(e) {
                            OfficeService.updatePhoto(
                                office.id,
                                e.target.files[0]
                            ).then(function(response) {
                                OfficeService.getOffice($stateParams.id).then(function(response) {
                                    ctrl.office = response.data;
                                });
                            });
                        });
                    }
                });
            };
        }
    ]
});