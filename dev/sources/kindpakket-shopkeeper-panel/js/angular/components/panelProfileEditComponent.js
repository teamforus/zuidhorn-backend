shopkeeperApp.component('panelProfileEditComponent', {
    templateUrl: './tpl/pages/panel/profile-edit.html',
    controller: [
        '$q',
        '$rootScope',
        '$state',
        '$scope',
        'AuthService',
        'CategoryService',
        'ShopKeeperService',
        'FormBuilderService',
        'CredentialsService',
        function(
            $q,
            $rootScope,
            $state,
            $scope,
            AuthService,
            CategoryService,
            ShopKeeperService,
            FormBuilderService,
            CredentialsService
        ) {
            var ctrl = this;

            ctrl.form = {};
            ctrl.form.profile = FormBuilderService.build();

            var unwatcher = $rootScope.$watch('user', function(user) {
                if (!user)
                    return;

                init();
                unwatcher();
            });

            var init = function() {
                var promises = [];

                var map = {
                    profile: 0,
                    categories: 1,
                    profile_categories: 2,
                };

                promises[map.profile] = $q(function(resolve, reject) {
                    AuthService.getUser().then(function(response) {
                        resolve(response.data);
                    });
                });

                promises[map.profile_categories] = $q(function(resolve, reject) {
                    ShopKeeperService.getShopKeeperCategories($rootScope.user.shop_keeper.id).then(function(response) {
                        console.log(response);
                        resolve(response.data);
                    });
                });

                promises[map.categories] = $q(function(resolve, reject) {
                    CategoryService.getCategories().then(function(response) {
                        resolve(response.data);
                    }, console.log);
                });

                $q.all(promises).then(function(promises) {
                    resolvePromises(promises, map);
                });
            };

            var resolvePromises = function(promises, map) {
                var profile = promises[map.profile];
                var categories = promises[map.categories];
                var profile_categories = promises[map.profile_categories];

                // prepare lsit all available categories
                ctrl.all_categories = {};

                for (var prop = categories.length - 1; prop >= 0; prop--) {
                    ctrl.all_categories[categories[prop].id] = categories[prop];
                }

                // placeholder
                ctrl.all_categories[0] = {
                    name: "Please select category"
                };

                // fill profile form values
                ctrl.form.profile.fillValues(profile.shop_keeper, ["name", "phone", "kvk_number", "btw_number", "iban"]);
                ctrl.form.profile.fillValues(profile, ["email"]);

                ctrl.form.profile.values.categories = profile_categories.map(function(category) {
                    return category.id;
                });

                // add new category to shopkeepeer
                ctrl.selectCategory = function(_void) {
                    var category_id = _void.category.id;

                    if (!category_id)
                        return;

                    if (ctrl.form.profile.values.categories.indexOf(category_id) == -1) {
                        ctrl.form.profile.values.categories.push(category_id);
                    }

                    _void.category = ctrl.all_categories[0];
                }

                // delete category from shopkeeper
                ctrl.deleteCategory = function(e, category_id) {
                    e && e.preventDefault() && e.stopPropagation();

                    ctrl.form.profile.values.categories.splice(
                        ctrl.form.profile.values.categories.indexOf(parseInt(category_id)), 1);
                }

                // submit form to api
                ctrl.submitForm = function(e, form) {
                    e && e.preventDefault() && e.stopPropagation();

                    if (form.is_locked)
                        return;

                    form.lock();

                    ShopKeeperService.update(
                        profile.shop_keeper.id,
                        form.values
                    ).then(function(response) {
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
                        ShopKeeperService.updatePhoto(
                            profile.shop_keeper.id,
                            e.target.files[0]
                        ).then(function(response) {
                            $scope.$emit('user:sync');
                        }, console.log);
                    });
                }
            };
        }
    ]
});