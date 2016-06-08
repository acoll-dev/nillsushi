<div class="view-header">{{'MODULE.BANNER.TITLE.LIST' | grTranslate}}</div>
<div class="view-content" ng-controller="tableCtrl">
    <table gr-table remote="{module: 'banner', action: 'get'}" export-csv="GRIFFO_bannerList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idbanner'" filter="{'idbanner': 'text'}" header-class="col1" class="col-1 text-center">
                {{data.idbanner}}
            </td>
            <td data-title="'LABEL.TITLE' | grTranslate" sortable="'title'" filter="{'title': 'text'}" header-class="col1" class="col-3" >
                {{data.title}}
            </td>
            <td data-title="'LABEL.LINK' | grTranslate" sortable="'link'" filter="{'link': 'text'}" header-class="col1" class="col-3">
                <a ng-href="{{data.link}}">{{data.link}}</a>
            </td>
            <td data-title="'LABEL.CATEGORY' | grTranslate" sortable="'name.category'" filter="{'name.category': 'text'}" header-class="col-2" class="col-2 text-center">
                {{data.category.name}}
            </td>
            <td data-title="'LABEL.SORT' | grTranslate" sortable="'sort'" header-class="col-1" class="col-1">
                <input type="number" class="form-control input-sm" ng-model="data.sort" ng-blur="updateOrder(data)" ng-change="data.changed = true" />
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" class="col-1 text-center">
                <span class="badge badge-{{data.status === 1 ? 'success' : 'danger'}}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idbanner,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idbanner)"><i class="fa fa-fw fa-times"></i></button>
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
