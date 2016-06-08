'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.installing = false;
        $scope.formInstall = {
            data: {
                status: 1
            },
            schema: [
                {
                    property: 'module',
                    type: 'url',
                    label: 'LABEL.URL.TO.INSTALL',
                    placeholder: 'http://',
                    attr: {
                        required: true,
                        ngMaxlength: 255,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.URL',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.URL[[255]]',
                        url: 'FORM.MESSAGE.URL.URL'
                    }
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                $scope.installing = true;
                alert.show('loading', 'MODULE.MODULE.INSTALL.RUNNING', 0);
                $grRestful.create({
                    module: 'module',
                    action: 'insert',
                    post: data
                }).then(function(r){
                    $scope.form.reset();
                    alert.show(r.status, r.message);
                }, function(r){
                    alert.show('danger', 'ERROR.FATAL');
                }).finally(function(){
                    $scope.installing = false;
                });
            }
        };
        $scope.$watch('installing', function(is){
            $scope.$parent.installing = is;
        });
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
