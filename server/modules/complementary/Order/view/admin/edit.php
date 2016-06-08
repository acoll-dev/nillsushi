<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formSettings" style="margin-bottom: 30px;"></form>
            <div class="container-fluid">
                <label>Informações do cliente</label>
                <hr/>
                <dl>
                    <dt>Nome</dt>
                    <dd>{{formSettings.data.client.name}}</dd>
                    <dt>Telefone</dt>
                    <dd>{{formSettings.data.client.phone + (formSettings.data.client.mobilephone ? ' / ' + formSettings.data.client.mobilephone : '')}}</dd>
                    <dt>Endereço</dt>
                    <dd>{{formSettings.data.client.address + ', ' + formSettings.data.client.number + (formSettings.data.client.complement ? ', ' + formSettings.data.client.complement : '') + ', ' + formSettings.data.client.city + ', ' + formSettings.data.client.state}}</dd>
                </dl>
                <hr/>
                <label>Produtos</label>
                <hr/>
                <table class="table" style="margin-bottom: 200px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{'LABEL.PRODUCT' | grTranslate}}</th>
                            <th>{{'LABEL.VALUE.UNIT' | grTranslate}}</th>
                            <th>{{'LABEL.QUANTITY' | grTranslate}}</th>
                            <th>{{'LABEL.VALUE.TOTAL' | grTranslate}}</th>
                            <th>{{'BUTTON.REMOVE' | grTranslate}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="product in formSettings.data.products" ng-if="formSettings.data.products.length > 0">
                            <td style="vertical-align: middle">{{product.idproduct}}</td>
                            <td style="vertical-align: middle">{{product.name}}</td>
                            <td style="vertical-align: middle">{{product.unitvalue | currency}}</td>
                            <td><input type="number" min="1" class="form-control" ng-model="product.quantity" /></td>
                            <td style="vertical-align: middle">{{product.unitvalue*product.quantity | currency}}</td>
                            <td><button class="btn btn-danger" ng-click="removeProduct(product)"><i class="fa fa-fw fa-times"></i></button></td>
                        </tr>
                        <tr ng-if="formSettings.data.products.length === 0">
                            <td colspan="6" class="text-muted">{{'MODULE.ORDER.LABEL.TABLE.PRODUCT.EMPTY' | grTranslate}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
