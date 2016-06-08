'use strict'
angular.module('mainApp').controller 'userCtrl', ($rootScope, $scope, $cookies, $window, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout, angularLoad) ->

    initNotification = ->

        permissionLevels = {}
        notify = $window.notify
        permissionLevels[notify.PERMISSION_GRANTED] = 0
        permissionLevels[notify.PERMISSION_DEFAULT] = 1
        permissionLevels[notify.PERMISSION_DENIED] = 2
        $scope.isSupported = notify.isSupported
        $scope.permissionLevel = permissionLevels[notify.permissionLevel()]

        $scope.requestPermissions = ->
            notify.requestPermission ->
                $scope.$apply $scope.permissionLevel = permissionLevels[notify.permissionLevel()]

        $scope.notify = (order) ->
            if $scope.isSupported and $scope.permissionLevel == 0
                status = undefined
                if order.status == 0
                    status = 'Aguardando atendimento'
                else if order.status == 1
                    status = 'Em produção'
                else if order.status == 2
                    status = 'Em transporte'
                else if order.status == 3
                    status = 'Entregue'
                else if order.status == 4
                    status = 'Concluído, aguardando retirada'
                notify.createNotification 'Status do pedido #' + order.idorder,
                    body: 'Alterado para "' + status + '"'
                    icon: $rootScope.GRIFFO.templatePath + 'image/notification-status-' + order.status + '.png'

        $scope.requestPermissions()

    $rootScope.gr.title =
        icon: 'fa fa-fw fa-lock'
        text: 'Acesso restrito'

    do ->
        alert = $grAlert.new()
        editable = false

        $scope.editable = (e) ->
            if e == undefined
                return editable
            else
                editable = e
            return

        $scope.shops = []

        $scope.findShop = (id) ->
            name = undefined
            angular.forEach $scope.shops, (shop) ->
                name = shop.label if shop.value == id
            name

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

        $grRestful.find(
            module: 'client'
            action: 'get'
            id: $rootScope.GRIFFO.user.id).then (r) ->
            if r.response
                $scope.formSettings.data = r.response
                $scope.form.updateDefaults()

        $scope.formSettings =
            data: {}
            schema: [
                {
                    property: 'name'
                    type: 'text'
                    label: 'Nome'
                    columns: 12
                    attr: required: true
                    msgs: required: 'Preencha o nome'
                }
                {
                    property: 'phone'
                    type: 'phone'
                    label: 'Telefone'
                    columns:
                        xs: 12
                        sm: 12
                        md: 6
                    attr:
                        ngRequired: '!formSettings.data.mobilephone'
                        required: true
                    msgs:
                        required: 'Informe o Telefone'
                        mask: 'O telefone é inválido'
                }
                {
                    property: 'mobilephone'
                    type: 'mobilephone'
                    label: 'Celular'
                    columns:
                        xs: 12
                        sm: 12
                        md: 6
                    attr:
                        ngRequired: '!formSettings.data.phone'
                        required: true
                    msgs:
                        required: 'Informe o Celular'
                        mask: 'O celular é inválido'
                }
                {
                    property: 'email'
                    type: 'email'
                    label: 'E-mail'
                    placeholder: 'exemplo@exemplo.com'
                    columns: 12
                    attr: required: true
                    msgs:
                        required: 'Informe o e-mail'
                        email: 'O formato do e-mail é inválido!'
                }
                {
                    property: 'address'
                    type: 'text'
                    label: 'Endereço'
                    columns: 8
                    attr: required: true
                    msgs: required: 'Preencha o endereço'
                }
                {
                    property: 'number'
                    type: 'text'
                    label: 'Número'
                    number: true
                    columns: 4
                    attr: required: true
                    msgs: required: 'Preencha o número'
                }
                {
                    property: 'complement'
                    type: 'text'
                    label: 'Complemento'
                    columns: 6
                }
                {
                    property: 'district'
                    type: 'text'
                    label: 'Bairro'
                    columns: 6
                    attr: required: true
                    msgs: required: 'Preencha o bairro'
                }
                {
                    property: 'city'
                    type: 'select'
                    label: 'Cidade'
                    columns: 6
                    list: 'item.value as item.label for item in cities'
                    attr: required: true
                    msgs: required: 'Selecione uma cidade'
                }
                {
                    property: 'state'
                    type: 'select'
                    label: 'Estado'
                    columns: 6
                    list: 'item.value as item.label for item in states'
                    attr: required: true
                    msgs: required: 'Selecione um estado'
                }
                {
                    property: 'fkidshop'
                    type: 'select'
                    label: 'Loja preferencial'
                    list: 'item.value as item.label for item in shops'
                    columns: 12
                    attr: required: true
                    msgs: required: 'Selecione uma loja preferencial'
                }
            ]
            submit: (data) ->
                return if !$scope.form.$valid
                $grRestful.update(
                    module: 'client'
                    action: 'update_attributes'
                    id: $rootScope.GRIFFO.user.id
                    post: data).then (r) ->
                    if r.response
                        $scope.editable false
                        $scope.form.updateDefaults()
                    alert.show r.status, r.message

        $scope.$watch 'formSettings.data.state', (e) ->
            if e
                if $scope.formSettings.data.state
                    $scope.cities = []
                    angular.forEach $cidadeEstado.get.cidades($scope.formSettings.data.state), (c) ->
                        $scope.cities.push
                            value: c
                            label: c
                else
                    $scope.cities = []
            else
                $scope.formSettings.data.city = undefined

        $scope.changePassword = ->
            modal = $grModal.new
                name: 'change-password'
                title: 'Alterar senha'
                size: 'sm'
                model: GRIFFO.templatePath + 'view/modal/change-password.php'
                define: id: $scope.formSettings.data.idclient
                buttons: [
                    {
                        type: 'primary'
                        label: 'Alterar'
                        onClick: ($scope, $element, controller) ->
                            $scope.form.submit()
                            return

                    }
                    {
                        type: 'danger'
                        label: 'Cancelar'
                        onClick: ($scope, $element, controller) ->
                            controller.close()
                            return

                    }
                ]
            modal.open()

    do ->

        getOrders = ->
            if !$scope.showCompleted
                $grRestful.find
                    module: 'order'
                    action: 'get'
                    params: 'fkidclient=' + $rootScope.GRIFFO.user.id
                .then (r) ->
                    if r.response
                        angular.forEach r.response, (order, id) ->
                            angular.forEach $scope.orders, (_order, _id) ->
                                if _order.idorder == order.idorder and _order.status != order.status
                                    $scope.notify order
                        $scope.orders = r.response
                    else
                        $scope.orders = []

                getOrdersTimeout = $timeout ->
                    getOrders()
                , 30000
            else
                $grRestful.find
                    module: 'order'
                    action: 'completed'
                    params: 'fkidclient=' + $rootScope.GRIFFO.user.id
                .then (r) ->
                    if r.response
                        $scope.orders = r.response
                    else
                        $scope.orders = []

        $scope.orders = []
        getOrdersTimeout = $timeout
        $scope.$watch 'showCompleted', ->
            $timeout.cancel getOrdersTimeout
            getOrders()
        getOrders()
        $scope.orderInfo = (order) ->
            modal = $grModal.new
                name: 'order-info'
                title: 'Informações do pedido #' + order.idorder
                size: 'md'
                model: GRIFFO.templatePath + 'view/modal/order-info.php'
                define:
                    order: order
                    findShop: $scope.findShop
                buttons: [
                    {
                        type: 'primary'
                        label: 'Ok'
                        onClick: ($scope, $element, controller) ->
                            controller.close()
                    }
                ]
            modal.open()

    delete $cookies.griffo_cart_ready
    angularLoad.loadScript($rootScope.GRIFFO.librariesPath + 'client/desktop-notify/desktop-notify.min.js').then initNotification

