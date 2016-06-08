'use strict';
(function(){
    angular.module('adminApp').controller('tableCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.updateOrder = function(data){
            if(data.changed){
                var newData = angular.copy(data);
                delete newData.changed;
                delete newData.category;
                alert.show('loading', 'ALERT.CHANGING.SORT', 2000);
                $grRestful.update({
                    module: $rootScope.GRIFFO.module,
                    action: 'update_attributes',
                    id: data.idproduct,
                    post: newData
                }).then(function(r){
                    if(r.response){
                        alert.show('success', 'ALERT.SUCCESS.CHANGE.SORT', 2000);
                    }
                });
                data.changed = false;
            }
        };
        $timeout(function(){
            $scope.$parent.grTable = $scope.grTable;
        });
    }]);
    angular.module('adminApp').controller('formCtrl', ['$scope', '$window', '$timeout', '$translate', '$grRestful', '$grAlert', function($scope, $window, $timeout, $translate, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            ready = {
                categories: false,
                data: false
            },
            checkReady = function(){
                if(ready.categories && ready.data){
                    $scope.modal.ready();
                }
            };

        $scope.status = [
            {
                value: 1,
                label: $translate.instant('LABEL.ACTIVE')
            },{
                value: 0,
                label: $translate.instant('LABEL.INACTIVE')
            }
        ];

        $scope.categories = [];

        $grRestful.find({
            module: 'product',
            action: 'select_category'
        }).then(function(r){
            if(r.response){
                $scope.categories = r.response;
            }
            ready.categories = true;
        }).finally(checkReady);

        $grRestful.find({
            module: 'product',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                if(r.response.url !== URLify(r.response.name) + '/'){
                    $scope.formSettings.data.urlEditable = true;
                }
                angular.forEach(r.response, function(_r, id){
                    $scope.formSettings.data[id] = _r;
                });
            }
            ready.data = true;
        }).finally(checkReady);

        $scope.$watch('formSettings.data.urlEditable', function(editable){
            if(!editable){
                if($scope.formSettings.data.name){
                    $scope.formSettings.data.url = URLify($scope.formSettings.data.name);
                }else{
                    $scope.formSettings.data.url = '';
                }
            }
        });

        $scope.$watch('formSettings.data.name', function(name){
            if(!$scope.formSettings.data.urlEditable){
                if(name){
                    $scope.formSettings.data.url = URLify(name);
                }else{
                    $scope.formSettings.data.url = '';
                }
            }
        });

        $scope.formSettings = {
            data: {
                status: 1,
                urlEditable: false
            },
            schema: [
                {
                    property: 'picture',
                    type: 'filemanager',
                    label: 'LABEL.IMAGE.COVER',
                    columns: 6,
                    attr: {
                        required: true,
                        filter: 'image',
                        label: {
                            icon: 'fa fa-fw fa-camera',
                            select: 'BUTTON.SELECT.IMAGE',
                            change: 'BUTTON.CHANGE.IMAGE'
                        }
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.IMAGE.COVER'
                    }
                }, {
                    property: 'status',
                    type: 'select',
                    label: 'LABEL.STATUS',
                    list: 'item.value as item.label for item in status',
                    columns: 6,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.STATUS'
                    }
                }, {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    columns: 6,
                    attr: {
                        required: true,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NAME'
                    }
                }, {
                    property: 'fkidcategory',
                    type: 'select',
                    label: 'LABEL.CATEGORY',
                    columns: 6,
                    list: 'item.value as item.label for item in categories',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.CATEGORY'
                    }
                }, {
                    property: 'unitvalue',
                    type: 'money',
                    label: 'LABEL.VALUE.UNIT',
                    placeholder: '0,00',
                    columns: 6,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.VALUE.UNIT'
                    }
                }, {
                    property: 'sort',
                    type: 'number',
                    label: 'LABEL.SORT',
                    columns: 6
                }, {
                    property: 'description',
                    type: 'html',
                    label: 'LABEL.DESCRIPTION'
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                var newData = angular.copy(data);
                delete newData.urlEditable;
                $grRestful.update({
                    module: 'product',
                    action: 'update_attributes',
                    id: $scope.grTableImport.id,
                    post: newData
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
