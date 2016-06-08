<div class="block" ng-controller="serviceCtrl">
    <div class="container">
        <!-- <h1 class="title" ng-if="GRIFFO.config.page.service.title">{{GRIFFO.config.page.service.title}}</h1> -->
        <div class="col-xs-12 col-md-3">
            <category-list params="GRIFFO.config.page.service.category"></category-list>
        </div>
        <div class="list-highlights col-xs-12 col-md-9" ng-if="!GRIFFO.filter.service">
            <highlight-carousel params="GRIFFO.config.page.service.highlight"></highlight-carousel>
        </div>
        <div class="col-xs-12 col-md-9" ng-if="GRIFFO.filter.service">
            <h2 class="page-title">{{GRIFFO.filter.service.name}}</h2>
            <div class="page-block-content" data-ta-bind data-ng-model="GRIFFO.filter.service.description"></div>
        </div>
    </div>
</div>
