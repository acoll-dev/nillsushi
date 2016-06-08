'use strict';
(function(){
    angular.module('gr.filemanager', ['ng', 'pascalprecht.translate', 'angularFileUpload', 'gr.restful', 'gr.ui.modal', 'gr.ui.alert', 'gr.ui.translate']);
}());

/* Example: <gr-file-manager-button gr-name="file" gr-label="Select file" gr-filter="image" gr-required gr-multiple></gr-file-manager-button> */

(function(){
    angular.module('gr.filemanager')
        .directive('fileManager', ['$grRestful', '$grModal', '$grAlert', '$grFilemanager', 'FileUploader', '$filter', '$injector', '$timeout', '$window', '$http', function (REST, MODAL, ALERT, MANAGER, FileUploader, $filter, $injector, $timeout, $window, $http){
                var window = angular.element($window),
                    initManager = function ($scope, $element){
                        var $cookies = $injector.has('$cookies') ? $injector.get('$cookies') : false,
                            $baseUrl = GRIFFO.baseUrl + GRIFFO.librariesPath + 'client/gr-filemanager/';
                        $scope.grManager.settings = angular.copy(MANAGER.defaultSettings);
                        if (MANAGER.settings) { $.extend($scope.grManager.settings, MANAGER.settings); }
                        $scope.grManager.filterType = $scope.grManager.settings.filter;
                        var grFilters = {
                                byType: function (value) {
                                    var items = angular.copy($scope.grManager.allItems),
                                        filteredItems = [];
                                    if (value !== 'all') {
                                        angular.forEach(items, function (item) {
                                            if (item.extension && item.fileType) {
                                                if (item.fileType === value) {
                                                    filteredItems.push(item);
                                                }
                                            } else {
                                                filteredItems.push(item);
                                            }
                                        });
                                    } else {
                                        filteredItems = items;
                                    }
                                    return filteredItems;
                                },
                                byName: function (value) {
                                    var items = angular.copy($scope.grManager.items),
                                        filteredItems = [];
                                    if (value && value !== '') {
                                        angular.forEach(items, function (item) {
                                            if (item.basename) {
                                                if (item.basename.toLowerCase().search(value.toLowerCase()) > -1) {
                                                    filteredItems.push(item);
                                                }
                                            }
                                        });
                                    } else {
                                        filteredItems = items;
                                    }
                                    return filteredItems;
                                }
                            },
                            grOrders = {
                                name: function(){
                                    var items = $scope.grManager.items;
                                    return items.sort(basenameSort);
                                },
                                type: function(reorder){
                                    var items = $scope.grManager.items,
                                        orderedItems = [],
                                        tempDirs = [],
                                        tempFiles = [];
                                    angular.forEach(items, function (item) {
                                        if (item.type === 'dir') {
                                            tempDirs.push(item);
                                        } else {
                                            tempFiles.push(item);
                                        }
                                    });

                                    if(!reorder){
                                        tempDirs.sort(basenameSort);
                                        tempFiles.sort(basenameSort);
                                    }

                                    angular.forEach(tempDirs, function (item) {
                                        orderedItems.push(item);
                                    });
                                    angular.forEach(tempFiles, function (item) {
                                        orderedItems.push(item);
                                    });

                                    return orderedItems;
                                },
                                size: function(){
                                    var items = $scope.grManager.items;
                                    return items.sort(sizeSort);
                                }
                            };
                        $scope.grManager.filter = {
                            byType: {
                                all: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ALL',
                                    extType: 'all',
                                    iconClass: 'fa fa-fw fa-asterisk'
                                },
                                dir: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ONLY.FOLDER',
                                    extType: 'dir',
                                    iconClass: 'fa fa-fw fa-folder'
                                },
                                image: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ONLY.IMAGE',
                                    extType: 'image',
                                    iconClass: 'fa fa-fw fa-camera'
                                },
                                video: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ONLY.VIDEO',
                                    extType: 'video',
                                    iconClass: 'fa fa-fw fa-video-camera'
                                },
                                text: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ONLY.TEXT',
                                    extType: 'text',
                                    iconClass: 'fa fa-fw fa-file-text-o'
                                },
                                pdf: {
                                    enable: true,
                                    label: 'MODULE.FILEMANAGER.LABEL.SHOW.ONLY.PDF',
                                    extType: 'pdf',
                                    iconClass: 'fa fa-fw fa-file-pdf-o'
                                }
                            }
                        };
                        $scope.grManager.order = {
                            type: {
                                label: 'MODULE.FILEMANAGER.LABEL.ORDER.BY.TYPE',
                                value: 'type'
                            },
                            name: {
                                label: 'MODULE.FILEMANAGER.LABEL.ORDER.BY.NAME',
                                value: 'name'
                            },
                            size: {
                                label: 'MODULE.FILEMANAGER.LABEL.ORDER.BY.SIZE',
                                value: 'size'
                            }
                        };
                        $scope.grManager.addFolder = function(){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-add-folder',
                                title: 'MODULE.FILEMANAGER.TITLE.CREATE.FOLDER',
                                size: 'sm',
                                model: $baseUrl + 'view/add-folder.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                buttons: [{
                                    type: 'success',
                                    label: 'BUTTON.CREATE',
                                    attr: {
                                        'gr-enter-bind': ''
                                    },
                                    onClick: function(scope, element, controller){
                                        if(angular.isDefined(scope.path) && scope.path !== ''){
                                            createFolder(scope.path);
                                            controller.close();
                                        }
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.renameFolder = function(path){
                            path = path ? path : $scope.grManager.dir.current.path;
                            var modal = MODAL.new({
                                name: 'gr-file-manager-rename-folder',
                                title: 'MODULE.FILEMANAGER.TITLE.RENAME.FOLDER',
                                size: 'sm',
                                model: $baseUrl + 'view/rename-folder.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    curFolder: path
                                },
                                buttons: [{
                                    type: 'success',
                                    label: 'BUTTON.SAVE',
                                    attr: {
                                        'gr-enter-bind': ''
                                    },
                                    onClick: function(scope, element, controller){
                                        if(angular.isDefined(scope.newName) && scope.newName !== ''){
                                            renameFolder(path, scope.newName);
                                            controller.close();
                                        }
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.deleteFolder = function(path){
                            path = path ? path : $scope.grManager.dir.current.path;
                            var modal = MODAL.new({
                                name: 'gr-file-manager-delete-folder',
                                title: 'MODULE.FILEMANAGER.TITLE.DELETE.FOLDER',
                                size: 'sm',
                                model: $baseUrl + 'view/delete-folder.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    curFolder: path
                                },
                                buttons: [{
                                    type: 'danger',
                                    label: 'BUTTON.DELETE',
                                    onClick: function(scope, element, controller){
                                        deleteFolder(path);
                                        controller.close();
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.renameFile = function(path){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-rename-file',
                                title: 'MODULE.FILEMANAGER.TITLE.RENAME.FILE',
                                size: 'sm',
                                model: $baseUrl + 'view/rename-file.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    curFile: path
                                },
                                buttons: [{
                                    type: 'success',
                                    label: 'BUTTON.SAVE',
                                    attr: {
                                        'gr-enter-bind': ''
                                    },
                                    onClick: function(scope, element, controller){
                                        if(angular.isDefined(scope.newName) && scope.newName !== ''){
                                            renameFile(path, scope.newName);
                                            controller.close();
                                        }
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.deleteFile = function(path){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-delete-file',
                                title: 'MODULE.FILEMANAGER.TITLE.DELETE.FILE',
                                size: 'sm',
                                model: $baseUrl + 'view/delete-file.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    curFile: path
                                },
                                buttons: [{
                                    type: 'danger',
                                    label: 'BUTTON.DELETE',
                                    onClick: function(scope, element, controller){
                                        deleteFile(path);
                                        controller.close();
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.deleteFiles = function(){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-delete-files',
                                title: 'MODULE.FILEMANAGER.TITLE.DELETE.FILES',
                                size: 'sm',
                                model: $baseUrl + 'view/delete-files.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    files: $scope.grManager.selection
                                },
                                buttons: [{
                                    type: 'danger',
                                    label: 'BUTTON.DELETE',
                                    onClick: function(scope, element, controller){
                                        deleteFiles();
                                        controller.close();
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.itemInfo = function(item){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-item-info',
                                title: 'MODULE.FILEMANAGER.TITLE.INFO',
                                size: 'md',
                                model: $baseUrl + 'view/item-info.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    item: item
                                },
                                buttons: [{
                                    type: 'primary',
                                    label: 'BUTTON.OK',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }]
                            });
                            modal.open();
                        };
                        $scope.grManager.toggleSelection = function(path){
                            if(path !== 'all'){
                                angular.forEach($scope.grManager.items, function(item){
                                    if(item.path === path){
                                        item.selected = item.selected ? false : true;
                                        if(item.selected){
                                            if($scope.grManager.selection.indexOf(item) === -1){
                                                if(!$scope.grManager.settings.multiple){
                                                    $scope.grManager.selection = [];
                                                    MANAGER.selection.remove('all');
                                                }
                                                $scope.grManager.selection.push(item);
                                                MANAGER.selection.set(item);
                                            }
                                        }else{
                                            var index = $scope.grManager.selection.indexOf(item);
                                            if(index > -1){
                                                $scope.grManager.selection.splice(index, 1);
                                                MANAGER.selection.remove(item);
                                            }
                                        }
                                    }else if(!$scope.grManager.settings.multiple){
                                        item.selected = false;
                                    }
                                });
                                if($scope.grManager.selection.length < $scope.grManager.filesLength){
                                    $scope.grManager.allSelected = false;
                                }else{
                                    $scope.grManager.allSelected = true;
                                }
                            }else{
                                if($scope.grManager.selection.length < $scope.grManager.filesLength){
                                    $scope.grManager.allSelected = false;
                                }
                                angular.forEach($scope.grManager.items, function(item){
                                    if(item.type !== 'dir'){
                                        item.selected = $scope.grManager.allSelected ? false : true;
                                        if(item.selected){
                                            if($scope.grManager.selection.indexOf(item) === -1){
                                                $scope.grManager.selection.push(item);
                                                MANAGER.selection.set(item);
                                            }
                                        }else{
                                            var index = $scope.grManager.selection.indexOf(item);
                                            if(index > -1){
                                                $scope.grManager.selection.splice(index, 1);
                                                MANAGER.selection.remove(item);
                                            }
                                        }
                                    }
                                });
                                $scope.grManager.allSelected = $scope.grManager.allSelected ? false : true;
                                if(!$scope.grManager.allSelected){
                                    $scope.grManager.selection = [];
                                    MANAGER.selection.remove('all');

                                }
                            }
                        }
                        $scope.grManager.uploadFile = function(){
                            var modal = MODAL.new({
                                name: 'gr-file-manager-upload-file',
                                title: 'MODULE.FILEMANAGER.TITLE.UPLOAD.FILES',
                                size: 'responsive',
                                model: $baseUrl + 'view/upload-file.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    uploader: $scope.grManager.uploader
                                },
                                buttons: [{
                                    type: 'primary',
                                    label: 'BUTTON.ADD.FILES',
                                    labelIcon: 'fa fa-fw fa-plus',
                                    onClick: function(scope, element, controller){
                                        $timeout(function(){
                                            element.find('#ger-upload-file-select').click();
                                        });
                                    }
                                },{
                                    type: 'success',
                                    label: 'BUTTON.UPLOAD.ALL',
                                    labelIcon: 'glyphicon glyphicon-upload',
                                    attr: {
                                        'ng-disabled': '!$parent.uploader.getNotUploadedItems().length'
                                    },
                                    onClick: function(scope, element, controller){
                                        scope.uploader.uploadAll();
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.CANCEL.ALL',
                                    labelIcon: 'glyphicon glyphicon-ban-circle',
                                    attr: {
                                        'ng-disabled': '!$parent.uploader.isUploading'
                                    },
                                    onClick: function(scope, element, controller){
                                        scope.uploader.cancelAll();
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.REMOVE.ALL',
                                    labelIcon: 'glyphicon glyphicon-trash',
                                    attr: {
                                        'ng-disabled': '!$parent.uploader.queue.length'
                                    },
                                    onClick: function(scope, element, controller){
                                        scope.uploader.clearQueue();
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.CLOSE',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            var modalOpended = modal.open();
                            $timeout(function(){
                                $scope.grManager.uploader.modal = modalOpended.element;
                            },100);
                        };
                        $scope.grManager.download = function(file){
                            if(!file){
                                var zip = new JSZip(), countSelection = 0;
                                angular.forEach($scope.grManager.selection, function(file){
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('GET', GRIFFO.baseUrl + GRIFFO.uploadPath + file.path, true);
                                    xhr.responseType = 'arraybuffer';
                                    xhr.onload = function(e) {
                                        if (this.status == 200) {
                                            zip.file(file.basename, this.response);
                                            countSelection ++;
                                            if(countSelection === $scope.grManager.selection.length){
                                                zip = zip.generate({type:"blob"});
                                                saveAs(zip, $scope.grManager.dir.current.basename + '.zip');
                                            }
                                        }
                                    };
                                    xhr.send();
                                });
                            }else{
                                var xhr = new XMLHttpRequest();
                                xhr.open('GET', GRIFFO.baseUrl + GRIFFO.uploadPath + file.path, true);
                                xhr.responseType = 'blob';
                                xhr.onload = function(e) {
                                    if (this.status == 200) {
                                        saveAs(this.response, file.basename);
                                    }
                                };
                                xhr.send();
                            }
                        };
                        $scope.grManager.moveFiles = function(){
                            var items = [];
                            angular.forEach($scope.grManager.breadcrumb.breads, function(item){
                                items.push({
                                    label: item.label !== '/' ? '/ ' + item.path.split('/').join(' / ') : item.label,
                                    path: item.path || '/'
                                });
                            });
                            angular.forEach($scope.grManager.items, function(item){
                                if(item.type === 'dir'){
                                    items.push({
                                        label: '/ ' + item.path.split('/').join(' / '),
                                        path: item.path
                                    });
                                }
                            });
                            var modal = MODAL.new({
                                name: 'gr-file-manager-move-files',
                                title: 'MODULE.FILEMANAGER.TITLE.MOVE.FILES',
                                size: 'sm',
                                model: $baseUrl + 'view/move.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    items: items
                                },
                                buttons: [{
                                    type: 'danger',
                                    label: 'BUTTON.MOVE',
                                    attr: {
                                        'ng-disabled': '!$parent.target'
                                    },
                                    onClick: function(scope, element, controller){
                                        moveFiles(scope.target !== '/' ? scope.target : '');
                                        controller.close();
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.moveFolder = function(folder){
                            var items = [];
                            angular.forEach($scope.grManager.breadcrumb.breads, function(item){
                                items.push({
                                    label: item.label !== '/' ? '/ ' + item.path.split('/').join(' / ') : item.label,
                                    path: item.path || '/'
                                });
                            });
                            angular.forEach($scope.grManager.items, function(item){
                                if(item.type === 'dir' && item !== folder){
                                    items.push({
                                        label: '/ ' + item.path.split('/').join(' / '),
                                        path: item.path
                                    });
                                }
                            });
                            var modal = MODAL.new({
                                name: 'gr-file-manager-move-folder',
                                title: 'MODULE.FILEMANAGER.TITLE.MOVE.FOLDER',
                                size: 'sm',
                                model: $baseUrl + 'view/move.php',
                                zIndex: MANAGER.settings.zIndex ? MANAGER.settings.zIndex + 20 : false,
                                define: {
                                    items: items
                                },
                                buttons: [{
                                    type: 'danger',
                                    label: 'BUTTON.MOVE',
                                    attr: {
                                        'ng-disabled': '!$parent.target'
                                    },
                                    onClick: function(scope, element, controller){
                                        moveFolder(folder, scope.target !== '/' ? scope.target : '');
                                        controller.close();
                                    }
                                },{
                                    type: 'default',
                                    label: 'BUTTON.CANCEL',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }],
                                onClose: modalOnClose
                            });
                            modal.open();
                        };
                        $scope.grManager.reload = loadItems;
                        $scope.$watch('grManager.filterType', function (v) {
                            $scope.grManager.filterName = '';
                            defineItems();
                        }, true);
                        $scope.$watch('grManager.orderBy', function (v) {
                            defineItems();
                        }, true);
                        $scope.$watch('grManager.filterName', function (v) {
                            defineItems();
                        }, true);
                        $scope.$watch('grManager.dir.current', function (v) {
                            if (angular.isDefined(v) && angular.isDefined(v.path)) {
                                var tempBread = (/[^\/]([^\/]+)([\/]{1}[^\/]+)*/g).test(v.path) ? v.path.split('/') : [v.path],
                                    breadcrumb = [],
                                    prevDir = [];

                                if (tempBread[0] !== '/') {
                                    tempBread.unshift('/');
                                }
                                angular.forEach(tempBread, function (bread, id) {
                                    bread = bread.trim();
                                    if (id < tempBread.length - 1 && id > 0) {
                                        prevDir.push(bread);
                                    }
                                    breadcrumb.push({
                                        label: bread,
                                        path: joinBread(tempBread, id),
                                        current: (id === tempBread.length - 1)
                                    });
                                });
                                $scope.grManager.breadcrumb.breads = breadcrumb;
                                $scope.grManager.dir.prev = $scope.grManager.dir.current !== '' ? prevDir.join('/') : false;
                            }
                        }, true);
                        function loadItems(path) {
                            resetVars();
                            $scope.grManager.isLoading = true;
                            if($cookies){
                                $cookies['grFileManagerFolder_UserId' + GRIFFO.user.id] = (path && path !== '/') ? path : '';
                            }
                            REST.fileManager({
                                action: 'list_contents',
                                post: {
                                    path: (path && path !== '/') ? path : ''
                                }
                            }).then(function (r) {
                                if(r.response){
                                    var response = [],
                                        currentDir = r.response.path;
                                    currentDir.dirname = currentDir.dirname.replace('\\', '/');
                                    currentDir.path = currentDir.path.replace('\\', '/');
                                    angular.forEach(r.response.contents, function (item) {
                                        if (item.extension) {
                                            item.fileType = getExtType(item.extension);
                                        }
                                        item.dirname = item.dirname.replace('\\', '/');
                                        item.path = item.path.replace('\\', '/');
                                        response.push(item);
                                    });
                                    $scope.grManager.dir.current = currentDir;
                                    $scope.grManager.items = response;
                                    $scope.grManager.allItems = response;
                                    $scope.grManager.isLoading = false;
                                    $scope.grManager.filterName = '';
                                    defineItems();
                                    setChangeEvents();
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.LOAD.FILES');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.LOAD.FILES');
                                $scope.grManager.isLoading = false;
                                setChangeEvents();
                            });
                        }
                        function defineItems() {
                            filterItems('byType', $scope.grManager.filterType);
                            filterItems('byName', $scope.grManager.filterName);
                            orderItems($scope.grManager.orderBy);
                            angular.forEach($scope.grManager.items, function(item){
                                if(item.type !== 'dir'){
                                    $scope.grManager.filesLength ++;
                                }
                            });
                            setChangeEvents();
                        }
                        function setChangeEvents(){
                            $timeout(function(){ angular.element('.imgLiquidFill').imgLiquid(); });
                            window.trigger('resize2');
                            if(angular.isDefined($scope.grManager.uploader)){
                                $scope.grManager.uploader.formData[0].path = $scope.grManager.dir.current.path;
                            }
                        }
                        function orderItems(orderBy) {
                            $scope.grManager.items = grOrders[orderBy]();
                            if(orderBy !== 'type'){
                                $scope.grManager.items = grOrders.type(true);
                            }
                        }
                        function filterItems(filter, value) {
                            var filteredItems = grFilters[filter](value);
                            $scope.grManager.items = filteredItems;
                        }
                        function createFolder(path) {
                            path = ($scope.grManager.dir.current.path !== '/') ? $scope.grManager.dir.current.path + '/' + path : path;
                            REST.fileManager({
                                action: 'add_folder',
                                post: {
                                    'path': path
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.CREATE.FOLDER');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.CREATE.FOLDER');
                                setChangeEvents();
                            });
                        }
                        function renameFolder(path, name) {
                            var curPath = path.split('/');
                            if(curPath.length > 1){
                                curPath.pop();
                                curPath = curPath.join('/');
                                curPath += '/';
                            }else{
                                curPath = '';
                            }
                            REST.fileManager({
                                action: 'rename_folder',
                                post: {
                                    'name': path,
                                    'new-name': curPath + name
                                }
                            }).then(function (r) {
                                if(r.response){
                                    if(path === $scope.grManager.dir.current){
                                        $scope.grManager.dir.current = r.response.path;
                                    }
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.RENAME.FOLDER');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.RENAME.FOLDER');
                                setChangeEvents();
                            });
                        }
                        function deleteFolder(path){
                            REST.fileManager({
                                action: 'delete_folder',
                                post: {
                                    'folder': path
                                }
                            }).then(function (r) {
                                if(r.response){
                                    if(path === $scope.grManager.dir.current.path){
                                        $scope.grManager.dir.current.path = $scope.grManager.dir.prev;
                                    }
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FOLDER');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FOLDER');
                                setChangeEvents();
                            });
                        }
                        function renameFile(path, name) {
                            var ext = path.indexOf('.') > -1 ? '.' + path.split('.').pop() : '',
                                curPath = path.split('/');
                            if(curPath.length > 1){
                                curPath.pop();
                                curPath = curPath.join('/');
                                curPath += '/';
                            }else{
                                curPath = '';
                            }
                            REST.fileManager({
                                action: 'rename_file',
                                post: {
                                    'name': path,
                                    'new-name': curPath + name + ext
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.RENAME.FILE');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.RENAME.FILE');
                                setChangeEvents();
                            });
                        }
                        function deleteFile(path){
                            REST.fileManager({
                                action: 'delete_file',
                                post: {
                                    'file': path
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FILE');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FILE');
                                setChangeEvents();
                            });
                        }
                        function deleteFiles(){
                            var files = [];
                            angular.forEach($scope.grManager.selection, function(file){
                                files.push(file.path);
                            });
                            REST.fileManager({
                                action: 'delete_files',
                                post: {
                                    'files': files
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FILES');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.DELETE.FILES');
                                setChangeEvents();
                            });
                        }
                        function moveFiles(target){
                            var files = [];
                            angular.forEach($scope.grManager.selection, function(file){
                                files.push({
                                    basename: file.basename,
                                    dirname: file.dirname
                                });
                            });
                            REST.fileManager({
                                action: 'move_files',
                                post: {
                                    'files': files,
                                    'path': target
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.MOVE.FILES');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.MOVE.FILES');
                                setChangeEvents();
                            });
                        }
                        function moveFolder(folder, target){
                            REST.fileManager({
                                action: 'move_folder',
                                post: {
                                    'folder': {
                                        'basename': folder.basename,
                                        'dirname': folder.dirname
                                    },
                                    'path': target
                                }
                            }).then(function (r) {
                                if(r.response){
                                    $scope.grManager.reload($scope.grManager.dir.current.path);
                                }else{
                                    grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.MOVE.FOLDER');
                                }
                            }, function (e) {
                                console.debug(e);
                                grAlert.show('danger', 'MODULE.FILEMANAGER.ERROR.MOVE.FOLDER');
                                setChangeEvents();
                            });
                        }
                        function initUploader(){
                            var uploader = $scope.grManager.uploader = new FileUploader({
                                url: GRIFFO.baseUrl + GRIFFO.restPath + 'gallery/upload_file',
                                formData: [{
                                    path: '',
                                    token: GRIFFO.user.token
                                }]
                            });

                            uploader.getType = getExtType;

                            // FILTERS

                            uploader.filters.push(
                                {
                                    name: 'sizeFilter',
                                    fn: function(item, options) {
                                        if(item.size > 2097152){
                                            return false;
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'imageFilter',
                                    fn: function(item /*{File|FileLikeObject}*/, options) {
                                        if($scope.grManager.settings.filter === 'image'){
                                            return getExtType(item.name, true) === 'image';
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'musicFilter',
                                    fn: function(item, options) {
                                        if($scope.grManager.settings.filter === 'music'){
                                            return getExtType(item.name, true) === 'music';
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'videoFilter',
                                    fn: function(item, options) {
                                        if($scope.grManager.settings.filter === 'video'){
                                            return getExtType(item.name, true) === 'video';
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'textFilter',
                                    fn: function(item, options) {
                                        if($scope.grManager.settings.filter === 'text'){
                                            return getExtType(item.name, true) === 'text';
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'pdfFilter',
                                    fn: function(item, options) {
                                        if($scope.grManager.settings.filter === 'pdf'){
                                            return getExtType(item.name, true) === 'pdf';
                                        }else{
                                            return true;
                                        }
                                    }
                                },{
                                    name: 'allFiles',
                                    fn: function(item, options) {
                                        return getExtType(item.name, true) !== 'undefined';
                                    }
                                });

                            // CALLBACKS

                            uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
                                var uploaderModal = ALERT.new(uploader.modal);
                                if(filter.name === 'sizeFilter'){
                                    var text = 'MODULE.FILEMANAGER.ERROR.LARGER.FILE.SIZE[[$1,$2]]';
                                    text = text.replace('$1', '2MB').replace('$2',item.name);
                                    uploaderModal.show('danger', [text]);
                                }else{
                                    var text = 'MODULE.FILEMANAGER.ERROR.INVALID.FILE.FORMAT[[$1,$2]]';
                                    var ext = item.name.split('.');
                                        ext = ext[ext.length-1].toUpperCase();
                                        text = text.replace('$1', ext).replace('$2',item.name);
                                    uploaderModal.show('danger', [text]);
                                }
                            };
                            /*
                                uploader.onAfterAddingFile = function(fileItem) {
                                    console.info('onAfterAddingFile', fileItem);
                                };
                                uploader.onAfterAddingAll = function(addedFileItems) {
                                    console.info('onAfterAddingAll', addedFileItems);
                                };
                                uploader.onBeforeUploadItem = function(item) {
                                    console.info('onBeforeUploadItem', item);
                                };
                                uploader.onProgressItem = function(fileItem, progress) {
                                    console.info('onProgressItem', fileItem, progress);
                                };
                                uploader.onProgressAll = function(progress) {
                                    console.info('onProgressAll', progress);
                                };
                                uploader.onSuccessItem = function(fileItem, response, status, headers) {
                                    console.info('onSuccessItem', fileItem, response, status, headers);
                                };
                                uploader.onErrorItem = function(fileItem, response, status, headers) {
                                    console.info('onErrorItem', fileItem, response, status, headers);
                                };
                                uploader.onCancelItem = function(fileItem, response, status, headers) {
                                    console.info('onCancelItem', fileItem, response, status, headers);
                                };
                                uploader.onCompleteItem = function(fileItem, response, status, headers) {
                                    console.info('onCompleteItem', fileItem, response, status, headers);
                                };
                            */
                            uploader.onCompleteAll = function() {
                                $scope.grManager.reload($scope.grManager.dir.current.path);
                            };
                        }
                        function resetVars(){
                            $scope.grManager.items = [];
                            $scope.grManager.allItems = [];
                            $scope.grManager.dir = {
                                current: '',
                                prev: ''
                            };
                            $scope.grManager.selection = [];
                            MANAGER.selection.remove('all');
                            $scope.grManager.allSelected = false;
                            $scope.grManager.filesLength = 0;
                            $scope.grManager.breadcrumb = {};
                        }
                        function joinBread(breads, pos){
                            var tmp = [];
                            angular.forEach(breads, function (bread, id) {
                                if (id <= pos && id > 0) {
                                    tmp.push(bread);
                                }
                            });
                            return tmp.join('/');
                        }
                        function basenameSort(a, b){
                            var a = a.basename.toUpperCase();
                            var b = b.basename.toUpperCase();
                            return (a < b) ? -1 : (a > b) ? 1 : 0;
                        }
                        function sizeSort(a, b){
                            var a = a.size;
                            var b = b.size;
                            return (a < b) ? -1 : (a > b) ? 1 : 0;
                        }
                        function getExtType(ext, split){
                            if(split){
                                ext = ext.split('.');
                                ext = ext[ext.length - 1];
                            }
                            ext = ext.toLowerCase();
                            var type = '';
                            if ((/(gif|jpg|jpeg|tiff|png|bmp)$/i).test(ext)) {
                                return 'image';
                            } else if ((/(mp3|wma|wav)$/i).test(ext)) {
                                return 'music';
                            } else if ((/(flv|avi|wmv|rm|rmvb|mp4|m4p|m4v|mpg|mp2|mpeg|mpe|mpv|m2v|mov|mkv)$/i).test(ext)) {
                                return 'video';
                            } else if ((/(txt|doc|docx)$/i).test(ext)) {
                                return 'text';
                            } else if ((/(pdf)$/i).test(ext)) {
                                return 'pdf';
                            } else {
                                return 'undefined';
                            }
                        }
                        function modalOnClose(element){
                            $scope.grManager.uploader.clearQueue();
                        }
                        var grAlert = ALERT.new($element.parents('.modal').eq(0));
                        loadItems($cookies['grFileManagerFolder_UserId' + GRIFFO.user.id] || '');
                        initUploader();
                    };
                return {
                    restrict: 'A',
                    link: function ($scope, $element) {
                        $scope.GRIFFO = GRIFFO;
                        $scope.grManager = {
                            items: [],
                            orderBy: 'type',
                            filterType: 'all',
                            filterName: '',
                            isLoading: false
                        };
                        initManager($scope, $element);
                    }
                };
        }])
        .provider('$grFilemanager', function(){
            var $grModal,
                $templateCache,
                $compile,
                $timeout,
                selection = [],
                getSelection = {},
                grFileManager = {
                    settings: {},
                    defaultSettings: {
                        id: 0,
                        filter: 'all',
                        multiple: false,
                        callback: true,
                        path:''
                    },
                    open: function(options){
                        options = options || {};
                        if(!options.path){ options.path = GRIFFO.baseUrl + GRIFFO.librariesPath + 'client/gr-filemanager/'; }
                        angular.extend(grFileManager.settings, grFileManager.defaultSettings, options);
                        var modal = $grModal.new({
                                name: 'global-file-manager',
                                title: 'MODULE.FILEMANAGER.NAME',
                                size: 'responsive',
                                model: options.path + 'gr-filemanager.php',
                                define: {
                                    grModalId: options.id
                                },
                                zIndex: (options.zIndex && parseInt(options.zIndex) > 0) ? parseInt(options.zIndex) : false,
                                buttons: [{
                                    type: 'primary',
                                    label: 'BUTTON.GET.SELECTION',
                                    labelIcon: 'fa fa-fw fa-arrow-circle-o-down',
                                    attr: {
                                        'ng-show': '$parent.grManager.settings.callback',
                                        'ng-disabled': '$parent.grManager.selection.length === 0'
                                    },
                                    onClick: function(scope, element, controller){
                                        options.onGetSelection(grFileManager.selection.get(scope.grModalId, true));
                                        controller.close();
                                    }
                                },{
                                    type: 'danger',
                                    label: 'BUTTON.CLOSE',
                                    onClick: function(scope, element, controller){
                                        controller.close();
                                    }
                                }]
                            });
                            modal.open();
                    },
                    selection: {
                        set: function(item){
                            if(selection.indexOf(item) === -1){
                                selection.push(item);
                            }
                        },
                        remove: function(item){
                            if(angular.isObject(item)){
                                if(selection.indexOf(item) > -1){
                                    var index = selection.indexOf(item);
                                    selection.splice(index, 1);
                                }
                            }else if(item === 'all'){
                                selection = [];
                            }
                        },
                        get: function(id, stringfy, divisor){
                            var files;
                            if(stringfy){
                                files ='';
                                angular.forEach(selection, function(item, id){
                                    files += item.path;
                                    if(selection[id+1]){
                                        files += divisor || ';'
                                    }
                                });
                            }else{
                                files = selection;
                            }
                            if(getSelection[id]){
                                getSelection[id](files);
                                return false;
                            }else{
                                return files;
                            }
                        }
                    },
                    onGetSelection: function(fn, id){
                        getSelection[id] = fn;
                    }
                };
            this.open = grFileManager.open;
            this.$get = ['$grModal', '$templateCache', '$compile', '$timeout', function(grModal, templaceCache, compile, timeout){
                $grModal = grModal;
                $templateCache = templaceCache;
                $compile = compile;
                $timeout = timeout;
                return grFileManager;
            }];
        })
        .directive('ngThumb', ['$window', function($window) {
            var helper = {
                support: !!($window.FileReader && $window.CanvasRenderingContext2D),
                isFile: function(item) {
                    return angular.isObject(item) && item instanceof $window.File;
                },
                isImage: function(file) {
                    var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                    return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
                }
            };
            return {
                restrict: 'A',
                template: '<canvas/>',
                link: function(scope, element, attributes){
                    if (!helper.support) return;
                    var params = scope.$eval(attributes.ngThumb);
                    if (!helper.isFile(params.file)) return;
                    if (!helper.isImage(params.file)) return;
                    var canvas = element.find('canvas');
                    var reader = new FileReader();
                    reader.onload = onLoadFile;
                    reader.readAsDataURL(params.file);
                    function onLoadFile(event) {
                        var img = new Image();
                        img.onload = onLoadImage;
                        img.src = event.target.result;
                    }
                    function onLoadImage() {
                        var width = params.width || this.width / this.height * params.height;
                        var height = params.height || this.height / this.width * params.width;
                        canvas.attr({ width: width, height: height });
                        canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                    }
                }
            };
        }])
        .directive('grFileManagerButton', ['$rootScope', '$templateCache', '$timeout', '$compile', '$injector', '$grFilemanager', function($rootScope, $templateCache, $timeout, $compile, $injector, MANAGER){
            return {
                restrict: 'EA',
                template: function(){
                    return $templateCache.get('gr-filemanager/button.html');
                },
                scope: {
                    model: '=ngModel',
                    callback: '=',
                    label: '=',
                    labelIcon: '=',
                    multiple: '=allowMultiple',
                    filter: '='
                },
                replace: true,
                link: function($scope, $element, $attrs){
                    $scope.openFileManager = function(e){
                        e.preventDefault();
                        var options = {
                            id: 'global-file-manager',
                            filter: $scope.filter || 'all',
                            multiple: ($scope.multiple === true || $scope.multiple === 'true' || $scope.multiple === 1 || $scope.multiple === '1') ? true : false,
                            callback: ($scope.callback === true || $scope.callback === 'true' || $scope.callback === 1 || $scope.callback === '1') ? true : false
                        };
                        if($scope.callback){
                            options.onGetSelection = function(files){
                                $scope.model = files;
                            }
                        }
                        MANAGER.open(options);
                    };
                    $rootScope.$watch('GRIFFO', function(g){
                        $scope.GRIFFO = g;
                    }, true);
                }
            }
        }]).run(['$templateCache', function($templateCache){
            $templateCache.put('gr-filemanager/button.html', [
                '<button type="button" class="btn btn-default gr-filemanager-button" ng-click="openFileManager($event)">',
                    '<i ng-class="labelIcon" ng-if="labelIcon"></i>',
                    '<span ng-if="GRIFFO.viewPort.bs !== \'xs\'">&nbsp; {{label}}</span>',
                '</button>',
            ].join(''));
        }])
        .filter('cutFilename', function () {
            return function (value, wordwise, max, tail) {
                if (!value) return '';
                max = parseInt(max, 10);
                if (!max) return value;
                if (value.length <= max) return value;
                value = value.substr(0, max);
                if (wordwise) {
                    var lastspace = value.lastIndexOf(' ');
                    if (lastspace != -1) {
                        value = value.substr(0, lastspace);
                    }
                }
                return value + (tail || '…');
            };
        })
        .filter('timestamp', function(){
            return function (timestamp) {
                var d = new Date(),
                    formatedDate = new Date(timestamp*1000 + d.getTimezoneOffset() * 60000);
                return formatedDate;
            }
        })
        .config(['$translateProvider', function($translateProvider){
            if(GRIFFO.language){
                $translateProvider.useStaticFilesLoader({
                    prefix: GRIFFO.applicationPath + 'language/',
                    suffix: '.json'
                }).preferredLanguage(GRIFFO.language);
            }
        }]);
}());
