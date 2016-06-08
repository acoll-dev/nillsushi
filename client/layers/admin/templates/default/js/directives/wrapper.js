'use strict';
(function(){
    angular.module('adminApp').directive('wrapper', ['$rootScope', '$window', '$compile', '$timeout', function ($rootScope, $window, $compile, $timeout) {
        return {
            restrict: 'C',
            link: function ($scope, $element) {
                var $navbar = $element.siblings('.navbar').eq(0);
                if ($navbar.length > 0) {
                    var window = angular.element($window),
                        ajustSize = function(){
                            var h = window.height(),
                                style,
                                minH = 376,
                                navH = $navbar.outerHeight();
                            if(h >= minH){
                                style = {
                                    height: h - navH,
                                    minHeight: minH - navH
                                };
                            }else{
                                style = {
                                    height: minH - navH,
                                    minHeight: minH - navH
                                }
                            }
                            $element.css(style);
                            var viewContent = $element.find('.view-content');
                            if(viewContent.length > 0){
                                viewContent.height(
                                    viewContent.parent().outerHeight()-
                                    viewContent.siblings('.view-header').outerHeight()-
                                    viewContent.siblings('.view-footer').outerHeight()-
                                    parseFloat(viewContent.css('padding-top'))-
                                    parseFloat(viewContent.css('padding-bottom'))
                                );
                            }
                            var table = angular.element('.gr-table').children('.ng-table');
                            if(table.length > 0){
                                table.show();
                                var setTableSize = function(){
                                    var interval = setInterval(function(){
                                        var thead = table.children('thead'),
                                            th = thead.children('tr').eq(0).children('th'),
                                            tbody = table.children('tbody'),
                                            td = tbody.children('tr').eq(0).children('td');
                                        if(td.length > 0){
                                            if(window.width() > 800){
                                                if(!td.eq(0).hasClass('ng-table-no-results')){
                                                    thead.outerWidth(tbody.outerWidth() + 1);
                                                    angular.forEach(th, function(item, id){
                                                        item = angular.element(item);
                                                        item.outerWidth(td.eq(id).outerWidth());
                                                    });
                                                }else{
                                                    var w = tbody.outerWidth() + 1;
                                                    thead.outerWidth(w);
                                                    angular.forEach(th, function(item, id){
                                                        item = angular.element(item);
                                                        item.outerWidth(w/th.length);
                                                    });
                                                }
                                            }else{
                                                thead.outerWidth('auto');
                                                angular.forEach(th, function(item, id){
                                                    item = angular.element(item);
                                                    item.outerWidth('auto');
                                                });
                                            }
                                            clearInterval(interval);
                                        }
                                    }, 50);
                                };
                                setTableSize();
                                $timeout(function(){
                                    setTableSize();
                                }, 200);
                            }
                        };
                    window.on('resize', ajustSize);
                    $timeout(ajustSize);
                }
            }
        }
    }]);
}());
