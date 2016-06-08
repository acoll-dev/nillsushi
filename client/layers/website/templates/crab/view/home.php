<div class="page" ng-controller="homeCtrl">
	<div class="container">
		<section class="home-content">
			<div class="panel-group" id="accordion" ng-if="ready()">
				<div class="panel product-panel panel-primary" ng-repeat="category in categories" ng-if="category.products.length > 0 || category.child.length > 0">
					<div class="panel-heading" ng-attr-id="product-panel-heading-{{category.idcategory}}" ng-click="(gr.home.collapse === category.idcategory) ? gr.home.collapse = -1 : gr.home.collapse = category.idcategory; gr.home.collapseSub = -1">
						<h2 class="panel-title">
							{{category.name}}
  						</h2>
  						<div class="panel-title-arrow">
						    <i class="fa fa-fw fa-2x" ng-class="{'fa-angle-down': gr.home.collapse !== category.idcategory, 'fa-angle-up': gr.home.collapse === category.idcategory}"></i>
						</div>
					</div>
					<div class="panel-collapse animate-show" ng-show="gr.home.collapse === category.idcategory" gr-collapse>
						<div class="panel-body">
							<div class="media-wrapper col-xs-12 col-md-6" ng-repeat="product in category.products">
								<div class="media">
                                    <div class="media-left">
                                        <a href="">
                                            <img class="media-object" ng-src="{{product.picture}}">
                                        </a>
									</div>
									<div class="media-body">
										<h3 class="media-heading">{{product.name}}</h3>
										<p class="description" gr-html="product.description"></p>
										<p class="unit-value"><strong>{{product.unitvalue | currency}}{{product.unit ? '/' : ''}}{{product.unit}}</strong></p>
										<div class="input-group amount">
											<span class="input-group-btn">
												<button type="button" class="btn btn-amount" ng-click="gr.cart.items[gr.cart.has(product)].count = gr.cart.items[gr.cart.has(product)].count - 1" ng-disabled="gr.cart.items[gr.cart.has(product)].count === 0"><i class="fa fa-fw fa-minus"></i></button>
											</span>
											<input type="text" class="form-control amount-field" ng-model="gr.cart.items[gr.cart.has(product)].count" ng-change="gr.cart.check(gr.cart.items[gr.cart.has(product)])">
											<span class="input-group-btn">
												<button type="button" class="btn btn-amount" ng-click="gr.cart.items[gr.cart.has(product)].count = gr.cart.items[gr.cart.has(product)].count + 1"><i class="fa fa-fw fa-plus"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
				            <div class="panel product-panel panel-secondary" ng-repeat="category in category.child" ng-show="category.products.length > 0 || category.child.length > 0">
                                <div class="panel-heading" ng-attr-id="product-panel-heading-{{category.idcategory}}" ng-click="gr.home.collapseSub === category.idcategory ? gr.home.collapseSub = -1 : gr.home.collapseSub = category.idcategory">
                                    <h2 class="panel-title">
                                        {{category.name}}
                                    </h2>
                                    <div class="panel-title-arrow">
                                        <i class="fa fa-fw fa-2x" ng-class="{'fa-angle-down': gr.home.collapseSub !== category.idcategory, 'fa-angle-up': gr.home.collapseSub === category.idcategory}"></i>
                                    </div>
                                </div>
                                <div class="panel-collapse animate-show" ng-show="gr.home.collapseSub === category.idcategory" gr-collapse>
                                    <div class="panel-body">
                                        <div class="media-wrapper col-xs-12 col-md-6" ng-repeat="product in category.products" ng-show="category.products.length > 0">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="">
                                                        <img class="media-object" ng-src="{{product.picture}}">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h3 class="media-heading">{{product.name}}</h3>
                                                    <p class="description" gr-html="product.description"></p>
                                                    <p class="unit-value"><strong>{{product.unitvalue | currency}}{{product.unit ? '/' : ''}}{{product.unit}}</strong></p>
                                                    <div class="input-group amount">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-amount" ng-click="gr.cart.items[gr.cart.has(product)].count = gr.cart.items[gr.cart.has(product)].count - 1" ng-disabled="gr.cart.items[gr.cart.has(product)].count === 0"><i class="fa fa-fw fa-minus"></i></button>
                                                        </span>
                                                        <input type="text" class="form-control amount-field" ng-model="gr.cart.items[gr.cart.has(product)].count" ng-change="gr.cart.check(gr.cart.items[gr.cart.has(product)])">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-amount" ng-click="gr.cart.items[gr.cart.has(product)].count = gr.cart.items[gr.cart.has(product)].count + 1"><i class="fa fa-fw fa-plus"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="loader" ng-if="!ready()">
			    <div class="loader-inner">
                    <i class="fa fa-fw fa-3x fa-refresh fa-spin"></i>
			    </div>
			</div>
		</section>
	</div>
</div>
