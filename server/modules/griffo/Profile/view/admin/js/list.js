'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$grRestful', '$grModal', '$grAlert', '$timeout', '$window', function($scope, $grRestful, $grModal, $grAlert, $timeout, $window){
        var alert = $grAlert.new($scope.modal.element),
            checkReady = function(){ $scope.$parent.modal.ready(); };
        $grRestful.find({
            module: 'profile',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                $scope.formSettings.data = r.response;
            }
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
                $grRestful.update({
                    module: 'profile',
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
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
