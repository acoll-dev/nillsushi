'use strict'
angular.module 'mainApp'
    .directive 'highlightCarousel', ($rootScope, $templateCache, $window, $timeout, $compile, $grRestful) ->
        {
            restrict: 'E'
            scope: params: '='
            template: '<div data-ng-show="items.length > 0" data-ng-class="params.containerClass"></div>'
            replace: true
            link: ($scope, $element, $attrs) ->
                $scope.$watch 'params', (params) ->
                    if params
                        $scope.items = []
                        $scope.filtered = []
                        if params.model.image and params.model.image.preload
                            $scope.ready = false
                            $scope.imagesLoadedEvent = always: (instance) ->
                                $timeout ->
                                    angular.element($window).trigger 'resize'
                                    $scope.ready = true
                        else
                            $scope.ready = true
                        $grRestful.find(
                            module: params.module
                            action: if $rootScope.GRIFFO.filter.category then 'category' else 'get'
                            id: if $rootScope.GRIFFO.filter.category then $rootScope.GRIFFO.filter.category.idcategory else ''
                            onelevel: if !$rootScope.GRIFFO.filter.category then false else true
                            params: if !$rootScope.GRIFFO.filter.category and params.highlightsOnly then 'highlight=1' else '').then (r) ->
                            if r.response
                                if params.sort
                                    if params.sort.type and params.sort.type != 'string'
                                        r.response.sort (a, b) ->
                                            _return = undefined
                                            _a = undefined
                                            _b = undefined
                                            if params.sort.type == 'date'
                                                _a = new Date(a[params.sort.by])
                                                _b = new Date(b[params.sort.by])
                                            else if params.sort.type == 'number'
                                                _a = new Number(a[params.sort.by])
                                                _b = new Number(b[params.sort.by])
                                            if params.sort.order == 'asc'
                                                _return = _a - _b
                                            else if params.sort.order == 'desc'
                                                _return = _b - _a
                                            _return
                                    else
                                        if params.sort.order == 'asc'
                                            r.response.sort()
                                        else if params.sort.order == 'desc'
                                            r.response.sort().reverse()
                                $scope.items = r.response
                                $timeout ->
                                    angular.element($window).trigger 'resize'
                            else
                                $scope.items = []
                        tmpl = []
                        angular.forEach params.model.sort, (t) ->
                            if t
                                tmpl.push $templateCache.get('griffo/highlight/' + t + '.html')
                        tmpl = tmpl.join('<div class="clearfix"></div>')
                        if !params.type or params.type == 'carousel'
                            tmpl = $templateCache.get('griffo/highlight/carousel-start.html') + tmpl
                        else if params.type == 'pager'
                            tmpl = $templateCache.get('griffo/highlight/pager-start.html') + tmpl
                        if !params.type or params.type == 'carousel'
                            tmpl += $templateCache.get('griffo/highlight/carousel-end.html')
                        else if params.type == 'pager'
                            tmpl += $templateCache.get('griffo/highlight/pager-end.html')
                        tmpl = angular.element(tmpl)
                        $compile(tmpl) $scope
                        $element.html tmpl
                $scope.GRIFFO = $rootScope.GRIFFO

        }
    .run ($templateCache) ->

        $templateCache.put 'griffo/highlight/pager-start.html', '''
            <div class="title" data-ng-if="params.title">
                <div class="container">
                    <h1>
                    {{params.title}}
                    </h1>
                </div>
            </div>
            <div class="highlight-wrapper" data-ng-class="params.wrapperClass">
                <gr-pager src="items" dest="filtered" per-page="params.pager.perPage" data-ng-show="params.pager.position === \'both\' || params.pager.position === \'top\'"></gr-pager>
                    <div class="highlight" data-ng-class="params.model.class" data-ng-repeat="item in filtered" images-loaded="imagesLoadedEvent">
                        <div class="highlight-inner">
        '''

        $templateCache.put 'griffo/highlight/pager-end.html', '''
                        </div>
                    </div>
                <gr-pager src="items" dest="filtered" per-page="params.pager.perPage" data-ng-show="params.pager.position === \'both\' || params.pager.position === \'bottom\'"></gr-pager>
            </div>
        '''

        $templateCache.put 'griffo/highlight/carousel-start.html', '''
            <div class="title">
                <div class="container">
                    <h1 data-ng-if="params.title">
                        {{params.title}}
                        <div class="highlight-controller highlight-controller-top hidden-xs hidden-sm" data-ng-if="params.carousel.controller && params.carousel.controller.position === \'top\' && params.carousel.bs[GRIFFO.viewPort.bs] < items.length">
                            <button class="btn" data-ng-click="carousel.prev()"><i data-ng-class="params.carousel.controller.icon.prev"></i></button>
                            <button class="btn" data-ng-click="carousel.next()"><i data-ng-class="params.carousel.controller.icon.next"></i></button>
                        </div>
                    </h1>
                </div>
            </div>
            <div class="highlight-wrapper container" data-ng-class="params.wrapperClass">
                <div class="highlight-controller highlight-controller-top hidden-md hidden-lg" data-ng-if="params.carousel.controller && params.carousel.controller.position === \'top\' && (GRIFFO.viewPort.bs === \'xs\' || GRIFFO.viewPort.bs === \'sm\')">
                    <button class="btn" data-ng-click="carousel.prev()"><i data-ng-class="params.carousel.controller.icon.prev"></i></button>
                    <button class="btn" data-ng-click="carousel.next()"><i data-ng-class="params.carousel.controller.icon.next"></i></button>
                </div>
                <gr-carousel class="highlight-carousel" bs="{{params.carousel.bs}}" autoplay="{{$scope.params.carousel.autoplay || \'4000\'}}" images-loaded="imagesLoadedEvent">
                    <gr-carousel-item class="highlight" data-ng-class="params.model.class" data-ng-repeat="item in items">
                        <div class="highlight-inner">
        '''

        $templateCache.put 'griffo/highlight/carousel-end.html', '''
                        </div>
                    </gr-carousel-item>
                </gr-carousel>
                <div class="highlight-controller highlight-controller-bottom" data-ng-if="params.carousel.controller && params.carousel.controller.position === \'bottom\' && (!params.type || params.type === \'carousel\')">
                    <button class="btn" data-ng-click="carousel.prev()"><i data-ng-class="params.carousel.controller.icon.prev"></i></button>
                    <button class="btn" data-ng-click="carousel.next()"><i data-ng-class="params.carousel.controller.icon.next"></i></button>
                </div>
            </div>
        '''

        $templateCache.put 'griffo/highlight/date.html', '''
            <div class="date" data-ng-class="params.model.date.class">
                <i data-ng-class="params.model.date.icon" data-ng-if="params.model.date.icon"></i><span data-ng-if="params.model.date.icon"> </span>
                <span data-ng-if="params.model.date.prefix">{{params.model.date.prefix}}</span>
                    {{item.registrationdate | date:(params.model.date.filter || \'dd/MM/yyyy\')}}
                <span data-ng-if="params.model.date.suffix">{{params.model.date.suffix}}</span>
            </div>
        '''

        $templateCache.put 'griffo/highlight/image.html', '''
            <div class="highlight-image preloader-wrapper" gr-autoscale="params.model.image.scale" data-ng-if="!ready">
                <div class="preloader">
                    <i class="fa fa-fw fa-refresh fa-2x fa-spin"></i>
                    <a data-ng-href="{{params.url.prefix + (item.url || item.link) + params.url.suffix}}" data-ng-show="ready" data-ng-attr-title="{{item.title || item.name}}" target="{{params.model.link.target}}"></a>
                </div>
            </div>
            <div class="highlight-image" gr-autoscale="params.model.image.scale" data-ng-show="ready">
                <a data-ng-href="{{params.url.prefix + (item.url || item.link) + params.url.suffix}}" data-ng-attr-title="{{item.title || item.name}}" target="{{params.model.link.target}}">
                    <img class="img-responsive" data-ng-src="{{GRIFFO.uploadPath + (item.picture || item.image)}}"/>
                </a>
            </div>
        '''

        $templateCache.put 'griffo/highlight/title.html', '''
            <a data-ng-href="{{params.url.prefix + item.url + params.url.suffix}}" data-ng-attr-title="{{item.title || item.name}}" target="{{params.model.link.target}}">
                <h2 class="highlight-title">{{item.name}}</h2>
            </a>
        '''

        $templateCache.put 'griffo/highlight/shortdescription.html', '<p>{{item.shortdescription}}</p>'

        $templateCache.put 'griffo/highlight/link.html', '''
            <a data-ng-href="{{params.url.prefix + item.url + params.url.suffix}}" data-ng-class="params.model.link.class" data-ng-attr-title="{{item.title || item.name}}" target="{{params.model.link.target}}">
                <i data-ng-class="params.model.link.icon" data-ng-if="params.model.link.icon"></i>
                <span data-ng-if="params.model.link.icon"> </span>
                <span data-ng-if="params.model.link.label">{{params.model.link.label}}</span>
            </a>
        '''
