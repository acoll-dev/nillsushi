'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$window', '$timeout', '$translate', '$grRestful', '$grAlert', function($scope, $window, $timeout, $translate, $grRestful, $grAlert) {
        var alert = $grAlert.new();

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
        });

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
                $grRestful.create({
                    module: 'product',
                    action: 'insert',
                    post: newData
                }).then(function(r){
                    if(r.response){ $scope.form.reset(); }
                    alert.show(r.status, r.message);
                }, function(r){
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
