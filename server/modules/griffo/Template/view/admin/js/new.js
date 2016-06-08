'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert){
            var alert = $grAlert.new();
            $scope.layers = [];
            $grRestful.find({
                module: 'layer',
                action: 'select'
            }).then(function(r){
                if(r.response){
                    $scope.layers = r.response;
                    $scope.form.updateDefaults();
                }
            });
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
                                property: 'path',
                                type: 'text',
                                label: 'LABEL.PATH',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.PATH'
                                }
                            }
                        ],
                        columns: 4
                    }
                ],
                submit: function(data){
                    if($scope.form.$invalid) return;
                    $grRestful.create({
                        module: 'template',
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
            $scope.$watch('form', function(form){
                $scope.$parent.form = form;
            });
        }]);
}());
