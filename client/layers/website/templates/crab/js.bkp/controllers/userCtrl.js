'use strict';
(function(){
    angular.module('mainApp').controller('userCtrl', ['$rootScope', '$scope', '$cookies', '$window', '$grRestful', '$grModal', '$grAlert', '$cidadeEstado', '$timeout', 'angularLoad', function($rootScope, $scope, $cookies, $window, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout, angularLoad){
        $rootScope.gr.title = {
            icon: 'fa fa-fw fa-lock',
            text: 'Acesso restrito'
        };
        (function initUserData(){
            var alert = $grAlert.new(),
                editable = false;
            $scope.editable = function(e){
                if(e === undefined){
                    return editable;
                }else{
                    editable = e;
                }
            };
            $scope.shops = [];
            $scope.findShop = function(id){
                var name;
                angular.forEach($scope.shops, function(shop){
                    if(shop.value === id){
                        name = shop.label;
                    }
                });
                return name;
            };
            $grRestful.find({
                module: 'shop',
                action: 'select'
            }).then(function(r){
                if(r.response){
                    $scope.shops = r.response;
                }
            });
            $scope.states = [];
            angular.forEach($cidadeEstado.get.estados(), function(e){
                $scope.states.push({
                    value: e[0],
                    label: e[1]
                });
            });
            $grRestful.find({
                module: 'client',
                action: 'get',
                id: $rootScope.GRIFFO.user.id
            }).then(function(r){
                if(r.response){
                    $scope.formSettings.data = r.response;
                    $scope.form.updateDefaults();
                }
            });
            $scope.formSettings = {
                data: {},
                schema: [
                    {
                        property: 'name',
                        type: 'text',
                        label: 'Nome',
                        columns: 12,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Preencha o nome'
                        }
                    }, {
                        property: 'phone',
                        type: 'phone',
                        label: 'Telefone',
                        columns: {
                            xs: 12,
                            sm: 12,
                            md: 6
                        },
                        attr: {
                            ngRequired: '!formSettings.data.mobilephone',
                            required: true,
                        },
                        msgs: {
                            required: 'Informe o Telefone',
                            mask: 'O telefone é inválido'
                        }
                    }, {
                        property: 'mobilephone',
                        type: 'mobilephone',
                        label: 'Celular',
                        columns: {
                            xs: 12,
                            sm: 12,
                            md: 6
                        },
                        attr: {
                            ngRequired: '!formSettings.data.phone',
                            required: true,
                        },
                        msgs:{
                            required: 'Informe o Celular',
                            mask: 'O celular é inválido'
                        }
                    }, {
                        property: 'email',
                        type: 'email',
                        label: 'E-mail',
                        placeholder: 'exemplo@exemplo.com',
                        columns: 12,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Informe o e-mail',
                            email: 'O formato do e-mail é inválido!'
                        }
                    }, {
                        property: 'address',
                        type: 'text',
                        label: 'Endereço',
                        columns: 8,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Preencha o endereço'
                        }
                    }, {
                        property: 'number',
                        type: 'text',
                        label: 'Número',
                        number: true,
                        columns: 4,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Preencha o número'
                        }
                    }, {
                        property: 'complement',
                        type: 'text',
                        label: 'Complemento',
                        columns: 6
                    }, {
                        property: 'district',
                        type: 'text',
                        label: 'Bairro',
                        columns: 6,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Preencha o bairro'
                        }
                    }, {
                        property: 'city',
                        type: 'select',
                        label: 'Cidade',
                        columns: 6,
                        list: 'item.value as item.label for item in cities',
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Selecione uma cidade'
                        }
                    }, {
                        property: 'state',
                        type: 'select',
                        label: 'Estado',
                        columns: 6,
                        list: 'item.value as item.label for item in states',
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Selecione um estado'
                        }
                    }, {
                        property: 'fkidshop',
                        type: 'select',
                        label: 'Loja preferencial',
                        list: 'item.value as item.label for item in shops',
                        columns: 12,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'Selecione uma loja preferencial'
                        }
                    }
                ],
                submit: function(data){
                    if(!$scope.form.$valid) return;
                    $grRestful.update({
                        module: 'client',
                        action: 'update_attributes',
                        id: $rootScope.GRIFFO.user.id,
                        post: data
                    }).then(function(r) {
                        if(r.response){
                            $scope.editable(false);
                            $scope.form.updateDefaults();
                        }
                        alert.show(r.status, r.message);
                    });
                }
            };
            $scope.$watch('formSettings.data.state', function(e){
                if(e){
                    if($scope.formSettings.data.state){
                        $scope.cities = [];
                        angular.forEach($cidadeEstado.get.cidades($scope.formSettings.data.state), function(c){
                            $scope.cities.push({
                                value: c,
                                label: c
                            });
                        });
                    }else{
                        $scope.cities = [];
                    }
                }else{
                    $scope.formSettings.data.city = undefined;
                }
            });
            $scope.changePassword = function(){
                var modal = $grModal.new({
                        name: 'change-password',
                        title: 'Alterar senha',
                        size: 'sm',
                        model: GRIFFO.templatePath + 'view/modal/change-password.php',
                        define: {
                            id: $scope.formSettings.data.idclient
                        },
                        buttons: [{
                                type: 'primary',
                                label: 'Alterar',
                                onClick: function($scope, $element, controller){
                                    $scope.form.submit();
                                }
                            }, {
                                type: 'danger',
                                label: 'Cancelar',
                                onClick: function($scope, $element, controller){
                                    controller.close();
                                }
                            }]
                    });
                modal.open();
            };
        }());
        (function initOrderData(){
            $scope.orders = [];
            var getOrdersTimeout = $timeout;
            function getOrders(){
                if(!$scope.showCompleted){
                    $grRestful.find({
                        module: 'order',
                        action: 'get',
                        params: 'fkidclient=' + $rootScope.GRIFFO.user.id
                    }).then(function(r){
                        if(r.response){
                            angular.forEach(r.response, function(order, id){
                                angular.forEach($scope.orders, function(_order, _id){
                                    if(_order.idorder === order.idorder && _order.status !== order.status){
                                        $scope.notify(order);
                                    }
                                });
                            });
                            $scope.orders = r.response;
                        }else{
                            $scope.orders = [];
                        }
                    });
                    getOrdersTimeout = $timeout(function(){
                        getOrders();
                    }, 30000);
                }else{
                    $grRestful.find({
                        module: 'order',
                        action: 'completed',
                        params: 'fkidclient=' + $rootScope.GRIFFO.user.id
                    }).then(function(r){
                        if(r.response){
                            $scope.orders = r.response;
                        }else{
                            $scope.orders = [];
                        }
                    });
                }
            }
            $scope.$watch('showCompleted', function(){
                $timeout.cancel(getOrdersTimeout);
                getOrders();
            });
            getOrders();
            $scope.orderInfo = function(order){
                var modal = $grModal.new({
                        name: 'order-info',
                        title: 'Informações do pedido #' + order.idorder,
                        size: 'md',
                        model: GRIFFO.templatePath + 'view/modal/order-info.php',
                        define: {
                            order: order,
                            findShop: $scope.findShop
                        },
                        buttons: [{
                                type: 'primary',
                                label: 'Ok',
                                onClick: function($scope, $element, controller){
                                    controller.close();
                                }
                            }]
                    });
                modal.open();
            }
        }());
        delete $cookies.griffo_cart_ready;
        function initNotification(){
            var permissionLevels = {},
                notify = $window.notify;
            permissionLevels[notify.PERMISSION_GRANTED] = 0;
            permissionLevels[notify.PERMISSION_DEFAULT] = 1;
            permissionLevels[notify.PERMISSION_DENIED] = 2;
            $scope.isSupported = notify.isSupported;
            $scope.permissionLevel = permissionLevels[notify.permissionLevel()];
            $scope.requestPermissions = function() {
                notify.requestPermission(function() {
                    $scope.$apply($scope.permissionLevel = permissionLevels[notify.permissionLevel()]);
                })
            };
            $scope.notify = function(order){
                if($scope.isSupported && $scope.permissionLevel === 0){
                    var status;
                    if(order.status === 0){
                        status = 'Aguardando atendimento';
                    }else if(order.status === 1){
                        status = 'Em produção';
                    }else if(order.status === 2){
                        status = 'Em transporte';
                    }else if(order.status === 3){
                        status = 'Entregue';
                    }else if(order.status === 4){
                        status = 'Concluído, aguardando retirada';
                    }
                    notify.createNotification("Status do pedido #" + order.idorder, {
                        body:'Alterado para "' + status + '"',
                        icon: $rootScope.GRIFFO.templatePath + 'image/notification-status-' + order.status + '.png'
                    });
                }
            };
            $scope.requestPermissions();
        };
        angularLoad.loadScript($rootScope.GRIFFO.librariesPath + 'client/desktop-notify/desktop-notify.min.js').then(initNotification);
    }]);
    angular.module('mainApp').controller('changePasswordCtrl', ['$scope', '$timeout', '$grRestful', '$grAlert', function($scope, $timeout, $grRestful, $grAlert){
        var alert = $grAlert.new();
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'oldpassword',
                    type: 'password',
                    label: 'Senha atual',
                    attr: {
                        required: true,
                        autofocus: true
                    },
                    msgs: {
                        required: 'A senha atual é obrigatória'
                    }
                }, {
                    type: 'hr'
                }, {
                    property: 'password',
                    type: 'password',
                    label: 'Nova senha',
                    attr: {
                        required: true,
                        ngMinlength: 4,
                        ngMaxlength: 16
                    },
                    msgs: {
                        required: 'A nova senha é obrigatória',
                        minlength: 'A senha deve possuir no mínimo 4 caractéres',
                        maxlength: 'A senha não pode ultrapassar 16 caractéres',
                        pattern: 'A nova senha é inválida'
                    }
                }, {
                    property: 'repassword',
                    type: 'password',
                    label: 'Confirmação da nova senha',
                    attr: {
                        required: true,
                        confirmPassword: 'formSettings.data.password'
                    },
                    msgs: {
                        required: 'Confirmar a nova senha é obrigatória',
                        match: 'As senhas precisam ser iguais'
                    }
                }
            ],
            submit: function(data){
                var newData = {
                    'old-password': data.oldpassword,
                    'password': data.password,
                    're-password': data.repassword
                };
                $grRestful.update({
                    module: 'client',
                    action: 'change_password',
                    id: $scope.$parent.id,
                    post: newData
                }).then(function(r){
                    if(r.response){
                        $scope.modal.close();
                        alert.show('success', 'ALERT.SUCCESS.CHANGE.PASSWORD');
                    }else{
                        alert.show(r.status, r.message);
                    }
                },function() {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
    angular.module('mainApp').directive('confirmPassword', function () {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                var validate = function (viewValue) {
                    var password = scope.$eval(attrs.confirmPassword);
                    ngModel.$setValidity('match', ngModel.$isEmpty(viewValue) || viewValue == password);
                    return viewValue;
                }
                ngModel.$parsers.push(validate);
                scope.$watch(attrs.confirmPassword, function(value){
                    validate(ngModel.$viewValue);
                })
            }
        }
    });
}());
