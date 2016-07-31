<footer class="footer" ng-cloak>
    <div class="footer-inner container">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 footer-block" ng-repeat="shop in gr.shops">
            <div class="footer-block-inner">
                <p ng-if="gr.shops.length > 1">
                    <strong>{{shop.name}}</strong>
                </p>
                <div class="contact-info">
                    <strong>Endereço</strong>
                    <span>{{shop.address}}, {{shop.number}}{{shop.complement ? ', ' + shop.complement : ''}}, {{shop.district}}, {{shop.city}}/{{shop.state}}</span>
                </div>
                <div class="contact-info" ng-if="shop.phone1">
                    <strong>Telefone</strong>
                    <span><i class="fa fa-fw fa-phone"></i> {{shop.phone1 | phone}}</span>
                    <span ng-if="shop.phone2"> / <i class="fa fa-fw fa-phone"></i> {{shop.phone2 | phone}}</span>
                    <span ng-if="shop.phone3"> / <i class="fa fa-fw fa-phone"></i> {{shop.phone3 | phone}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 footer-block" gr-autoheight="{xs:1,sm:1,md:2,lg:2}">
                <div class="footer-block-inner">
                    <small class="copyright">{{gr.website.copyright}}</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 footer-block" gr-autoheight="{xs:1,sm:1,md:2,lg:2}">
                <div class="sig-box footer-block-inner text-right">
                    <a title="Desenvolvido por Acoll Assessoria & Comunicação" ng-href="http://www.acoll.com.br/" target="_blank">
                        <i class="acoll-sig"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
<script type="text/ng-template" id="cart.html">
    <label class="pull-left">{{gr.cart.title}}</label>
    <span class="pull-right" ng-if="gr.cart.length(true) > 0">{{gr.cart.length(true) + (gr.cart.length(true) > 1 ? ' itens' : ' item')}}</span>
    <div class="clearfix"></div>
    <hr/>
    <ul class="gr-cart-list" ng-if="gr.cart.length() > 0">
        <li class="gr-cart-list-item" ng-repeat="item in gr.cart.items" ng-if="item.count > 0">
            <span class="gr-cart-list-item-name">{{item.name}}</span>
            <div class="gr-cart-list-item-control">
                <input type="number" class="form-control input-sm" ng-model="item.count"  ng-change="gr.cart.check(item)" />
                <button class="btn btn-danger btn-xs" title="Remover {{item.name}} da lista" ng-click="gr.cart.remove(item)">&times;</button>
            </div>
        </li>
    </ul>
    <small class="text-muted" ng-if="gr.cart.length() === 0">Nenhum produto na lista...</small>
    <hr ng-if="gr.cart.length() > 0"/>
    <span class="cart-total" ng-if="gr.cart.length() > 0">
        <strong>Total:</strong> {{gr.cart.total() | currency}}
    </span>
    <hr ng-if="gr.cart.length() > 0"/>
    <button ng-click="gr.cart.submit()" class="btn btn-success btn-sm" ng-if="gr.cart.length() > 0">Finalizar</button>
    <button class="btn btn-danger btn-sm" ng-if="gr.cart.length() > 0" ng-click="gr.cart.clear()">Limpar</button>
</script>
