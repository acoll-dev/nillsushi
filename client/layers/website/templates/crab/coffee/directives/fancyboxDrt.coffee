'use strict'
angular.module 'mainApp'
    .directive 'fancybox', ($rootScope, $timeout) ->
        restrict: 'A'
        link: ($scope, $element, $attrs) ->

            setFancybox = ->
                el = $element.find('.fancybox')
                if $attrs.fancyGroup
                    el.attr 'rel', $attrs.fancyGroup
                el.fancybox
                    type: $scope.type
                    fitToView: false
                    maxWidth: '100%'
                    tpl:
                        closeBtn: '''
                            <a title='Fechar' class='fancybox-icone-close' href='javascript:;'>
                                <div class='fancybox-icone-background'>
                                    <i class='fa fa-times'></i>
                                </div>
                            </a>
                        '''

            $scope.type = 'image'
            $timeout setFancybox
            $scope.$watch $attrs.fancyType, (type) ->
                if type
                    $scope.type = type
                    setFancybox()
