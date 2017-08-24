oauth2App.component('panelComponent', {
    templateUrl: './tpl/pages/panel.html',
    controller: [
        '$state',
        'AuthService',
        function(
            $state,
            AuthService
        ) {
            var ctrl = this;

            AuthService.getUser().then(function(response) {
                ctrl.user = response.data;
            });

            ctrl.scanQrCode = function(e) {
                e && (e.stopPropagation() & e.preventDefault());

                var qr_code = window.prompt(
                    'QR-Code.',
                    'VIES-2F9M-J8RR-TC5W');

                if (qr_code == null)
                    return;

                $state.go('voucher-form', {
                    voucherCode: qr_code
                });
            };
        }
    ]
});