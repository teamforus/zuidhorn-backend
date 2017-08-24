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