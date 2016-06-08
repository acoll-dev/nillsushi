<?php
    global $PAGE;
    $PAGE->setTag(array(
        'css' => array(
            PATH_TEMPLATE . 'css/bootstrap.css',
            PATH_TEMPLATE . 'css/main.css',
            PATH_LIBRARIES . 'client/vendor/fancybox/source/jquery.fancybox.css'
        ),
        'htmlAttr' => array(
            'ng-app' => 'mainApp',
            'ng-controller' => 'mainCtrl',
        ),
        'bodyAttr' => array(
            'ng-class' => '{\'home\': GRIFFO.filter.page.url === \'home/\'}'
        )
    ));
    $PAGE->getHeader();
?>

<header>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button-toggle-collapse data-target-id="'navbar-menu-collapse'"></button-toggle-collapse>
                <a class="navbar-brand" href="./">
                    <img class="img-responsive" data-ng-src="{{GRIFFO.uploadPath + GRIFFO.customer.logo}}" />
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li data-ng-class="{'active': menu.active, 'dropdown': menu.child.length > 0, 'open': menu.open}" data-gr-dropdown data-ng-repeat="menu in GRIFFO.menu">
                    <a href="#" data-ng-if="menu.child.length > 0" data-gr-dropdown-toggle>
                        <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
                    </a>
                    <a data-ng-href="{{menu.path}}" data-ng-if="menu.child.length === 0">
                        <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
                    </a>
                    <ul class="dropdown-menu" data-ng-if="menu.child.length > 0" data-gr-dropdown-target>
                        <li data-ng-include="'dropdown/sub-menu.html'" data-ng-class="{'active': menu.active, 'dropdown': menu.child.length > 0, 'open': menu.open}" data-ng-repeat="menu in menu.child" data-gr-dropdown></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="navbar-menu-collapse" class="container">
            <breadcrumb></breadcrumb>
            <ul class="nav navbar-collapse">
                <li data-ng-class="{'active': menu.active, 'dropdown': menu.child.length > 0, 'open': menu.open}" data-gr-dropdown data-ng-repeat="menu in GRIFFO.menu">
                    <a href="#" data-ng-if="menu.child.length > 0" data-gr-dropdown-toggle>
                        <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
                    </a>
                    <a data-ng-href="{{menu.path}}" data-ng-if="menu.child.length === 0">
                        <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
                    </a>
                    <ul class="dropdown-menu" data-ng-if="menu.child.length > 0" data-gr-dropdown-target>
                        <li data-ng-include="'dropdown/sub-menu.html'" data-ng-class="{'active': menu.active, 'dropdown': menu.child.length > 0, 'open': menu.open}" data-ng-repeat="menu in menu.child" data-gr-dropdown></li>
                    </ul>
                </li>
                <ng-include src="'social-li.html'"></ng-include>
            </ul>
        </div>
        <script type="text/ng-template" id="dropdown/sub-menu.html">
            <a href="#" data-ng-if="menu.child.length > 0" data-gr-dropdown-toggle>
                <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
            </a>
            <a data-ng-href="{{menu.path}}" data-ng-if="menu.child.length === 0">
                <i ng-class="menu.icon" ng-if="menu.icon"></i><span ng-if="menu.icon"></span>{{menu.label}}
            </a>
            <ul class="dropdown-menu" data-ng-if="menu.child.length > 0" data-gr-dropdown-target>
                <li data-ng-include="'dropdown/sub-menu.html'" data-ng-class="{'active': menu.active, 'dropdown': menu.child.length > 0, 'open': menu.open}" data-ng-repeat="menu in menu.child" data-gr-dropdown></li>
            </ul>
        </script>
    </nav>
</header>
<banner data-category="GRIFFO.filter.page.fileview"></banner>
<breadcrumb data-ng-if="GRIFFO.viewPort.bs !== 'xs' && GRIFFO.viewPort.bs !== 'sm'"></breadcrumb>
<main data-ng-include="GRIFFO.curView"></main>
<section class="go-back" data-ng-if="!GRIFFO.filter.page.url !== 'home/' && (GRIFFO.filter.category || GRIFFO.filter[GRIFFO.filter.page.fileview])">
    <div class="container">
        <a data-ng-href="{{GRIFFO.filter.page.url}}" class="btn btn-griffo-1" data-ng-if="GRIFFO.filter.page.title"><i class="fa fa-fw fa-lg fa-angle-left"></i> Voltar para {{GRIFFO.filter.page.title}}</a>
    </div>
