'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            ready = {
                menus: false,
                modules: false,
                layers: false,
                data: false
            },
            checkReady = function(){
                if(ready.menus && ready.modules && ready.layers && ready.data){
                    $scope.$parent.modal.ready();
                }
            };
        $scope.status = [
            {
                value: 1,
                label: $filter('grTranslate')('LABEL.ACTIVE')
            }, {
                value: 0,
                label: $filter('grTranslate')('LABEL.INACTIVE')
            }
        ];
        $scope.menus = {};
        $scope.modules = {};
        $scope.layers = {};
        $grRestful.find({'module': 'menu','action': 'select'}).then(function(r){
            if(r.response){ $scope.menus = r.response; }
            ready.menus = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'module','action': 'select'}).then(function(r){
            if(r.response){
                $scope.modules = r.response;
                angular.forEach($scope.modules, function(module){
                    if(module && module.label){
                        module.label = 'MODULE.' + module.label.toUpperCase() + '.NAME';
                    }
                });
            }
            ready.modules = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'layer','action': 'select'}).then(function(r){
            if(r.response){ $scope.layers = r.response; }
            ready.layers = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'menu','action': 'get','id': $scope.grTableImport.id}).then(function(r){
            if(r.response){ $scope.formSettings.data = r.response; $scope.form.updateDefaults(); }
            ready.data = true;
        }).finally(checkReady);
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    type: 'multiple',
                    fields: [
                        {
                            property: 'label',
                            type: 'text',
                            label: 'LABEL.LABEL',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.LABEL'
                            }
                        }, {
                            property: 'icon',
                            type: 'text',
                            label: 'LABEL.ICON.CLASS'
                        }, {
                            property: 'status',
                            type: 'select',
                            label: 'LABEL.STATUS',
                            list: 'value.value as value.label for value in status',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.STATUS'
                            }
                        }
                    ],
                    columns: 4
                }, {
                    type: 'multiple',
                    fields: [
                        {
                            property: 'path',
                            type: 'text',
                            label: 'LABEL.PATH'
                        }, {
                            property: 'idmenuparent',
                            type: 'select',
                            label: 'LABEL.MENU.PARENT',
                            list: 'item.value as item.label for item in menus'
                        }
                    ],
                    columns: 6
                }
            ],
            options: {
                validation: {
                    showMessages: false
                },
                defaultOption: 'Selecione...'
            },
            submit: function(data) {
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'menu',
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
