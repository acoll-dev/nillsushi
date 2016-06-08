'use strict'
angular.module('mainApp').directive 'includeReplace', ->
  require: 'ngInclude'
  restrict: 'A'
  link: (scope, el, attrs) ->
    el.replaceWith el.children()
