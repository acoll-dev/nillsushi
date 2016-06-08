'use strict';
(function(){
    angular.module('mainApp').directive('fancybox',function($compile, $timeout){
        return {
            restrict: 'C',
            link: function($scope, element, attrs) {
                $timeout(function(){
                    $('.fancybox').fancybox();
                });
            }
        }
    });
}());
