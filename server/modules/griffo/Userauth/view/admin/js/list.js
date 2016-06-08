'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$grRestful', '$grModal', '$grAlert', '$timeout', '$window', function($scope, $grRestful, $grModal, $grAlert, $timeout, $window){
        var alert = $grAlert.new($scope.modal.element),
            checkReady = function(){ $scope.$parent.modal.ready(); };
        $grRestful.find({
            module: 'userauth',
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
                            property: 'user',
                            type: 'text',
                            label: 'LABEL.USER',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.USER'
                            }
                        }, {
                            property: 'password',
                            type: 'text',
                            label: 'LABEL.PASSWORD',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.PASSWORD'
                            }
                        }
                    ],
                    columns: 2
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'userauth',
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
        $timeout(function(){
            $scope.$parent.form = $scope.form;
        });
    }]);
}());
