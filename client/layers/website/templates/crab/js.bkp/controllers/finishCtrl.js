'use strict';
(function(){
    angular.module('mainApp').controller('finishCtrl', ['$rootScope', '$scope', '$cookies', '$grRestful', '$grModal', '$grAlert', '$cidadeEstado', '$timeout', function($rootScope, $scope, $cookies, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout){
        var alert = $grAlert.new(),
            finished = false;
        function cartEmpty(url){
            alert.show('info', 'Sua lista de pedidos está vazia, você será redirecionado!');
            $timeout(function(){
                location.href = $rootScope.GRIFFO.curAlias + '/' + (url ? url : '');
            }, 5000);
        }
        if($rootScope.gr.cart.length() === 0){
            cartEmpty();
        }else{
            $rootScope.gr.title = {
                icon: 'fa fa-fw fa-check',
                text: 'Finalizar pedido'
            };
            $scope.payments = ['Dinheiro', 'Cartão de Crédito/Débito'];
            $scope.addresses = [$rootScope.GRIFFO.user.address + ', ' + $rootScope.GRIFFO.user.number + ($rootScope.GRIFFO.user.complement ? ', ' + $rootScope.GRIFFO.user.complement : '') + ', ' + $rootScope.GRIFFO.user.district, 'Outro...'];
            $scope.shops = [];
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
            delete $cookies.griffo_cart_ready;
            $scope.formSettings = {
                data: {
                    status: 0,
                    formpayment: 'Dinheiro',
                    address: $scope.addresses[0],
                    state: $rootScope.GRIFFO.user.state,
                    city: $rootScope.GRIFFO.user.city,
                    fkidshop: $rootScope.GRIFFO.user.fkidshop
                },
                schema: [
                    {
                        property: 'fkidshop',
                        type: 'select',
                        label: 'Loja',
                        list: 'item.value as item.label for item in shops',
                        columns: 6,
                        attr: {
                            required: true
                        },
                        msgs: {
                            required: 'A loja é obrigatória'
                        }

                    }, {
                        property: 'fetch',
                        type: 'checkbox',
                        label: 'Vai buscar?',
                        columns: 6
                    }, {
                        property: 'formpayment',
                        type: 'select',
                        label: 'Forma de pagamento',
                        list: 'item as item for item in payments',
                        attr: {
                            required: true,
                            ngIf: '!formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'A forma de pagamento é obrigatória'
                        }
                    }, {
                        property: 'change',
                        type: 'money',
                        label: 'LABEL.CHANGE.FOR',
                        placeholder: '0,00',
                        columns: 6,
                        attr: {
                            ngIf: 'formSettings.data.formpayment === \'Dinheiro\' && !formSettings.data.fetch'
                        }
                    }, {
                        type: 'hr',
                        attr: {
                            'ng-if': '!formSettings.data.fetch'
                        }
                    }, {
                        property: 'address',
                        type: 'select',
                        label: 'Endereço de entrega',
                        list: 'item as item for item in addresses',
                        attr: {
                            required: true,
                            ngIf: '!formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'O endereço de entrega é obrigatório'
                        }
                    }, {
                        property: 'address1',
                        label: 'Novo endereço',
                        columns: 8,
                        attr: {
                            required: true,
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'O novo endereço é obrigatório'
                        }
                    }, {
                        property: 'number',
                        type: 'number',
                        label: 'Número',
                        columns: 4,
                        attr: {
                            required: true,
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'O número é obrigatório'
                        }
                    }, {
                        property: 'complement',
                        label: 'Complemento',
                        columns: 6,
                        attr: {
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        }
                    }, {
                        property: 'district',
                        label: 'Bairro',
                        columns: 6,
                        attr: {
                            required: true,
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'O bairro é obrigatório'
                        }
                    }, {
                        property: 'state',
                        type: 'select',
                        label: 'Estado',
                        columns: 6,
                        list: 'item.value as item.label for item in states',
                        attr: {
                            required: true,
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'Selecione um estado'
                        }
                    }, {
                        property: 'city',
                        type: 'select',
                        label: 'Cidade',
                        columns: 6,
                        list: 'item.value as item.label for item in cities',
                        attr: {
                            required: true,
                            ngIf: 'formSettings.data.address === \'Outro...\' && !formSettings.data.fetch'
                        },
                        msgs: {
                            required: 'Selecione uma cidade'
                        }
                    }
                ],
                submit: function(data){
                    if(!$scope.form.$valid) return;
                    var order = {
                        fkidclient: $rootScope.GRIFFO.user.id,
                        fetch: data.fetch,
                        subtotal: $rootScope.gr.cart.total(),
                        fkidshop: data.fkidshop,
                        products: []
                    };
                    angular.forEach($rootScope.gr.cart.items, function(item){
                        if(item.count > 0){
                            order.products.push({
                                idproduct: item.id,
                                fkidshop: data.fkidshop,
                                quantity: item.count
                            });
                        }
                    });
                    if(!data.fetch){
                        order.formpayment = data.formpayment ? data.formpayment : undefined;
                        order.change = data.change ? data.change : undefined;
                        if(data.address === 'Outro...'){
                            order.address = data.address1;
                            order.number = data.number;
                            order.complement = data.complement;
                            order.district = data.district;
                            order.city = data.city;
                            order.state = data.state;
                        }else{
                            order.address = $rootScope.GRIFFO.user.address;
                            order.number = $rootScope.GRIFFO.user.number;
                            order.complement = $rootScope.GRIFFO.user.complement;
                            order.district = $rootScope.GRIFFO.user.district;
                            order.city = $rootScope.GRIFFO.user.city;
                            order.state = $rootScope.GRIFFO.user.state;
                        }
                    }
                    $grRestful.create({
                        module: 'order',
                        action: 'insert',
                        post: order
                    }).then(function(r){
                        if(r.response){
                            finished = true;
                            $scope.form.reset();
                            $rootScope.gr.cart.clear();
                            alert.show(r.status, 'Seu pedido foi enviado com sucesso, você será redirecionado para acompanhar seu pedido.');
                            $timeout(function(){
                                location.href = $rootScope.GRIFFO.curAlias + '/user';
                            }, 5000);
                        }else{
                            alert.show(r.status, r.message);
                        }
                    }, function(){
                        alert.show('danger', 'ERROR.FATAL');
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
                    if($scope.formSettings.data.state === 'SP'){
                        $scope.formSettings.data.city = 'Itapeva';
                    }else{
                        $scope.formSettings.data.city = undefined;
                    }
                }else{
                    $scope.formSettings.data.city = undefined;
                }
            });
            $scope.$watch(function(){
                return $rootScope.gr.cart.length();
            }, function(){
                if($rootScope.gr.cart.length() === 0 && !finished){
                    cartEmpty();
                }
            });
            $scope.$watch('form', function(form){
                $rootScope.finishForm = form;
            });
        }
    }]);
}());
