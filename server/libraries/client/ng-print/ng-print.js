'use strict';
(function(){
    angular.module('ngPrint', []).factory('$ngPrint', [function(){
        var printSection;
        function checkSection(){
            printSection = angular.element('#printSection');
            if (!printSection || printSection.length === 0) {
                printSection = angular.element('<div id="printSection"/>');
                angular.element('body').append(printSection);
            };
            window.onafterprint = function(){
                printSection.html('');
            }
        }
        return function ngPrint(obj){
            if(angular.isObject(obj)){
                checkSection();
                if(obj.element){
                    printSection.append(obj.element.clone());
                    window.print();
                }else if(obj.content){
                    printSection.html(obj.content);
                    window.print();
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }]);
}());
