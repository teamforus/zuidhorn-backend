shopkeeperApp.component('panelOfficesCreateComponent', {
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
                var office = {};

                var photoForupload = false;

                ctrl.office = office;
                ctrl.schedule_options = {};

                for (var i = 0; i < 24; i++) {
                    var hour = (i <= 9 ? '0' : '') + i;

                    ctrl.schedule_options[hour + ':00'] = hour + ':00';
                    ctrl.schedule_options[hour + ':30'] = hour + ':30';
                }

                ctrl.office.schedules = [{
                    start_time: '09:00',
                    end_time: '17:00',
                }, {
                    start_time: '09:00',
                    end_time: '17:00',
                }, {
                    start_time: '09:00',
                    end_time: '17:00',
                }, {
                    start_time: '09:00',
                    end_time: '17:00',
                }, {
                    start_time: '09:00',
                    end_time: '17:00',
                }];

                // fill profile form values
                ctrl.form.office.fillValues(office, ["email", "phone", "address", 'schedules']);

                // submit form to api
                ctrl.submitForm = function(e, form) {
                    e && e.preventDefault() && e.stopPropagation();

                    if (form.is_locked)
                        return;

                    form.lock();

                    OfficeService.create(form.values).then(function(response) {
                        var office = response.data;

                        if (photoForupload) {
                            OfficeService.updatePhoto(
                                office.id,
                                photoForupload
                            ).then(function(response) {
                                form.reset().unlock();

                                $scope.$emit('offices:sync');
                                $state.go('panel-offices');
                            }, form.unlock);
                        } else {
                            form.reset().unlock();

                            $scope.$emit('offices:sync');
                            $state.go('panel-offices');
                        }
                    }, function(response) {
                        form.fillErrors(response.data).unlock();
                    });
                };

                ctrl.selectPhoto = function(e) {
                    e && e.preventDefault() && e.stopPropagation();

                    var input = $('<input type="file" />');

                    input.click();

                    input.unbind('change').bind('change', function(e) {
                        photoForupload = e.target.files[0];
                    });
                }
            };
        }
    ]
});