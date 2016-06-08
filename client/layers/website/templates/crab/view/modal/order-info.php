<div class="container-fluid">
    <dl class="dl-horizontal">
        <dt>ID</dt>
        <dd>#{{order.idorder}}</dd>
        <dt>Loja</dt>
        <dd>{{findShop(order.fkidshop)}}</dd>
        <dt>Data</dt>
        <dd>{{order.created | date:'dd/MM/yyyy'}}</dd>
        <dt>Vai buscar</dt>
        <dd>{{order.fetch ? 'Sim' : 'Não'}}</dd>
        <dt>Estado do pedido</dt>
        <dd><span class="badge badge-empty" ng-class="{'badge-success': order.status === 3, 'badge-info': order.status === 4, 'badge-warning': order.status === 2, 'badge-danger': order.status === 1, 'badge-primary': order.status === 0}">&nbsp;</span></dd>
        <dt data-ng-if="!order.fetch">Forma de pagamento</dt>
        <dd data-ng-if="!order.fetch">{{order.formpayment}}</dd>
        <dt data-ng-if="!order.fetch && order.change === 'Dinheiro'">Troco para</dt>
        <dd data-ng-if="!order.fetch && order.change === 'Dinheiro'">{{(order.change | currency) || '-'}}</dd>
        <dt data-ng-if="!order.fetch">Endereço de entrega</dt>
        <dd data-ng-if="!order.fetch">{{order.address}}, {{order.number}}{{order.complement ? ',' + order.complement : ''}}</dd>
        <dt data-ng-if="!order.fetch">Bairro</dt>
        <dd data-ng-if="!order.fetch">{{order.district}}</dd>
        <dt data-ng-if="!order.fetch">Cidade</dt>
        <dd data-ng-if="!order.fetch">{{order.city}}</dd>
        <dt data-ng-if="!order.fetch">Estado</dt>
        <dd data-ng-if="!order.fetch">{{order.state}}</dd>
    </dl>
    <hr/>
    <table class="table">
        <thead>
            <tr>
                <th class="text-left">Produto</th>
                <th class="text-left">Valor unitário</th>
                <th class="text-left">Quantidade</th>
                <th class="text-left">Valor total</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="product in order.products">
                <td>
                    {{product.name}}
                </td>
                <td>
                    {{product.unitvalue | currency}}
                </td>
                <td>
                    {{product.quantity}}
                </td>
                <td>
                    {{(product.quantity*product.unitvalue) | currency}}
                </td>
            </tr>
        </tbody>
    </table>
    <hr/>
    <dl class="dl-horizontal">
        <dt>Sub-total</dt>
        <dd>{{order.subtotal | currency}}</dd>
        <dt>Taxa de entrega</dt>
        <dd>{{(order.deliveryfee | currency) || '-'}}</dd>
        <hr/>
        <dt><h4>Total</h4></dt>
        <dd><h4><strong>{{(order.total | currency) || '-'}}</strong></h4></dd>
    </dl>
    <div class="toolbar">
        <span>Estados de pedido:</span>
        <span class="badge badge-primary">Aguardando atendimento</span>
        <span class="badge badge-danger">Em produção</span>
        <span class="badge badge-warning">Em transporte</span>
        <span class="badge badge-info">Concluído, aguardando retirada</span>
        <span class="badge badge-success">Entregue</span>
    </div>
</div>
