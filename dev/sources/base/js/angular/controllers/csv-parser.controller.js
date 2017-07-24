app.controller('CSVParserCtrl', [
    '$scope',
    'HashService',
    'CategoryService',
    'DataUploadService',
    'ChildEstimationService',
    function(
        $scope,
        HashService,
        CategoryService,
        DataUploadService,
        ChildEstimationService
    ) {
        var csvParser = {};

        var bind = function(csvParser) {
            csvParser.selectFile = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var input = angular.element('<input type="file" accept=".csv"/>');

                input.click();

                input.unbind('change').bind('change', function(e) {
                    var target_file = input[0].files[0];

                    Papa.parse(target_file, {
                        complete: function(results) {
                            var file_rows = results.data.length - 1;
                            var file_size = Math.max(
                                target_file.size / 1024 / 1024, 0.01
                            ).toFixed(2);

                            var csvContent = results.data;

                            csvContent[0] = csvContent[0].map(function(field) {
                                return field.trim();
                            });

                            $scope.$apply(function() {
                                csvParser.steps[1].csvContent = csvContent;
                                csvParser.steps[1].file = target_file;
                                csvParser.progress = 2;
                            });
                        }
                    });
                });
            };

            csvParser.estimateCsv = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var csvContent = csvParser.steps[1].csvContent.map(function(row) {
                    return row.slice();
                });

                var bugetPos = csvContent[0].indexOf('BETAALD CRED');

                csvContent[0].push('KINDEREN COUNT');

                for (var i = csvContent.length - 1; i >= 1; i--) {
                    var buget_size = parseFloat(csvContent[i][bugetPos]);

                    csvContent[i].push(
                        ChildEstimationService.estimateChildsByBuget(
                            buget_size)[0].toString());
                }

                csvParser.steps[2].csvContent = csvContent;
                csvParser.steps[2].info = "Success, all " + (csvContent.length - 1) + " rows parsed.";
                csvParser.progress = 3;
            };

            csvParser.downloadCsv = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var file = csvParser.steps[1].file;
                var file_name = file.type.replace('.csv', '') + '-edited.csv';
                var file_type = file.type + ';charset=utf-8;';
                var file_data = Papa.unparse(csvParser.steps[2].csvContent);

                var blob = new Blob([file_data], {
                    type: file_type,
                });

                saveAs(blob, file_name);

                csvParser.steps[3].downloaded = true;
            };

            csvParser.uploadCsvInit = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var step = {};

                step.buget_name = '';

                CategoryService.categoryOptions().then(function(data) {
                    step.categoryOptions = data.data.response.filter(
                        function(val, key) {
                            return !isNaN(parseInt(val.id));
                        });
                    step.selectedCategories = [step.categoryOptions[0]]

                    csvParser.steps[4] = step;

                    csvParser.progress = 4;
                    csvParser.realProgress = 4;
                });
            };

            csvParser.addCategory = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                csvParser.steps[4].selectedCategories.push(
                    csvParser.steps[4].categoryOptions[0]);
            };

            csvParser.deleteCategory = function(e, category_key) {
                if (e.preventDefault() & e.stopPropagation()) return;

                if (csvParser.steps[4])
                    csvParser.steps[4].selectedCategories.splice(category_key, 1);
            };

            csvParser.uploadToServer = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var csvContent = csvParser.steps[2].csvContent;
                var fields = ['NR. PERS', 'NAAM PERS', 'BETAALD CRED'];
                var sensitiveFields = ['NR. PERS'];

                var fieldIndexes = [];
                var sensitiveFieldIndexes = [];


                for (var i = fields.length - 1; i >= 0; i--) {
                    fieldIndexes.push(
                        csvContent[0].indexOf(fields[i]));

                    sensitiveFieldIndexes.push(
                        csvContent[0].indexOf(sensitiveFields[i]));
                }

                csvContent = csvContent.map(function(csvRow, csvRowIndex) {
                    if (csvRowIndex > 0) {
                        csvRow = csvRow.map(function(csvCol, csvColIndex) {
                            if (sensitiveFieldIndexes.indexOf(
                                    csvColIndex) != -1)
                                return HashService.hashWithSalt(
                                    csvCol, 'tmp-key');

                            return csvCol;
                        });
                    }

                    return csvRow.filter(function(csvCol, csvColIndex) {
                        return fieldIndexes.indexOf(csvColIndex) != -1;
                    });
                });

                csvParser.steps[4].csvContent = csvContent;

                var categories = csvParser.steps[4].selectedCategories;

                categories = categories.map(function(category) {
                    return category.id;
                });

                DataUploadService.submitData(
                    csvParser.steps[4].buget_name,
                    csvContent, categories
                ).then(
                    function(data) {
                        csvParser.steps[4].serverResponse = data.data.response;
                        csvParser.steps[4].uploaded = true;
                        csvParser.progress = 5;
                    });
            }

            csvParser.saveFromServer = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;

                var csvContent = csvParser.steps[2].csvContent.slice();
                var serverResponse = csvParser.steps[4].serverResponse.slice();

                var hexKey = serverResponse[0].indexOf('BSN_HEX');
                var voucherKey = serverResponse[0].indexOf('VOUCHER CODE');

                console.log('voucherKey', voucherKey, csvContent[0]);

                var downloadContent = csvContent.map(function(row, rowIndex) {
                    row = row.slice();

                    if (rowIndex == 0) {
                        row.push('VOUCHER CODE');
                        return row;
                    }

                    row.push(serverResponse[rowIndex][voucherKey]);
                    return row;
                });



                var file = csvParser.steps[1].file;
                var file_name = file.type.replace('.csv', '') + '-with-vouchers.csv';
                var file_type = file.type + ';charset=utf-8;';
                var file_data = Papa.unparse(downloadContent);

                var blob = new Blob([file_data], {
                    type: file_type,
                });

                saveAs(blob, file_name);

                csvParser.steps[5].downloaded = true;

                console.log(downloadContent);
            };

            csvParser.goForward = function(e) {
                if (e.preventDefault() & e.stopPropagation()) return;
                this.realProgress++;
            };
        };

        var init = function() {
            bind(csvParser);

            csvParser.progress = 1;
            csvParser.realProgress = 1;
            csvParser.steps = {};

            for (var i = 7; i >= 0; i--) {
                csvParser.steps[i] = {};
            }

            $scope.csvParser = csvParser;
        };

        init();
    }
]);