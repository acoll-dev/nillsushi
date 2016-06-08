'use strict';
(function(){
    angular.module('adminApp').controller('tabCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.modules = [];
        $scope.change = function(data){
            if(data.default){
                angular.forEach($scope.modules, function(mod){
                    if(mod.idmodule !== data.idmodule){
                        mod.default = false;
                    }else{
                        mod.default = true;
                    }
                });
            }
        };
        $grRestful.find({
            module: 'module',
            action: 'get_module_layer'
        }).then(function(r){
            if(r.response){
                $scope.modules = r.response;
            }
        });
        $grRestful.find({
            module: 'language',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.languages = r.response;
            }
        });
        $grRestful.find({
            module: 'template',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.templates = r.response;
            }
        });
        $grRestful.find({
            module: 'layer',
            action: 'get_config'
        }).then(function(r){
            if(r.response){
                $scope.form1 = angular.element('[name="form1"]').scope().form1;
                $timeout(function(){
                    $scope.formSettings1.data = r.response;
                    $scope.form1.updateDefaults();
                    $scope.$parent.form1 = $scope.form1;
                }, 500);
            }
        });
        $scope.tab = {
            active: 0
        };
        $scope.formSettings1 = {
            data: {},
            schema: [
                {
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
                    property: 'urllogin',
                    type: 'text',
                    label: 'LABEL.URL.LOGIN',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 255
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.URL.LOGIN',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.URL.LOGIN[[255]]'
                    }
                }, {
                    property: 'filtermodel',
                    type: 'text',
                    label: 'LABEL.MODEL.FILTER',
                    columns: 4,
                    placeholder: '/%filtro1%/%filtro2%...',
                    attr: {
                        ngMaxlength: 255
                    },
                    msgs: {
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.MODEL.FILTER[[255]]'
                    }
                }, {
                    property: 'fkidlanguage',
                    type: 'select',
                    label: 'LABEL.LANGUAGE',
                    columns: 4,
                    list: 'lang.value as lang.label for lang in languages',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LANGUAGE'
                    }
                }, {
                    property: 'fkidtemplate',
                    type: 'select',
                    label: 'LABEL.TEMPLATE',
                    columns: 4,
                    list: 'tmpl.value as tmpl.label for tmpl in templates',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.TEMPLATE'
                    }
                }, {
                    property: 'defaultsitemap',
                    type: 'text',
                    label: 'LABEL.DEFAULT.URL.SITEMAP',
                    placeholder: '.../',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 255
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DEFAULT.URL.SITEMAP',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.DEFAULT.URL.SITEMAP'
                    }
                }, {
                    property: 'thumbwidth',
                    type: 'number',
                    label: 'LABEL.THUMBNAIL.WIDTH',
                    columns: 4,
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
                    label: 'LABEL.THUMBNAIL.HEIGHT',
                    columns: 4,
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
                var newData = angular.copy(data);
                newData.modules = $scope.modules;
                $grRestful.update({
                    module: 'layer',
                    action: 'config',
                    post: newData
                }).then(function(r){
                    if($scope.form1.$invalid) return;
                    if(r.response){
                        $scope.form1.updateDefaults();
                    }
                    alert.show(r.status, r.message);
                },
                function(r){
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
    }]);
}());
