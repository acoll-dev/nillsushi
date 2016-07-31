<div class="page" ng-controller="userCtrl">
    <div class="container">
        <div class="col-xs-12 form-box">
            <div class="form-box-inner" ng-show="formSettings.data.name">
                <div ng-show="!editable()">
                    <h2 class="page-header"><i class="fa fa-fw fa-file-o"></i>Meus dados</h2>
                    <dl class="dl-horizontal">
                        <dt>Nome</dt>
                        <dd>{{formSettings.data.name || '-'}}</dd>
                        <dt>E-mail</dt>
                        <dd>{{formSettings.data.email || '-'}}</dd>
                        <dt>Telefone</dt>
                        <dd>{{(formSettings.data.phone || '-') | phone}}</dd>
                        <dt>Celular</dt>
                        <dd>{{(formSettings.data.mobilephone || '-') | phone}}</dd>
                        <dt>Endereço</dt>
                        <dd>{{(formSettings.data.address || formSettings.data.number) ? (formSettings.data.address + (formSettings.data.number ? ', ' + formSettings.data.number : '')) : '-'}}</dd>
                        <dt>Complemento</dt>
                        <dd>{{formSettings.data.complement || '-'}}</dd>
                        <dt>Bairro</dt>
                        <dd>{{formSettings.data.district || '-'}}</dd>
                        <dt>Estado</dt>
                        <dd>{{formSettings.data.state || '-'}}</dd>
                        <dt>Cidade</dt>
                        <dd>{{formSettings.data.city || '-'}}</dd>
                        <dt ng-if="gr.shops.length > 1">Loja preferencial</dt>
                        <dd ng-if="gr.shops.length > 1">{{findShop(formSettings.data.fkidshop) || '-'}}</dd>
                    </dl>
                </div>
                <div  ng-show="editable()">
                    <h2 class="page-header"><i class="fa fa-fw fa-edit"></i>Editar dados</h2>
                    <form name="form" gr-autofields="formSettings"></form>
                </div>
                <div class="toolbar">
                    <button class="btn btn-success btn-sm pull-left" ng-click="form.submit();" ng-show="editable()"><i class="fa fa-fw fa-check"></i> Salvar dados</button>
                    <button class="btn btn-danger btn-sm pull-left" ng-click="form.reset(); editable(false)" ng-show="editable()"><i class="fa fa-fw fa-times"></i> Cancelar edição</button>
                    <button class="btn btn-primary btn-sm pull-left" ng-click="editable(true)" ng-show="!editable()"><i class="fa fa-fw fa-edit"></i> Editar dados</button>
                    <button class="btn btn-warning btn-sm pull-left" ng-show="!editable()" ng-click="changePassword()"><i class="fa fa-fw fa-edit"></i> Alterar senha</button>
                </div>
            </div>
            <div class="form-box-inner loader" ng-if="!formSettings.data.name">
                <div class="loader-inner">
                    <i class="fa fa-fw fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="col-xs-12 form-box">
            <div class="form-box-inner">
                <h2 class="page-header"><i class="fa fa-fw fa-list"></i>Pedidos anteriores</h2>
                <div class="autofields col-xs-12 col-sm-4 pull-right" style="padding: 0 0 7px">
                    <div class="checkbox form-group" style="margin-bottom: 0;">
                        <label class="control-label form-control">
                            <input type="checkbox" ng-model="showCompleted">
                            Mostrar pedidos concluídos
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <table gr-table list="orders" sortby="{'created': 'desc'}">
                    <tr gr-repeat="(key, data) in $data">
                        <td data-title="'#'" sortable="'idorder'" filter="{'idorder': 'text'}" header-class="col-1" class="col-1 text-center" ng-show="GRIFFO.viewPort.width >= 600">
                            #{{data.idorder}}
                        </td>
                        <td data-title="'Data'" sortable="'created'" header-class="col-2" class="col-2 text-center" ng-show="GRIFFO.viewPort.bs !== 'xs'">
                            {{data.created | date:'dd/MM/yyyy HH:mm'}}
                        </td>
                        <td data-title="'Data'" sortable="'created'" header-class="col-2" class="col-2 text-center" ng-show="GRIFFO.viewPort.bs === 'xs'">
                            {{data.created | date:'dd/MM/yyyy'}}
                        </td>
                        <td data-title="'Total'" sortable="'total'" header-class="col-2" class="col-2 text-center" ng-show="GRIFFO.viewPort.bs !== 'xs'">
                            {{data.total | currency}}
                        </td>
                        <td data-title="'Status'" sortable="'status'" header-class="col-1" class="col-1 text-center">
                            <span class="badge badge-empty" ng-class="{'badge-success': data.status === 3, 'badge-info': data.status === 4, 'badge-warning': data.status === 2, 'badge-danger': data.status === 1, 'badge-primary': data.status === 0}">&nbsp;</span>
                        </td>
                        <td data-title="'Loja'" sortable="'fkidshop'" header-class="col-2" class="col-2 text-center" ng-show="GRIFFO.viewPort.width >= 400">
                            {{findShop(data.fkidshop)}}
                        </td>
                        <td class="col-1 text-center">
                            <button class="btn btn-xs btn-table btn-info" ng-click="orderInfo(data)">Ver</button>
                        </td>
                    </tr>
                </table>
                <div class="toolbar" style="padding: 0 30px">
                    <span>Estados de pedido:</span>
                    <span class="badge badge-primary">Aguardando atendimento</span>
                    <span class="badge badge-danger">Em produção</span>
                    <span class="badge badge-warning">Em transporte</span>
                    <span class="badge badge-info">Concluído, aguardando retirada</span>
                    <span class="badge badge-success">Entregue</span>
                </div>
                <div class="toolbar">
                    <gr-table-pager class="pull-left"></gr-table-pager>
                    <div class="pull-right" title="Linhas por página">
                        <gr-table-count></gr-table-count>
                    </div>
                </div>
                <div class="alert alert-warning" style="margin-bottom: 0">
                    <strong>Atenção</strong>
                    <small>Mantenha essa página aberta para receber notificações quando o status dos seus pedidos for alterado.</small>
                </div>
            </div>
        </div>
    </div>
</div>
