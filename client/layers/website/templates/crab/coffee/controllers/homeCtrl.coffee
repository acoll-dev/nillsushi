'use strict'
angular.module('mainApp').controller 'homeCtrl', ($rootScope, $grModal, $scope, $timeout) ->
    
  $rootScope.gr.home =
    collapse: -1
    collapseSub: -1

  $rootScope.gr.title =
    icon: 'fa fa-fw fa-check'
    text: 'Monte seu pedido'

  scrollTo = (c, timeout) ->
    element = if c > 0 then angular.element('#product-panel-heading-' + c) else angular.element('#accordion')
    if element.length > 0
      timeout = if timeout >= 0 then timeout else 500
      $timeout (->
        affix = angular.copy($rootScope.affixed)
        top = element.offset().top - (angular.element('.gr-affixed').outerHeight() + 4)
        angular.element('body, html').stop(true, false).animate { 'scrollTop': top + 'px' }, 500, ->
          if element.offset().top - (angular.element('.gr-affixed').outerHeight() + 4) != top
            scrollTo c
      ), timeout

  $rootScope.$watch 'gr.home.collapse', (c) ->
    scrollTo c
  $rootScope.$watch 'gr.home.collapseSub', (c) ->
    scrollTo c
