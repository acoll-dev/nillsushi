'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$grRestful', '$grModal', '$grAlert', '$timeout', '$window', function($scope, $grRestful, $grModal, $grAlert, $timeout, $window){
        var alert = $grAlert.new();
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    type: 'multiple',
                    fields: [
                        {
                            property: 'name',
                            type: 'text',
                            label: 'LABEL.NAME',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.NAME'
                            }
                        }, {
                            property: 'label',
                            type: 'text',
                            label: 'LABEL.LABEL',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.LABEL'
                            }
                        }
                    ],
                    columns: 6
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.create({
                    module: 'profile',
                    action: 'insert',
                    post: data
                }).then(function(r){
                    if(r.response){
                        $scope.form.reset();
                    }
                    alert.show(r.status, r.message);
                },
                function (r) {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
