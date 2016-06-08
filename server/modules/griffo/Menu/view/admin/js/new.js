'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$rootScope', '$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.menus = {};
        $scope.modules = {};
        $scope.layers = {};
        $grRestful.find({'module': 'menu','action': 'select'}).then(function(r){ if(r.response){ $scope.menus = r.response; }});
        $grRestful.find({'module': 'module','action': 'select'}).then(function(r){
            if(r.response){
                $scope.modules = r.response;
                angular.forEach($scope.modules, function(module){
                    if(module && module.label){
                        module.label = 'MODULE.' + module.label.toUpperCase() + '.NAME';
                    }
                });
            }
        });
        $grRestful.find({'module': 'layer','action': 'select'}).then(function(r){
            if(r.response){
                $scope.layers = r.response;
                angular.forEach($scope.layers, function(layer){
                    if($rootScope.GRIFFO.filter.layer && layer.name === $rootScope.GRIFFO.filter.layer){
                        $scope.formSettings.data.fkidlayer = layer.value;
                    }
                    $scope.form.updateDefaults();
                });
            }
        });
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
            data: {
                status: 1,
                fkidmodule: 9
            },
            schema: [
                {
                    property: 'label',
                    type: 'text',
                    label: 'LABEL.LABEL',
                    columns: 3,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LABEL'
                    }
                }, {
                    property: 'icon',
                    type: 'text',
                    label: 'LABEL.ICON.CLASS',
                    columns: 3
                }, {
                    property: 'path',
                    type: 'text',
                    label: 'LABEL.PATH',
                    columns: 3
                }, {
                    property: 'idmenuparent',
                    type: 'select',
                    label: 'LABEL.MENU.PARENT',
                    list: 'item.value as item.label for item in menus',
                    columns: 3
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                $grRestful.create({
                    module: 'menu',
                    action: 'insert',
                    post: data
                }).then(function(r) {
                    $scope.form.reset();
                    alert.show(r.status, r.message);
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
