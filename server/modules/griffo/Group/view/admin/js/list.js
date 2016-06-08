'use strict';
(function () {
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$translate', '$locale', '$grRestful', '$grAlert', function ($scope, $filter, $window, $timeout, $translate, $locale, $grRestful, $grAlert) {
            var alert = $grAlert.new();

            $scope.default = [
                {
                    value: 1,
                    label: $translate.instant('LABEL.ACTIVE')
                }
            ];

            $grRestful.find({
                module: 'group',
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
                data: {
                    default: 1
                },
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
                        property: 'default',
                        type: 'select',
                        label: 'LABEL.DEFAULT',
                        list: 'item.value as item.label for item in default',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.DEFAULT'
                        }
                    }
                ],
                submit: function (data) {
                    if ($scope.form.$invalid)
                        return;
                    $grRestful.update({
                        module: 'group',
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
