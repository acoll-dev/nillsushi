'use strict'
angular.module 'mainApp'
    .directive 'signature', ($rootScope, $templateCache) ->
        {
            restrict: 'E'
            template: ->
                $templateCache.get 'griffo/signature.html'
            replace: true
            link: ($scope, $element, $attrs) ->
                if !$attrs.type
                    $attrs.$set 'type', 'black'
                $attrs.$observe 'type', (type) ->
                    $scope.type = type
        }

    .run ($templateCache) ->
            $templateCache.put 'griffo/signature.html', '''
                <div class="signature">
                    <a href="http://www.acoll.com.br" title="Made with GRIFFO Framework - by Acoll">
                        <img ng-src="{{GRIFFO.templatePath + \'image/griffo-sig-\' + type + \'.png\'}}"/>
                    </a>
                </div>
            '''
