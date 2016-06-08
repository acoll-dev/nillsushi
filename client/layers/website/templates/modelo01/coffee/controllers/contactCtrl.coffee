'use strict'
angular.module 'mainApp'
    .controller 'contactCtrl', ($rootScope, $scope) ->
        $scope.formSettings =
            data: {}
            schema: $rootScope.GRIFFO.config.page.contact.form.schema
            submit: (data) ->
                if $scope.form.$invalid
                    false
                else
                    console.debug data
