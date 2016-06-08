'use strict';
(function(){
    angular.module('adminApp')
        .controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert){
            var alert = $grAlert.new();

            $scope.formSettings = {
                data: {},
                schema: [
                    {
                        type: 'multiple',
                        fields: [
                            {
                                property: 'user',
                                type: 'text',
                                label: 'LABEL.USER',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.USER'
                                }
                            }, {
                                property: 'password',
                                type: 'text',
                                label: 'LABEL.PASSWORD',
                                attr: {
                                    required: true
                                },
                                msgs: {
                                    required: 'FORM.MESSAGE.REQUIRED.PASSWORD'
                                }
                            }
                        ],
                        columns: 2
                    }
                ],
                submit: function(data){
                    if($scope.form.$invalid) return;
                    $grRestful.create({
                        module: 'userauth',
                        action: 'insert',
                        post: data
                    }).then(function(r){
                        if(r.response){ $scope.form.reset(); }
                        alert.show(r.status, r.message);
                    }, function(r){
                        alert.show('danger', 'ERROR.FATAL');
                    });
                }
            };
            $timeout(function(){
                $scope.$parent.form = $scope.form;
            });
        }]);
}());
