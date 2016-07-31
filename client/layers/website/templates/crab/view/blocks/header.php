<header class="header" ng-cloak>
    <nav class="navbar navbar-default">
        <div class="container" ng-show="GRIFFO.viewPort.width >= 768">
            <div class="col-xs-2">
                <div class="logo" gr-autoscale="'1:1'">
                    <a ng-href="{{GRIFFO.curAlias}}">
                        <img class="img-responsive" ng-src="{{GRIFFO.baseUrl + GRIFFO.templatePath}}image/logo.svg" />
                    </a>
                </div>
            </div>
            <div class="col-xs-10">
                <div class="navbar-text-header">
                    <h1 class="text-primary"><strong>Delivery Online</strong></h1>
                    <p class="text-primary" ng-repeat="shop in gr.shops" ng-if="shop.phone1">
                        <strong ng-if="gr.shops.length > 1">{{shop.name}}</strong><span ng-if="gr.shops.length > 1"> </span><i class="fa fa-fw fa-phone"></i> {{shop.phone1 | phone}}
                    </p>
                </div>
                <div class="contact-wrapper pull-right" ng-show="GRIFFO.viewPort.bs !== 'sm'">
                    <a ng-repeat="contact in gr.contactHeader" ng-attr-title="{{contact.title}}" ng-href="{{contact.link}}" target="_blank" ng-class="contact.class"></a>
                </div>
            </div>
        </div>
        <div class="container" ng-show="GRIFFO.viewPort.width < 768">
            <div class="logo" gr-autoscale="'1:1'">
                <a ng-href="{{GRIFFO.curAlias}}">
                    <img class="img-responsive" ng-src="{{GRIFFO.baseUrl + GRIFFO.templatePath}}image/logo.svg" />
                </a>
            </div>
            <div class="navbar-text-header">
                <p class="text-primary" ng-repeat="shop in gr.shops" ng-if="shop.phone1">
                    <strong ng-if="gr.shops.length > 1">{{shop.name}}</strong><span ng-if="gr.shops.length > 1"> </span><i class="fa fa-fw fa-phone"></i> {{shop.phone1 | phone}}
                </p>
            </div>
        </div>
    </nav>
    <div class="banner hidden-xs" ng-cloak>
        <gr-carousel autoplay="4000" ng-if="gr.banners.length > 0">
            <gr-carousel-item ng-repeat="banner in gr.banners" img-liquid>
                <img ng-src="{{GRIFFO.uploadPath + banner.picture}}"/>
            </gr-carousel-item>
        </gr-carousel>
        <div class="loader" ng-if="gr.banners.length === 0" style="height: 100%">
            <div class="loader-inner">
                <i class="fa fa-refresh fa-2x fa-spin"></i>
            </div>
        </div>
    </div>
    <div class="user-toolbar" ng-if="GRIFFO.user" gr-affix offset-top="{xs: 87,sm: 367, md: 367, lg: 367}" ng-cloak>
        <div class="container">
            <a class="btn btn-default pull-left" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs'}" ng-href="{{GRIFFO.curAlias}}" ng-if="GRIFFO.filter.page.url !== 'home/'">
                <!-- <i class="fa fa-fw fa-home"></i> -->
                <i class="fa fa-fw fa-list"></i>
                <span ng-show="GRIFFO.viewPort.bs !== 'xs'">Catálogo</span>
            </a>
            <button class="btn btn-default pull-left" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs'}" title="Voltar para o topo" ng-click="scrollTop()" ng-if="affixed">
                <i class="fa fa-fw fa-arrow-up"></i>
                <span ng-show="GRIFFO.viewPort.bs !== 'xs'">Voltar para o topo</span>
            </button>
            <a ng-href="{{GRIFFO.curAlias}}/user/" class="btn btn-default" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs'}" title="Painel do cliente" ng-if="GRIFFO.filter.page.url !== 'user/'">
                <i class="fa fa-fw fa-user"></i>
                <span ng-show="GRIFFO.viewPort.bs !== 'xs'">{{GRIFFO.user.name.split(' ')[0]}}</span>
            </a>
            <div class="cart">
                <button class="btn btn-default btn-cart" title="Carrinho de pedidos" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs', 'active': gr.cart.opened, 'btn-primary': gr.cart.length() > 0}" popover-placement="{{gr.cart.placement}}" popover-trigger="{{gr.cart.trigger}}" popover-animation="{{gr.cart.animation}}" popover-template="gr.cart.templateUrl" ng-click="gr.cart.opened = !gr.cart.opened">
                    <i class="fa fa-fw fa-shopping-cart"></i>
                    <small class="btn-cart-total" ng-if="gr.cart.length() > 0 && GRIFFO.viewPort.bs !== 'xs'">{{gr.cart.total() | currency}}</small>
                </button>
            </div>
            <button ng-click="gr.cart.submit()" class="btn btn-success" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs'}" title="Finalizar pedido" ng-if="gr.cart.length() > 0 && GRIFFO.filter.page.url !== 'finish/'">
                <i class="fa fa-fw fa-check"></i>
                <span ng-show="GRIFFO.viewPort.bs !== 'xs'">Finalizar pedido</span>
            </button>
            <a class="btn btn-danger" ng-class="{'btn-sm': GRIFFO.viewPort.bs === 'xs'}" title="Sair" ng-click="logout()" ng-disabled="logouting" ng-if="GRIFFO.user.id">
                <i class="fa fa-fw fa-sign-out"></i>
            </a>
        </div>
    </div>
    <div class="page-header" ng-if="gr.title" ng-cloak>
        <div class="container">
            <div ng-class="gr.title.wrapperClass">
                <h1>
                   <i ng-class="gr.title.icon" ng-if="gr.title.icon"></i>
                   {{gr.title.text}}
                </h1>
            </div>
        </div>
    </div>
</header>
