<?php
    global $PAGE;
    $PAGE->setTag(array(
        "css" => array(
            PATH_TEMPLATE . "css/bootstrap.css",
            PATH_TEMPLATE . "css/main.css",
            PATH_TEMPLATE . "css/admin.css",

            PATH_LIBRARIES . "client/vendor/angular-ui-tree/dist/angular-ui-tree.min.css",
            PATH_LIBRARIES . "client/vendor/angular-toggle-switch/angular-toggle-switch.css",
            PATH_LIBRARIES . "client/vendor/fancybox/source/jquery.fancybox.css",

            /* CRAB ORDER DEPENDENCIES */
            PATH_LIBRARIES . "client/vendor/fullcalendar/dist/fullcalendar.css"
        ),
        "htmlAttr" => array(
            "ng-app" => "adminApp",
            "ng-controller"=>"adminCtrl"
        ),
        "bodyAttr" => array(
            "ng-class" => "{'open-left': sidebarOpen === 'left', 'open-right': sidebarOpen === 'right', 'open-both': sidebarOpen === 'both', 'open-none': sidebarOpen === 'none'}",
            "class" => "{{user.theme ? 'theme-' + user.theme : 'theme-dark'}}"
        )
    ));
    $PAGE->getHeader();
?>

<nav class="navbar" ng-cloak>
    <div class="container-fluid">
        <div class="navbar-left">
            <button type="button" class="btn btn-link-full btn-menu" sidebar-button ng-click="sidebarAction('left')" ng-class="{'active': sidebarOpen === 'left' || sidebarOpen === 'both'}">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>
        <a class="btn btn-full btn-brand" href="#">
            <i class="acoll-griffo"></i>
        </a>
        <div class="navbar-center">
            <div class="pull-left navbar-center-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-default sidebar-menu-layer-display inactive" data-toggle="dropdown">{{curLayer.label || '-'}}</button>
                    <a class="btn btn-default" ng-href="{{GRIFFO.curAlias + '/' + GRIFFO.filter.layer}}/layer/config/" data-ng-if="GRIFFO.user.profile.accessControl.layer.resources.config.status"><i class="fa fa-fw fa-cogs"></i></a>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" ng-if="layers.length > 0">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" ng-if="layers.length > 0">
                        <li class="active" ng-if="curLayer">
                            <span class="text-muted">{{curLayer.label}}</span>
                        </li>
                        <li class="divider" ng-if="curLayer"></li>
                        <li ng-repeat="layer in layers">
                            <a ng-href="{{layer.url}}" ng-if="!layer.active && !layer.divider" ng-click="changeLayer(layer.name)">{{layer.label}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pull-left navbar-center-group" data-ng-if="GRIFFO.user.profile.name === 'admin'">
                <button class="btn btn-default" data-ng-attr-title="{{'BUTTON.TOOLS' | grTranslate}}" data-ng-click="openTools()"><i class="fa fa-fw fa-wrench"></i><span ng-show="GRIFFO.viewPort.bs !== 'xs'"> <gr-translate>BUTTON.TOOLS</gr-translate></span></button>
            </div>
            <div class="pull-right navbar-center-group">
                <gr-file-manager-button label="('BUTTON.FILEMANAGER' | grTranslate)" label-icon="'fa fa-fw fa-folder'" class="btn-file-manager" allow-multiple="true"></gr-file-manager-button>
                <button type="button" class="btn btn-default btn-user" user-button uib-popover-template="userPopover.templateUrl" popover-placement="{{userPopover.placement}}" popover-trigger="userPopover.trigger" popover-is-open="userPopover.isOpened" ng-click="userPopover.isOpened = !userPopover.isOpened">
                    <span class="visible-md visible-lg">
                        <i class="fa fa-fw fa-user"></i>
                        {{user.nickname}}
                    </span>
                    <span class="visible-xs visible-sm">
                        <i class="fa fa-fw fa-user"></i>
                    </span>
                </button>
            </div>
        </div>
<!--
        <div class="navbar-right">
            <button type="button" class="btn btn-link-full btn-notification" sidebar-button ng-click="sidebarAction('right')" ng-class="{'active': sidebarOpen === 'right || sidebarOpen === 'both'}">
                <i class="fa fa-fw fa-bell"></i>
            </button>
        </div>
-->
    </div>
</nav>
<div class="wrapper">
    <div class="wrapper-inner">
        <nav class="sidebar sidebar-left sidebar-menu">
            <div class="sidebar-menu-client text-center">
                <i class="acoll-griffo"></i>
            </div>
            <form class="sidebar-menu-filter" ng-submit="filter()">
                <div class="input-group">
                    <input type="text" ng-model="menuFilter" class="form-control" placeholder="{{'LABEL.SEARCH.MENU' | grTranslate}}" />
                    <span class="input-group-btn">
                        <button type="submit" class="btn"><i class="fa fa-fw fa-search"></i></button>
                    </span>
                </div>
            </form>
            <div class="sidebar-menu-content angular-ui-tree-{{draggable ? 'on' : 'off'}}" ng-if="menu.length > 0" ui-tree="dragSettings" drag-enabled="draggable" data-lock-x="true">
                <ul class="sidebar-menu-list-group sidebar-menu-list-group-parent list-group" ui-tree-nodes ng-model="menu">
                    <li class="list-group-item" ng-class="{'open': (menu.child.length > 0 && menu.open) && !draggable, 'active': (menu.child.length <= 0 && menu.active) && !draggable}" ng-init="menu.open = menu.active ? true : false" ng-repeat="menu in menu" ui-tree-node>
                        <div class="list-group-item-label">
                            <div class="list-group-item-icon">
                                <i ng-class="menu.icon"></i>
                            </div>
                            <div class="list-group-item-text">
                                <a ng-href="{{menu.href}}" ng-click="menuCheck(menu, $event)">
                                    <div class="list-group-item-text-inner">
                                        <div class="list-group-item-text-content" gr-translate>{{menu.label}}</div>
                                        <div class="list-group-item-text-arrow">
                                            <i class="fa fa-fw" ng-class="{'fa-angle-right':(menu.child.length <= 0 && !draggable), 'fa-plus-square-o':(menu.child.length > 0 && !menu.open) && !draggable, 'fa-minus-square-o':(menu.child.length > 0 && menu.open) && !draggable, 'fa-bars draggable': draggable}" ui-tree-handle></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <ol class="sidebar-menu-list-group list-group" ng-if="menu.child.length> 0" ng-show="!draggable" ng-include="'menu_renderer.html'"></ol>
                    </li>
                </ul>
                <script type="text/ng-template" id="menu_renderer.html">
                    <li class="list-group-item" ng-class="{'open': (menu.child.length > 0 && menu.open) && !draggable, 'active': (menu.child.length <= 0 && menu.active) && !draggable}" ng-init="menu.open = menu.active ? true : false" ng-repeat="menu in menu.child">
                        <div class="list-group-item-label">
                            <div class="list-group-item-icon">
                                <i ng-class="menu.icon"></i>
                            </div>
                            <div class="list-group-item-text">
                                <a ng-href="{{menu.href}}" ng-click="menuCheck(menu, $event)">
                                    <div class="list-group-item-text-inner">
                                        <div class="list-group-item-text-content" gr-translate>{{menu.label}}</div>
                                        <div class="list-group-item-text-arrow">
                                            <i class="fa fa-fw" ng-class="{'fa-angle-right':(menu.child.length <= 0 && !draggable), 'fa-plus-square-o':(menu.child.length > 0 && !menu.open) && !draggable, 'fa-minus-square-o':(menu.child.length > 0 && menu.open) && !draggable, 'fa-bars': draggable}" ui-tree-handle></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <ol class="sidebar-menu-list-group list-group" ng-if="menu.child.length> 0" ng-include="'menu_renderer.html'"></ol>
                    </li>
                </script>
            </div>
        </nav>
        <section class="container">
            <div class="container-inner" ng-include="GRIFFO.curView"></div>
        </section>
        <!-- <section class="sidebar sidebar-right sidebar-notification"></section> -->
    </div>
</div>

<script>
    WebFontConfig = {
        google: {
            families: ['Open+Sans:400,700,300:latin']
        }
    };
</script>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>

<?php
    $PAGE->setTag(array(
        "js"=>array(

            /* TEMPLATE SCRIPTS */

            PATH_TEMPLATE . "js/adminApp.js",
            PATH_TEMPLATE . "js/controllers/adminCtrl.js",
            PATH_TEMPLATE . "js/controllers/changePasswordCtrl.js",
            PATH_TEMPLATE . "js/controllers/editInfoCtrl.js",
            PATH_TEMPLATE . "js/controllers/editProfileCtrl.js",
            PATH_TEMPLATE . "js/controllers/toolsCtrl.js",
            PATH_TEMPLATE . "js/directives/wrapper.js",
            PATH_TEMPLATE . "js/directives/sidebarButton.js",
            PATH_TEMPLATE . "js/directives/sidebarMenu.js",
            PATH_TEMPLATE . "js/directives/userButton.js",
            PATH_TEMPLATE . "js/directives/viewFooter.js",
            PATH_TEMPLATE . "js/directives/fancyboxDrt.js",

            /* TOOLS */

            PATH_LIBRARIES . "client/vendor/imgLiquid/js/imgLiquid.js",
            PATH_LIBRARIES . "client/vendor/angular-ui-tree/dist/angular-ui-tree.js",
            PATH_LIBRARIES . "client/vendor/angular-toggle-switch/angular-toggle-switch.js",
            PATH_LIBRARIES . "client/vendor/urlify/urlify.js",
            PATH_LIBRARIES . "client/vendor/fancybox/source/jquery.fancybox.pack.js",

            /* CRAB ORDER DEPENDENCIES */
            PATH_LIBRARIES . "client/vendor/moment/min/moment.min.js",
            PATH_LIBRARIES . "client/vendor/angular-ui-calendar/src/calendar.js",
            PATH_LIBRARIES . "client/vendor/fullcalendar/dist/fullcalendar.min.js",
            PATH_LIBRARIES . "client/vendor/fullcalendar/dist/gcal.js",
            PATH_LIBRARIES . "client/vendor/fullcalendar/dist/lang-all.js"
        )//,
//        "analyticsCode"=>"UA-XXXXX-X"
    ),'footer');
    $PAGE->getFooter();
?>
