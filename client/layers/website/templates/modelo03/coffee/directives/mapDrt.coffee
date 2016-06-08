'use strict'
angular.module 'mainApp'
    .directive 'map', ($rootScope, $templateCache, $window) ->
        {
            restrict: 'E'
            template: ->
                $templateCache.get 'griffo/map.html'
            replace: true
            link: ($scope, $element, $attrs) ->
                $scope.map =
                    refresh: false
                    zoom: 15
                    coords: {}
                    marker: {}
                    control: ->
                    options:
                        draggable: false
                        scrollwheel: false
                        mapTypeControl: false
                        streetViewControl: false
                        zoomControl: true
                        panControl: true
                $attrs.$observe 'config', (config) ->
                    config = if angular.isString(config) then JSON.parse(config) else config
                    if config.coords
                        coords =
                            latitude: Number(config.coords.split(',')[0].trim())
                            longitude: Number(config.coords.split(',')[1].trim())
                        $scope.map.coords = angular.copy(coords)
                        $scope.map.marker = angular.copy(coords)
                    if config.zoom
                        $scope.map.zoom = Number(config.zoom)
                angular.element($window).on 'resize', ->
                    if $scope.map.control.refresh
                        $scope.map.control.refresh $scope.map.marker

        }

    .run ($templateCache) ->

        $templateCache.put 'griffo/map.html', '''
            <div class="map">
                <ui-gmap-google-map center="map.coords" zoom="map.zoom" control="map.control" options="map.options">
                    <ui-gmap-marker coords="map.marker" idkey="1"></ui-gmap-marker>
                </ui-gmap-google-map>
            </div>
        '''
