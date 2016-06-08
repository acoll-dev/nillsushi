'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$locale', '$grRestful', '$grAlert', '$cidadeEstado', function($scope, $filter, $window, $timeout, $locale, $grRestful, $grAlert, $cidadeEstado) {
        var alert = $grAlert.new();

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

        $grRestful.find({
            module: 'shop',
            action: 'get',
            id: $scope.grTableImport.id
        }).then(function(r){
            if(r.response){
                $scope.formSettings.data = r.response;
                $scope.modal.ready();
                $timeout(function(){
                    $scope.form.updateDefaults();
                });
            }
        });

        angular.forEach($cidadeEstado.get.estados(), function(e){
            $scope.states.push({
                value: e[0],
                label: e[1]
            });
        });

        $scope.formSettings = {
            data: {},
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
                    property: 'address',
                    type: 'text',
                    label: 'LABEL.ADDRESS',
                    columns: 6,
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
                    columns: 3,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NUMBER',
                        number: 'FORM.MESSAGE.ONLY.NUMBER'
                    }
                }, {
                    property: 'complement',
                    type: 'text',
                    label: 'LABEL.ADDRESS.COMPLEMENT',
                    columns: 3
                }, {
                    property: 'district',
                    type: 'text',
                    label: 'LABEL.DISTRICT',
                    columns: 4,
                    attr: {
                        required: true
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
                        required: true
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
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.STATE'
                    }
                }, {
                    type: 'hr'
                }, {
                    property: 'phone1',
                    type: 'phone',
                    label: 'LABEL.PHONE1',
                    columns: 4,
                    msgs: {
                        mask: 'FORM.MESSAGE.MASK.PHONE'
                    }
                }, {
                    property: 'phone2',
                    type: 'phone',
                    label: 'LABEL.PHONE2',
                    columns: 4,
                    msgs: {
                        mask: 'FORM.MESSAGE.MASK.PHONE'
                    }
                }, {
                    property: 'phone3',
                    type: 'phone',
                    label: 'LABEL.PHONE3',
                    columns: 4,
                    msgs: {
                        mask: 'FORM.MESSAGE.MASK.PHONE'
                    }
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'shop',
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
