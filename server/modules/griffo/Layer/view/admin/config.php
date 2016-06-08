<div class="view-header">{{'MODULE.LAYER.TITLE.CONFIG'| grTranslate}}</div>
<div class="view-content" ng-controller="tabCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <li ng-class="{'active': tab.active == 0}"><a href="" ng-click="tab.active = 0" gr-translate>LABEL.GENERAL</a></li>
                <li ng-class="{'active': tab.active == 1}"><a href="" ng-click="tab.active = 1" gr-translate>LABEL.MODULES</a></li>
            </ul>
            <div ng-show="tab.active == 0">
                <div class="container-fluid">
                    <form name="form1" gr-autofields="formSettings1"></form>
                </div>
            </div>
            <div ng-show="tab.active == 1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <gr-table-clear-sorting class="btn btn-warning" type="button" title="{{'BUTTON.CLEAR.SORTING'| grTranslate}}"><i class="fa fa-fw fa-unsorted"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.SORTING'| grTranslate}}</span>
                        </gr-table-clear-sorting>

                        <gr-table-clear-filter class="btn btn-warning" type="button" title="{{'BUTTON.CLEAR.FILTER'| grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.FILTER'| grTranslate}}</span>
                        </gr-table-clear-filter>

                        <gr-table-export-csv class="btn btn-primary pull-right" type="button" title="{{'BUTTON.EXPORT.TABLE.CSV'| grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'BUTTON.EXPORT.TABLE.CSV'| grTranslate}}</span>
                        </gr-table-export-csv>
                    </div>
                    <table gr-table list="modules" export-csv="GRIFFO_layerModulesList" sortby="{'idmodule': 'asc'}" filterby="{'idmodule': ''}">
                        <tr gr-repeat="(key, data) in $data">
                            <td data-title="'#'" sortable="'idmodule'" filter="{'idmodule': 'text'}" header-class="col-1" class="col-1 text-center">
                                {{data.idmodule}}
                            </td>
                            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-4" class="col-4">
                                {{'MODULE.' + data.name.toUpperCase() + '.NAME' | grTranslate}}
                            </td>
                            <td data-title="'LABEL.NATIVE' | grTranslate" sortable="'struct'" header-class="col-1" class="col-1 text-center">
                                {{(data.struct === '1' || data.struct === 1 ? 'LABEL.YES' : 'LABEL.NO') | grTranslate}}
                            </td>
                            <td data-title="'LABEL.PATH' | grTranslate" sortable="'path'" filter="{'path': 'text'}" header-class="col-3" class="col-3">
                                {{data.path}}
                            </td>
                            <td data-title="'LABEL.DEFAULT' | grTranslate" sortable="'default'" header-class="col-2" class="col-2 text-center">
                                <span class="sr-only">{{data.status}}</span>
                                <input toggle-switch type="checkbox"
                                       model="data.default"
                                       ng-model="data.default"
                                       on-label="ON"
                                       off-label="OFF"
                                       class="switch-mini switch-on-success switch-off-danger"
                                       gr-change="change(data)"
                                       ></input>
                            </td>
                            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" header-class="col-2" class="col-2 text-center">
                                <span class="sr-only">{{data.status}}</span>
                                <input toggle-switch type="checkbox"
                                       model="data.status"
                                       ng-model="data.status"
                                       on-label="ON"
                                       off-label="OFF"
                                       class="switch-mini switch-on-success switch-off-danger"
                                       ></input>
                            </td>
                        </tr>
                    </table>
                    <div class="panel-footer" style="display: table; width: 100%;">
                        <gr-table-pager class="pull-left"></gr-table-pager>
                        <gr-table-count class="pull-right"></gr-table-count>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="view-footer">
    <button ng-click="form1.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
    <button ng-click="form1.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
</div>
