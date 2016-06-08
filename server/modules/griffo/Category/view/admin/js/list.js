'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$filter', '$grRestful', '$grAlert', function($scope, $window, $timeout, $filter, $grRestful, $grAlert){
            var alert = $grAlert.new(),
                ready = {
                    categories: false,
                    modules: false,
                    data: false
                },
                checkReady = function(){
                    if(ready.categories && ready.modules && ready.data){
                        $scope.modal.ready();
                    }
                };

            $scope.categories = [];

            $scope.modules = [];

            $scope.status = [
                {
                    value: 1,
                    label: $filter('grTranslate')('LABEL.ACTIVE')
                }, {
                    value: 0,
                    label: $filter('grTranslate')('LABEL.INACTIVE')
                }
            ];

            $grRestful.find({
                module: 'category',
                action: 'select'
            }).then(function(r){
                if(r.response){
                    $scope.categories = r.response;
                    $scope.form.updateDefaults();
                }
                ready.categories = true;
            }).finally(checkReady);

            $grRestful.find({
                module: 'module',
                action: 'select'
            }).then(function(r){
                if(r.response){
                    $scope.modules = r.response;
                    $scope.form.updateDefaults();
                }
                ready.modules = true;
            }).finally(checkReady);

            $grRestful.find({
                module: 'category',
                action: 'get',
                id: $scope.grTableImport.id
            }).then(function(r){
                if(r.response){
                    if(r.response[0].url !== URLify(r.response[0].name) + '/'){
                        $scope.formSettings.data.urlEditable = true;
                    }
                    angular.forEach(r.response[0], function(_r, id){
                        $scope.formSettings.data[id] = _r;
                    });
                    delete $scope.formSettings.data.namemodule;
                    $scope.form.updateDefaults();
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
                    urlEditable: false
                },
                schema: [
                    {
                        property: 'name',
                        type: 'text',
                        label: 'LABEL.NAME',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.NAME'
                        }
                    }, {
                        property: 'url',
                        type: 'text',
                        label: 'LABEL.URL',
                        columns: 4,
                        addons: [{
                            icon: 'fa fa-fw {{formSettings.data.urlEditable ? \'fa-times\' : \'fa-pencil\'}}',
                            button: true,
                            class: 'btn',
                            attr: {
                                ngClass: '{\'btn-primary\': !formSettings.data.urlEditable, \'btn-danger\': formSettings.data.urlEditable}',
                                ngClick: 'formSettings.data.urlEditable = !formSettings.data.urlEditable'
                            }
                        }],
                        attr: {
                            required: true,
                            grDisabled: '!formSettings.data.urlEditable'
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.URL'
                        }
                    }, {
                        property: 'status',
                        type: 'select',
                        label: 'LABEL.STATUS',
                        list: 'value.value as value.label for value in status',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.STATUS'
                        }
                    }, {
                        property: 'idcategoryparent',
                        type: 'select',
                        label: 'LABEL.CATEGORY.PARENT',
                        list: 'value.value as value.label for value in categories',
                        columns: 4
                    }, {
                        property: 'fkidmodule',
                        type: 'select',
                        label: 'LABEL.MODULE',
                        list: 'value.value as ((value.label !== \'\' ? (\'MODULE.\' + value.label.toUpperCase() + \'.NAME\') : \'\') | grTranslate) for value in modules',
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.MODULE'
                        }
                    }, {
                        property: 'sort',
                        type: 'number',
                        label: 'LABEL.SORT',
                        columns: 4
                    }
                ],
                submit: function(data){
                    if($scope.form.$invalid) return;
                    var newData = angular.copy(data);
                    delete newData.urlEditable;
                    delete newData.child;
                    delete newData.parent;
                    $grRestful.update({
                        module: 'category',
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
