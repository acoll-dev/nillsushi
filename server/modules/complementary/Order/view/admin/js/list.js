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
        $scope.clients = [];
        $grRestful.find({
            module: 'client',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.clients = r.response;
            }
        });
        $grRestful.find({
            module: 'shop',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $scope.shops = r.response;
            }
        });
        $grRestful.find({
            module: 'order',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response && r.response[0]){
                angular.forEach(r.response[0].products, function(product){
                    if(typeof product.quantity !== 'number'){
                        product.quantity = parseFloat(product.quantity);
                    }
                    if(typeof product.idproduct !== 'number'){
                        product.idproduct = parseInt(product.idproduct);
                    }
                });
                makeFormSettings(r.response[0]);
            }else{
                alert.show('danger', 'ERROR.FATAL')
            }
        });
        $grRestful.find({
            module: 'product',
            action: 'get'
        }).then(function(r){
            if(r.response){
                var products = r.response;
                $grRestful.find({
                    module: 'category',
                    action: 'module',
                    onelevel: true,
                    params: 'name=product'
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
        $scope.shops = [];
        $scope.states = [];
        $scope.products = [];
        $scope.categories = [];
        angular.forEach($cidadeEstado.get.estados(), function(e){
            $scope.states.push({
                value: e[0],
                label: e[1]
            });
        });
        function makeFormSettings(data){
            $scope.formSettings = {
                data: {
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
                        property: 'new_client',
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
                                    'ng-disabled': '!formSettings.data.product',
                                    class: "btn btn-primary"
                                }
                            }
                        ]
                    }
                ],
                submit: function(data){
                    if($scope.form.$invalid) return;
                    if(data.products.length === 0){
                        alert.show('danger', 'MODULE.ORDER.FORM.MESSAGE.REQUIRED.PRODUCTS');
                        return false;
                    }
                    var newData = angular.copy(data);
                    newData.fkidclient = angular.copy(newData.new_client.value);
                    delete newData.client;
                    delete newData.new_client;
                    delete newData.product;
                    $grRestful.update({
                        module: 'order',
                        action: 'update_attributes',
                        id: $scope.grTableImport.id,
                        post: newData
                    }).then(function(r) {
                        if(r.response){
                            $scope.grTableImport.grTable.reloadData();
                            $scope.modal.forceClose();
                        }else{
                            alert.show(r.status, r.message);
                        }
                    },function(r) {
                        alert.show('danger', 'ERROR.FATAL');
                    });
                }
            };
            if(data){
                $scope.formSettings.data = angular.copy(data);
                $timeout(function(){
                    $scope.form.updateDefaults();
                    $scope.modal.ready();
                }, 500);
            }
            function setClient(){
                if($scope.clients && $scope.formSettings.data.fkidclient){
                    angular.forEach($scope.clients, function(client){
                        if(client.value === $scope.formSettings.data.fkidclient){
                            $timeout(function(){
                                $scope.formSettings.data.new_client = client;
                            }, 1000);
                        }
                    });
                }
            }
            $scope.$watch('clients', setClient);
            $scope.$watch('formSettings.data.fkidclient', setClient);
            $scope.$watch('formSettings.data.fetch', function(fetch){
                if(fetch){
                    $scope.formSettings.data.deliveryfee = 0;
                }
            });
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
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
    angular.module('adminApp').controller('tableCtrl', ['$rootScope', '$scope', '$filter', '$window', '$timeout', '$locale', '$grRestful', '$grAlert', '$cidadeEstado', 'angularLoad', '$ngPrint', function($rootScope, $scope, $filter, $window, $timeout, $locale, $grRestful, $grAlert, $cidadeEstado, angularLoad, $ngPrint) {
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
            $scope.notify = function(){
                if($scope.isSupported && $scope.permissionLevel === 0){
                    if($scope.orders.length > 0){
                        notify.createNotification("Novo pedido #" + $scope.orders[$scope.orders.length - 1].idorder, {
                            body:"Novo pedido para a loja " + $scope.findShop($rootScope.GRIFFO.curShop),
                            icon: $rootScope.GRIFFO.templatePath + "image/notification-check.png"
                        });
                    }else{
                        notify.createNotification("Novo pedido", {
                            body:"Novo pedido para a loja " + $scope.findShop($rootScope.GRIFFO.curShop),
                            icon: $rootScope.GRIFFO.templatePath + "image/notification-check.png"
                        });
                    }
                }
            };
            $scope.requestPermissions();
        };
        angularLoad.loadScript(GRIFFO.librariesPath + 'client/desktop-notify/desktop-notify.min.js').then(initNotification);
        $scope.print = function(order){
            $scope.orderPrint = order;
            $timeout(function(){
                $ngPrint({
                    content: angular.element('#orderPrintSection').html()
                });
                $scope.orderPrint = false;
            });
        };
        function makeShopTranslate(){
            if($rootScope.GRIFFO.shops.length > 0 && $scope.orders.length > 0){
                angular.forEach($scope.orders, function(order){
                    order.shop = $scope.findShop(order.fkidshop);
                });
            }
        };
        $rootScope.GRIFFO.curShop = 1;
        $rootScope.GRIFFO.shops = [];
        $scope.orders = [];
        $scope.clients = [];
        $scope.findClient = function(id){
            var curClient;
            angular.forEach($scope.clients, function(client){
                if(client.idclient === id){
                    curClient = client
                }
            });
            return curClient;
        };
        $scope.findShop = function(id){
            var name;
            angular.forEach($rootScope.GRIFFO.shops, function(shop){
                if(shop.value === id){
                    name = shop.label;
                }
            });
            return name;
        };
        $grRestful.find({
            module: 'client',
            action: 'get'
        }).then(function(r){
            if(r.response){
                $scope.clients = r.response;
            }
        });
        $grRestful.find({
            module: 'shop',
            action: 'select'
        }).then(function(r){
            if(r.response){
                $rootScope.GRIFFO.shops = r.response;
                $rootScope.GRIFFO.shops[0].label = "Selecione uma loja...";
                makeShopTranslate();
            }
        });
        var loopOrders = $timeout,
            lastID = 0,
            firstLoop = true,
            alert = $grAlert.new();

        $scope.reload = function(reload){
            if(!reload){
                alert.show('loading', 'ALERT.LOADING.TABLE.DATA', 0);
            }else{
                alert.show('loading', 'ALERT.RELOADING.TABLE.DATA', 0);
            }
            loop(true);
        }

        function loop(reloading){
            if(reloading){
                $timeout.cancel(loopOrders);
            }
            if(angular.isDefined($rootScope.GRIFFO.curShop)){
                if(!$scope.showCompleted){
                    $grRestful.find({
                        module: 'order',
                        action: 'get',
                        params: 'fkidshop=' + $rootScope.GRIFFO.curShop
                    }).then(function(r){
                        if(r.response){
                            var _lastID = 0;
                            angular.forEach(r.response, function(order){
                                if(order.idorder > _lastID){
                                    _lastID  = order.idorder;
                                }
                            });
                            if(!firstLoop && (_lastID > lastID)){
                                makeShopTranslate();
                                $scope.notify();
                            }else if(firstLoop){
                                makeShopTranslate();
                                firstLoop = false;
                            }
                            if(_lastID > lastID){
                                lastID = _lastID;
                            }
                            $scope.orders = r.response;
                            if(reloading){
                                alert.show('success', 'ALERT.SUCCESS.LOAD.TABLE.DATA', 2000);
                            }
                        }else{
                            console.debug(r);
                            alert.show('danger', 'ALERT.ERROR.LOAD.TABLE.DATA');
                        }
                    });
                    loopOrders = $timeout(function(){
                        loop();
                    }, 30000);
                }else{
                    $grRestful.find({
                        module: 'order',
                        action: 'completed',
                        params: 'fkidshop=' + $rootScope.GRIFFO.curShop
                    }).then(function(r){
                        if(r.response){
                            $scope.orders = r.response;
                            if(reloading){
                                alert.show('success', 'ALERT.SUCCESS.LOAD.TABLE.DATA', 2000);
                            }
                        }else{
                            console.debug(r);
                            alert.show('danger', 'ALERT.ERROR.LOAD.TABLE.DATA');
                        }
                    });
                }
            }
        };
        $scope.$watch('showCompleted', function(completed){
            if($rootScope.GRIFFO.curShop){
                $timeout.cancel(loopOrders);
                firstLoop = true;
                loop();
            }
        });
        $rootScope.$watch('GRIFFO.curShop', function(id){
            if(id){
                $timeout.cancel(loopOrders);
                lastID = 0;
                firstLoop = true;
                loop();
            }
        });
        $timeout(function(){
            $scope.$parent.grTable = $scope.grTable;
        });
        var initCalendar = function (){
            $scope.calendar_mode = true;
            $scope.calendar = {
                orders: [],
                config: {
                    lang: 'pt-br',
                    header: {
                        left: 'month agendaWeek agendaDay',
                        center: 'title',
                        right: 'today prev,next'
                    },
                    height: 650,
                    views: {
                        day: { titleFormat: 'D [de] MMMM YYYY' },
                        week: { titleFormat: 'D [de] MMMM YYYY' },
                        month: { titleFormat: 'MMMM YYYY' }
                    },
                    // editable: true,
                    // dayClick: $scope.alertEventOnClick,
                    // eventDrop: $scope.alertOnDrop,
                    // eventResize: $scope.alertOnResize
                }
            };
        }
        initCalendar();
    }]);
}());
