'use strict';
(function(){
    angular.module('mainApp').directive('imgLiquid',function($compile, $timeout){
        return {
            restrict: 'AC',
            link: function($scope, $element, $attrs) {
                $element.addClass('imgLiquidFill imgLiquid');
                $timeout(function(){
                    $element.imgLiquid();
                });
            }
        }
    });
}());
