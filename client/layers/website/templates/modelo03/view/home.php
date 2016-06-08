<div class="block">
    <div class="container">
        <div class="highlight-wrapper">
            <div class="col-xs-12 col-sm-4 highlight" data-ng-repeat="feature in GRIFFO.config.page.home.feature">
                <div class="highlight-inner">
                    <i data-ng-class="feature.icon" data-ng-if="feature.icon"></i>
                    <div class="clearfix"></div>
                    <h2 class="highlight-title" data-ng-if="feature.title">{{feature.title}}</h2>
                    <div class="clearfix"></div>
                    <p data-ng-if="feature.description">{{feature.description}}</p>
                    <div class="clearfix"></div>
                    <a data-ng-class="feature.link.class" data-ng-if="feature.link" data-ng-show="feature.link.class" data-ng-href="{{feature.link.url}}" target="{{feature.link.target}}"><i data-ng-class="feature.link.icon" data-ng-if="feature.link.icon"></i><span data-ng-if="feature.link.icon"> </span><span data-ng-if="feature.link.label">{{feature.link.label}}</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="block" data-ng-class="'block-' + ($index + 1)" data-ng-repeat="highlight in GRIFFO.config.page.home.highlight" data-ng-if="highlight">
    <highlight-carousel data-params="highlight"></highlight-carousel>
</div>
