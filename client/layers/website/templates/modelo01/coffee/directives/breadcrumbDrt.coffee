'use strict'
angular.module 'mainApp'
    .directive 'breadcrumb', ($rootScope, $templateCache, $window, $timeout) ->
        {
            restrict: 'E'
            template: ->
                $templateCache.get 'griffo/breadcrumb.html'
            scope: {}
            replace: true
            link: ($scope, $element, $attrs) ->

                setBread = ->
                    if $rootScope.GRIFFO.filter.page and $rootScope.GRIFFO.config and $rootScope.GRIFFO.filter.page.fileview != 'home'
                        $scope.breadcrumbs = []
                        # $scope.breadcrumbs = [{
                        #     url: './',
                        #     label: 'Home',
                        #     active: false
                        # }];
                        $scope.breadcrumbs.push
                            url: $rootScope.GRIFFO.filter.page.url
                            label: $rootScope.GRIFFO.filter.page.title
                            active: !$rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview] and !$rootScope.GRIFFO.filter.urlcategory and !$rootScope.GRIFFO.filter.category
                        if $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview]
                            $scope.breadcrumbs.push
                                url: $rootScope.GRIFFO.filter.page.url + $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].url
                                label: $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].name or $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].title
                                active: true
                        if $rootScope.GRIFFO.filter.urlcategory and $rootScope.GRIFFO.filter.category
                            $scope.breadcrumbs.push
                                label: $rootScope.GRIFFO.filter.urlcategory
                                active: true
                            $scope.breadcrumbs.push
                                url: $rootScope.GRIFFO.filter.page.url + $rootScope.GRIFFO.filter.urlcategory + '/' + $rootScope.GRIFFO.filter.category.url
                                label: $rootScope.GRIFFO.filter.category.name
                                active: true

                $rootScope.$watch 'GRIFFO.filter.page', ->
                    setBread()

                $rootScope.$watch 'GRIFFO.config', ->
                    setBread()

        }
    .run ($templateCache) ->
            $templateCache.put 'griffo/breadcrumb.html', '''
                <section class="breadcrumb-wrapper" ng-show="breadcrumbs.length > 0">
                    <div class="container">
                        <ol class="breadcrumb">
                            <li ng-repeat="bread in breadcrumbs" ng-class="{\'active\': bread.active}"><a ng-href="{{bread.url}}" ng-if="!bread.active && bread.url">{{bread.label}}</a><span ng-if="bread.active || !bread.url">{{bread.label}}</span></li>
                        </ol>
                    </div>
                </section>
            '''
