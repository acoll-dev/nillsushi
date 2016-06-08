'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', '$cidadeEstado', function($scope, $filter, $window, $timeout, $grRestful, $grAlert, $cidadeEstado) {
        var alert = $grAlert.new();
        $scope.shops = [];
        $scope.status = [
            {
                value: 1,
                label: $filter('grTranslate')('LABEL.ACTIVE')
            },{
                value: 0,
                label: $filter('grTranslate')('LABEL.INACTIVE')
            }
        ];
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
        $scope.formSettings = {
            data: {
                status: 1,
                state: 'SP',
                preferredshop: 'Itapeva'
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
                    type: 'hr'
                }, {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    columns: 4,
                    attr: {
                        required: true,
                        autofocus: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NAME'
                    }
                }, {
                    property: 'email',
                    type: 'email',
                    label: 'LABEL.EMAIL',
                    columns: 4,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.EMAIL',
                        email: 'FORM.MESSAGE.EMAIL.EMAIL'
                    }
                }, {
                    property: 'password',
                    type: 'password',
                    label: 'LABEL.PASSWORD',
                    columns: 4,
                    attr: {
                        required: true,
                        ngMinlength: 5,
                        ngMaxlength: 16
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD',
                        minlength: 'FORM.MESSAGE.MINLENGTH.PASSWORD[[5]]',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.PASSWORD[[16]]',
                        pattern: 'FORM.MESSAGE.PATTERN.PASSWORD'
                    }
                }, {
                    property: 'address',
                    type: 'text',
                    label: 'LABEL.ADDRESS',
                    columns: 5,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.ADDRESS'
                    }
                }, {
                    property: 'number',
                    type: 'number',
                    label: 'LABEL.NUMBER',
                    columns: 2,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NUMBER',
                        number: 'FORM.MESSAGE.ONLY.NUMBER'
                    }
                }, {
                    property: 'complement',
                    type: 'text',
                    label: 'LABEL.ADDRESS.COMPLEMENT',
                    columns: 2
                }, {
                    property: 'district',
                    type: 'text',
                    label: 'LABEL.DISTRICT',
                    columns: 3,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DISTRICT'
                    }
                }, {
                    property: 'city',
                    type: 'select',
                    label: 'Cidade',
                    columns: 3,
                    list: 'item.value as item.label for item in cities',
                    msgs: {
                        required: 'Selecione uma cidade'
                    }
                }, {
                    property: 'state',
                    type: 'select',
                    label: 'Estado',
                    columns: 3,
                    list: 'item.value as item.label for item in states',
                    msgs: {
                        required: 'Selecione um estado'
                    }
                }, {
                    property: 'phone',
                    type: 'phone',
                    label: 'LABEL.PHONE',
                    columns: 3,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PHONE',
                        mask: 'FORM.MESSAGE.MASK.PHONE'
                    }
                }, {
                    property: 'mobilephone',
                    type: 'phone',
                    label: 'LABEL.PHONE.MOBILE',
                    columns: 3,
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.MOBILEPHONE',
                        mask: 'FORM.MESSAGE.MASK.MOBILEPHONE'
                    }
                }
            ],
            submit: function(data) {
                if(!$scope.form.$valid) return false;
                $grRestful.create({
                    module: 'client',
                    action: 'insert',
                    post: data
                }).then(function(r){
                    if(r.response){ $scope.form.reset(); }
                    alert.show(r.status, r.message);
                }, function(r){
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
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
