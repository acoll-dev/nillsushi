<div class="view-header">{{'MODULE.USER.TITLE.LIST' | grTranslate}}</div>
<div class="view-content">
    <table gr-table remote="{module: 'user', action: 'get', onelevel: true}" export-csv="GRIFFO_UserList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'iduser'" filter="{'iduser': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.iduser}}
            </td>
            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-3" class="col-3">
                {{data.name}}
            </td>
            <td data-title="'LABEL.LOGIN' | grTranslate" sortable="'login'" filter="{'login': 'text'}" header-class="col-2" class="col-2">
                {{data.login}}
            </td>
            <td data-title="'LABEL.EMAIL' | grTranslate" sortable="'email'" filter="{'email': 'text'}" header-class="col-3" class="col-3">
                {{data.email}}
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" class="col-1 text-center">
                <span class="badge badge-{{data.status === 1 ? 'success' : 'danger'}}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.iduser,'view/admin/edit.php')" ng-attr-title="{{'BUTTON.EDIT' | grTranslate}}"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.iduser)" ng-attr-title="{{'BUTTON.DELETE' | grTranslate}}"><i class="fa fa-fw fa-times"></i></button>
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
