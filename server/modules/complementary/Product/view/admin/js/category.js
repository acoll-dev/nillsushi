'use strict';
(function(){
    angular.module('adminApp').controller('tableCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();

        $scope.categoriesList = [];

        $scope.updateCategories = function(reload){
            if(!reload){
                alert.show('loading', 'ALERT.LOADING.TABLE.DATA', 0);
            }else{
                alert.show('loading', 'ALERT.RELOADING.TABLE.DATA', 0);
            }
            $grRestful.find({
                module: 'category',
                action: 'module',
                onelevel: true,
                params: 'name=product'
            }).then(function(r){
                if(r.response){
                    var categories = categoryLoop(r.response);
                    function categoryLoop(arrCategories){
                        var categories = [];
                        angular.forEach(arrCategories, function(category){
                            categories.push(category);
                            if(category.child){
                                angular.forEach(categoryLoop(category.child), function(subcategory){
                                    subcategory.namecategoryparent = category.name;
                                    categories.push(subcategory);
                                });
                            }
                        });
                        return categories;
                    }
                    $scope.categoriesList = categories;
                    if(!reload){
                        alert.hide();
                    }else{
                        alert.show('success', 'ALERT.SUCCESS.LOAD.TABLE.DATA', 2000);
                    }
                }else{
                    console.debug(r);
                    alert.show('danger', 'ALERT.ERROR.LOAD.TABLE.DATA');
                }
            });
        };

        $scope.updateCategories();

        $scope.updateOrder = function(data){
            if(data.changed){
                var newData = angular.copy(data);
                delete newData.changed;
                delete newData.namemodule;
                delete newData.parent;
                alert.show('loading', 'ALERT.CHANGING.SORT', 2000);
                $grRestful.update({
                    module: 'category',
                    action: 'update_attributes',
                    id: data.idcategory,
                    post: newData
                }).then(function(r){
                    if(r.response){
                        alert.show('success', 'ALERT.SUCCESS.CHANGE.SORT', 2000);
                    }
                });
                data.changed = false;
            }
        };

        $scope.$watch('grTable', function(grTable){
            $scope.$parent.grTable = grTable;
        });
    }]);
    angular.module('adminApp').controller('formCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();

        $scope.categories = {};

        $grRestful.find({
            'module': 'module',
            'action': 'select'
        }).then(function(r){
            if(r.response){
                angular.forEach(r.response, function(mod){
                    if(mod.label === $rootScope.GRIFFO.module){
                        $scope.formSettings.data.fkidmodule = mod.value;
                        $timeout(function(){
                            $scope.form.updateDefaults();
                        }, 1000);
                    }
                });
            }
        });

        setCategories();

        function setCategories(){
            $grRestful.find({
                'module': 'product',
                'action': 'select_category_parent'
            }).then(function(r){
                if(r.response){
                    $scope.categories = r.response;
                }
            });
        }

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
                fkidmodule: 0,
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
                    columns: 3,
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
                },{
                    property: 'idcategoryparent',
                    type: 'select',
                    label: 'LABEL.CATEGORY.PARENT',
                    columns: 3,
                    list: 'item.value as item.label for item in categories'
                },{
                    property: 'sort',
                    type: 'number',
                    label: 'LABEL.SORT',
                    columns: 2
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                var newData = angular.copy(data);
                delete newData.urlEditable;
                $grRestful.create({
                    module: 'category',
                    action: 'insert',
                    post: newData
                }).then(function (r) {
                    if(r.response){ $scope.form.reset(); $scope.grTable.reloadData(); setCategories(); }
                    alert.show(r.status, r.message);
                }, function (r) {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
    angular.module('adminApp').controller('formModalCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            ready = {
                categories: false,
                module: false,
                data: false
            },
            checkReady = function(){
                if(ready.categories && ready.module && ready.data){
                    $scope.modal.ready();
                }
            };

        $scope.categories = {};

        $scope.modules = {};

        $scope.status = [
            {
                value: 1,
                label: $filter('grTranslate')('LABEL.ACTIVE')
            },{
                value: 0,
                label: $filter('grTranslate')('LABEL.INACTIVE')
            }
        ];

        $grRestful.find({
            'module': 'product',
            'action': 'select_category_parent'
        }).then(function(r){
            if(r.response){
                $scope.categories = r.response;
            }
            ready.categories = true;
        }).finally(checkReady);

        $grRestful.find({
            'module': 'module',
            'action': 'select'
        }).then(function(r){
            if(r.response){
                angular.forEach(r.response, function(mod){
                    if(mod.label === GRIFFO.module){
                        $scope.formSettings.data.fkidmodule = mod.value;
                        $scope.form.updateDefaults();
                    }
                });
            }
            ready.module = true;
        }).finally(checkReady);

        $grRestful.find({
            'module': 'category',
            'action': 'get',
            'id': $scope.grTableImport.id
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
                fkidmodule: 0,
                urlEditable: false
            },
            schema: [
                {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    columns: 6,
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
                    columns: 6,
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
                    property: 'idcategoryparent',
                    type: 'select',
                    label: 'LABEL.CATEGORY.PARENT',
                    columns: 5,
                    list: 'item.value as item.label for item in categories'
                }, {
                    property: 'status',
                    type: 'select',
                    label: 'LABEL.STATUS',
                    columns: 4,
                    list: 'item.value as item.label for item in status',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.STATUS'
                    }
                },{
                    property: 'sort',
                    type: 'number',
                    label: 'LABEL.SORT',
                    columns: 3
                }
            ],
            submit: function(data) {
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
                }).then(function(r){
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
