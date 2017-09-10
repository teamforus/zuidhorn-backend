kindpakketApp.controller('BaseController', [
    '$scope',
    'AuthService',
    'CategoryService',
    'FormBuilderService',
    'CredentialsService',
    function(
        $scope,
        AuthService,
        CategoryService,
        FormBuilderService,
        CredentialsService
    ) {
        $scope.categories = [];
        $scope.forms = {};

        $scope.forms.login = FormBuilderService.build();
        $scope.forms.voucher = FormBuilderService.build();

        $scope.credentials = CredentialsService.get();

        $scope.auth = {};
        
        if ($scope.credentials) {
            AuthService.getVoucher().then(function(response) {
                $scope.targetVoucher = response.data;
            });
        }

        $scope.auth.signOut = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            AuthService.signOut();

            $scope.credentials = CredentialsService.get();
        };

        $scope.auth.signIn = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.login.reset();

            $('body').addClass('has-modal');
            $('.popup').addClass('act');
            $('.popup-block').hide();
            $('.login').show();
        };

        $scope.auth.viewQrCode = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            var PrintElem = function PrintElem(html)
            {
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');

                mywindow.document.write(
                    '<html><head></head><body>' + html + '</body></html>');

                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/

                mywindow.print();
                mywindow.close();

                return true;
            }

            AuthService.getQrCode().then(function(response) {
                swal({
                    title: '<img src="' + response.data + '" style="width: 33%;">',
                    html: true,
                    showCancelButton: true,
                    closeOnConfirm: false,
                    disableButtonsOnConfirm: true,
                    confirmButtonText: "Print QR-Code",
                    confirmLoadingButtonColor: '#DD6B55'
                }, function(inputValue) {
                    if (inputValue == true) {
                        PrintElem('<img src="' + response.data + '" style="width: 33%;">');
                    }
                });
            });
        };

        $scope.auth.signInSubmit = function(e, form) {
            e && e.stopPropagation() & e.preventDefault();

            if (form.submit)
                return;

            form.submit = true;

            AuthService.signIn(form.values).then(function(response) {
                CredentialsService.set(response.data);
                $scope.credentials = CredentialsService.get();
                $scope.targetVoucher = AuthService.getVoucher();
                $scope.auth.closeModals();
                form.submit = false;
  
                if ($scope.credentials) {
                    AuthService.getVoucher().then(function(response) {
                        $scope.targetVoucher = response.data;
                    });
                }
            }, function(response) {
                form.errors.email = ["Wrong E-mail or password!"];
                form.submit = false;
            });
        };

        $scope.auth.activateVoucher = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.voucher.reset();

            $('body').addClass('has-modal');
            $('.popup').addClass('act');
            $('.popup-block').hide();
            $('.voucher').show();
        };

        $scope.auth.activateVoucherSuccess = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.voucher.reset();

            $('body').addClass('has-modal');
            $('.popup').addClass('act');
            $('.popup-block').hide();
            $('.vcr-act').show();
        };

        $scope.auth.activateVoucherSubmit = function(e, form) {
            e && e.stopPropagation() & e.preventDefault();

            if (form.submit)
                return;

            form.submit = true;

            AuthService.activateVoucher(
                form.values.code || 'empty',
                form.values
            ).then(function(response) {
                $scope.auth.activateVoucherSuccess();
                form.submit = false;
            }, function(response) {
                form.errors = response.data;
                form.submit = false;
            });
        };

        $scope.auth.closeModals = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $('body').removeClass('has-modal');
            $('.popup').removeClass('act');
            $('.popup-block').hide();
        };

        CategoryService.getCategories().then(function(response) {
            $scope.categories = response.data;
            $scope.deselectAll();
        }, console.log);

        $scope.selectAll = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.categories = $scope.categories.map(function(el) {
                el.selected = true;
                return el;
            });

            if ($scope.updatePoints)
                $scope.updatePoints();
        };

        $scope.deselectAll = function(e, category) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.categories = $scope.categories.map(function(el) {
                el.selected = false;
                return el;
            });

            if ($scope.updatePoints)
                $scope.updatePoints();
        };

        $scope.selectCategory = function(e, category) {
            e && e.stopPropagation() & e.preventDefault();

            category.selected = !category.selected;

            if ($scope.updatePoints)
                $scope.updatePoints();
        };
    }
]);