'use strict';
(function(){
    angular.module('adminApp').controller('accessCtrl', ['$rootScope', '$scope', '$grRestful', '$grModal', '$grAlert', '$timeout', '$window', function($rootScope, $scope, $grRestful, $grModal, $grAlert, $timeout, $window){
        var alert = $grAlert.new();
        definitions();
        $grRestful.find({
            module: 'profile',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.profiles = r.response;
                if($rootScope.GRIFFO.user.profile.idprofile === 1){
                    $scope.profiles.splice(1,0,{
                        value: 1,
                        label: 'Administrator'
                    });
                }
            }
        });
        $scope.updateControl = function(accessList){
            $scope.control = accessList;
            angular.forEach($scope.control, function(item, key){
                var hasFalse = false;
                item.status = (item.status === 'true' || item.status === true || item.status === '1' || item.status === 1) ? true : false;
                angular.forEach(item.resources, function(citem){
                    citem.status = (citem.status === 'true' || citem.status === true || citem.status === '1' || citem.status === 1) ? true : false;
                    if(!citem.status){
                        hasFalse = true;
                    }
                });
                if(hasFalse && item.status){
                    item.collapse = false;
                }else{
                    item.collapse = true;
                }
            });
            $timeout(function(){
                angular.element($window).trigger('resize');
            });
        };
        $scope.length = function(obj){
            var length = 0;
            angular.forEach(obj, function(){ length++; });
            return length;
        };
        $scope.save = function(){
            $grModal.confirm('MODULE.PROFILE.CONFIRM.CHANGE', function(){
                var alert = $grAlert.new();
                alert.show('loading', 'MODULE.PROFILE.SAVING.PERMISSIONS');
                $scope.saving = true;
                $grRestful.update({
                    module: 'authentication',
                    action: 'update_access_control',
                    id: $scope.curProfile,
                    post: $scope.control
                }).then(function(r){
                    if(r.response){
                        $rootScope.GRIFFO.user.profile.accessControl = angular.copy($scope.control);
                        $rootScope.$broadcast('accessControlChanged');
                        alert.show('success', r.message);
                        $scope.updateControl($scope.control);
                    }else{
                        alert.show('danger', r.message);
                    }
                }, function(e){
                    alert.show('danger', 'ERROR.FATAL');
                }).finally(function(){
                    $scope.saving = false;
                });
            });
        };
        $scope.$watch('curProfile', function(id, oid){
            if(id && id > 0){
                $scope.loading = true;
                alert.show('loading', 'MODULE.PROFILE.LOADING.PERMISSIONS');
                $grRestful.find({
                    module: 'authentication',
                    action: 'access_control',
                    params: 'idprofile=' + id
                }).then(function(r){
                    if(r.response){
                        $scope.updateControl(r.response);
                        alert.hide();
                    }else{
                        alert.show('danger', 'ERROR.FATAL');
                    }
                }).finally(function(){
                    $scope.loading = false;
                });
            }else if(id <= 0 || !id){
                $scope.updateControl([]);
            }
        });
        function definitions(){
            $scope.switch = {
                onColor: 'success',
                offColor: 'danger',
                trueValue: 'true',
                falseValue: 'false',
                size: 'mini',
                animate: true,
                radioOff: false,
                handleWidth: 'auto',
                labelWidth: 'auto',
                inverse: 'true',
                readyOnly: false
            };
            $scope.profiles = [];
            $scope.curProfile = GRIFFO.user.profile.idprofile;
            $scope.control = [];
            $scope.saving = false;
            $scope.loading = false;
            $scope.$parent.saving = function(){
                return $scope.saving || $scope.loading;
            }
        };
        $timeout(function(){
            $scope.$parent.save = $scope.save;
        });
    }]);
}());
