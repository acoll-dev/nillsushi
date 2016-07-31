<div class="view-header">{{'MODULE.ORDER.TITLE.NEW' | grTranslate}}</div>
<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formSettings" style="margin-bottom: 30px;"></form>
            <div class="container-fluid">
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
<div class="view-footer">
    <button ng-click="form.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
    <button ng-click="form.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
</div>
