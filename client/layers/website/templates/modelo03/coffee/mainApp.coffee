'use strict'
angular.module 'mainApp', ['griffo', 'uiGmapgoogle-maps']
    .config ($compileProvider) ->
        $compileProvider.aHrefSanitizationWhitelist /^\s*(https?|ftp|mailto|file|tel):/
