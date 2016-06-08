'use strict'
angular.module 'mainApp'
    .directive 'ellipsis', ($rootScope, $compile, $window, $timeout) ->
        {
            restrict: 'A'
            scope:
                rows: '='
                suffix: '='
                ngBind: '='
            link: ($scope, $element, $attrs) ->
                newContent = ''
                ajustTimeout = $timeout(->)
                defaults =
                    rows:
                        xs: 0
                        sm: 0
                        md: 0
                        lg: 0
                    suffix: '...'
                $el = undefined

                ellipsis = ->
                    if !$el
                        $element.html '<p id="ellipsis-container" style="margin: 0; padding: 0;"></p>'
                        $el = $element.children('#ellipsis-container').html(angular.copy($scope.ngBind))
                    if $scope.limit and $scope.ngBind and $rootScope.GRIFFO.viewPort
                        newContent = angular.copy($scope.ngBind).split(' ')
                        $el.html newContent.join(' ')
                        setHeight()
                        if getRows() and getRows() > $scope.limit[$rootScope.GRIFFO.viewPort.bs] and $scope.limit[$rootScope.GRIFFO.viewPort.bs] != 0
                            $scope.ajusting = true
                            ajustText()
                    return

                ajustText = ->
                    $timeout.cancel ajustTimeout
                    ajustTimeout = $timeout(->
                        newContent = newContent.slice(0, -1)
                        $el.html newContent.join(' ') + (if $scope.suffix then $scope.suffix else defaults.suffix)
                        if getRows() > $scope.limit[$rootScope.GRIFFO.viewPort.bs]
                            ajustText()
                        else
                            $scope.ajusting = false
                            setHeight()
                    )

                getRows = ->
                    if noUnit($el.css('height')) then parseInt(noUnit($el.css('height')) / parseInt(noUnit($el.css('line-height')))) else false

                noUnit = (str) ->
                    Number str.split('px')[0].split('%')[0]

                setHeight = ->
                    if $scope.limit[$rootScope.GRIFFO.viewPort.bs] != 0
                        $element.css('height', $scope.limit[$rootScope.GRIFFO.viewPort.bs] * noUnit($el.css('line-height')) + noUnit($element.css('padding-top')) + noUnit($element.css('padding-bottom'))).css 'overflow', 'hidden'
                    else
                        $element.css('height', '').css 'overflow', 'visible'
                    return

                $scope.limit = defaults.rows
                $scope.$watch 'ngBind', ellipsis
                $scope.$watch 'rows', (rows) ->
                    if rows
                        if angular.isObject(rows)
                            if !$scope.limit
                                $scope.limit = {}
                            angular.forEach defaults.rows, (r, id) ->
                                if angular.isDefined(rows[id])
                                    $scope.limit[id] = rows[id]
                                return
                        else
                            newRows = {}
                            angular.forEach defaults.rows, (r, id) ->
                                newRows[id] = rows
                                return
                            $scope.limit = newRows
                        ellipsis()

                $scope.$watch 'suffix', ellipsis
                $rootScope.$watch 'GRIFFO.viewPort.width', ->
                    $timeout ellipsis, 100
        }
