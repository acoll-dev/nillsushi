'use strict';
(function(){
    angular.module('adminApp').directive('sidebarButton', ['$rootScope', '$window', '$timeout', function ($rootScope, $window, $timeout) {
        return {
            restrict: 'A',
            link: function($scope, $element, $attrs){
                $rootScope.sidebarOpen = 'none';
                function toggleOpen(type, closeOthers){
                    if(!closeOthers){
                        if($rootScope.sidebarOpen !== 'none'){
                            $rootScope.sidebarOpen = 'none';
                        }else if($rootScope.sidebarOpen !== type && $rootScope.sidebarOpen !== 'none'){
                            $rootScope.sidebarOpen = 'both';
                        }else if($rootScope.sidebarOpen === 'none'){
                            $rootScope.sidebarOpen = type;
                        }
                    }else{
                        if($rootScope.sidebarOpen === type){
                            $rootScope.sidebarOpen = 'none';
                        }else{
                            $rootScope.sidebarOpen = type;
                        }
                    }
                }
                $scope.sidebarAction = function(type){
                    if($rootScope.GRIFFO.viewPort.width > 1200){
                        toggleOpen(type);
                    }else{
                        toggleOpen(type, true);
                    }
                };
                angular.element($window).on('resize', function(){
                    $timeout(function(){
                        if($rootScope.GRIFFO.viewPort.width < 1200){
                            $rootScope.sidebarOpen = 'none';
                        }else{
                            $rootScope.sidebarOpen = 'left';
                        }
                    });
                });
                $timeout(function(){
                    if($rootScope.GRIFFO.viewPort.width > 1200){
                        $rootScope.sidebarOpen = 'left';
                    }
                });
            }
        }
    }])
}());