</section>
<footer>
    <div class="footer-top">
        <div class="social" ng-if="_.size(GRIFFO.customer.social) > 0">
            <ul ng-include="'social-li.html'"></ul>
        </div>
    </div>
    <div class="footer-center container">
        <div class="col-xs-12 col-sm-6">
            <a data-ng-href="./">
                <img data-ng-src="{{GRIFFO.uploadPath + GRIFFO.customer.logo}}" />
            </a>
            <address class="address">
                <dl class="dl-horizontal">
                    <dt>TELEFONE</dt>
                    <dd><a data-ng-href="phoneto:{{GRIFFO.customer.phone[0]}}">{{GRIFFO.customer.phone[0] | phone}}</a><span ng-if="GRIFFO.customer.phone[1]"> / </span><a data-ng-href="phoneto:{{GRIFFO.customer.phone[1]}}" ng-if="GRIFFO.customer.phone[1]">{{GRIFFO.customer.phone[1] | phone}}</a></dd>
                    <dt data-ng-if="GRIFFO.customer.phone[2]">CELULAR</dt>
                    <dd data-ng-if="GRIFFO.customer.phone[2]"><a data-ng-href="phoneto:{{GRIFFO.customer.phone[2]}}">{{GRIFFO.customer.phone[2] | phone}}</a></dd>
                    <dt>ENDEREÇO</dt>
                    <dd>{{GRIFFO.customer.address}}</dd>
                    <dt>E-MAIL</dt>
                    <dd><a data-ng-href="mailto:{{GRIFFO.customer.email[0]}}">{{GRIFFO.customer.email[0]}}</a></dd>
                </dl>
            </address>
        </div>
        <div class="col-xs-12 col-sm-6">
            <map config="{{GRIFFO.customer.map}}" data-ng-if="GRIFFO.customer.map"></map>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <span class="copyright">
                Copyright &copy; {{GRIFFO.curYear}} - Todos os direitos reservados.
            </span>
            <signature class="pull-right" type="dark-grey"></signature>
        </div>
    </div>
</footer>
<script type="text/ng-template" id="social-li.html">
    <li data-ng-if="GRIFFO.customer.social.facebook" class="navbar-social facebook">
        <a data-ng-href="{{GRIFFO.customer.social.facebook}}" target="_blank" title="Facebook">
            <i class="fa fa-fw fa-2x fa-facebook-square"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.twitter" class="navbar-social twitter">
        <a data-ng-href="{{GRIFFO.customer.social.twitter}}" target="_blank" title="Twitter">
            <i class="fa fa-fw fa-2x fa-twitter-square"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.instagram" class="navbar-social instagram">
        <a data-ng-href="{{GRIFFO.customer.social.instagram}}" target="_blank" title="Instagram">
            <i class="fa fa-fw fa-2x fa-instagram"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.youtube" class="navbar-social youtube">
        <a data-ng-href="{{GRIFFO.customer.social.youtube}}" target="_blank" title="Youtube">
            <i class="fa fa-fw fa-2x fa-youtube-play"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.googlePlus" class="navbar-social googlePlus">
        <a data-ng-href="{{GRIFFO.customer.social.googlePlus}}" target="_blank" title="Google+">
            <i class="fa fa-fw fa-2x fa-google-plus-square"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.github" class="navbar-social github">
        <a data-ng-href="{{GRIFFO.customer.social.github}}" target="_blank" title="Github">
            <i class="fa fa-fw fa-2x fa-github-square"></i>
        </a>
    </li>
    <li data-ng-if="GRIFFO.customer.social.whatsapp" class="navbar-social whatsapp">
        <a data-ng-href="phoneto:{{GRIFFO.customer.social.whatsapp}}" title="Whatsapp">
            <i class="fa fa-fw fa-2x fa-whatsapp"></i>
        </a>
    </li>
</script>
<script>
    WebFontConfig = {
        google: {
            families: ['Droid+Sans:400,700,300:latin', 'Cabin::latin']
        }
    };
</script>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>

<?php
    $PAGE->setTag(array(
        'js' => array(

            PATH_LIBRARIES . 'client/vendor/imgLiquid/js/imgLiquid.js',
            PATH_LIBRARIES . 'client/vendor/fancybox/source/jquery.fancybox.pack.js',
            PATH_LIBRARIES . 'client/vendor/angular-simple-logger/dist/angular-simple-logger.js',
            PATH_LIBRARIES . 'client/vendor/angular-google-maps/dist/angular-google-maps.js',

            /* TEMPLATE SCRIPTS */

            PATH_TEMPLATE . 'js/mainApp.js',
            PATH_TEMPLATE . 'js/controllers/mainCtrl.js',
            PATH_TEMPLATE . 'js/directives/buttonToggleCollapseDrt.js',
            PATH_TEMPLATE . 'js/directives/bannerDrt.js',
            PATH_TEMPLATE . 'js/directives/breadcrumbDrt.js',
            PATH_TEMPLATE . 'js/directives/mapDrt.js',
            PATH_TEMPLATE . 'js/directives/highlightDrt.js',
            PATH_TEMPLATE . 'js/directives/categoryListDrt.js',
            PATH_TEMPLATE . 'js/directives/signatureDrt.js',
            // PATH_TEMPLATE.'js/directives/ellipsisDrt.js',
            PATH_TEMPLATE . 'js/directives/imgLiquidDrt.js',
            PATH_TEMPLATE . 'js/directives/fancyboxDrt.js'
        )//,
//        "analyticsCode"=>"UA-XXXXX-X"
    ), 'footer');
    $PAGE->getFooter();
?>
