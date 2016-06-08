'use strict';
(function(){
    angular.module('adminApp')
        .controller('tableCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$grRestful', '$grAlert', '$grModal', function($rootScope, $scope, $window, $timeout, $grRestful, $grAlert, $grModal) {
            $scope.editPage = function(grTable, id, templatePath, label){
                var modal = $grModal.new({
                        name: 'edit',
                        title: 'MODULE.PAGE.TITLE.EDIT',
                        size: 'lg',
                        backdrop: 'static',
                        model: GRIFFO.baseUrl + GRIFFO.modulePath + templatePath,
                        preload: true,
                        define: {
                            grTableImport:{
                                id: id,
                                grTable: grTable
                            }
                        },
                        beforeClose: function(resolve, reject){
                            var formScope = angular.element('form[name="form"]').scope(),
                                hasBlockChange = !angular.equals(formScope.blocks.default, formScope.blocks.items),
                                hasMetatagChange = !angular.equals(formScope.metatags.default, formScope.metatags.items);
                            if(hasBlockChange || hasMetatagChange){
                                $grModal.confirm('PAGE.CONFIRM.EDIT.LEAVE', function(){
                                    resolve();
                                }, function(){ reject(); });
                            }else{
                                resolve();
                            }
                        },
                        buttons: [{
                            type: 'success',
                            label: 'BUTTON.SAVE',
                            onClick: function(scope, element, controller){
                                scope.form.submit();
                            }
                        },
                        {
                            type: 'default',
                            label: 'BUTTON.RESET',
                            onClick: function(scope, element, controller){
                                scope.form.reset();
                            }
                        },
                        {
                            type: 'danger',
                            label: 'BUTTON.CLOSE',
                            onClick: function(scope, element, controller){
                                controller.close();
                            }
                        }]
                    });
                modal.open();
            };
            $timeout(function(){
                $scope.$parent.grTable = $scope.grTable;
            });
        }])
        .controller('formCtrl', ['$rootScope', '$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', '$grModal', function($rootScope, $scope, $filter, $window, $timeout, $grRestful, $grAlert, $grModal) {
            var alert,
                ready = {
                    pages: false,
                    modules: false,
                    data: false
                },
                checkReady = function(){
                    if(ready.pages && ready.modules && ready.data){
                        $scope.$parent.modal.ready();
                    }
                };
            $timeout(function(){ alert = $grAlert.new($scope.$parent.modal.element); });
            $scope.pages = {};
            $scope.modules = {};
            $scope.status = [
                {
                    value: 1,
                    label: $filter('grTranslate')('LABEL.ACTIVE')
                }, {
                    value: 0,
                    label: $filter('grTranslate')('LABEL.INACTIVE')
                }
            ];
            $scope.blocks = {
                items: [],
                default: [],
                new: function(){
                    var modal = $grModal.new({
                        name: 'block',
                        title: 'MODULE.PAGE.TITLE.BLOCK.ADD',
                        size: 'lg',
                        backdrop: 'static',
                        model: GRIFFO.baseUrl + GRIFFO.modulePath + 'view/admin/modal/block.php',
                        define: {
                            types: {
                                'text': $filter('grTranslate')('LABEL.PAGE.BLOCK.TYPE.TEXT'),
                                'textEditor': $filter('grTranslate')('LABEL.PAGE.BLOCK.TYPE.TEXTEDITOR')
                            },
                            addBlock: function(item){
                                $scope.blocks.items.push(item);
                                $scope.$apply();
                                alert.show('success', 'MODULE.PAGE.SUCCESS.BLOCK.ADD');
                            }
                        },
                        buttons: [{
                            type: 'success',
                            label: 'BUTTON.ADD',
                            onClick: function(scope, element, controller){
                                element.find('form').scope().formModal.submit();
                            }
                        }, {
                            type: 'danger',
                            label: 'BUTTON.CANCEL',
                            onClick: function(scope, element, controller){
                                controller.close();
                            }
                        }]
                    });
                    modal.open();
                },
                edit: function(id){
                    var modal = $grModal.new({
                            name: 'block',
                            title: 'MODULE.PAGE.TITLE.BLOCK.EDIT',
                            size: 'lg',
                            backdrop: 'static',
                            model: GRIFFO.baseUrl + GRIFFO.modulePath + 'view/admin/modal/block.php',
                            define: {
                                types: {
                                    'text': $filter('grTranslate')('LABEL.PAGE.BLOCK.TYPE.TEXT'),
                                    'textEditor': $filter('grTranslate')('LABEL.PAGE.BLOCK.TYPE.TEXTEDITOR')
                                },
                                edit: true,
                                block: $scope.blocks.items[id],
                                editBlock: function(item){
                                    $scope.blocks.items[id] = item;
                                    $scope.$apply();
                                    alert.show('success', 'MODULE.PAGE.SUCCESS.BLOCK.EDIT');
                                }
                            },
                            buttons: [{
                                type: 'success',
                                label: 'BUTTON.ADD',
                                onClick: function(scope, element, controller){
                                    element.find('form').scope().formModal.submit();
                                }
                            }, {
                                type: 'danger',
                                label: 'BUTTON.CANCEL',
                                onClick: function(scope, element, controller){
                                    controller.close();
                                }
                            }]
                        });
                        modal.open();
                },
                remove: function(id){
                    $timeout(function(){
                        $scope.blocks.items.splice(id, 1);
                        $scope.$apply();
                    });
                    alert.show('success', 'MODULE.PAGE.SUCCESS.BLOCK.DELETE');
                }
            };
            $scope.metatags = {
                items: [],
                default: [],
                new: function(){
                    var modal = $grModal.new({
                        name: 'metatag',
                        title: 'MODULE.PAGE.TITLE.METATAG.ADD',
                        size: 'md',
                        backdrop: 'static',
                        model: GRIFFO.baseUrl + GRIFFO.modulePath + 'view/admin/modal/metatag.php',
                        define: {
                            addMetatag: function(item){
                                $scope.metatags.items.push(item);
                                $scope.$apply();
                                alert.show('success', 'MODULE.PAGE.SUCCESS.METATAG.ADD');
                            }
                        },
                        buttons: [
                            {
                                type: 'success',
                                label: 'BUTTON.ADD',
                                onClick: function(scope, element, controller){
                                    element.find('form').scope().formModal.submit();
                                }
                            },
                            {
                                type: 'danger',
                                label: 'BUTTON.CANCEL',
                                onClick: function(scope, element, controller){
                                    controller.close();
                                }
                            }
                        ]
                    });
                    modal.open();
                },
                edit: function(id){
                    var modal = $grModal.new({
                        name: 'metatag',
                        title: 'MODULE.PAGE.TITLE.METATAG.EDIT',
                        size: 'md',
                        backdrop: 'static',
                        model: GRIFFO.baseUrl + GRIFFO.modulePath + 'view/admin/modal/metatag.php',
                        define: {
                            edit: true,
                            metatag: $scope.metatags.items[id],
                            editMetatag: function(item){
                                $scope.metatags.items[id] = item;
                                $scope.$apply();
                                alert.show('success', 'MODULE.PAGE.SUCCESS.METATAG.EDIT');
                            }
                        },
                        buttons: [
                            {
                                type: 'success',
                                label: 'BUTTON.ADD',
                                onClick: function(scope, element, controller){
                                    element.find('form').scope().formModal.submit();
                                }
                            }, {
                                type: 'danger',
                                label: 'BUTTON.CANCEL',
                                onClick: function(scope, element, controller){
                                    controller.close();
                                }
                            }
                        ]
                    });
                    modal.open();
                },
                remove: function(id){
                    $timeout(function(){
                        $scope.metatags.items.splice(id, 1);
                        $scope.$apply();
                    });
                    alert.show('success', 'MODULE.PAGE.SUCCESS.METATAG.DELETE');
                }
            };
            $scope.$watch('blocks.items', function(items){ angular.forEach($scope.blocks.items, function(item, id){ item.id = id; }); }, true);
            $scope.$watch('metatags.items', function(){ angular.forEach($scope.metatags.items, function(item, id){ item.id = id; }); }, true);
            $grRestful.find({'module': 'page','action': 'select'}).then(function(r){
                if(r.response){ $scope.pages = r.response; }
                ready.pages = true;
            }).finally(checkReady);
            $grRestful.find({'module': 'module','action': 'select'}).then(function(r){
                if(r.response){ $scope.modules = r.response; }
                ready.modules = true;
            }).finally(checkReady);
            $grRestful.find({
                'module': 'page',
                'action': 'get',
                'id': $scope.grTableImport.id
            }).then(function(r){
                if(r.response){
                    $scope.formSettings.data = r.response;
                    $scope.form.updateDefaults();
                    $scope.blocks.items = angular.copy($scope.formSettings.data.blocks);
                    $scope.metatags.items = angular.copy($scope.formSettings.data.metatags);
                    $scope.blocks.default = angular.copy($scope.formSettings.data.blocks);
                    $scope.metatags.default = angular.copy($scope.formSettings.data.metatags);
                    angular.forEach($scope.blocks.default, function(item, id){ item.id = id; });
                    angular.forEach($scope.metatags.default, function(item, id){ item.id = id; });
                    if($scope.formSettings.data.child){
                        delete $scope.formSettings.data.child;
                    };
                }
                ready.data = true;
            }).finally(checkReady);
            $scope.formSettings = {
                data: {
                    status: 1
                },
                schema:  [
                    {
                        property: 'picture',
                        type: 'filemanager',
                        label: 'LABEL.IMAGE.COVER',
                        columns: 6,
                        attr: {
                            class: 'btn-success',
                            filter: 'image',
                            label: {
                                icon: 'fa fa-fw fa-image',
                                select: 'BUTTON.SELECT.IMAGE',
                                change: 'BUTTON.CHANGE.IMAGE'
                            }
                        }
                    }, {
                        property: 'authenticate',
                        type: 'checkbox',
                        label: 'LABEL.AUTHENTICATION.REQUIRED',
                        columns: 6
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
                    }, {
                        property: 'fkidmodule',
                        type: 'select',
                        label: 'LABEL.MODULE',
                        columns: 4,
                        list: 'module.value as ((module.label !== \'\' ? (\'MODULE.\' + module.label.toUpperCase() + \'.NAME\') : \'\') | grTranslate) for module in modules',
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.MODULE'
                        }
                    }, {
                        property: 'idpageparent',
                        type: 'select',
                        label: 'LABEL.PAGE.PARENT',
                        columns: 4,
                        list: 'page.value as page.label for page in pages'
                    }, {
                        property: 'fileview',
                        type: 'text',
                        label: 'LABEL.FILE.VIEW',
                        columns: 4,
                        attr: {
                            required: true,
                            autofocus: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.FILE.VIEW'
                        }
                    }, {
                        property: 'filecss',
                        type: 'text',
                        label: 'LABEL.FILE.CSS',
                        columns: 4
                    }, {
                        property: 'filejs',
                        type: 'text',
                        label: 'LABEL.FILE.JS',
                        columns: 4
                    }, {
                        property: 'title',
                        type: 'text',
                        label: 'LABEL.PAGE.TITLE',
                        attr: {
                            required: true,
                            ngMaxlength: 45
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.PAGE.TITLE',
                            maxlength: 'FORM.MESSAGE.MAXLENGTH.PAGE.TITLE[[45]]'
                        }
                    }, {
                        property: 'description',
                        type: 'text',
                        label: 'LABEL.PAGE.DESCRIPTION'
                    }, {
                        property: 'url',
                        type: 'text',
                        label: 'LABEL.URL',
                        columns: 6,
                        attr: {
                            required: true,
                            ngMaxlength: 255
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.URL',
                            maxlength: 'FORM.MESSAGE.MAXLENGTH.URL[[255]]'
                        }
                    }, {
                        property: 'keywords',
                        type: 'text',
                        label: 'LABEL.KEYWORDS',
                        columns: 6
                    }
                ],
                submit: function(data) {
                    if($scope.form.$invalid) return;
                    var metatagsAux = [];
                    data.blocks = angular.copy($scope.blocks.items);
                    data.metatags = angular.copy($scope.metatags.items);
                    angular.forEach(data.blocks, function(block, id){
                        delete block.id;
                    });
                    angular.forEach(data.metatags, function(metatag, id){
                        delete metatag.id;
                        while(metatag.name.indexOf(';') > -1){ metatag.name.replace(';', '\;'); }
                        while(metatag.name.indexOf(',') > -1){ metatag.name.replace(',', '\,'); }
                        while(metatag.content.indexOf(';') > -1){ metatag.content.replace(';', '\;'); }
                        while(metatag.content.indexOf(',') > -1){ metatag.content.replace(',', '\,'); }
                        metatagsAux.push(metatag.name + ',' + metatag.content);
                    });
                    data.metatags = metatagsAux.join(';');
                    $grRestful.update({
                        module: 'page',
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
        }])
        .controller('modalBlockCtrl', ['$scope', '$timeout', '$grAlert', function($scope, $timeout, $grAlert){
            $scope.modalFormSettings = {
                data: {
                    name: '',
                    type: 'text',
                    content: ''
                },
                schema: [
                    {
                        property: 'name',
                        type: 'text',
                        label: 'LABEL.PAGE.BLOCK.NAME',
                        columns: 6,
                        attr: {
                            required: true,
                            autofocus: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.PAGE.BLOCK.NAME'
                        }
                    }, {
                        property: 'type',
                        type: 'select',
                        label: 'LABEL.PAGE.BLOCK.TYPE',
                        list: 'key as value for (key, value) in types',
                        columns: 6,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.PAGE.BLOCK.TYPE'
                        }
                    }
                ],
                submit: function(data){
                    $scope.modal.close();
                    if($scope.$parent.edit){
                        $scope.$parent.editBlock(angular.copy(data));
                    }else{
                        $scope.$parent.addBlock(angular.copy(data));
                    }
                }
            };
            if($scope.$parent.edit){
                $scope.modalFormSettings.data = angular.copy($scope.$parent.block);
                $scope.$parent.modalFormSettings = $scope.modalFormSettings;
            };
            $scope.$watch('modalFormSettings.data.type', function(type){
                if(type === 'textEditor'){
                    type = 'html';
                }
                if(type){
                    $scope.modalFormSettings.schema[2] = {
                        property: 'content',
                        type: type,
                        label: 'LABEL.PAGE.BLOCK.CONTENT'
                    }
                }else{
                    delete $scope.modalFormSettings.schema[2];
                }
            });
        }])
        .controller('modalMetatagCtrl', ['$scope', '$grAlert', function($scope, $grAlert){
            $scope.modalFormSettings = {
                data: {
                    name: '',
                    content: ''
                },
                schema: [
                    {
                        property: 'name',
                        type: 'text',
                        label: 'LABEL.PAGE.METATAG.NAME',
                        columns: 6,
                        attr: {
                            required: true,
                            autofocus: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.PAGE.METATAG.NAME'
                        }
                    }, {
                        property: 'content',
                        type: 'text',
                        label: 'LABEL.PAGE.METATAG.CONTENT',
                        columns: 6,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'FORM.MESSAGE.REQUIRED.PAGE.METATAG.CONTENT'
                        }
                    }
                ],
                submit: function(data){
                    $scope.modal.close();
                    if($scope.$parent.edit){
                        $scope.$parent.editMetatag(angular.copy(data));
                    }else{
                        $scope.$parent.addMetatag(angular.copy(data));
                    }
                }
            };
            if($scope.$parent.edit){
                $scope.modalFormSettings.data = angular.copy($scope.$parent.metatag);
            }
        }])
        .run(['$templateCache', function($templateCache){
            $templateCache.put('gr-block/text.html', '<input class="form-control" ng-model="block.content" placeholder="{{\'LABEL.PAGE.BLOCK.CONTENT\' | grTranslate}}" autofocus />');
            $templateCache.put('gr-block/textEditor.html', '<text-angular ng-model="block.content" placeholder="{{\'LABEL.PAGE.BLOCK.CONTENT\' | grTranslate}}" />');
        }]);
}());
