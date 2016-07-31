'use strict'
angular.module('mainApp').controller 'loginCtrl', ($rootScope, $scope, $cookies, $grRestful, $grAlert) ->

    $rootScope.gr.title =
        icon: 'fa fa-fw fa-lock'
        text: 'Acesso restrito'

    alert = $grAlert.new()

    $scope.formSettings =
        data: {}
        schema: [
            {
                property: 'user'
                type: 'email'
                label: 'E-mail'
                addons: [
                    {
                        before: true
                        icon: 'fa fa-fw fa-envelope'
                    }
                ]
                attr: required: true
                msgs:
                    required: 'Preencha o usuário'
                    email: 'E-mail inválido'
            }
            {
                property: 'password'
                type: 'password'
                label: 'Senha'
                addons: [
                    {
                        before: true
                        icon: 'fa fa-fw fa-lock'
                    }
                ]
                attr:
                    required: true
                    ngMinlength: 4
                    ngMaxlength: 16
                msgs:
                    required: 'A senha é obrigatória'
                    minlength: 'A senha deve possuir no mínimo 4 caractéres'
                    maxlength: 'A senha deve possuir no máximo 16 caractéres'
            }
        ]
        submit: (data) ->
            return if $scope.form.$invalid

            $grRestful.auth
                action: 'login'
                post: data
            .then (r) ->

                if r.response
                    if !$cookies.griffo_cart_ready
                        location.href = $rootScope.GRIFFO.curAlias + '/user'
                    else
                        location.href = $rootScope.GRIFFO.curAlias + '/finish'
                    delete $cookies.griffo_cart_ready

                alert.show r.status, r.message

            , (error) ->
                alert.show 'danger', 'ERROR.FATAL'

angular.module('mainApp').controller 'signupCtrl', ($rootScope, $scope, $cookies, $grRestful, $grAlert, $cidadeEstado, $timeout) ->

        alert = $grAlert.new()
        $scope.shops = []

        $grRestful.find
            module: 'shop'
            action: 'select'
        .then (r) ->
            $scope.shops = r.response if r.response
            $scope.formSettings.data.fkidshop = $scope.shops[1].value if $scope.shops[1]?

        $scope.states = []

        angular.forEach $cidadeEstado.get.estados(), (e) ->
            $scope.states.push
                value: e[0]
                label: e[1]

        $scope.formSettings =
            data:
                status: true
                state: 'SP'
                mobilephone: ''
                fkidshop: ''
            schema: [
                {
                    property: 'fkidshop'
                    type: 'select'
                    label: 'Loja preferencial'
                    list: 'item.value as item.label for item in shops'
                    columns: 12
                    attr: required: true, ngShow: 'shops.length > 2'
                    msgs: required: 'Selecione uma loja preferencial'
                }
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
                    columns:
                        xs: 12
                        sm: 12
                        md: 6
                    attr: required: true
                    msgs:
                        required: 'Informe o e-mail'
                        email: 'O formato do e-mail é inválido!'
                }
                {
                    property: 'password'
                    type: 'password'
                    label: 'Senha'
                    columns:
                        xs: 12
                        sm: 12
                        md: 6
                    attr:
                        required: true
                        ngMinlength: 4
                        ngMaxlength: 16
                    msgs:
                        required: 'A senha é obrigatória'
                        minlength: 'A senha deve possuir no mínimo 4 caractéres'
                        maxlength: 'A senha deve possuir no máximo 16 caractéres'
                        pattern: 'A senha é inválida'
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
                    property: 'state'
                    type: 'select'
                    label: 'Estado'
                    columns: 6
                    list: 'item.value as item.label for item in states'
                    attr: required: true
                    msgs: required: 'Selecione um estado'
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
            ]
            submit: (data) ->
                return if $scope.form.$invalid

                $grRestful.create
                    module: 'client'
                    action: 'insert'
                    post: data
                .then (r) ->
                    if r.response
                        $grRestful.auth
                            action: 'login'
                            post:
                                user: data.email
                                password: data.password
                        .then (r) ->
                            if r.response
                                alert.show 'success', 'Cadastro realizado com sucesso, você está sendo redirecionado...'
                                if !$cookies.griffo_cart_ready
                                    location.href = $rootScope.GRIFFO.curAlias + '/user'
                                else
                                    location.href = $rootScope.GRIFFO.curAlias + '/finish'
                                delete $cookies.griffo_cart_ready
                            else
                                alert.show r.status, r.message

                , (error) ->
                    alert.show 'danger', 'ERROR.FATAL'

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

                if $scope.formSettings.data.state == 'SP'
                    $scope.formSettings.data.city = 'Itapeva'
                else
                    $scope.formSettings.data.city = undefined

            else
                $scope.formSettings.data.city = undefined
