'use strict';
(function(){
    angular.module('adminApp').directive('fancybox', ['$rootScope', '$timeout', function($rootScope, $timeout){
        return {
            restrict: 'A',
            link: function($scope, $element, $attrs){
                $scope.type = 'image';
                function setFancybox(){
                    var el = $element.find('.fancybox');
                    if($attrs.fancyGroup){
                        el.attr('rel', $attrs.fancyGroup);
                    }
                    el.fancybox({
                        type: $scope.type
                    });
                }
                $timeout(setFancybox);
                $scope.$watch($attrs.fancyType, function(type){
                    if(type){
                        $scope.type = type;
                        setFancybox();
                    }
                });
            }
        }
    }]);
}());
