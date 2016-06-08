'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert){


            $grRestful.find({
                module: 'application',
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
                    $grRestful.update({
                        module: 'application',
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
            $timeout(function(){
                $scope.$parent.form = $scope.form;
            });
        }]);
}());
