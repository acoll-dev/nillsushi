'use strict';
(function(){
    angular.module('adminApp').directive('viewFooter', ['$window', '$compile', '$timeout',function ($window, $compile, $timeout) {
        var setScrollbar = false;
        return {
            restrict: 'C',
            link: function ($scope, $element, $attrs) {
                var isFooterBind = $element.parent().is('.container-inner');
                if(!isFooterBind){
                    var footerBind = $element.parents('.view-content').siblings('.view-footer'),
                    container = $element.parents('.container-inner').eq(0);

                    if(footerBind.length === 0){
                        if(container.length > 0){
                            $element.appendTo(container);
                            $compile($element)($scope);
                        }
                    }else{
                        $element.contents().appendTo(container.children('.view-footer'));
                        $compile(container.children('.view-footer'))($scope);
                        $element.remove();
                    }
                }
            }
        }
    }]);
}());
