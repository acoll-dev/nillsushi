<div class="container" ng-controller="finishCtrl" style="padding: 20px 0">
    <div class="col-xs-12 col-md-6" ng-show="gr.cart.length() > 0">
        <form name="form" gr-autofields="formSettings"></form>
        <div class="toolbar" ng-attr-title="{{!gr.cart.length() > 0 ? 'É necessário selecionar produtos para finalizar o pedido.' : ''}}">
            <button class="btn btn-success" ng-click="form.submit()" ng-disabled="!gr.cart.length() > 0">Finalizar</button>
            <button class="btn btn-danger" ng-click="gr.cart.cancel()" ng-disabled="!gr.cart.length() > 0">Cancelar</button>
        </div>
    </div>
    <div class="col-xs-12 col-md-6" ng-show="gr.cart.length() > 0">
        <table class="table table-bordered" style="background: #FFFFFF">
            <thead>
                <tr style="background: #F6F6F6">
                    <th class="text-left">Produto</th>
                    <th class="text-left">Valor unitário</th>
                    <th class="text-left">Quantidade</th>
                    <th class="text-left">Valor total</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="product in gr.cart.items" ng-if="gr.cart.length() > 0 && product.count > 0">
                    <td>
                        {{product.name}}
                    </td>
                    <td>
                        {{product.unitvalue | currency}}
                    </td>
                    <td>
                        {{product.count}}
                    </td>
                    <td>
                        {{(product.count*product.unitvalue) | currency}}
                    </td>
                </tr>
                <tr ng-if="gr.cart.length() === 0">
                    <td colspan="4">
                        <span class="text-muted">Nenhum produto na lista...</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr/>
        <strong>Total do pedido:</strong> {{gr.cart.total() | currency}}<span ng-if="!formSettings.data.fetch">*</span>
        <hr ng-if="!formSettings.data.fetch"/>
        <small class="text-muted" ng-if="!formSettings.data.fetch">*O total do pedido não inclúi a taxa de entrega.</small>
        <hr ng-if="!formSettings.data.fetch"/>
        <div class="alert alert-warning">
            <strong>Atenção</strong>
            <p>
                <small>O horário de atendimento é <strong>todos os dias</strong> das <strong>17:00 às 23:00</strong>. Se seu pedido for realizado fora desse período, só será processado no próximo horário de atendimento.</small>
            </p>
        </div>
    </div>
    <div class="container loader" ng-show="gr.cart.length() === 0">
        <div class="loader-inner">
            <i class="fa fa-fw fa-refresh fa-3x fa-spin"></i>
        </div>
    </div>
</div>
