'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert){
            var alert = $grAlert.new(),
                ready = {
                    layers: false,
                    data: false
                },
                checkReady = function(){
                    if(ready.layers && ready.data){
                        $scope.modal.ready();
                    }
                };
            $scope.layers = [];
            $grRestful.find({
                module: 'layer',
                action: 'select'
            }).then(function(r){
                if(r.response){
                    $scope.layers = r.response;
                }
                ready.layers = true;
            }).finally(checkReady);
            $grRestful.find({
                module: 'template',
                action: 'get',
                id: $scope.grTableImport.id
            }).then(function(r){
                if(r.response){
                    $scope.formSettings.data = r.response;
                    $scope.form.updateDefaults();
                }
                ready.data = true;
            }).finally(checkReady);
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
                    $grRestful.update({
                        module: 'template',
                        action: 'update_attributes',
                        id: $scope.grTableImport.id,
                        post: data
                    }).then(function(r) {
                        if(r.response){
                            $scope.grTableImport.grTable.reloadData();
                            $scope.modal.forceClose();
                        }else{
                            alert.show(r.status, r.message);
                        }
                    },function(r) {
                        alert.$message.show('danger', 'ERROR.FATAL');
                    });
                }
            };
            $scope.$watch('form', function(form){
                $scope.$parent.form = form;
            });
        }]);
}());
