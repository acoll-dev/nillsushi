'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$locale', '$grRestful', '$grAlert', '$cidadeEstado', function($scope, $filter, $window, $timeout, $locale, $grRestful, $grAlert, $cidadeEstado) {
        var alert = $grAlert.new();

        $scope.status = [
            {
                value: 0,
                label: 'Aguardando atendimento'
            },{
                value: 1,
                label: 'Em produção'
            },{
                value: 2,
                label: 'Em transporte'
            },{
                value: 4,
                label: 'Concluído, aguardando retirada'
            },{
                value: 3,
                label: 'Entregue'
            }
        ];

        $scope.payments = ['Dinheiro', 'Cartão de Crédito/Débito'];

        $scope.states = [];

        angular.forEach($cidadeEstado.get.estados(), function(e){
            $scope.states.push({
                value: e[0],
                label: e[1]
            });
        });

        $scope.clients = [];

        $grRestful.find({
            module: 'client',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.clients = r.response;
            }
        });

	    $scope.shops = [];

        $grRestful.find({
            module: 'shop',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.shops = r.response;
            }
        });

        $scope.products = [];
        $scope.categories = [];

        $grRestful.find({
            module: 'product',
            action: 'get'
        }).then(function(r){
            if(r.response){
                var products = r.response;
                $grRestful.find({
                    module: 'product',
                    action: 'category'
                }).then(function(r){
                    if(r.response){
                        angular.forEach(products, function(product){
                            angular.forEach(r.response, function(category){
                                if(category.idcategory === product.fkidcategory){
                                    product.category = category.name;
                                }
                            });
                        });
                        $scope.products = products;
                    }
                });
            }
        });

        $scope.$watch('formSettings.data.fetch', function(fetch){
            if(fetch){
                $scope.formSettings.data.deliveryfee = 0;
            }
        });

        $scope.formSettings = {
            data: {
                status: 0,
                fetch: false,
                formpayment: 'Dinheiro',
                products: []
            },
            schema: [
                {
                    property: 'status',
                    type: 'select',
                    label: 'LABEL.STATUS',
                    list: 'item.value as item.label for item in status',
                    columns: 4,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.STATUS'
                    }
                }, {
                    property: 'fkidshop',
                    type: 'select',
                    label: 'LABEL.SHOP',
                    list: 'item.value as item.label for item in shops',
                    columns: 4,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.SHOP'
                    }

                }, {
                    property: 'fetch',
                    type: 'checkbox',
                    label: 'Vai buscar?',
                    columns: 4
                }, {
                    property: 'address',
                    label: 'LABEL.ADDRESS',
                    columns: 8,
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.ADDRESS'
                    }
                }, {
                    property: 'number',
                    type: 'text',
                    label: 'LABEL.NUMBER',
                    columns: 4,
                    number: true,
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NUMBER'
                    }
                }, {
                    property: 'complement',
                    label: 'LABEL.COMPLEMENT',
                    columns: 6,
                    attr: {
                        ngIf: 'formSettings.data.address === \'Outro...\''
                    }
                }, {
                    property: 'district',
                    label: 'LABEL.DISTRICT',
                    columns: 4,
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DISTRICT'
                    }
                }, {
                    property: 'city',
                    type: 'select',
                    label: 'LABEL.CITY',
                    columns: 4,
                    list: 'item.value as item.label for item in cities',
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.CITY'
                    }
                }, {
                    property: 'state',
                    type: 'select',
                    label: 'LABEL.STATE',
                    columns: 4,
                    list: 'item.value as item.label for item in states',
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.STATE'
                    }
                }, {
                    type: 'hr',
                    attr: {
                        required: true,
                        'ng-if': '!formSettings.data.fetch'
                    }
                }, {
                    property: 'formpayment',
                    type: 'select',
                    label: 'LABEL.PAYMENT.METHOD',
                    list: 'item as item for item in payments',
                    columns: 6,
                    attr: {
                        required: true,
                        ngIf: '!formSettings.data.fetch'
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PAYMENT.METHOD'
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
                    type: 'hr'
                }, {
                    property: 'deliveryfee',
                    type: 'money',
                    label: 'LABEL.DELIVERYFEE.MONEY',
                    placeholder: '0,00',
                    columns: 4,
                    attr: {
                        ngIf: '!formSettings.data.fetch'
                    }
                }, {
                    property: 'subtotal',
                    type: 'money',
                    label: 'LABEL.SUBTOTAL.MONEY',
                    placeholder: '0,00',
                    columns: 4,
                    attr: {
                        disabled: true
                    }
                }, {
                    property: 'total',
                    type: 'money',
                    label: 'LABEL.TOTAL.MONEY',
                    placeholder: '0,00',
                    columns: 4,
                    attr: {
                        disabled: true
                    }
                }, {
                    type: 'hr'
                }, {
                    property: 'client',
                    type: 'select2',
                    columns: 4,
                    label: 'LABEL.CLIENT',
                    list: 'clients',
                    placeholder: 'LABEL.CLIENT',
                    choice: {
                        value: 'label',
                        item: {
                            big: 'label'
                        }
                    },
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.CLIENT'
                    }
                }, {
                    property: 'product',
                    type: 'select2',
                    columns: 8,
                    label: 'LABEL.PRODUCT',
                    list: 'products',
                    placeholder: 'MODULE.ORDER.LABEL.SELECT.PRODUCT',
                    groupby: 'category',
                    choice: {
                        value: 'name',
                        item: {
                            big: 'name'
                        }
                    },
                    addons: [
                        {
                            button: true,
                            content: $filter('grTranslate')('BUTTON.ADD'),
                            attr:{
                                'ng-click': 'addProduct()',
                                class: "btn btn-primary"
                            }
                        }
                    ]
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                if(data.products.length === 0){
                    alert.show('danger', 'MODULE.ORDER.FORM.MESSAGE.REQUIRED.PRODUCTS');
                    return false;
                }
                var newData = angular.copy(data);
                newData.fkidclient = newData.client.value;
                delete newData.client;
                delete newData.product;
                $grRestful.create({
                    module: 'order',
                    action: 'insert',
                    post: newData
                }).then(function(r){
                    if(r.response){ $scope.form.reset(); }
                    alert.show(r.status, r.message);
                }, function(r){
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.addProduct = function(){
            var product = angular.copy($scope.formSettings.data.product),
                found = false;
            angular.forEach($scope.formSettings.data.products, function(p){
                if(p.idproduct === product.idproduct){
                    found = true;
                    p.quantity++;
                }
            });
            if(!found){
                product.quantity = 1;
                $scope.formSettings.data.products.push(product);
            }
            $scope.formSettings.data.product = undefined;
        };
        $scope.removeProduct = function(product){
            angular.forEach($scope.formSettings.data.products, function(p, id){
                if(p.idproduct === product.idproduct){
                    $scope.formSettings.data.products.splice(id, 1);
                }
            });
        };
        $scope.$watch('formSettings.data.deliveryfee', function(deliveryfee){
            $scope.formSettings.data.total = $scope.formSettings.data.subtotal + deliveryfee;
        });
        $scope.$watch('formSettings.data.products', function(products){
            var sub = 0;
            angular.forEach(products, function(product){
                sub += product.unitvalue * product.quantity;
            });
            $scope.formSettings.data.subtotal = sub;
            $scope.formSettings.data.total = sub + $scope.formSettings.data.deliveryfee;
        }, true);

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
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