angular.module('mainApp').controller 'changePasswordCtrl', ($scope, $timeout, $grRestful, $grAlert) ->
    alert = $grAlert.new()
    $scope.formSettings =
        data: {}
        schema: [
            {
                property: 'oldpassword'
                type: 'password'
                label: 'Senha atual'
                attr:
                    required: true
                    autofocus: true
                msgs: required: 'A senha atual é obrigatória'
            }
            { type: 'hr' }
            {
                property: 'password'
                type: 'password'
                label: 'Nova senha'
                attr:
                    required: true
                    ngMinlength: 4
                    ngMaxlength: 16
                msgs:
                    required: 'A nova senha é obrigatória'
                    minlength: 'A senha deve possuir no mínimo 4 caractéres'
                    maxlength: 'A senha não pode ultrapassar 16 caractéres'
                    pattern: 'A nova senha é inválida'
            }
            {
                property: 'repassword'
                type: 'password'
                label: 'Confirmação da nova senha'
                attr:
                    required: true
                    confirmPassword: 'formSettings.data.password'
                msgs:
                    required: 'Confirmar a nova senha é obrigatória'
                    match: 'As senhas precisam ser iguais'
            }
        ]
        submit: (data) ->
            newData =
                'old-password': data.oldpassword
                'password': data.password
                're-password': data.repassword
            $grRestful.update
                module: 'client'
                action: 'change_password'
                id: $scope.$parent.id
                post: newData
            .then (r) ->
                if r.response
                    $scope.modal.close()
                    alert.show 'success', 'ALERT.SUCCESS.CHANGE.PASSWORD'
                else
                    alert.show r.status, r.message
            , ->
                alert.show 'danger', 'ERROR.FATAL'

    $scope.$watch 'form', (form) ->
        $scope.$parent.form = form

angular.module('mainApp').directive 'confirmPassword', ->
    restrict: 'A'
    require: 'ngModel'
    link: (scope, element, attrs, ngModel) ->
        validate = (viewValue) ->
            password = scope.$eval(attrs.confirmPassword)
            ngModel.$setValidity 'match', ngModel.$isEmpty(viewValue) or viewValue == password
            viewValue
        ngModel.$parsers.push validate
        scope.$watch attrs.confirmPassword, (value) ->
            validate ngModel.$viewValue
