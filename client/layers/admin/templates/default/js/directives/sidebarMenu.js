'use strict';
(function(){
    angular.module('adminApp').directive('sidebarMenu', ['$rootScope', '$window', '$timeout', '$grRestful', function ($rootScope, $window, $timeout, $grRestful) {
        var scope,
            element,
            wnd,
            setMenuCtrl = function(){
                ajustSizes();
                defineDrag();
            },
            ajustSizes = function(){
                $timeout(function(){
                    var sidebarMenu = angular.element('.sidebar-menu'),
                        sidebarMenuClient = sidebarMenu.find('.sidebar-menu-client'),
                        sidebarMenuFilter = sidebarMenu.find('.sidebar-menu-filter'),
                        sidebarMenuContent = sidebarMenu.find('.sidebar-menu-content'),
                        h = sidebarMenu.outerHeight(),
                        cH = sidebarMenuClient.outerHeight() + parseInt(sidebarMenuClient.css('marginTop')) + parseInt(sidebarMenuClient.css('marginBottom')) + parseInt(sidebarMenuFilter.outerHeight());
                    if($rootScope.GRIFFO.viewPort.bs !== 'xs'){
                        h -=  23;
                    }
                    sidebarMenuContent.css({
                        height: (h-cH) + 'px',
                        maxHeight: (h-cH) + 'px'
                    });
                });
            },
            defineDrag = function(){
                scope.dragSettings = {
                    dragStart: function(event) {},
                    dragStop: function(event) {},
                    dropped: function(event) {
                        var order = [];
                        angular.forEach(scope.menu, function(menu){
                            order.push(menu.idmodule);
                        });
                        $grRestful.update({
                            module: 'user',
                            action: 'update_attributes',
                            id: GRIFFO.user.id,
                            post: {
                                menuorder: order
                            }
                        });
                        setMenuCtrl();
                    }
                }
            },
            generateMenu = function(menu, accessControl){
                var arr = menu.child || menu,
                    _menu = [],
                    _this = this,
                    /*length = 0, */
                    length2;
                angular.forEach(arr, function(m, id){
                    if(!accessControl[m.name] || (accessControl[m.name] && accessControl[m.name].status)){
                        length2 = 0;
                        if(menu.child){
                            m.label = 'MODULE.' + menu.name.toUpperCase() + '.MENU.' + m.name.toUpperCase();
                        }else{
                            m.label = 'MODULE.' + m.name.toUpperCase() + '.NAME';
                        }
                        while(m.label.indexOf(' ') > -1){
                            m.label = m.label.replace(' ','');
                        }
                        _menu[id] = m;
                        // if (length > 0) {
                            var resources = accessControl[m.name] ? accessControl[m.name].resources : undefined;
                            _menu[id].child = generateMenu(_menu[id], resources);
                        // }
                        angular.forEach(_menu[id].child, function(){ length2++; });
                        _menu[id].open = false;
                        _menu[id].child.length = length2;
                        // length++;
                    }
                });
                if(!_menu.length){
                    _menu.length = length;
                }
                return _menu;
            },
            setFilter = function(){
                if(typeof scope.menuFilter === 'undefined'){ setMenuCtrl(); return false; }
                var filter = scope.menuFilter;
                $grRestful.find({
                    module: 'search',
                    action: 'get',
                    params: 'query=' + (filter ? filter : '')
                }).then(function(r){
                    if(r.response && angular.isObject(r.response)){
                        $rootScope.GRIFFO.menu = r.response;
                        $timeout(function(){
                            wnd.trigger('resize2');
                            setMenuCtrl();
                        }, 200);
                    }
                });
            },
            menuCheck = function(menu, $event){
                var target = angular.element($event.target),
                    tParents = target.parents('.list-group-item');
                if(menu.child.length > 0){
                    $event.preventDefault();
                }
                menu.open = !menu.open;
                if(menu.open){
                    var el = tParents.eq(0),
                        parent = element.children('.sidebar-menu-content'),
                        pH = parent.outerHeight(),
                        pO = parent.offset().top,
                        pS = parent.scrollTop() - el.outerHeight(),
                        eH,
                        eO = el.offset().top - 1;
                    $timeout(function(){
                        eH = el.outerHeight();
                        if(((eO - pO) + eH) > pH && eH < pH){
                            parent.scrollTop(pS + eH);
                            if(el.offset().top < pO){
                                scrollToBeforeEl(parent, el);
                            }
                        }else if(eH >= pH){
                            scrollToBeforeEl(parent, el);
                        }
                    });
                }
                wnd.trigger('resize2');
                function scrollToBeforeEl(parent, el){
                    var h = 0;
                    angular.forEach(parent.children('.sidebar-menu-list-group').children('.list-group-item'), function(parent, id){
                        var _parent = angular.element(parent);
                        if(id < el.index()){
                            h += _parent.height() + 1;
                        }
                    });
                    parent.scrollTop(h);
                };
            };
        return {
            restrict: 'C',
            scope: true,
            link: function($scope, $element, attrs){
                scope = $scope;
                element = $element;
                wnd = angular.element($window);
                $rootScope.GRIFFO.mainMenuDraggable = false;
                $scope.filter = setFilter;
                $scope.menuCheck = menuCheck;
                $rootScope.$watch('GRIFFO.mainMenuDraggable', function(v){
                    scope.draggable = v;
                });
                $rootScope.$watch('GRIFFO.menu', genMenu);
                $rootScope.$on('accessControlChanged', function(){ genMenu($rootScope.GRIFFO.menu); });
                function genMenu(newMenu){
                    if(newMenu && angular.isObject(newMenu)){
                        $scope.menu = generateMenu(newMenu, $rootScope.GRIFFO.user.profile.accessControl);
                    }else{
                        $scope.menu = generateMenu({});
                    }
                    $timeout(function(){
                        var menuActive = element.children('.sidebar-menu-content').children('ul').children('.list-group-item.active');
                        if(menuActive.length === 0){
                            menuActive = element.children('.sidebar-menu-content').children('ul').children('.list-group-item.open');
                        }
                        if(menuActive.length > 0){
                            var offset = menuActive.offset().top - element.children('.sidebar-menu-content').offset().top - 1;
                            element.children('.sidebar-menu-content').scrollTop(offset);
                        }
                    }, 800);
                };
                wnd.on('resize', ajustSizes);
                $timeout(setMenuCtrl, 200);
            }
        };
    }]);
}());
