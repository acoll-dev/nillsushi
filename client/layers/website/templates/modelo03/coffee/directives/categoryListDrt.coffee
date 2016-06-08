'use strict'
angular.module 'mainApp'
    .directive 'categoryList', ($rootScope, $templateCache, $compile, $timeout, $window, $grRestful) ->

        {
            restrict: 'AE'
            template: ->
                $templateCache.get 'category/list.html'
            scope: params: '='
            replace: true
            link: ($scope, $element, $attrs) ->

                setCategories = (params) ->
                    if $scope.categories.length > 0
                        _loop = (categories) ->
                            angular.forEach categories, (category) ->
                                hasChildActive = false
                                category.href = (if params.url and params.url.prefix then params.url.prefix else '') + category.url + (if params.url and params.url.suffix then params.url.suffix else '')
                                if category.child and category.child.length > 0
                                    category.child = _loop(angular.copy(category.child))
                                    angular.forEach category.child, (c) ->
                                        if c.active
                                            hasChildActive = true
                                        return
                                    $timeout ajustIconHeight
                                if hasChildActive
                                    category.active = true
                                    category.open = true
                                else if $rootScope.GRIFFO.filter.category and category.url == $rootScope.GRIFFO.filter.category.url
                                    category.active = true
                                    category.open = false
                                return
                            categories

                        $scope.categories = _loop(angular.copy($scope.categories))
                        $timeout ajustIconHeight
                    return

                ajustIconHeight = ->
                    icons = $element.find('.parent-icon')
                    angular.forEach icons, (icon) ->
                        icon = angular.element(icon)
                        icon.css
                            height: icon.siblings('a').eq(0).innerHeight() + 'px'
                            lineHeight: icon.siblings('a').eq(0).innerHeight() + 'px'

                $scope.categories = []
                $scope.$watch 'params', (params) ->
                    if params
                        if params.parent
                            $grRestful.find(
                                module: 'category'
                                action: 'get'
                                id: params.parent).then (r) ->
                                if r.response
                                    $scope.categories = r.response
                                    setCategories params
                        else if params.module
                            $grRestful.find(
                                module: 'category'
                                action: 'module'
                                params: 'name=' + params.module).then (r) ->
                                if r.response
                                    $scope.categories = r.response
                                    setCategories params

                $scope.toggleOpen = (category) ->
                    category.open = !category.open
                    $timeout ajustIconHeight

                angular.element($window).on 'resize', ajustIconHeight
        }

    .run ($templateCache) ->

        $templateCache.put 'category/list.html', '''
            <div class="category-list-wrapper">
                <h2>{{params.title || \'Categorias\'}}</h2>
                <ul class="category-list">
                    <li ng-repeat="category in categories" ng-attr-title="{{category.name}}" ng-class="{\'parent\': category.child.length > 0, \'open\': category.open, \'active\': category.active}">
                        <a ng-href="{{category.href}}">
                            {{category.name}}
                            <span class="fa fa-fw fa-lg fa-angle-right" ng-if="category.active && category.child.length === 0"></span>
                        </a>
                        <span class="parent-icon fa fa-fw" ng-class="{\'fa-minus\': category.open, \'fa-plus\': !category.open}" ng-if="category.child.length > 0" ng-click="toggleOpen(category)" ng-attr-title="{{params.category.toggle.title.prefix + category.name + params.category.toggle.title.suffix}}"></span>
                        <ul class="category-list" ng-if="category.child.length > 0" ng-include="\'category/sublist.html\'"></ul>
                    </li>
                    <li class="category-empty" ng-if="!categories || categories.length === 0">
                        <p class="text-muted">{{params.empty}}</p>
                    </li>
                </ul>
            </div>
        '''

        $templateCache.put 'category/sublist.html', '''
            <li ng-repeat="category in category.child" ng-attr-title="{{category.name}}" ng-class="{\'parent\': category.child.length > 0, \'open\': category.open, \'active\': category.active}">
                <a ng-href="{{category.href}}">
                    {{category.name}}
                    <span class="fa fa-fw fa-lg fa-angle-right" ng-if="category.active && category.child.length === 0"></span>
                </a>
                <span class="parent-icon fa fa-fw" ng-class="{\'fa-minus\': category.open, \'fa-plus\': !category.open}" ng-if="category.child.length > 0" ng-click="toggleOpen(category)" ng-attr-title="{{params.category.toggle.title.prefix + category.name + params.category.toggle.title.suffix}}"></span>
                <ul class="category-list" ng-if="category.child.length > 0" ng-include="\'category/sublist.html\'"></ul>
            </li>
        '''
