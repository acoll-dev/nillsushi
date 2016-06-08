<div class="view-header">{{'MODULE.PRODUCT.TITLE.LIST' | grTranslate}}</div>
<div class="view-content" ng-controller="tableCtrl">
    <table gr-table remote="{module: 'product', action: 'get', onelevel: true}" export-csv="GRIFFO_productList">
		<tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idproduct'" filter="{'idproduct': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idproduct}}
            </td>
            <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-4" class="col-4">
                {{data.name}}
            </td>
            <td data-title="'LABEL.VALUE.UNIT' | grTranslate" sortable="'category.unitvalue'" filter="{'category.unitvalue': 'text'}" header-class="col-3" class="col-3">
                {{data.unitvalue | currency}}
            </td>
            <td data-title="'LABEL.CATEGORY' | grTranslate" sortable="'category.name'" filter="{'category.name': 'text'}" header-class="col-3" class="col-3">
                {{data.category.name}}
            </td>
            <td data-title="'LABEL.SORT' | grTranslate" sortable="'sort'" header-class="col-1" class="col-1">
                <input type="number" class="form-control input-sm" ng-model="data.sort" ng-blur="updateOrder(data)" ng-change="data.changed = true" />
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" class="col-1 text-center">
                <span class="badge badge-{{data.status === 1 ? 'success' : 'danger'}}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idproduct,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idproduct)"><i class="fa fa-fw fa-times"></i></button>
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
