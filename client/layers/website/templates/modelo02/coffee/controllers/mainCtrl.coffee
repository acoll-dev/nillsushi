'use strict'
angular.module 'mainApp'
    .controller 'mainCtrl', ($rootScope, $scope, $timeout, $window) ->
        $rootScope.GRIFFO.curYear = (new Date).getFullYear()
        if $rootScope.GRIFFO.filter.page.blocks
            $rootScope.BLOCKS = {}
            angular.forEach $rootScope.GRIFFO.filter.page.blocks, (block) ->
                $rootScope.BLOCKS[block.name] = block.content
