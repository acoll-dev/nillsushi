'use strict';
(function(){
    angular.module('gr.restful', ['gr.restful.provider', 'ngResource']);
}());
(function(){
    angular.module('gr.restful.provider', [])
        .provider('$grRestful', function(){
            var setup,
                $injector,
                $resource,
                requestUrl,
                requestUrlModel,
                request,
                getToken,
                checkSlashes;
            this.config = function(config){
                if(config.url){
                    requestUrl = config.url;
                }
            };
            setup = function (injector) {
                $injector = injector;
                $resource = $injector.get('$resource');
                requestUrl = requestUrl ? requestUrl : GRIFFO.baseUrl + GRIFFO.restPath,
                requestUrlModel = requestUrl + ':module/:action/:onelevel/:id';
                request = $resource(requestUrlModel + '/:params', {}, {
                    'auth': {
                        method: 'POST',
                        isArray: false,
                        params: {
                            module: 'authentication'
                        }
                    },
                    'update': {
                        method: 'PUT'
                    },
                    'post': {
                        method: 'POST',
                        isArray: false,
                        params: {
                            module: 'gallery'
                        }
                    }
                });
                getToken = function(data){
                    if(data){
                        var dataAux = [];
                        angular.forEach(data, function(d, i){
                            if(angular.isDefined(d) && d !== null && d.name && d.value){
                                dataAux.push(d);
                            }else{
                                dataAux.push({
                                    name: i,
                                    value: d || ''
                                });
                            }
                        });
                        data = dataAux;
                        data.push({
                            name: 'token',
                            value: GRIFFO.user.token || ''
                        });
                    }else{
                        data = {};
                    }
                    return data;
                };
            };
            this.$get = ['$injector',
                function (injector) {
                    setup(injector);
                    return {
                        'find': function (o) {
                            var obj = {
                                    'module': o.module,
                                    'action': o.action,
                                    'id': o.id || undefined,
                                    'onelevel': angular.isDefined(o.onelevel) ? o.onelevel : false
                                };
                            if(o.params && (angular.isObject(o.params) || (angular.isString(o.params) && o.params.indexOf('/') > -1))){
                                var paramUrl = '',
                                    count = 1;
                                if(angular.isString(o.params) && o.params.indexOf('/') > -1){
                                    var params = o.params.split('/');
                                    angular.forEach(params, function(p){
                                        if(p){
                                            paramUrl += '/:param' + count;
                                            obj['param' + count] = p;
                                            count ++;
                                        }
                                    });
                                }else if(angular.isObject(o.params)){
                                    angular.forEach(o.params, function(p, id){
                                        if(p){
                                            paramUrl += '/:param' + count;
                                            obj['param' + count] = id + '=' + p;
                                            count ++;
                                        }
                                    });
                                }
                                return $resource(requestUrlModel + paramUrl).get(obj).$promise.then();
                            }else{
                                obj.params = o.params || undefined;
                                return request.get(obj).$promise.then();
                            }
                        },
                        'create': function (o) {
                            o.post = getToken(o.post);
                            return request.save({
                                'module': o.module,
                                'action': o.action
                            }, o.post).$promise.then();
                        },
                        'delete': function (o) {
                            return request.delete({
                                'module': o.module,
                                'id': o.id
                            }).$promise.then();
                        },
                        'update': function (o) {
                            o.post = getToken(o.post);
                            return request.update({
                                'module': o.module,
                                'action': o.action,
                                'id': o.id
                            }, o.post).$promise.then();
                        },
                        'auth': function (o) {
                            o.post = getToken(o.post);
                            return request.auth({
                                'action': o.action
                            }, o.post).$promise.then();
                        },
                        'fileManager': function (o) {
                            o.post = getToken(o.post);
                            return request.post({
                                'action': o.action
                            }, o.post).$promise.then();
                        }
                    };
            }];
        });
}());
