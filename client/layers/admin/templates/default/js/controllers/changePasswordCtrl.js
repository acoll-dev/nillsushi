'use strict';
(function(){
    angular.module('adminApp').controller('changePasswordCtrl', ['$rootScope', '$scope', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $timeout, $grRestful, $grAlert){
        var alert = $grAlert.new($scope.modal.element);
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
                    id: $rootScope.GRIFFO.user.id,
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
