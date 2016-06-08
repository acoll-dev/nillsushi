<div class="block" data-ng-controller="productCtrl">
    <div class="container">
        <!-- <h1 class="title" data-ng-if="GRIFFO.config.page.product.title">{{GRIFFO.config.page.product.title}}</h1> -->
        <div class="col-xs-12 col-md-3">
            <category-list data-params="GRIFFO.config.page.product.category"></category-list>
        </div>
        <div class="list-highlights col-xs-12 col-md-9" data-ng-if="!GRIFFO.filter.product">
            <highlight-carousel data-params="GRIFFO.config.page.product.highlight"></highlight-carousel>
        </div>
        <div class="col-xs-12 col-md-9" data-ng-if="GRIFFO.filter.product">
            <h2 class="page-title">{{GRIFFO.filter.product.name}}</h2>
            <div class="page-block-content" data-ta-bind data-ng-model="GRIFFO.filter.product.description"></div>
            <div class="page-block-gallery" data-ng-if="GRIFFO.filter.product.pictures" data-fancybox>
                <h4 class="page-title">Galeria de imagens</h4>
                <div class="gallery-image col-xs-4 col-sm-4 col-md-3 col-lg-2" ng-repeat="image in GRIFFO.filter.product.pictures.split(';')">
                    <div class="gallery-image-inner" data-gr-autoscale="'1:1'" data-img-liquid>
                        <a data-ng-href="{{GRIFFO.uploadPath + image}}" class="fancybox">
                            <img class="img-responsive" data-ng-src="{{GRIFFO.uploadPath + image}}" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
