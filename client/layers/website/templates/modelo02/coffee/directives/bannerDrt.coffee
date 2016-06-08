'use strict'
angular.module 'mainApp'
    .directive 'banner', ($rootScope, $templateCache, $window, $timeout, $grRestful) ->
        {
            restrict: 'E'
            scope:
                category: '='
                delay: '='
            replace: true
            template: ->
                $templateCache.get 'griffo/banner.html'
            link: ($scope, $element, $attrs) ->
                $scope.GRIFFO = $rootScope.GRIFFO
                $scope.banners = []
                $scope.preloading = true
                $scope.imagesLoaded = always: (instance) ->
                    $scope.preloading = false
                    $timeout ->
                        angular.element($window).trigger 'resize'
                $scope.$watch $attrs.category, (category) ->
                    $grRestful.find(
                        module: 'banner'
                        action: if category then 'category' else 'get'
                        params: if category then 'name=' + category else '').then (r) ->
                        if r.response
                            $scope.banners = r.response
                            angular.forEach $scope.banners, (banner) ->
                                if banner.link and (banner.link.indexOf('http://') > -1 or banner.link.indexOf('https://') > -1)
                                    banner.target = '_blank'
                                else
                                    banner.target = '_self'

        }
    .run ($templateCache) ->
        $templateCache.put 'griffo/banner.html', '''
            <div class="banner">
                <gr-carousel id="banner" autoplay="{{delay || 4000}}" data-ng-if="banners.length > 0" data-ng-show="!preloading" images-loaded="imagesLoaded">
                    <gr-carousel-item data-ng-repeat="banner in banners">
                        <img data-ng-src="{{GRIFFO.uploadPath + banner.picture}}" />
                        <section class="banner-content container">
                            <h1 data-ng-if="banner.title">{{banner.title}}</h1>
                            <h2 data-ng-if="banner.description">{{banner.description}}</h2>
                            <a class="btn btn-griffo-1" data-ng-class="{\'btn-xs\': GRIFFO.viewPort.width <= 990}" data-ng-href="{{banner.link}}" target="{{banner.target}}" data-ng-if="banner.link">Saber mais</a>
                        </section>
                    </gr-carousel-item>
                    <gr-carousel-indicators for="banner" data-ng-show="banners.length > 1"></gr-carousel-indicators>
                </gr-carousel>
                <div class="preloader" data-ng-if="banners.length <= 0 || preloading">
                    <div class="preloader-inner">
                        <i class="fa fa-fw fa-refresh fa-spin fa-3x"></i>
                    </div>
                </div>
            </div>
        '''
