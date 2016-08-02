<style>
    @media screen {
        #printSection{
            display: none !important;
        }
    }
    @media print {
        body *:not(table):not(thead):not(th):not(tbody):not(tr):not(td){
            position: absolute !important;
            height: 0 !important;
        }
        .sidebar-menu,
        :before,
        :after{
            display: none !important;
        }
        #printSection{
            position: absolute !important;
            display: table !important;
            top: 0 !important;
        }
        #printSection,
        #printSection table{
            width: 65mm !important;
        }
        #printSection *:not(table):not(thead):not(th):not(tbody):not(tr):not(td){
            position: relative !important;
            display: block !important;
            height: auto !important;
        }
        #printSection *:not(.total){
            font-size: .7rem !important;
        }
        #printSection dl dd{
            margin-bottom: 5px !important;
        }
        #printSection .total{
            font-size: 1.2rem !important;
        }
        #printSection table td{
            vertical-align: middle;
        }
    }
</style>
<div class="view-header">
    {{'MODULE.ORDER.TITLE.LIST' | grTranslate}}
    <div class="pull-right" style="width: 25%">
        <select class="form-control" ng-model="GRIFFO.curShop" ng-options="item.value as item.label for item in GRIFFO.shops"></select>
    </div>
</div>
<div class="view-content" ng-controller="tableCtrl">
    <div class="col-xs-12" style="padding: 7px">
        <div class="checkbox gr-form-group" style="margin-bottom: 0;">
            <label class="control-label form-control">
                <input type="checkbox" ng-model="showCompleted">
                Mostrar pedidos concluídos
            </label>
        </div>
    </div>
    <div class="col-xs-6" style="padding: 7px">
        <button type="bytton" class="btn btn-default btn-lg" ng-click="legendVisible = !legendVisible" ng-class="{'active': legendVisible}" title="Mostrar/Ocultar legenda">
            <i class="fa fa-fw fa-bookmark"></i>
        </button>
    </div>
    <div class="col-xs-6 text-right" style="padding: 7px;">
        <button type="button" class="btn btn-primary btn-lg" ng-click="calendarMode = !calendarMode" ng-attr-title="{{calendarMode ? 'Mostrar lista' : 'Mostrar calendário'}}">
            <i class="fa fa-fw" ng-class="{'fa-calendar': !calendarMode, 'fa-list': calendarMode}"></i>
        </button>
    </div>
    <div class="text-center col-xs-12 well" style="padding: 7px" ng-show="legendVisible">
        <div class="pull-left">
            <i class="fa fa-fw fa-bookmark-o"></i>
        </div>
        <label style="margin-bottom: 0;">Estados de pedido</label>
        <hr style="margin: 5px 0;"/>
        <span class="badge badge-primary" style="margin: 7px;">Aguardando atendimento</span>
        <span class="badge badge-danger" style="margin: 7px;">Em produção</span>
        <span class="badge badge-warning" style="margin: 7px;">Em transporte</span>
        <span class="badge badge-info" style="margin: 7px;">Concluído, aguardando retirada</span>
        <span class="badge badge-success" style="margin: 7px;">Entregue</span>
    </div>
    <div class="clearfix"></div>
    <div class="well" style="background: #FFFFFF;" ng-show="calendarMode">
        <div class="container-fluid">
            <label>
                Gerenciar dias - {{formSettings.data.enabled}}
            </label>
        </div>
        <div class="well">
            <form name="form" gr-autofields="formSettings"></form>
            <button ng-click="form.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
            <button ng-click="form.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
            <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
        </div>
        <hr style="margin-top: 0;"/>
        <div ui-calendar="calendar.config" ng-model="calendar.orders"></div>
    </div>
    <table gr-table list="orders" reload="reload()" sortby="{'idorder': 'desc'}" export-csv="GRIFFO_orderList" ng-show="!calendarMode">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idorder'" filter="{'idorder': 'text'}"   class="col-1 text-center" header-class="col-1">
                {{data.idorder}}
            </td>
            <td data-title="'LABEL.CLIENT' | grTranslate" header-class="col-3" class="col-3">
                {{data.client.idclient}} - {{data.client.name}}
            </td>
            <td data-title="'LABEL.CREATED' | grTranslate" sortable="'created'" filter="{'created': 'text'}" header-class="col-3">
                {{data.created | date:'dd/MM/yyyy'}} - {{data.created | date:'mediumTime'}}
            </td>
            <td data-title="'LABEL.TOTAL.MONEY' | grTranslate" sortable="'total'" filter="{'total': 'text'}" header-class="col-1">
                {{data.total | currency}}
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" header-class="col-1" class="text-center col-1">
                <span class="badge" ng-class="{'badge-success': data.status === 3, 'badge-info': data.status === 4,'badge-warning': data.status === 2, 'badge-danger': data.status === 1, 'badge-primary': data.status === 0}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.PRINT' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-success" ng-click="print(data)"><i class="fa fa-fw fa-print"></i></button>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idorder,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idorder)"><i class="fa fa-fw fa-times"></i></button>
            </td>
        </tr>
    </table>
    <div id="orderPrintSection" ng-show="false">
        <h3><strong>Informações do pedido #{{orderPrint.idorder}}</strong></h3>
        <hr/>
        <dl class="dl-horizontal">
            <dt>Cliente</dt>
            <dd>{{orderPrint.client.idclient}} - {{orderPrint.client.name}}</dd>
            <dt>Telefone</dt>
            <dd>{{findClient(orderPrint.fkidclient).phone + (findClient(orderPrint.fkidclient).mobilephone ? ' / ' + findClient(orderPrint.fkidclient).mobilephone : '-')}}</dd>
            <dt>Data do pedido</dt>
            <dd>{{orderPrint.created | date:'dd/MM/yyyy'}}</dd>
            <dt>Vai buscar</dt>
            <dd>
                <span ng-if="orderPrint.fetch">Sim</span>
                <span ng-if="!orderPrint.fetch">Não</span>
            </dd>
            <dt>Estado do pedido</dt>
            <dd>
                <span ng-if="orderPrint.status === 0">Aguardando atendimento</span>
                <span ng-if="orderPrint.status === 1">Em produção</span>
                <span ng-if="orderPrint.status === 2">Em transporte</span>
                <span ng-if="orderPrint.status === 4">Concluído, aguardando retirada</span>
                <span ng-if="orderPrint.status === 3">Entregue</span>
            </dd>
            <dt data-ng-if="!orderPrint.fetch">Forma de pagamento</dt>
            <dd data-ng-if="!orderPrint.fetch">{{orderPrint.formpayment}}</dd>
            <dt data-ng-if="!orderPrint.fetch && orderPrint.change === 'Dinheiro'">Troco para</dt>
            <dd data-ng-if="!orderPrint.fetch && orderPrint.change === 'Dinheiro'">{{(orderPrint.change | currency) || '-'}}</dd>
            <dt data-ng-if="!orderPrint.fetch">Endereço de entrega</dt>
            <dd data-ng-if="!orderPrint.fetch">{{orderPrint.address}}, {{orderPrint.number}}{{orderPrint.complement ? ',' + orderPrint.complement : ''}}</dd>
            <dt data-ng-if="!orderPrint.fetch">Bairro</dt>
            <dd data-ng-if="!orderPrint.fetch">{{orderPrint.district}}</dd>
            <dt data-ng-if="!orderPrint.fetch">Cidade</dt>
            <dd data-ng-if="!orderPrint.fetch">{{orderPrint.city}} - {{orderPrint.state}}</dd>
        </dl>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-left">Quant.</th>
                    <th class="text-left">Produto</th>
                    <!-- <th class="text-left">ID</th> -->
                    <th class="text-left">Val. unit.</th>
                    <th class="text-left">Val. total</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="product in orderPrint.products">
                    <td>
                        {{product.quantity}}
                    </td>
                    <td>
                        {{product.name}}
                    </td>
                    <!-- <td>
                        {{product.idproduct}}
                    </td> -->
                    <td>
                        {{product.unitvalue | currency}}
                    </td>
                    </td>
                    <td>
                        {{(product.unitvalue * product.quantity) | currency}}
                    </td>
                </tr>
            </tbody>
        </table>
        <hr/>
        <dl class="dl-horizontal">
            <dt>Sub-total</dt>
            <dd>{{orderPrint.subtotal | currency}}</dd>
            <dt>Taxa de entrega</dt>
            <dd>{{(orderPrint.deliveryfee | currency) || '-'}}</dd>
            <hr/>
            <dt class="total">Total</dt>
            <dd class="total">{{(orderPrint.total | currency) || '-'}}</dd>
        </dl>
    </div>
</div>
<div class="view-footer">
    <div class="view-footer-header">
        <gr-table-pager></gr-table-pager>
        <gr-table-count class="pull-right"></gr-table-count>
    </div>
    <gr-table-clear-sorting class="btn btn-warning" title="{{'BUTTON.CLEAR.SORTING' | grTranslate}}"><i class="fa fa-fw fa-unsorted"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.SORTING' | grTranslate}}</span>
    </gr-table-clear-sorting>

    <gr-table-clear-filter class="btn btn-warning" title="{{'BUTTON.CLEAR.FILTER' | grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.FILTER' | grTranslate}}</span>
    </gr-table-clear-filter>

    <gr-table-export-csv class="btn btn-primary pull-right" title="{{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}</span>
    </gr-table-export-csv>
</div>
