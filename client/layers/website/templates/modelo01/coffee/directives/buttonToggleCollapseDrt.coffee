'use strict'
angular.module 'mainApp'
    .directive 'buttonToggleCollapse', ($rootScope, $templateCache, $compile, $document, $timeout, $grRestful) ->
        $rootScope.collapse = {}
        {
            restrict: 'E'
            scope: target: '=targetId'
            template: ->
                $templateCache.get 'griffo/buttonToggleCollapse.html'
            replace: true
            link: ($scope, $element, $attrs) ->
                $rootScope.collapse[$scope.target] =
                    collapsed: true
                    collapse: (value) ->
                        $rootScope.collapse[$scope.target].collapsed = value
                    target: angular.element('#' + $scope.target)
                $rootScope.collapse[$scope.target].target.attr 'data-ng-show': '!collapse[\'' + $scope.target + '\'].collapsed'
                $compile($rootScope.collapse[$scope.target].target) $rootScope
                $scope.collapse = $rootScope.collapse[$scope.target]

                clickEvent = (e) ->
                    if $rootScope.collapse[$scope.target].target.find(e.target).length == 0 and !angular.equals($element[0], e.target) and !angular.element.contains($element[0], e.target)
                        $rootScope.collapse[$scope.target].collapse true

                $document.bind
                    'click': clickEvent
                    'touch': clickEvent

        }
    .run ($templateCache) ->
        $templateCache.put 'griffo/buttonToggleCollapse.html', '''
            <button type="button" class="btn btn-griffo-toggle-collapse" ng-class="{\'active\': !collapse.collapsed}" ng-click="collapse.collapse(!collapse.collapsed)">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        '''
