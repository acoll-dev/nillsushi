'use strict'
angular.module 'mainApp'
    .directive 'imgLiquid', ($compile, $timeout) ->
        restrict: 'AC'
        link: ($scope, $element, $attrs) ->
            $element.addClass 'imgLiquidFill imgLiquid'
            $timeout ->
                $element.imgLiquid verticalAlign: 'top'
