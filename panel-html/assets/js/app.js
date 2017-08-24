app = angular.module('forusApp', ['ngSanitize']);
app.controller('baseCtrl', ['$scope', function ($scope) {
    
}]);
app.controller('CSVParserCtrl', [
    '$scope',
    'HashService',
    'DataUploadService',
    'ChildEstimationService',
    function(
        $scope,
        HashService,
        DataUploadService,
        ChildEstimationService
    ) {
        var csvParser = {};

        var bind = function() {
            csvParser.selectFile = function(e) {
                e && (e.preventDefault() & e.stopPropagation());

                var input = angular.element('<input type="file" accept=".csv"/>');

                input.unbind('change').bind('change', function(e) {
                    var target_file = input[0].files[0];

                    var parserCallback = function(results) {
                        $scope.$apply(function() {
                            var header = results.data[0];

                            var bugetPos = header.indexOf('BETAALD CRED');
                            var bsnPos = header.indexOf('NR. PERS');

                            var data = results.data.slice(1);

                            csvParser.data = {};

                            data.forEach(function(row, key) {
                                var count_childs = ChildEstimationService
                                    .estimateChildsByBuget(
                                        parseFloat(row[bugetPos])
                                    )[0].toString();

                                csvParser.data[key] = {
                                    id: key,
                                    count_childs: count_childs,
                                    nr_pers: row[bsnPos]
                                };
                            });

                            csvParser.csvFile = target_file;
                            csvParser.progress = 2;
                        });
                    };

                    Papa.parse(target_file, {
                        complete: parserCallback
                    });
                });

                input.click();
            };

            csvParser.uploadToServer = function(e) {
                e && (e.preventDefault() & e.stopPropagation());

                csvParser.progress = 3;

                var submitData = {};

                Object.values(csvParser.data).forEach(function(row) {
                    if (row.count_childs < 1)
                        return;

                    submitData[row.id] = {
                        id: row.id,
                        count_childs: row.count_childs,
                    };
                })

                DataUploadService.submitData(
                    submitData
                ).then(function(data) {
                    csvParser.serverData = data.data.response;
                    csvParser.progress = 4;
                });
            }

            csvParser.saveFromServer = function(e) {
                e && (e.preventDefault() & e.stopPropagation());

                var csvContent = [];

                csvContent[0] = ['NR PERS', 'COUNT CHILDS', 'CODE'];

                for (var prop in csvParser.serverData) {
                    var id = parseInt(prop);

                    csvContent.push([
                        csvParser.data[id].nr_pers,
                        csvParser.data[id].count_childs,
                        csvParser.serverData[id].code,
                    ]);
                }

                console.log(csvContent);

                var file = csvParser.csvFile;
                var file_name = file.name.replace('.csv', '') + '-final.csv';
                var file_type = file.type + ';charset=utf-8;';
                var file_data = Papa.unparse(csvContent);

                var blob = new Blob([file_data], {
                    type: file_type,
                });

                saveAs(blob, file_name);
            };
        };

        var init = function() {
            csvParser.progress = 1;

            bind();

            $scope.csvParser = csvParser;
        };

        init();
    }
]);
app.directive('csvParser', ['$http', function($http) {
    // Runs during compile
    return {
        // name: '',
        // priority: 1,
        // terminal: true,
        // scope: {}, // {} = isolate, true = child, false/undefined = no change
        // controller: function($scope, $element, $attrs, $transclude) {},
        // require: 'ngModel', // Array = multiple requires, ? = optional, ^ = check parent elements
        restrict: 'A', // E = Element, A = Attribute, C = Class, M = Comment
        // template: '',
        templateUrl: '/tpl/csv-parser.tpl.html',
        // replace: true,
        // transclude: true,
        // compile: function(tElement, tAttrs, function transclude(function(scope, cloneLinkingFn){ return function linking(scope, elm, attrs){}})),
        link: function($scope, iElm, iAttrs, controller) {}
    };
}]);
app.service('ChildEstimationService', ['$http', function($http) {
    var service = {
        single: 119,
        couple: 164,
        child: 82,
        getSelection: function() {
            var selection = [];
            var percentages = [.8, .9, 1];

            for (var i = 0; i <= 10; i++) {
                selection[i] = [];

                for (var j = percentages.length - 1; j >= 0; j--) {
                    selection[i].push(((this.single + (this.child * i)) * percentages[j]).toFixed(2));
                    selection[i].push(((this.couple + (this.child * i)) * percentages[j]).toFixed(2));
                }
            }

            return selection;
        },
        estimateChildsByBuget: function(val) {
            var count_childrens = 1;
            var selection = this.getSelection();

            val = val.toFixed(2);

            for (var i = selection.length - 1; i >= 0; i--) {
                if (selection[i].indexOf(val) != -1)
                    return [i];
            }

            return [0];
        }
    };

    return service;
}]);
app.service('DataUploadService', ['$http', function($http) {
    var service = {
        submitData: function(data) {
            return $http.post('/ajax/buget/submit-data', {
                data: data,
                _method: 'PUT'
            });
        }
    };

    return service;
}]);
app.service('HashService', ['$http', function($http) {
    var service = {
        SHA512: new Hashes.SHA512,
        hashWithSalt: function(text, salt) {
            return this.SHA512.hex_hmac(salt, text);
        }
    };

    return service;
}]);
app.filter('decode', function() {
    "use strict";

    function htmlDecode(input) {
        var e = document.createElement('div');
        
        e.innerHTML = input;

        return e.childNodes[0].nodeValue;
    }

    return function(input) {
        return htmlDecode(input);
    }
});
(function($) {
    $.prototype.confirmBox = function(_args) {
        if (this.length === 0)
            return;

        var confirmBox = function($node) {
            var self = this;

            self.bind = function() {
                $node.unbind('click').bind('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var href = $node.attr('href');
                    var title = $node.data('box-title');
                    var text = $node.data('box-text');

                    swal({
                        title: title,
                        text: text,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: true,
                    }, function() {
                        document.location = href;
                    });
                });
            };

            self.bind();
        };

        for (var i = 0; i < this.length; i++) {
            new confirmBox($(this[i]));
        }
    };
})(jQuery);

$(function() {
    $('[confirm-box]').confirmBox();
});