'use strict';
(function(){
    angular.module('adminApp').controller('editProfileCtrl', ['$rootScope', '$scope', '$timeout', '$grRestful', '$grAlert', function($rootScope, $scope, $timeout, $grRestful, $grAlert){
        var alert = $grAlert.new($scope.modal.element);
        $grRestful.find({
            module: 'user',
            action: 'get',
            id: $rootScope.GRIFFO.user.id
        }).then(function(r){
            if(r.response){
                $scope.formSettings.data.picture = r.response.picture;
                $scope.formSettings.data.name = r.response.name;
                $scope.formSettings.data.nickname = r.response.nickname;
                $scope.formSettings.data.email = r.response.email;
            }
        });
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'picture',
                    type: 'filemanager',
                    label: 'LABEL.PICTURE',
                    attr: {
                        label: {
                            icon: 'fa fa-fw fa-camera',
                            select: 'BUTTON.SELECT.PICTURE',
                            change: 'BUTTON.CHANGE.PICTURE'
                        },
                        filter: 'image',
                        multiple: false
                    }
                }, {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NAME'
                    }
                }, {
                    property: 'nickname',
                    type: 'text',
                    label: 'LABEL.NICKNAME',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NICKNAME'
                    }
                }, {
                    property: 'email',
                    type: 'email',
                    label: 'LABEL.EMAIL',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.EMAIL',
                        email: 'FORM.MESSAGE.EMAIL.EMAIL'
                    }
                }
            ],
            submit: function(data){
                $grRestful.update({
                    module: 'user',
                    action: 'update_attributes',
                    id: $rootScope.GRIFFO.user.id,
                    post: data
                }).then(function(r){
                    if(r.response){
                        $rootScope.GRIFFO.user.picture = data.picture;
                        $rootScope.GRIFFO.user.name = data.name;
                        $rootScope.GRIFFO.user.nickname = data.nickname;
                        $rootScope.GRIFFO.user.email = data.email;
                        $scope.modal.close();
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
    }]);
}());
