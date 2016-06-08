'use strict';
(function(){
    angular.module('adminApp').controller('toolsCtrl', ['$rootScope', '$scope', '$grModal', '$grAlert', '$grRestful', function($rootScope, $scope, $grModal, $grAlert, $grRestful){
        var alert = $grAlert.new();
        $scope.running = false;
        $scope.generateSitemaps = function(){
            $scope.running = true;
            $grModal.confirm("CONFIRM.ACTION", function(){
                alert.show('loading', 'ALERT.GENERATING.SITEMAPS', 0);
                $grRestful.find({
                    module: 'sitemap',
                    action: 'get'
                }).then(function(r){
                    if(r.response){
                        alert.show('success', 'ALERT.SUCCESS.GENERATE.SITEMAPS');
                    }else{
                        alert.show('danger', r.message);
                    }
                }, function(e){
                    alert.show('danger', 'ERROR.FATAL');
                }).finally(function(){
                    $scope.running = false;
                });
            }, function(){
                $scope.running = false;
            });
        }
    }]);
}());
