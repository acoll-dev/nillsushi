'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $scope.pages = {};
        $grRestful.find({
            'module': 'page',
            'action': 'select_config'
        }).then(function(r){
            if(r.response){
                $scope.pages = r.response;
            }
        });
        $grRestful.find({
            'module': 'page',
            'action': 'get_config'
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
                    type: 'multiple',
                    fields: [
                        {
                            property: 'url',
                            type: 'text',
                            label: 'LABEL.URL',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.URL'
                            }
                        },{
                            property: 'filtermodel',
                            type: 'text',
                            label: 'LABEL.MODEL.FILTER',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.MODEL.FILTER'
                            }
                        },{
                            property: 'custom',
                            type: 'select',
                            label: 'LABEL.DEFAULT.PAGE',
                            list: 'page.value as page.label for page in pages',
                            attr: {
                                required: true
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.DEFAULT.PAGE'
                            }
                        }
                    ],
                    columns: 4
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'page',
                    action: 'update_config',
                    post: data
                }).then(function (r) {
                    if(r.response){ $scope.form.updateDefaults(); $scope.form.reset(); }
                    alert.show(r.status, r.message);
                }, function (error) {
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $scope.bindForm = function(form){ $scope.form = form; }
        $scope.$watch('form', function(form){
            $scope.$parent.form = form;
        });
    }]);
}());
