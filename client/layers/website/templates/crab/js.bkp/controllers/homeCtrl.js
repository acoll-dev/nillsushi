'use strict';
(function(){
    angular.module('mainApp').controller('homeCtrl', ['$rootScope', '$grModal', '$scope', '$timeout', function($rootScope, $grModal, $scope, $timeout){
        $rootScope.gr.home = {
            collapse: -1,
            collapseSub: -1
        };
        $rootScope.gr.title = {
            icon: 'fa fa-fw fa-check',
            text: 'Monte seu pedido'
        };
        var scrollTo = function(c, timeout){
            var element = c > 0 ? angular.element('#product-panel-heading-' + c) : angular.element('#accordion');
            if(element.length > 0){
                timeout = (timeout >= 0) ? timeout : 500;
                $timeout(function(){
                    var affix = angular.copy($rootScope.affixed),
                        top = element.offset().top - (angular.element('.gr-affixed').outerHeight() + 4);
                    angular.element('body, html').stop(true, false).animate({
                        'scrollTop': top + 'px'
                    }, 500, function(){
                        if(element.offset().top - (angular.element('.gr-affixed').outerHeight() + 4) !== top){
                            scrollTo(c);
                        }
                    });
                }, timeout);
            }
        };
        $rootScope.$watch('gr.home.collapse', function(c){ scrollTo(c); });
        $rootScope.$watch('gr.home.collapseSub', function(c){ scrollTo(c); });
    }]);
}());
