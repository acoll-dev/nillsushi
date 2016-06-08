'use strict';
(function () {
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$translate', '$locale', '$grRestful', '$grAlert', function ($scope, $filter, $window, $timeout, $translate, $locale, $grRestful, $grAlert) {
            var alert = $grAlert.new();

            $grRestful.find({
                module: 'fraction',
                action: 'get',
                id: $scope.grTableImport.id
            }).then(function (r) {
                if (r.response) {
                    $scope.formSettings.data = r.response[0];
                }
                $scope.modal.ready();
                $timeout(function () {
                    $scope.form.updateDefaults();
                }, 1000);
            });
            $scope.formSettings = {
                schema: [
                    {
                        property: 'name',
                        type: 'text',
                        label: 'LABEL.NAME',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.NAME'
                        }

                    }, {
                        property: 'formula',
                        type: 'text',
                        label: 'LABEL.FORMULA',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.FORMULA'
                        }
                    }
                ],
                submit: function (data) {
                    if ($scope.form.$invalid)
                        return;
                    $grRestful.update({
                        module: 'fraction',
                        action: 'update_attributes',
                        id: $scope.grTableImport.id,
                        post: data
                    }).then(function (r) {
                        if (r.response) {
                            $scope.grTableImport.grTable.reloadData();
                            $scope.modal.forceClose();
                        } else {
                            alert.show(r.status, r.message);
                        }
                    }, function (r) {
                        alert.show('danger', 'ERROR.FATAL');
                    });
                }
            };
            $scope.$watch('form', function (form) {
                $scope.$parent.form = form;
            });
        }]);
}());
