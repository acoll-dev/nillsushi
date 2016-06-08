'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.profiles = {};
        $scope.languages = {};
        $scope.themes = {};
        $grRestful.find({'module': 'profile', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.profiles = r.response;
            }
        });
        $grRestful.find({'module': 'language', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.languages = r.response;
            }
        });
        $grRestful.find({'module': 'theme', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.themes = r.response;
            }
        });
        $grRestful.find({'module': 'layer', 'action': 'select'}).then(function(r) {
            if (r.response) {
                $scope.layers = r.response;
            }
        });
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
                    columns: 4,
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
                    type: 'null'
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
                        required: true
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
                    property: 'password',
                    type: 'password',
                    label: 'LABEL.PASSWORD',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMinlength: 5,
                        ngMaxlength: 16
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD',
                        minlength: 'FORM.MESSAGE.MINLENGTH.PASSWORD[[5]]',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.PASSWORD[[16]]'
                    }
                }, {
                    property: 'repassword',
                    type: 'password',
                    label: 'LABEL.CONFIRMPASSWORD',
                    columns: 4,
                    attr: {
                        required: true,
                        confirmPassword: 'formSettings.data.password'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.CONFIRMPASSWORD',
                        match: 'FORM.MESSAGE.MATCH.CONFIRMPASSWORD'
                    }
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                var newData = angular.copy(data);
                delete newData.repassword;
                $grRestful.create({
                    module: 'user',
                    action: 'insert',
                    post: newData
                }).then(function(r) {
                    if (r.response) {
                        $scope.form.reset();
                    }
                    alert.show(r.status, r.message);
                }, function(r) {
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
