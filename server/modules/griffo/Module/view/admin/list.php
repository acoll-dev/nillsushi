<div class="view-header">{{'MODULE.MODULE.TITLE.LIST' | grTranslate}}</div>
<div class="view-content" ng-controller="tableCtrl">
    <table gr-table remote="{module: 'module', action: 'get'}" export-csv="GRIFFO_moduleList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idmodule'" filter="{'idmodule': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idmodule}}
            </td>
            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-3" class="col-3">
                {{'MODULE.' + data.name.toUpperCase() + '.NAME' | grTranslate}}
            </td>
            <td data-title="'LABEL.NATIVE' | grTranslate" sortable="'struct'" header-class="col-1" class="col-1 text-center">
                {{(data.struct === '1' || data.struct === 1 ? 'LABEL.YES' : 'LABEL.NO') | grTranslate}}
            </td>
            <td data-title="'LABEL.PATH' | grTranslate" sortable="'path'" filter="{'path': 'text'}" header-class="col-4" class="col-4">
                {{data.path}}
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" class="col-1 text-center">
                <span class="badge badge-{{data.status === 1 ? 'success' : 'danger'}}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idmodule,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.UNINSTALL' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="uninstall(grTable,data.idmodule)"><i class="fa fa-fw fa-times"></i></button>
            </td>
        </tr>
    </table>
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
