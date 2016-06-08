'use strict';
(function(){
    angular.module('adminApp').controller('editInfoCtrl', ['$rootScope', '$scope', '$timeout', '$grRestful', '$grAlert', '$http', function($rootScope, $scope, $timeout, $grRestful, $grAlert, $http){
        var alert = $grAlert.new($scope.modal.element);
        $grRestful.find({
            module: 'customer',
            action: 'get',
            id: $rootScope.GRIFFO.user.id
        }).then(function(r){
            if(r.response){
                if(r.response.map){
                    r.response.map = JSON.parse(r.response.map);
                }else{
                    r.response.map = {
                        coords: '',
                        zoom: 15
                    }
                }
                $scope.formSettings.data = r.response;
            }
        });
        $scope.formSettings = {
            data: {},
            schema: [
                {
                    property: 'logo',
                    type: 'filemanager',
                    label: 'LABEL.LOGO',
                    attr: {
                        label: {
                            icon: 'fa fa-fw fa-image',
                            select: 'BUTTON.SELECT.LOGO',
                            change: 'BUTTON.CHANGE.LOGO'
                        },
                        filter: 'image',
                        multiple: false,
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.LOGO'
                    }
                }, {
                    property: 'name',
                    type: 'text',
                    label: 'LABEL.NAME',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.NAME'
                    }
                }, {
                    property: 'address',
                    type: 'text',
                    label: 'LABEL.ADDRESS',
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.ADDRESS'
                    }
                }, {
                    property: 'map.zoom',
                    type: 'number',
                    label: 'LABEL.MAP.ZOOM',
                    attr: {
                        min: 1,
                        max: 20
                    }
                }, {
                    property: 'phone[0]',
                    type: 'phone',
                    label: 'LABEL.PHONE',
                    columns: 6,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.PHONE'
                    }
                }, {
                    property: 'phone[1]',
                    type: 'phone',
                    label: 'LABEL.PHONE2',
                    columns: 6
                }, {
                    property: 'phone[2]',
                    type: 'mobilephone',
                    label: 'LABEL.PHONE.MOBILE',
                    columns: 6
                }, {
                    property: 'email[0]',
                    type: 'email',
                    label: 'LABEL.EMAIL',
                    columns: 6,
                    attr: {
                        required: true
                    },
                    msgs: {
                        required: 'FORM.MESSAGE.REQUIRED.EMAIL',
                        email: 'FORM.MESSAGE.EMAIL.EMAIL'
                    }
                }, {
                    property: 'social.facebook',
                    type: 'text',
                    label: 'Facebook',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-facebook'
                        }
                    ]
                }, {
                    property: 'social.twitter',
                    type: 'text',
                    label: 'Twitter',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-twitter'
                        }
                    ]
                }, {
                    property: 'social.instagram',
                    type: 'text',
                    label: 'Instagram',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-instagram'
                        }
                    ]
                }, {
                    property: 'social.youtube',
                    type: 'text',
                    label: 'Youtube',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-youtube'
                        }
                    ]
                }, {
                    property: 'social.googlePlus',
                    type: 'text',
                    label: 'Google+',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-google-plus'
                        }
                    ]
                }, {
                    property: 'social.github',
                    type: 'text',
                    label: 'Github',
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-github'
                        }
                    ]
                }, {
                    property: 'social.whatsapp',
                    type: 'text',
                    label: 'Whatsapp',
                    placeholder: '(xx) xxxxx-xxxx',
                    attr: {
                        'ui-br-phone-number': true
                    },
                    addons: [
                        {
                            before: true,
                            icon: 'fa fa-fw fa-whatsapp'
                        }
                    ]
                }
            ],
            submit: function(data){
                if($scope.form.$invalid) return;
                var update = function(){
                    var newData = angular.copy(data);
                    newData.map = JSON.stringify(newData.map);
                    angular.forEach(newData.social, function(social, key){
                        if(!social){
                            delete newData.social[key];
                        }
                    });
                    $grRestful.update({
                        module: 'customer',
                        action: 'update_attributes',
                        post: newData
                    }).then(function(r){
                        if(r.response){
                            $scope.modal.close();
                        }
                        alert.show(r.status, r.message);
                    },function() {
                        alert.show('danger', 'ERROR.FATAL');
                    });
                };
                $http.get('http://maps.google.com/maps/api/geocode/json?address=' + URLify(data.address))
                    .then(function(r){
                        if(r.data.results.length > 0){
                            data.map.coords = r.data.results[0].geometry.location.lat + ',' + r.data.results[0].geometry.location.lng;
                            data.map.zoom = data.map.zoom || 15;
                            update();
                        }else{
                            var coord = prompt('A Localização do endereço não foi encontrada pelo Google Maps, digite respectivamente a latitude e longitude separandos por "," do endereço que deseja mostrar no mapa:', data.map.coords);
                            data.map = {
                                coords: coord || '',
                                zoom: 15
                            };
                        }
                        update();
                    });
            }
        };
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
