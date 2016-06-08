'use strict';
(function(){
    angular.module('adminApp').controller('adminCtrl', ['$rootScope', '$scope', '$grModal', '$cookies', '$location', function($rootScope, $scope, $grModal, $cookies, $location){
        console.debug($rootScope.GRIFFO);

        $cookies['grAdminLastPage_UserID' + $rootScope.GRIFFO.user.id] = $location.absUrl().split($rootScope.GRIFFO.curAlias)[1];

        var generateSelectLayer = function(){
                var curLayer,
                    layers = [];
                angular.forEach($rootScope.GRIFFO.layers, function(l,i){
                    if(l.name === $rootScope.GRIFFO.filter.layer){
                        curLayer = l;
                    }else{
                        layers.push(l);
                    }
                });
                return {
                    curLayer: curLayer,
                    layers: layers
                }
            },
            selectLayer = generateSelectLayer();
        $scope.customer = $rootScope.GRIFFO.customer;
        $scope.user = $rootScope.GRIFFO.user;
        $scope.curLayer = selectLayer.curLayer;
        $scope.layers = selectLayer.layers;
        $scope.themes = [{
            label: 'Dark',
            name: 'dark',
            color: '#666666'
        },{
            label: 'Green',
            name: 'green',
            color: '#1AB394'
        },{
            label: 'Blue',
            name: 'blue',
            color: '#428BCA'
        },{
            label: 'Red',
            name: 'red',
            color: '#F7635F'
        }];
        $scope.openTools = function(){
            var modal = $grModal.new({
                name: 'tools',
                title: 'LABEL.TOOLS',
                size: 'lg',
                model: $rootScope.GRIFFO.templatePath + 'view/modal/tools.php',
                buttons: [{
                        type: 'default',
                        label: 'BUTTON.CLOSE',
                        onClick: function($scope, $element, controller){
                            controller.close();
                        }
                    }]
            });
            modal.open();
        };
    }]);
}());
