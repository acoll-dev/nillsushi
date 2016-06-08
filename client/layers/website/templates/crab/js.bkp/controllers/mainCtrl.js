'use strict';
(function(){
    angular.module('mainApp').controller('mainCtrl', ['$rootScope', '$scope', '$grRestful', '$grAlert', '$grModal', '$cookies', '$timeout', function($rootScope, $scope, $grRestful, $grAlert, $grModal, $cookies, $timeout){

        var alert = $grAlert.new();

        $rootScope.logouting = false;

		$rootScope.gr = {};

        $rootScope.gr.website = {
            copyright: 'Copyright © ' + new Date().getFullYear() + ' - Todos os direitos reservados.'
        };

		$rootScope.gr.contactHeader = [
            {
				title: 'Facebook Acoll',
				link: 'http://fb.com/fan.acoll',
				class: 'contact fa fa-fw fa-2x fa-facebook-square'
			},{
				title: 'E-mail Acoll',
				link: 'mailto:contato@acoll.com.br',
				class: 'contact fa fa-fw fa-2x fa-envelope'
			}
        ];

        $rootScope.gr.shops = [];
        $rootScope.gr.banners = [];

        $grRestful.find({
            module: 'banner',
            action: 'get'
        }).then(function(r){
            if(r.response){
                $rootScope.gr.banners = r.response;
            }
        });

        $grRestful.find({
            module: 'shop',
            action: 'get'
        }).then(function(r){
            if(r.response){
                $rootScope.gr.shops = r.response;
            }
        });

        $rootScope.logout = function(){
            alert.show('loading', 'Saindo...', 0);
            $rootScope.logouting = true;
            $grRestful.auth({
                action: 'logout'
            }).then(function (r) {
                delete $cookies.griffo_cart_ready;
                if(r.response){
                    alert.show('success', 'Você saiu do sistema e está sendo redirecionado!');
                    window.location.reload();
                }else{
                    console.debug('Falha no logout!');
                }
                $rootScope.logouting = false;
            }, function(r){
                console.debug('Falha no logout!');
            });
        };

        (function initOrder(){
            var init = true,
                ready = {
                    categories: false,
                    products: false
                };
            if($cookies.griffo_cart){
                var struct = JSON.parse($cookies.griffo_cart)[0],
                    properties = ['id', 'name', 'count', 'unitvalue'];
                if(struct){
                    if(!angular.equals(Object.getOwnPropertyNames(struct), properties)){
                        $cookies.griffo_cart = JSON.stringify([]);
                    }
                }
            }
            $rootScope.$watch('gr.cart.items', function(items){
                var arr = [];
                angular.forEach(items, function(i){
                    if(i.count > 0){
                        arr.push({
                            id: i.idproduct || i.id,
                            name: i.name,
                            count: i.count,
                            unitvalue: i.unitvalue
                        });
                    }
                });
                $cookies.griffo_cart = JSON.stringify(arr);
            }, true);
            $rootScope.ready = function(){
                if(ready.categories && ready.products){
                    return true
                }else{
                    return false
                }
            };
            $rootScope.products = [];
            $rootScope.categories = [];
            $rootScope.gr.cart = {
                title: 'Lista do pedido',
                templateUrl: 'cart.html',
                placement: 'bottom',
                animation: false,
                trigger: 'click',
                opened: false,
                items: $cookies.griffo_cart ? JSON.parse($cookies.griffo_cart) : [],
                length: function(total){
                    var l = 0;
                    angular.forEach($rootScope.gr.cart.items, function(item){
                        if(item.count > 0 && !total){
                            l++;
                        }else if(total){
                            l += item.count;
                        }
                    });
                    return l;
                },
                check: function(item){
                    var count = parseInt(item.count);
                    if(count > 1000){
                        count = 1000;
                    }else if(count < 0){
                        count = 0;
                    }else if(!/[0-9]+/.test(count)){
                        count = 0;
                    }
                    item.count = count;
                },
                clear: function(){
                    angular.forEach($rootScope.gr.cart.items, function(item){
                        item.count = 0;
                    });
                },
                has: function(item){
                    var found = false,
                        index;
                    angular.forEach($rootScope.gr.cart.items, function(i, id){
                        if(i.name === item.name){
                            found = true;
                            index = id;
                        }
                    });
                    return found ? index : found;
                },
                remove: function(item){
                    item.count = 0;
                },
                add: function(item){
                    var found = false;
                    angular.forEach($rootScope.gr.cart.items, function(i){
                        if(i.name === item.name){
                            found = true;
                        }
                    });
                    if(!found){
                        item.count = 0;
                        $rootScope.gr.cart.items.push(item);
                    }
                },
                total: function(){
                    var total = 0;
                    angular.forEach($rootScope.gr.cart.items, function(item){
                        total += item.unitvalue * item.count;
                    });
                    return total;
                },
                submit: function(){
                    if($rootScope.GRIFFO.filter.page.url !== 'finish/'){
                        $cookies.griffo_cart_ready = true;
                        $timeout(function(){
                            location.href = $rootScope.GRIFFO.curAlias + '/finish';
                        });
                    }else{
                        $rootScope.finishForm.submit();
                    }
                },
                cancel: function(){
                    $grModal.confirm('Tem certeza que deseja cancelar?', function(){
                        $rootScope.gr.cart.clear();
                        delete $cookies.griffo_cart_ready;
                    });
                }
            };
            $grRestful.find({
                module: 'category',
                action: 'module',
                params: 'name=product'
            }).then(function(r){
                if(r.response){
                    $rootScope.categories = r.response;
                    ready.categories = true;
                    if($rootScope.ready){
                        setProducts();
                    }
                }
            });
            $grRestful.find({
                module: 'product',
                action: 'get'
            }).then(function(r){
                if(r.response){
                    $scope.products = r.response;
                    angular.forEach(r.response, function(item){
                        item.picture = $rootScope.GRIFFO.uploadPath + item.picture;
                    });
                    ready.products = true;
                    if($rootScope.ready){
                        setProducts();
                    }
                }
            });
            function setProducts(){
                function productLoop(category){
                    category.products = [];
                    angular.forEach($scope.products, function(product){
                        if(product.fkidcategory === category.idcategory){
                            product.count = 0;
                            category.products.push(product);
                            $rootScope.gr.cart.add(product);
                        }
                    });
                    if(category.child){
                        angular.forEach(category.child, function(subcategory){
                            productLoop(subcategory);
                        });
                    }
                };
                angular.forEach($scope.categories, function(category){
                    productLoop(category);
                });
            };
            angular.element('body').on({
                mousedown: function(e){
                    var target = angular.element(e.target);
                    if(target.parents('.cart').length === 0 && $scope.gr.cart.opened){
                        angular.element('.btn-cart').trigger('click');
                    }
                }
            });
        }());

    }]);
}());
