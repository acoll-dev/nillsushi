'use strict';
(function(){
    angular.module('adminApp').controller('tableCtrl', ['$scope', '$filter', '$window', '$timeout', '$grModal', '$grAlert', '$grRestful', function($scope, $filter, $window, $timeout, $grModal, $grAlert, $grRestful){
        $scope.uninstall = function(grTable, id){
            var alert = $grAlert.new(),
                modal = $grModal.new({
                    name: 'delete',
                    title: 'MODULE.MODULE.TITLE.UNINSTALL',
                    size: 'sm',
                    text: 'MODULE.MODULE.CONFIRM.UNINSTALL',
                    backdrop: 'static',
                    define: {
                        grTableImport: {
                            id: id,
                            grTable: grTable
                        },
                        uninstalling: false
                    },
                    buttons: [
                        {
                            type: 'danger',
                            label: 'BUTTON.UNINSTALL',
                            attr: {
                                'ng-disabled': '$parent.uninstalling'
                            },
                            onClick: function(scope, element, controller){
                                scope.uninstalling = true;
                                alert.show('loading', 'MODULE.MODULE.UNINSTALL.RUNNING', 0);
                                $grRestful.delete({
                                    module: 'module',
                                    id: id
                                }).then(function(data){
                                    if(data.response){
                                        scope.grTableImport.grTable.reloadData();
                                        alert.destroy();
                                    }else{
                                        alert.show('danger', data.message);
                                    }
                                },function(e){
                                    alert.show('danger', e);
                                }).finally(function(){
                                    controller.close();
                                    scope.uninstalling = false;
                                });
                            }
                        },{
                            type: 'default',
                            label: 'BUTTON.CANCEL',
                            attr: {
                                'ng-disabled': '$parent.uninstalling'
                            },
                            onClick: function(scope, element, controller){
                                controller.close();
                            }
                        }
                    ]
                });
            modal.open();
        }
        $timeout(function(){
            $scope.$parent.grTable = $scope.grTable;
        });
    }]);
}());
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            checkReady = function(){ $scope.$parent.modal.ready(); };
        $grRestful.find({
            'module': 'module',
            'action': 'get',
            'id': $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                $scope.formSettings.data = r.response;
                $scope.form.updateDefaults();
            }
        }).finally(checkReady);
        $scope.status = [
            {
                value: 1,
                label: $filter('grTranslate')('LABEL.ACTIVE')
            }, {
                value: 0,
                label: $filter('grTranslate')('LABEL.INACTIVE')
            }
        ];
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 45,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NAME',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.NAME[[45]]'
                    }
                }, {
                    property: 'path',
                    type: 'text',
                    label: 'LABEL.PATH',
                    placeholder: '.../',
                    columns: 4,
                    attr: {
                        ngMaxlength: 255
                    },
                    msgs: {
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.PATH[[255]]'
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
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'module',
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
