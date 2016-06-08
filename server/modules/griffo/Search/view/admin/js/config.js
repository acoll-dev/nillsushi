'use strict';
(function(){
    angular.module('adminApp').controller('formCtrl', ['$scope', '$filter', '$window', '$timeout', '$grRestful', '$grAlert', function($scope, $filter, $window, $timeout, $grRestful, $grAlert) {
        var alert = $grAlert.new();
        $grRestful.find({
            'module': 'search',
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
                            property: 'filtermodel',
                            type: 'text',
                            label: 'LABEL.MODEL.FILTER',
                            attr: {
                                required: true,
                                ngMaxlength: 255
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.MODEL.FILTER',
                                maxlength: 'FORM.MESSAGE.MAXLENGTH.MODEL.FILTER[[255]]'
                            }
                        }, {
                            property: 'url',
                            type: 'text',
                            label: 'LABEL.URL',
                            attr: {
                                required: true,
                                ngMaxlength: 255
                            },
                            msgs: {
                                required: 'FORM.MESSAGE.REQUIRED.URL',
                                maxlength: 'FORM.MESSAGE.MAXLENGTH.URL[[255]]'
                            }
                        }
                    ],
                    columns: 6
                }
            ],
            submit: function(data) {
                if($scope.form.$invalid) return;
                $grRestful.update({
                    module: 'search',
                    action: 'config',
                    post: data
                }).then(function(r){
                    if(r.response){ $scope.form.updateDefaults(); $scope.form.reset(); }
                    alert.show(r.status, r.message);
                }, function(r){
                    alert.show('danger', 'ERROR.FATAL');
                });
            }
        };
        $timeout(function(){
            $scope.$parent.form = $scope.form;
        });
    }]);
}());
