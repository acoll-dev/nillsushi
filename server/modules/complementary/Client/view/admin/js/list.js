'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', '$grModal', '$cidadeEstado', function($scope, $filter, $window, $timeout, $grRestful, $grAlert, $grModal, $cidadeEstado) {
        var alert = $grAlert.new()

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

        $scope.states = [];

        angular.forEach($cidadeEstado.get.estados(), function(e){
            $scope.states.push({
                value: e[0],
                label: e[1]
            });
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
            module: 'client',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                r.response.number = r.response.number ? parseInt(r.response.number) : r.response.number;
                $scope.formSettings.data = r.response;
                $scope.modal.ready();
                $timeout(function(){
                    $scope.form.updateDefaults();
                }, 1000);
            }
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
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.EMAIL',
                        email: 'FORM.MESSAGE.EMAIL.EMAIL'
                    }
                }, {
                    type: 'button',
                    label: 'Alterar senha',
                    columns: 4,
                    attr: {
                        class: 'btn btn-warning',
                        'ng-click': 'changePassword()'
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
                     attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.ADDRESS'
                    }
                }, {
                    property: 'number',
                    type: 'number',
                    label: 'LABEL.NUMBER',
                    columns: 2,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NUMBER',
                        number: 'FORM.MESSAGE.NUMBER.NUMBER'
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
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.DISTRICT'
                    }
                }, {
                    property: 'city',
                    type: 'select',
                    label: 'Cidade',
                    columns: 3,
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
                    columns: 3,
                    list: 'item.value as item.label for item in states',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'Selecione um estado'
                    }
                }, {
                    property: 'phone',
                    type: 'phone',
                    label: 'LABEL.PHONE',
                    columns: 3,
                    attr: {
                        ngRequired: '!formSettings.data.mobilephone',
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PHONE',
                        mask: 'FORM.MESSAGE.MASK.PHONE'
                    }
                }, {
                    property: 'mobilephone',
                    type: 'phone',
                    label: 'LABEL.PHONE.MOBILE',
                    columns: 3,
                    attr: {
                        ngRequired: '!formSettings.data.phone',
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.MOBILEPHONE',
                        mask: 'FORM.MESSAGE.MASK.MOBILEPHONE'
                    }
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'client',
                    action: 'update_attributes',
                    id: $scope.grTableImport.id,
                    post: data
                }).then(function(r) {
                    if(r.response){
                        $scope.grTableImport.grTable.reloadData();
                        $scope.modal.forceClose();
                    }else{
                        alert.show(r.status, r.message);
                    }
                },function(r) {
                    alert.$message.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.changePassword = function(){
            var modal = $grModal.new({
                name: 'change-password',
                title: 'TITLE.PASSWORD.CHANGE',
                size: 'md',
                model: GRIFFO.modulePath + 'view/admin/modal/change-password.php',
                define: {
                    id: $scope.grTableImport.id
                },
                buttons: [{
                    type: 'success',
                    label: 'BUTTON.SAVE',
                    onClick: function($scope, $element, controller){
                        $scope.form.submit();
                    }
                }, {
                    type: 'danger',
                    label: 'BUTTON.CLOSE',
                    onClick: function($scope, $element, controller){
                        controller.close();
                    }
                }]
            });
            modal.open();
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

(function(){
    angular.module('adminApp').controller('changePassword2Ctrl', ['$scope', '$timeout', '$grRestful', '$grAlert', function($scope, $timeout, $grRestful, $grAlert){
        var alert = $grAlert.new();
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'password',
                    type: 'password',
                    label: 'LABEL.PASSWORD',
                    columns: 6,
                    attr: {
                        required: true,
                        ngMinlength: 5,
                        ngMaxlength: 16
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PASSWORD',
                        minlength: 'FORM.MESSAGE.MINLENGTH.PASSWORD[[5]]',
                        maxlength: 'FORM.MESSAGE.MAXLENGTH.PASSWORD[[16]]'
                    }
                }
            ],
            submit: function(data){
                var newData = {
                    'password': data.password
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
}());
