'use strict'
angular.module('mainApp').controller 'finishCtrl', ($rootScope, $scope, $localStorage, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout) ->

    alert = $grAlert.new()
    finished = false

    cartEmpty = (url) ->
        alert.show 'info', 'Sua lista de pedidos está vazia, você será redirecionado!'
        $timeout ->
            location.href = $rootScope.GRIFFO.curAlias + '/' + (if url then url else '')
        , 5000

    if $rootScope.gr.cart.length() == 0
        cartEmpty()
    else
        $rootScope.gr.title =
            icon: 'fa fa-fw fa-check'
            text: 'Finalizar pedido'
        $scope.payments = [
            'Dinheiro'
            'Cartão de Crédito/Débito'
        ]
        $scope.addresses = [
            $rootScope.GRIFFO.user.address + ', ' + $rootScope.GRIFFO.user.number + (if $rootScope.GRIFFO.user.complement then ', ' + $rootScope.GRIFFO.user.complement else '') + ', ' + $rootScope.GRIFFO.user.district
            'Outro...'
        ]
        $scope.shops = []

        $grRestful.find(
            module: 'shop'
            action: 'select').then (r) ->
            if r.response
                $scope.shops = r.response

        $scope.states = []

        angular.forEach $cidadeEstado.get.estados(), (e) ->
            $scope.states.push
                value: e[0]
                label: e[1]
            return

        delete $localStorage.griffo_cart_ready

        $scope.formSettings =
            data:
                status: 0
                fetch: yes
                date: new Date
                time: moment(new Date).format("HH:mm")
                # time: '00:00'
                formpayment: 'Dinheiro'
                address: $scope.addresses[0]
                state: $rootScope.GRIFFO.user.state
                city: $rootScope.GRIFFO.user.city
                fkidshop: $rootScope.GRIFFO.user.fkidshop
            schema: [
                {
                    property: 'fkidshop'
                    type: 'select'
                    label: 'Loja'
                    list: 'item.value as item.label for item in shops'
                    columns: 12
                    attr: required: yes, ngShow: 'shops.length > 2'
                    msgs: required: 'A loja é obrigatória'
                }
                {
                  property: 'date'
                  type: 'date'
                  label: 'Data do pedido'
                  columns: 6
                  attr: required: yes, min: moment(new Date).format("YYYY-MM-DD")
                  msgs: required: 'A data é obrigatória', min: "Precisa ser #{moment(new Date).format("DD/MM/YYYY")} ou depois"
                }
                {
                  property: 'time'
                  type: 'text'
                  label: 'Hora do pedido'
                  columns: 6
                #   attr: required: yes, uiTimeMask: "short", ngPattern: "/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/d+"
                  attr: required: yes, uiTimeMask: "short"
                  msgs: required: 'A hora é obrigatória', time: 'A hora é inválida'
                }
                {
                    property: 'fetch'
                    type: 'checkbox'
                    label: 'Vai buscar?'
                    columns: 12
                    attr: disabled: yes
                    # columns: 6
                }
                # {
                #     property: 'formpayment'
                #     type: 'select'
                #     label: 'Forma de pagamento'
                #     list: 'item as item for item in payments'
                #     attr:
                #         required: yes
                #         ngIf: '!formSettings.data.fetch'
                #     msgs: required: 'A forma de pagamento é obrigatória'
                # }
                # {
                #     property: 'change'
                #     type: 'money'
                #     label: 'LABEL.CHANGE.FOR'
                #     placeholder: '0,00'
                #     columns: 6
                #     attr: ngIf: 'formSettings.data.formpayment === \'Dinheiro\' && !formSettings.data.fetch'
                # }
                # {
                #     type: 'hr'
                #     attr: 'ng-if': '!formSettings.data.fetch'
                # }
                # {
                #     property: 'address'
                #     type: 'select'
                #     label: 'Endereço de entrega'
                #     list: 'item as item for item in addresses'
                #     attr:
                #         required: yes
                #         ngIf: '!formSettings.data.fetch'
                #     msgs: required: 'O endereço de entrega é obrigatório'
                # }
                # {
                #     property: 'address1'
                #     label: 'Novo endereço'
                #     columns: 8
                #     attr:
                #         required: yes
                #         ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                #     msgs: required: 'O novo endereço é obrigatório'
                # }
                # {
                #     property: 'number'
                #     type: 'number'
                #     label: 'Número'
                #     columns: 4
                #     attr:
                #         required: yes
                #         ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                #     msgs: required: 'O número é obrigatório'
                # }
                # {
                #     property: 'complement'
                #     label: 'Complemento'
                #     columns: 6
                #     attr: ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                # }
                # {
                #     property: 'district'
                #     label: 'Bairro'
                #     columns: 6
                #     attr:
                #         required: yes
                #         ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                #     msgs: required: 'O bairro é obrigatório'
                # }
                # {
                #     property: 'state'
                #     type: 'select'
                #     label: 'Estado'
                #     columns: 6
                #     list: 'item.value as item.label for item in states'
                #     attr:
                #         required: yes
                #         ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                #     msgs: required: 'Selecione um estado'
                # }
                # {
                #     property: 'city'
                #     type: 'select'
                #     label: 'Cidade'
                #     columns: 6
                #     list: 'item.value as item.label for item in cities'
                #     attr:
                #         required: yes
                #         ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                #     msgs: required: 'Selecione uma cidade'
                # }
            ]
            submit: (data) ->
                return if !$scope.form.$valid
                time = data.time.split(':')
                order =
                    fkidclient: $rootScope.GRIFFO.user.id
                    fetch: data.fetch
                    subtotal: $rootScope.gr.cart.total()
                    fkidshop: data.fkidshop
                    created: moment(data.date).hour(time[0]).minute(time[1]).second(0).format('YYYY-MM-DD HH:mm:ss')
                    products: []

                angular.forEach $rootScope.gr.cart.items, (item) ->
                    if item.count > 0
                        order.products.push
                            idproduct: item.id
                            fkidshop: data.fkidshop
                            quantity: item.count

                if !data.fetch
                    order.formpayment = if data.formpayment then data.formpayment else undefined
                    order.change = if data.change then data.change else undefined
                    if data.address == 'Outro...'
                        order.address = data.address1
                        order.number = data.number
                        order.complement = data.complement
                        order.district = data.district
                        order.city = data.city
                        order.state = data.state
                    else
                        order.address = $rootScope.GRIFFO.user.address
                        order.number = $rootScope.GRIFFO.user.number
                        order.complement = $rootScope.GRIFFO.user.complement
                        order.district = $rootScope.GRIFFO.user.district
                        order.city = $rootScope.GRIFFO.user.city
                        order.state = $rootScope.GRIFFO.user.state

                $grRestful.create
                    module: 'order'
                    action: 'insert'
                    post: order
                .then (r) ->
                    if r.response
                        finished = yes
                        $scope.form.reset()
                        $rootScope.gr.cart.clear()
                        alert.show r.status, 'Seu pedido foi enviado com sucesso, você será redirecionado para acompanhar seu pedido.'
                        $timeout (->
                            location.href = $rootScope.GRIFFO.curAlias + '/user'
                            return
                        ), 5000
                    else
                        alert.show r.status, r.message
                    return
                , ->
                    alert.show 'danger', 'ERROR.FATAL'

        $scope.$watch 'formSettings.data.state', (e) ->
            if e
                if $scope.formSettings.data.state
                    $scope.cities = []
                    angular.forEach $cidadeEstado.get.cidades($scope.formSettings.data.state), (c) ->
                        $scope.cities.push
                            value: c
                            label: c
                        return
                else
                    $scope.cities = []
                if $scope.formSettings.data.state == 'SP'
                    $scope.formSettings.data.city = 'Itapeva'
                else
                    $scope.formSettings.data.city = undefined
            else
                $scope.formSettings.data.city = undefined

        $scope.$watch ->
            $rootScope.gr.cart.length()
        , ->
            cartEmpty() if $rootScope.gr.cart.length() == 0 and !finished

        $scope.$watch 'form', (form) ->
            $rootScope.finishForm = form
