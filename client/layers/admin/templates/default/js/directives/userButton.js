'use strict';
(function(){
    angular.module('adminApp').directive('userButton', ['$rootScope', '$window', '$timeout', '$grModal', '$grRestful', '$grAlert', function($rootScope, $window, $timeout, $grModal, $grRestful, $grAlert){
        return {
            restrict: 'A',
            link: function($scope, $element, $attrs){
                var alert = $grAlert.new();
                angular.element($window).on('click', function(e){
                    if($scope.userPopover.isOpened){
                        if(
                            !angular.element(e.target).hasClass('popover') &&
                            angular.element(e.target).parents('.popover').length == 0 &&
                            !angular.element(e.target).hasClass('btn-user') &&
                            angular.element(e.target).parents('.btn-user').length == 0
                        ){
                            $scope.userPopover.close();
                        }
                    }
                });
                $scope.userPopover = {
                    isOpened: false,
                    trigger: 'none',
                    placement: 'bottom',
                    element: false,
                    templateUrl: $rootScope.GRIFFO.templatePath + 'view/modal/profileMenu.php',
                    open: function(){
                        $scope.$applyAsync(function(){
                            $scope.userPopover.isOpened = true;
                        });
                    },
                    close: function(){
                        $scope.$applyAsync(function(){
                            $scope.userPopover.isOpened = false;
                        });
                    }
                };
                $scope.editInfo = function(){
                    $scope.userPopover.close();
                    var modal = $grModal.new({
                        name: 'edit-information',
                        title: 'BUTTON.EDIT.INFORMATIONS',
                        size: 'md',
                        model: $rootScope.GRIFFO.templatePath + 'view/modal/edit-info.php',
                        define: {},
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
                $scope.editProfile = function(theme, e){
                    $scope.userPopover.close();
                    var modal = $grModal.new({
                        name: 'edit-profile',
                        title: 'MODULE.PROFILE.TITLE.EDIT',
                        size: 'sm',
                        model: $rootScope.GRIFFO.templatePath + 'view/modal/edit-profile.php',
                        define: {
                            changePassword: $scope.changePassword
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
                $scope.changePassword = function(){
                    $scope.userPopover.close();
                    var modal = $grModal.new({
                        name: 'change-password',
                        title: 'TITLE.PASSWORD.CHANGE',
                        size: 'md',
                        model: $rootScope.GRIFFO.templatePath + 'view/modal/change-password.php',
                        buttons: [
                            {
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
                            }
                        ]
                    });
                    modal.open();
                };
                $scope.changeAppearance = function() {
                    if(!$rootScope.GRIFFO.user.theme){ return; }
                    $grRestful.update({
                        module: 'user',
                        action: 'update_attributes',
                        id: $rootScope.GRIFFO.user.id,
                        post: {
                            theme: $rootScope.GRIFFO.user.theme
                        }
                    }).then(function(r){
                        if(!r.response){
                            alert.show(r.status, r.message);
                        }
                    }, function(e){
                        alert.show(r.status, 'ERROR.FATAL');
                    });
                };
                $scope.info = function() {
                    $scope.userPopover.close();
                    var modal = $grModal.new({
                        name: 'system-info',
                        title: 'TITLE.SYSTEM.INFO',
                        size: 'sm',
                        model: $rootScope.GRIFFO.templatePath + 'view/modal/info.php',
                        buttons: [{
                            type: 'danger',
                            label: 'BUTTON.CLOSE',
                            onClick: function(scope, element, controller){
                                controller.close();
                            }
                        }]
                    });
                    modal.open();
                };
                $scope.logout = function () {
                    $rootScope.logouting = true;
                    $timeout(function(){
                        $grRestful.auth({
                            action: 'logout'
                        }).then(function (r) {
                            $window.location.reload();
                        }, function (r) {
                            console.debug('Falha no logout!');
                        });
                    }, 500);
                };
                var firstRun = true;
                $rootScope.$watch('GRIFFO.user.theme', function(){
                    if(!firstRun){
                        $scope.changeAppearance();
                    }else{
                        firstRun = false;
                    }
                }, true);
            }
        }
    }]);
}());
