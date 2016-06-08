<div class="view-header">{{'MODULE.FRACTION.TITLE.LIST' | grTranslate}}</div>
<div class="view-content" >
    <table gr-table remote="{module: 'fraction', action: 'get', onelevel: true}" export-csv="GRIFFO_fractionList">
		<tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idfraction'" filter="{'idfraction': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idfraction}}
            </td>
            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-4" class="col-4">
                {{data.name}}
            </td>
            <td data-title="'LABEL.FORMULA' | grTranslate" sortable="'formula'" filter="{'formula': 'text'}" header-class="col-1">
                {{data.formula}}
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idfraction,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idfraction)"><i class="fa fa-fw fa-times"></i></button>
            </td>
		</tr>
    </table>
</div>
<div class="view-footer">
    <div class="view-footer-header">
        <gr-table-pager></gr-table-pager>
        <gr-table-count class="pull-right"></gr-table-count>
    </div>

    <gr-table-clear-filter class="btn btn-warning" title="{{'BUTTON.CLEAR.FILTER' | grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.FILTER' | grTranslate}}</span>
    </gr-table-clear-filter>

    <gr-table-export-csv class="btn btn-primary pull-right" title="{{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}</span>
    </gr-table-export-csv>
</div>
