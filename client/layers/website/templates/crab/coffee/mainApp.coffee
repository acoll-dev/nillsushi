'use strict'
angular.module 'mainApp', ['griffo', 'ui.bootstrap.collapse', 'ngAnimate']
    .config ($compileProvider) ->
        $compileProvider.aHrefSanitizationWhitelist /^\s*(https?|ftp|mailto|file|tel):/
