'use strict';
(function(){
    angular.module('adminApp').controller('tableCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.updateOrder = function(banner){
            if(banner.changed){
                var newData = angular.copy(banner);
                delete newData.changed;
                delete newData.category;
                alert.show('loading', 'ALERT.CHANGING.SORT', 2000);
                $grRestful.update({
                    module: 'banner',
                    action: 'update_attributes',
                    id: banner.idbanner,
                    post: newData
                }).then(function(r){
                    if(r.response){
                        alert.show('success', 'ALERT.SUCCESS.CHANGE.SORT', 2000);
                    }
                });
                banner.changed = false;
            }
        };
        $timeout(function(){
            $scope.$parent.grTable = $scope.grTable;
        });
    }]);
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            ready = {
                banners: false,
                data: false
            },
            checkReady = function(){
                if(ready.banners & ready.data){
                    $scope.modal.ready();
                }
            };
        $scope.categories = {};
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
            'module': 'banner',
            'action': 'select_category'
        }).then(function(r){
            if(r.response){
                $scope.categories = r.response;
            }
            ready.banners = true;
        }).finally(checkReady);
        $grRestful.find({
            'module': 'banner',
            'action': 'get',
            'id': $scope.grTableImport.id
        }).then(function (r) {
            if(r.response){
                $scope.formSettings.data = r.response;
                $scope.form.updateDefaults();
            }
            ready.data = true;
        }).finally(checkReady);
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'picture',
                    type: 'filemanager',
                    label: 'LABEL.IMAGE',
                    columns: 6,
                    attr: {
                        required: true,
                        class: 'btn-success',
                        filter: 'image',
                        label: {
                            icon: 'fa fa-fw fa-image',
                            select: 'BUTTON.SELECT.IMAGE',
                            change: 'BUTTON.CHANGE.IMAGE'
                        }
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.IMAGE'
                    }
                }, {
                    property: 'status',
                    type: 'select',
                    label: 'LABEL.STATUS',
                    columns: 6,
                    list: 'item.value as item.label for item in status',
                    attr: {
                        required: true
                    },
                    msgs: {
                        require: 'FORM.MESSAGE.REQUIRED.STATUS'
                    }
                }, {
                    property: 'title',
                    type: 'text',
                    label: 'LABEL.TITLE',
                    columns: 6,
                    attr: {
                        required: true,
                        ngMaxlength: 255
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.TITLE',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.TITLE[[255]]'
                    }
                }, {
                    property: 'description',
                    type: 'text',
                    label: 'LABEL.DESCRIPTION',
                    columns: 6
                }, {
                    property: 'link',
                    type: 'text',
                    label: 'LABEL.LINK',
                    columns: 3
                }, {
                    property: 'keywords',
                    type: 'text',
                    label: 'LABEL.KEYWORDS',
                    columns: 3,
                    attr: {
                        ngMaxlength: 255
                    },
                    msgs: {
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.KEYWORDS[[255]]'
                    }
                }, {
                    property: 'fkidcategory',
                    type: 'select',
                    label: 'LABEL.CATEGORY',
                    columns: 3,
                    list: 'item.value as item.label for item in categories'
                },{
                    property: 'sort',
                    type: 'number',
                    label: 'LABEL.SORT',
                    columns: 3
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'banner',
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
    }]);
}());
