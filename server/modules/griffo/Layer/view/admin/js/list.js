'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter','$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new(),
            ready = {
                languages: false,
                templates: false,
                data: false
            },
            checkReady = function(){
                if(ready.languages && ready.templates && ready.data){
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
        $grRestful.find({
            'module': 'language',
            'action': 'select'
        }).then(function(r){
            if(r.response){
                $scope.languages = r.response;
            }
            ready.languages = true;
        }).finally(checkReady);
        $grRestful.find({
            'module': 'template',
            'action': 'select'
        }).then(function(r){
            if(r.response){
                $scope.templates = r.response;
            }
            ready.templates = true;
        }).finally(checkReady);
        $grRestful.find({
            module: 'layer',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
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
                    property: 'label',
                    type: 'text',
                    label: 'LABEL.LABEL',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 45
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LABEL',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.LABEL[[45]]'
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
                    property: 'url',
                    type: 'text',
                    label: 'LABEL.URL',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 255
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.URL',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.URL[[255]]'
                    }
                }, {
                    property: 'filtermodel',
                    type: 'text',
                    label: 'LABEL.MODEL.FILTER',
                    placeholder: '/%filter1%/%filter2%...',
                    columns: 4,
                    attr: {
                        ngMaxlength: 255
                    },
                    msgs: {
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.MODEL.FILTER'
                    }
                }, {
                    property: 'defaultsitemap',
                    type: 'text',
                    label: 'LABEL.DEFAULT.URL.SITEMAP',
                    columns: 4,
                    placeholder: '.../',
                    attr: {
                        required: true,
                        ngMaxlength: 255
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DEFAULT.URL.SITEMAP',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.DEFAULT.URL.SITEMAP[[255]]'
                    }
                }, {
                    property: 'fkidlanguage',
                    type: 'select',
                    label: 'LABEL.LANGUAGE',
                    list: 'value.value as value.label for value in languages',
                    columns: 3,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LANGUAGE'
                    }
                }, {
                    property: 'fkidtemplate',
                    type: 'select',
                    label: 'LABEL.DEFAULT.TEMPLATE',
                    list: 'value.value as value.label for value in templates',
                    columns: 3,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DEFAULT.TEMPLATE'
                    }
                }, {
                    property: 'thumbwidth',
                    type: 'number',
                    label: 'Largura do thumb',
                    columns: 3,
                    attr: {
                        min: 0,
                        max: 300
                    },
                    msgs: {
                        min: 'FORM.MESSAGE.MIN.THUMBNAIL.WIDTH[[0]]',
                        max: 'FORM.MESSAGE.MAX.THUMBNAIL.WIDTH[[300]]'
                    }
                }, {
                    property: 'thumbheight',
                    type: 'number',
                    label: 'Altura do thumb',
                    columns: 3,
                    attr: {
                        min: 0,
                        max: 300
                    },
                    msgs: {
                        min: 'FORM.MESSAGE.MIN.THUMBNAIL.HEIGHT[[0]]',
                        max: 'FORM.MESSAGE.MAX.THUMBNAIL.HEIGHT[[300]]'
                    }
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'layer',
                    action: 'update_attributes',
                    id: $scope.grTableImport.id,
                    post: data
                }).then(function(r){
                    if(r.response){
                        $scope.grTableImport.grTable.reloadData();
                        $scope.modal.forceClose();
                    }else{
                        alert.show(r.status, r.message);
                    }
                },function (error) {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
