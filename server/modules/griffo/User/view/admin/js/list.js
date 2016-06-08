'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', '$grModal', function($scope, $filter, $window, $timeout, $grRestful, $grAlert, $grModal) {
        var alert = $grAlert.new(),
            ready = {
                profiles: false,
                themes: false,
                layers: false,
                languages: false,
                data: false
            },
            checkReady = function(){
                if(ready.profiles && ready.themes && ready.layers && ready.languages && ready.data){
                    $scope.$parent.modal.ready();
                }
            };
        $scope.isAdmin = false;
        $scope.profiles = {};
        $scope.languages = {};
        $scope.themes = {};
        $grRestful.find({'module': 'profile', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.profiles = r.response;
                if(GRIFFO.user.profile.idprofile === 1){
                    $scope.profiles.splice(1, 0, {
                        value: 1,
                        label: 'Administrator'
                    });
                }
            }
            ready.profiles = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'language', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.languages = r.response;
            }
            ready.languages = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'theme', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.themes = r.response;
            }
            ready.themes = true;
        }).finally(checkReady);
        $grRestful.find({'module': 'layer', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.layers = r.response;
            }
            ready.layers = true;
        }).finally(checkReady);
        $grRestful.find({
            module: 'user',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                $scope.formSettings.data = r.response;
                $scope.form.updateDefaults();
                $scope.isAdmin = $scope.formSettings.data.fkidprofile === 1;
                delete $scope.formSettings.data.password;
            }
            ready.data = true;
        }).finally(checkReady);
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
                fkidlanguage: 2,
                fkidtheme: 1,
                fkidlayer: 2
            },
            schema: [
                {
                    property: 'picture',
                    type: 'filemanager',
                    label: 'LABEL.PICTURE',
                    columns: 8,
                    attr: {
                        class: 'btn-success',
                        filter: 'image',
                        label: {
                            icon: 'fa fa-fw fa-image',
                            select: 'BUTTON.SELECT.PICTURE',
                            change: 'BUTTON.CHANGE.PICTURE'
                        }
                    }
                }, {
                    property: 'status',
                    type: 'select',
                    label: 'LABEL.STATUS',
                    list: 'item.value as item.label for item in status',
                    columns: 4,
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
                    property: 'nickname',
                    type: 'text',
                    label: 'LABEL.NICKNAME',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 15
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NICKNAME',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.NICKNAME[[15]]'
                    }
                }, {
                    property: 'email',
                    type: 'email',
                    label: 'LABEL.EMAIL',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 100
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.EMAIL',
                        email: 'FORM.MESSAGE.EMAIL.EMAIL',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.EMAIL[[100]]'
                    }
                }, {
                    property: 'fkidprofile',
                    type: 'select',
                    label: 'LABEL.PROFILE',
                    list: 'value.value as value.label for value in profiles',
                    columns: 4,
                    attr: {
                        required: true,
                        ngDisabled: 'isAdmin'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PROFILE'
                    }
                }, {
                    property: 'fkidlanguage',
                    type: 'select',
                    label: 'LABEL.LANGUAGE',
                    list: 'value.value as value.label for value in languages',
                    columns: 4,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LANGUAGE'
                    }
                }, {
                    property: 'fkidtheme',
                    type: 'select',
                    label: 'LABEL.THEME',
                    list: 'value.value as value.label for value in themes',
                    columns: 4,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.THEME'
                    }
                }, {
                    property: 'fkidlayer',
                    type: 'select',
                    label: 'LABEL.DEFAULT.LAYER',
                    list: 'value.value as value.label for value in layers',
                    columns: 4
                }, {
                    property: 'login',
                    type: 'text',
                    label: 'LABEL.LOGIN',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMaxlength: 45
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LOGIN',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.LOGIN[[45]]'
                    }
                }, {
                    type: 'button',
                    label: 'BUTTON.CHANGE.PASSWORD',
                    columns: 4,
                    addons: {
                        icon: 'fa fa-fw fa-lock',
                        before: true
                    },
                    attr: {
                        'ng-click': 'changePassword()',
                        class: 'btn btn-danger'
                    }
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                var newData = angular.copy(data);
                delete newData.repassword;
                $grRestful.update({
                    module: 'user',
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
                }, function(r) {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.changePassword = function(){
            var modal = $grModal.new({
                name: 'change-password',
                title: 'TITLE.PASSWORD.CHANGE',
                size: 'md',
                model: GRIFFO.modulePath + 'view/admin/modal/change-password.php',
                define: {
                    id: $scope.grTableImport.id
                },
                buttons: [{
                    type: 'success',
                    label: 'BUTTON.SAVE',
                    onClick: function($scope, $element, controller){
                        $scope.form.submit();
                    }
                }, {
                    type: 'danger',
                    label: 'BUTTON.CLOSE',
                    onClick: function($scope, $element, controller){
                        controller.close();
                    }
                }]
            });
            modal.open();
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
(function(){
    angular.module('adminApp').controller('changePassword2Ctrl', ['$scope', '$timeout', '$grRestful', '$grAlert', function($scope, $timeout, $grRestful, $grAlert){
        var alert = $grAlert.new();
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'oldpassword',
                    type: 'password',
                    label: 'LABEL.PASSWORD.CURRENT',
                    columns: 4,
                    attr: {
                        required: true,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD.CURRENT'
                    }
                }, {
                    property: 'password',
                    type: 'password',
                    label: 'LABEL.PASSWORD.NEW',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMinlength: 5,
                        ngMaxlength: 16
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD.NEW',
                        minlength: 'FORM.MESSAGE.MINLENGTH.PASSWORD.NEW[[5]]',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.PASSWORD.NEW[[16]]'
                    }
                }, {
                    property: 'repassword',
                    type: 'password',
                    label: 'LABEL.PASSWORD.NEW.CONFIRM',
                    columns: 4,
                    attr: {
                        required: true,
                        confirmPassword: 'formSettings.data.password'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD.NEW.CONFIRM',
                        match: 'FORM.MESSAGE.MATCH.CONFIRMPASSWORD'
                    }
                }
            ],
            submit: function(data){
                var newData = {
                    'old-password': data.oldpassword,
                    'password': data.password,
                    're-password': data.repassword
                };
                $grRestful.update({
                    module: 'user',
                    action: 'change_password',
                    id: $scope.$parent.id,
                    post: newData
                }).then(function(r){
                    if(r.response){
                        $scope.modal.close();
                        alert.show('success', 'ALERT.SUCCESS.CHANGE.PASSWORD');
                    }else{
                        alert.show(r.status, r.message);
                    }
                },function() {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }])
    .directive('confirmPassword', function () {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                var validate = function (viewValue) {
                    var password = scope.$eval(attrs.confirmPassword);
                    ngModel.$setValidity('match', ngModel.$isEmpty(viewValue) || viewValue == password);
                    return viewValue;
                }
                ngModel.$parsers.push(validate);
                scope.$watch(attrs.confirmPassword, function(value){
                    validate(ngModel.$viewValue);
                })
            }
        }
    });
}());
