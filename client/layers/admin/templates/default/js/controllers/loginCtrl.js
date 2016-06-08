'use strict';
(function(){
    angular.module('loginApp').controller('loginCtrl', ['$rootScope', '$scope', '$location', '$cookies', '$filter', '$timeout', '$window', '$grRestful', '$grAlert', function($rootScope, $scope, $location, $cookies, $filter, $timeout, $window, $grRestful, $grAlert){
        var alert = $grAlert.new();
        $scope.loginForm = {
            data: {},
            schema: [
                {
                    property: 'user',
                    type: 'text',
                    label: 'LABEL.USER',
                    addons: [{
                        icon: 'fa fa-fw fa-user',
                        before: true
                    }],
                    attr: {
                        ngMinlength: 4,
                        required: true,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.USER'
                    }
                },{
                    property: 'password',
                    type: 'password',
                    label: 'LABEL.PASSWORD',
                    addons: [{
                        icon: 'fa fa-fw fa-lock',
                        before: true
                    }],
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD'
                    }
                }
            ],
            submit: function(data){
                if($scope.loginForm.$invalid) return;
                $grRestful.auth({
                    action: 'login',
                    post: data
                }).then(
                    function (r) {
                        if(r.response){
                            if($cookies['grAdminLastPage_UserID' + r.response.user.iduser]){
                                location.href = $rootScope.GRIFFO.curAlias + $cookies['grAdminLastPage_UserID' + r.response.user.iduser];
                            }else{
                                location.reload();
                            }
                        }
                        alert.show(r.status, r.message);
                    },
                    function (error) {
                        alert.show('danger', 'ERROR.FATAL');
                    }
                );
            }
        };
    }]);
}());
