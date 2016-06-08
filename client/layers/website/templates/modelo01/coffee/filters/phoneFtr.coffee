'use strict'
angular.module 'mainApp'
    .filter 'phone', ->
        (value) ->
            arr = value.split('')
            arr.splice(-4, 0, '-')
            arr.splice(0, 0, '(')
            arr.splice(3, 0, ') ')
            arr.join('')
