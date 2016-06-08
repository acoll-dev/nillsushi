(function(){
    'use strict';
    angular.module('mainApp').directive('grid', ['$timeout', '$compile', function($timeout, $compile){
        return {
            restrict: 'E',
            scope: {
                size: '=',
                col: '='
            },
            template: '<div ng-class="colClass"><h1>Grid {{size}}</h1></div>',
            replace: true,
            link: function($scope, $element, $attrs){
                $scope.$watch('col', function(col){
                    if(col){
                        $scope.colClass = 'col-xs-18-18' + ' col-sm-' + 18/col + '-18';
                    }
                });
                $scope.$watch('size', function(size){
                    var size = parseInt(size),
                        blocks = angular.element('<div class="col-demo" />');
                    for(var x = size; x > 0; x--){
                        if(size%x === 0){
                            for(var x2 = 0; x2 < (size/x); x2++){
                                $element.append(blocks.clone().addClass('col-xs-' + x + '-' + size).text('.col-xs-' + x + '-' + size));
                            }
                        }
                    }

                });
            }
        }
    }])
}());
