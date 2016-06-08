'use strict';
(function () {
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$translate', '$locale', '$grRestful', '$grAlert', function ($scope, $filter, $window, $timeout, $translate, $locale, $grRestful, $grAlert) {
            var alert = $grAlert.new();

            $scope.status = [
                {
                    value: 1,
                    label: $translate.instant('LABEL.ACTIVE')
                }, {
                    value: 0,
                    label: $translate.instant('LABEL.INACTIVE')
                }
            ];
            $scope.divisible = [
                {
                    value: 1,
                    label: $translate.instant('LABEL.ACTIVE')
                },
                {
                    value: 0,
                    label: $translate.instant('LABEL.INACTIVE')
                }
            ];
            $grRestful.find({
                module: 'optional',
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
                    status: 1,
                    divisible: 0
                },
                schema: [
                    {
                        property: 'image',
                        type: 'filemanager',
                        label: 'LABEL.IMAGE.COVER',
                        columns: 4,
                        attr: {
                            filter: 'image',
                            label: {
                                icon: 'fa fa-fw fa-camera',
                                select: 'BUTTON.SELECT.IMAGE',
                                change: 'BUTTON.CHANGE.IMAGE'
                            }
                        }
                    }, {
                        property: 'status',
                        type: 'select',
                        label: 'LABEL.STATUS',
                        list: 'item.value as item.label for item in status',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.STATUS'
                        }
                    }, {
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
                        property: 'value',
                        type: 'money',
                        label: 'LABEL.VALUE.MONEY',
                        placeholder: '0,00',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.NAME'
                        }
                    }, {
                        property: 'info',
                        label: 'LABEL.INFO',
                        type: 'html'
                    }, {
                        property: 'divisible',
                        type: 'select',
                        label: 'LABEL.DIVISIBLE',
                        list: 'item.value as item.label for item in divisible',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.DIVISIBLE'
                        }
                    }, {
                        property: 'list',
                        label: 'LABEL.LIST',
                        type: 'text',
                        columns: 8,
                    }
                ],
                submit: function (data) {
                    if ($scope.form.$invalid)
                        return;
                    $grRestful.update({
                        module: 'optional',
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
