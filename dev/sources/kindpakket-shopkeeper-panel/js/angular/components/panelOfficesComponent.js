shopkeeperApp.component('panelOfficesComponent', {
    templateUrl: './tpl/pages/panel/offices.html',
    controller: [
        '$rootScope',
        '$state',
        '$scope',
        '$timeout',
        'OfficeService',
        function(
            $rootScope,
            $state,
            $scope,
            $timeout,
            OfficeService
        ) {
            var ctrl = this;
            
            ctrl.$onInit = function() {
                OfficeService.getOffices().then(function(response) {
                    ctrl.offices = response.data;
                }, console.log);
            };
        }
    ]
});