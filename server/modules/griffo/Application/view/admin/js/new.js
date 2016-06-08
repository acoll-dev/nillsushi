'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert){
            var alert = $grAlert.new();

            $scope.formSettings = {
                data: {},
                schema: [
                    {
                        type: 'multiple',
                        fields: [
                            {
                                property: 'id',
                                type: 'text',
                                label: 'LABEL.ID',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.ID'
                                }
                            }, {
                                property: 'key',
                                type: 'text',
                                label: 'LABEL.KEY',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.KEY'
                                }
                            }, {
                                property: 'name',
                                type: 'text',
                                label: 'LABEL.NAME',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.NAME'
                                }
                            }
                        ],
                        columns: 3
                    }
                ],
                submit: function(data){
                    if($scope.form.$invalid) return;
                    $grRestful.create({
                        module: 'application',
                        action: 'insert',
                        post: data
                    }).then(function(r){
                        if(r.response){ $scope.form.reset(); }
                        alert.show(r.status, r.message);
                    }, function(r){
                        alert.show('danger', 'ERROR.FATAL');
                    });
                }
            };
            $timeout(function(){
                $scope.$parent.form = $scope.form;
            });
        }]);
}());
