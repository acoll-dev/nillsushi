<div class="view-header">{{'MODULE.APPLICATION.TITLE.LIST' | grTranslate}}</div>
<div class="view-content">
    <table gr-table remote="{module: 'application', action: 'get', onelevel: true}" export-csv="GRIFFO_applicationList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idapplication'" filter="{'idapplication': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idapplication}}
            </td>
            <td data-title="'LABEL.ID' | grTranslate" sortable="'id'" filter="{'id': 'text'}" header-class="col-4" class="col-4 text-center">
                {{data.id}}
            </td>
            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-5" class="col-5 text-center">
                {{data.name}}
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idapplication,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idapplication)"><i class="fa fa-fw fa-times"></i></button>
            </td>
        </tr>    
    </table>
</div>
<div class="view-footer">
    <div class="view-footer-header">
        <gr-table-pager></gr-table-pager>

        <div class="pull-right">
            <label class="text-muted hidden-xs hidden-sm">{{'LABEL.ROWSPERPAGE' | grTranslate}}:</label>
            <gr-table-count></gr-table-count>
        </div>
    </div>

    <gr-table-clear-sorting class="btn btn-warning" title="{{'BUTTON.CLEAR.SORTING' | grTranslate}}"><i class="fa fa-fw fa-unsorted"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.SORTING' | grTranslate}}</span>
    </gr-table-clear-sorting>

    <gr-table-clear-filter class="btn btn-warning" title="{{'BUTTON.CLEAR.FILTER' | grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.FILTER' | grTranslate}}</span>
    </gr-table-clear-filter>

    <gr-table-export-csv class="btn btn-primary pull-right" title="{{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}</span>
    </gr-table-export-csv>
</div>
