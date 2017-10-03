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
        $scope.locations = [];
        $scope.forms = {};
        $scope.forms.login = FormBuilderService.build();
        $scope.forms.voucher = FormBuilderService.build();

        $scope.credentials = CredentialsService.get();

        $scope.auth = {};

        $scope.page = "home";
        
        if ($scope.credentials) {
            AuthService.getVoucher().then(function(response) {
                $scope.targetVoucher = response.data;
            });

            AuthService.getTransactions().then(function(response) {
                $scope.transactions = response.data;
            });
        }

        $scope.navigate = function(e, page) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.page = page;
        };

        $scope.auth.signOut = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            AuthService.signOut();

            $scope.credentials = CredentialsService.get();
        };

        $scope.auth.signIn = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.login.reset();

            $('body').addClass('popup-open');
            $('.popups .popup').hide();
            $('.popups .popup-auth').show();
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

                $scope.loadQrCode();
            }, function(response) {
                form.errors.email = ["Wrong E-mail or password!"];
                form.submit = false;
            });
        };

        $scope.auth.activateVoucher = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.voucher.reset();

            $('body').addClass('popup-open');
            $('.popup-voucher').show();
        };

        $scope.auth.activateVoucherSuccess = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.voucher.reset();

            $('body').addClass('popup-open');
            $('.popups .popup').hide();
            $('.popups .popup-voucher-success').show();
        };

        $scope.emailQrCodeSentSuccess = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.forms.voucher.reset();

            $('body').addClass('popup-open');
            $('.popups .popup').hide();
            $('.popups .popup-qr-code-email-success').show();
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

            $('body').removeClass('popup-open');
            $('.popup').hide();
        };

        CategoryService.getCategories().then(function(response) {
            $scope.categories = response.data;
            $scope.deselectAll();
            $scope.updateOfficesCategory();
        }, console.log);

        $scope.selectAll = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.categories.forEach(function(el) {
                el.selected = true;
            });
        };

        $scope.deselectAll = function(e, category) {
            e && e.stopPropagation() & e.preventDefault();

            $scope.categories.forEach(function(el) {
                el.selected = false;
            });
        };

        $scope.selectCategory = function(e, category) {
            e && e.stopPropagation() & e.preventDefault();

            category.selected = !category.selected;
        };

        $scope.updateOfficesCategory = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            var locations = {};

            var categories = $scope.categories.filter(function(category) {
                return category.selected;
            });
            
            if (categories.length == 0)
                categories = $scope.categories;

            categories.forEach(function(category) {
                category.shopkeepers.forEach(function(shopkeeper) {
                    shopkeeper.offices.forEach(function(office) {
                        if (locations[office.id])
                            return;

                        locations[office.id] = JSON.parse(JSON.stringify(office));
                        locations[office.id].shopkeeper = JSON.parse(JSON.stringify(shopkeeper));
                        locations[office.id].category = JSON.parse(JSON.stringify(category));
                    });
                });
            });

            $scope.locations = Object.values(locations);

            $scope.locations.forEach(function(location) {
                location.selected = false;
            });

            if ($scope.updatePoints)
                $scope.updatePoints($scope.locations);
        };

        $scope.selectLocation = function(e, location) {
            e && e.stopPropagation() & e.preventDefault();

            var selected = location.selected;

            $scope.locations.forEach(function(location) {
                location.selected = false;
            });

            location.selected = !selected;

            if (location.selected) {
                if ($scope.updatePoints)
                    $scope.updatePoints([location]);
            } else {
                $scope.updateOfficesCategory();
            }
        };

        $scope.loadQrCode = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            AuthService.getQrCode().then(function(response) {
                $scope.qrCode = response.data;
            });
        };

        $scope.printQrCode = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            var PrintElem = function PrintElem(html)
            {
                var mywindow = window.open('', 'PRINT', 'directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=no,resizable=no,height=700,width=1200');

                mywindow.document.write(
                    '<html><head></head><body>' + html + '</body></html>');

                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/

                mywindow.print();
                mywindow.close();

                return true;
            }

            if ($scope.qrCode)
                PrintElem('<img src="' + $scope.qrCode + '" style="width: 50%; maring-left: 25%; display: block;">');
        };

        $scope.sendQrCodeEmail = function(e) {
            e && e.stopPropagation() & e.preventDefault();

            if ($scope.sendingQrCode)
                return;

            $scope.sendingQrCode = true;

            AuthService.sendQrCodeEmail().then(function(response) {
                $scope.emailQrCodeSentSuccess();
                $scope.sendingQrCode = false;
            }, function() {
                $scope.sendingQrCode = false;
            });
        };

        if ($scope.credentials)
            $scope.loadQrCode();
    }
]);