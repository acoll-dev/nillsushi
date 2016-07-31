'use strict'
angular.module('mainApp').controller 'mainCtrl', ($rootScope, $scope, $grRestful, $grAlert, $grModal, $localStorage, $timeout) ->

    $rootScope.GRIFFO.curYear = (new Date).getFullYear()

    if $rootScope.GRIFFO.filter.page.blocks
        $rootScope.BLOCKS = {}
        angular.forEach $rootScope.GRIFFO.filter.page.blocks, (block) ->
            $rootScope.BLOCKS[block.name] = block.content


    alert = $grAlert.new()
    $rootScope.logouting = false
    $rootScope.gr = {}
    $rootScope.gr.website = copyright: 'Copyright © ' + (new Date).getFullYear() + ' - Todos os direitos reservados.'
    $rootScope.gr.contactHeader = [
        {
            title: 'Facebook Acoll'
            link: 'http://fb.com/fan.acoll'
            class: 'contact fa fa-fw fa-2x fa-facebook-square'
        }
        {
            title: 'E-mail Acoll'
            link: 'mailto:contato@acoll.com.br'
            class: 'contact fa fa-fw fa-2x fa-envelope'
        }
    ]
    $rootScope.gr.shops = []
    $rootScope.gr.banners = []

    $grRestful.find(
        module: 'banner'
        action: 'get'
    ).then (r) ->
        if r.response
            $rootScope.gr.banners = r.response

    $grRestful.find(
        module: 'shop'
        action: 'get'
    ).then (r) ->
        if r.response
            $rootScope.gr.shops = r.response

    $rootScope.logout = ->
        alert.show 'loading', 'Saindo...', 0
        $rootScope.logouting = true
        $grRestful.auth
            action: 'logout'
        .then (r) ->
            delete $localStorage.griffo_cart_ready
            if r.response
                alert.show 'success', 'Você saiu do sistema e está sendo redirecionado!'
                window.location.reload()
            else
                console.debug 'Falha no logout!'
            $rootScope.logouting = false
            return
        , (r) ->
            console.debug 'Falha no logout!'

    do ->
        init = true
        ready =
            categories: false
            products: false

        setProducts = ->
            productLoop = (category) ->
                category.products = []
                angular.forEach $scope.products, (product) ->
                    if product.fkidcategory == category.idcategory
                        product.count = 0
                        category.products.push product
                        $rootScope.gr.cart.add product
                if category.child
                    angular.forEach category.child, (subcategory) ->
                        productLoop subcategory

            angular.forEach $scope.categories, (category) ->
                productLoop category

        $localStorage.griffo_cart = [] if !$localStorage.griffo_cart
        # if $localStorage.griffo_cart
        #     struct = $localStorage.griffo_cart
        #     properties = [
        #         'id'
        #         'name'
        #         'count'
        #         'unitvalue'
        #     ]
        #
        #     if struct
        #         if !angular.equals(Object.getOwnPropertyNames(struct), properties)
        #             $localStorage.griffo_cart = []

        $rootScope.$watch 'gr.cart.items', ((items) ->
            arr = []
            angular.forEach items, (i) ->
                if i.count > 0
                    arr.push
                        id: i.idproduct or i.id
                        name: i.name
                        count: i.count
                        unitvalue: i.unitvalue

            $localStorage.griffo_cart = arr
        ), true

        $rootScope.ready = ->
            if ready.categories and ready.products
                true
            else
                false

        $rootScope.products = []
        $rootScope.categories = []
        $rootScope.gr.cart =
            title: 'Lista do pedido'
            templateUrl: 'cart.html'
            placement: 'bottom'
            animation: false
            trigger: 'click'
            opened: false
            items: $localStorage.griffo_cart ? []
            length: (total) ->
                l = 0
                angular.forEach $rootScope.gr.cart.items, (item) ->
                    if item.count > 0 and !total
                        l++
                    else if total
                        l += item.count
                    return
                l
            check: (item) ->
                count = parseInt(item.count)
                if count > 1000
                    count = 1000
                else if count < 0
                    count = 0
                else if !/[0-9]+/.test(count)
                    count = 0
                item.count = count
                return
            clear: ->
                angular.forEach $rootScope.gr.cart.items, (item) ->
                    item.count = 0
                    return
                return
            has: (item) ->
                found = false
                index = undefined
                angular.forEach $rootScope.gr.cart.items, (i, id) ->
                    if i.name == item.name
                        found = true
                        index = id
                    return
                if found then index else found
            remove: (item) ->
                item.count = 0
                return
            add: (item) ->
                found = false
                angular.forEach $rootScope.gr.cart.items, (i) ->
                    if i.name == item.name
                        found = true
                    return
                if !found
                    item.count = 0
                    $rootScope.gr.cart.items.push item
                return
            total: ->
                total = 0
                angular.forEach $rootScope.gr.cart.items, (item) ->
                    total += item.unitvalue * item.count
                    return
                total
            submit: ->
                if $rootScope.GRIFFO.filter.page.url != 'finish/'
                    $localStorage.griffo_cart_ready = true
                    $timeout ->
                        location.href = $rootScope.GRIFFO.curAlias + '/finish'
                        return
                else
                    $rootScope.finishForm.submit()
                return
            cancel: ->
                $grModal.confirm 'Tem certeza que deseja cancelar?', ->
                    $rootScope.gr.cart.clear()
                    delete $localStorage.griffo_cart_ready
                    return
                return

        $grRestful.find
            module: 'category'
            action: 'module'
            params: 'name=product'
        .then (r) ->
            if r.response
                $rootScope.categories = r.response
                ready.categories = true
                if $rootScope.ready
                    setProducts()

        $grRestful.find
            module: 'product'
            action: 'get'
        .then (r) ->
            if r.response
                $scope.products = r.response
                angular.forEach r.response, (item) ->
                    item.picture = $rootScope.GRIFFO.uploadPath + item.picture

                ready.products = true
                if $rootScope.ready
                    setProducts()

        angular.element('body').on
            mousedown: (e) ->
                target = angular.element(e.target)
                if target.parents('.cart').length == 0 and $scope.gr.cart.opened
                    angular.element('.btn-cart').trigger 'click'
