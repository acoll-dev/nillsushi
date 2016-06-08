'use strict';
(function(){
    angular.module('mainApp').controller('mainCtrl', ['$rootScope', '$scope', '$grRestful', '$timeout', '$window', function($rootScope, $scope, $grRestful, $timeout, $window){
        $rootScope.GRIFFO = GRIFFO;

        $rootScope.BLOCKS = {};

        angular.forEach(GRIFFO.filter.page.blocks, function(block){ $rootScope.BLOCKS[block.name] = block.content; });
    }]);
}());
