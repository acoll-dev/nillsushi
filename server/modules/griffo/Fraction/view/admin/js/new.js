'use strict';
(function () {
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$translate', '$locale', '$grRestful', '$grAlert', function ($scope, $filter, $window, $timeout, $translate, $locale, $grRestful, $grAlert) {
            var alert = $grAlert.new();

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
                    if ($scope.form.$invalid) return;
                    $grRestful.create({
                        module: 'fraction',
                        action: 'insert',
                        post: data
                    }).then(function (r) {
                        if (r.response) {
                            $scope.form.reset();
                        }
                        alert.show(r.status, r.message);
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
